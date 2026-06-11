<?php

namespace App\Models;

use App\Core\Database;

class Booking {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createBooking($data) {
        $sql = "INSERT INTO bookings (user_id, booking_type, item_name, start_date, end_date, quantity_or_guests, details, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['user_id'],
            $data['booking_type'], // hotel, tour, guide, transport
            $data['item_name'],
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['quantity_or_guests'],
            $data['details'] ?? null,
            $data['total_price'],
            $data['status'] ?? 'pending'
        ]);

        return $this->db->lastInsertId();
    }

    public function getUserBookings($userId) {
        $sql = "SELECT * FROM bookings WHERE user_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$userId]);
    }

    public function allBookings() {
        $sql = "SELECT b.*, u.name as user_name, u.email as user_email 
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                ORDER BY b.id DESC";
        return $this->db->query($sql);
    }

    public function updateStatus($bookingId, $status) {
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $bookingId]);
    }

    // Payment auditing records
    public function recordPayment($data) {
        $sql = "INSERT INTO payments (user_id, order_id, transaction_id, amount, gateway, status, reference_type, reference_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['user_id'],
            $data['order_id'],
            $data['transaction_id'],
            $data['amount'],
            $data['gateway'],
            $data['status'] ?? 'captured',
            $data['reference_type'], // event_registration, booking, premium_membership
            $data['reference_id']
        ]);

        // If booking payment success, update booking status to confirmed
        if ($data['reference_type'] === 'booking') {
            $this->updateStatus($data['reference_id'], 'confirmed');
        }

        return $this->db->lastInsertId();
    }

    public function getTransactions() {
        $sql = "SELECT p.*, u.name as user_name 
                FROM payments p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.id DESC";
        return $this->db->query($sql);
    }

    public function getUserTransactions($userId) {
        $sql = "SELECT * FROM payments WHERE user_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$userId]);
    }
}
