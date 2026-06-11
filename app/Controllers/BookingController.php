<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Models\Booking;

class BookingController extends Controller {

    public function index() {
        $this->render('bookings', [
            'title' => 'Hotel & Travel Bookings - Bihar Vihaan'
        ]);
    }

    public function handleRequest() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to make bookings.');
            $this->redirect('/login');
        }

        $type = $_POST['booking_type'] ?? '';
        $itemName = $_POST['item_name'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? null;
        $guests = intval($_POST['quantity_or_guests'] ?? 1);
        $price = floatval($_POST['total_price'] ?? 0);

        if (empty($type) || empty($itemName) || empty($startDate)) {
            Session::setFlash('error', 'Please complete all required fields.');
            $this->redirect('/bookings');
        }

        $bookingModel = new Booking();
        $bookingId = $bookingModel->createBooking([
            'user_id' => Session::get('user_id'),
            'booking_type' => $type,
            'item_name' => $itemName,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'quantity_or_guests' => $guests,
            'details' => json_encode($_POST),
            'total_price' => $price,
            'status' => 'pending'
        ]);

        if ($bookingId) {
            Logger::log('Booking Created', "Booking request #$bookingId created for $itemName by user ID " . Session::get('user_id'));
            
            // Queue Notification in database (SMS / WhatsApp mock)
            $db = \App\Core\Database::getInstance();
            $db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (?, 'system', ?, ?)", [
                Session::get('user_id'),
                'Booking Request Initiated',
                "Your request for $itemName has been submitted successfully. Please complete the payment to confirm."
            ]);

            Session::setFlash('success', 'Booking request submitted! Complete payment to confirm.');
            $this->redirect('/dashboard');
        } else {
            Session::setFlash('error', 'Failed to request booking. Please try again.');
            $this->redirect('/bookings');
        }
    }

    public function handlePayment() {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userId = Session::get('user_id');
        $orderId = $_POST['order_id'] ?? '';
        $txnId = $_POST['transaction_id'] ?? '';
        $amount = floatval($_POST['amount'] ?? 0);
        $refType = $_POST['reference_type'] ?? '';
        $refId = intval($_POST['reference_id'] ?? 0);

        if (empty($txnId) || $amount <= 0 || empty($refType) || $refId <= 0) {
            $this->json(['success' => false, 'message' => 'Missing transaction data'], 400);
        }

        $bookingModel = new Booking();
        $paymentId = $bookingModel->recordPayment([
            'user_id' => $userId,
            'order_id' => $orderId,
            'transaction_id' => $txnId,
            'amount' => $amount,
            'gateway' => 'Razorpay (Simulated)',
            'status' => 'captured',
            'reference_type' => $refType,
            'reference_id' => $refId
        ]);

        if ($paymentId) {
            Logger::log('Payment Logged', "Payment #$paymentId of INR $amount recorded for ref $refType #$refId");
            
            // Trigger confirmation emails/SMS queues
            $db = \App\Core\Database::getInstance();
            $db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (?, 'email', ?, ?)", [
                $userId,
                'Invoice & Ticket Confirmed',
                "Payment of INR $amount for your $refType request was captured successfully. Receipt TXN ID: $txnId."
            ]);

            // For event, update event registration status
            if ($refType === 'event_registration') {
                $eventModel = new \App\Models\Event();
                $eventModel->updateRegistrationPayment($refId, 'paid');
            }

            $this->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'invoice_url' => BASE_URL . "/invoice?id=" . $paymentId
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to record transaction'], 500);
        }
    }
}
