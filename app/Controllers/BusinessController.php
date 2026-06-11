<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Models\Business;

class BusinessController extends Controller {

    public function index() {
        $businessModel = new Business();
        $category = $_GET['category'] ?? null;
        $listings = $businessModel->getListings($category);

        $this->render('business', [
            'title' => 'Business Directory - Bihar Vihaan',
            'listings' => $listings,
            'selected_category' => $category
        ]);
    }

    public function detail($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/business');
        }

        $businessModel = new Business();
        $listing = $businessModel->findListing($id);

        if (!$listing) {
            die("Business listing not found.");
        }

        $this->render('business', [
            'title' => $listing['name'] . ' - Business Details',
            'listing' => $listing,
            'view_mode' => 'detail'
        ]);
    }

    public function handleLead() {
        $businessId = intval($_POST['business_id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';

        if ($businessId <= 0 || empty($name) || empty($phone)) {
            Session::setFlash('error', 'Name and phone number are required.');
            $this->redirect('/business');
        }

        $businessModel = new Business();
        $success = $businessModel->addLead([
            'business_id' => $businessId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message
        ]);

        if ($success) {
            Logger::log('Business Lead Submitted', "Lead generated for business ID: $businessId from $name");
            
            $listing = $businessModel->findListing($businessId);
            
            // Queue notification in DB for WhatsApp
            $db = \App\Core\Database::getInstance();
            $db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (?, 'whatsapp', ?, ?)", [
                $listing['owner_id'],
                'New Lead Inquiry Received',
                "Customer $name ($phone) inquired about your business listing: $message"
            ]);

            Session::setFlash('success', 'Inquiry sent successfully! The business owner has been notified via WhatsApp integration.');
        } else {
            Session::setFlash('error', 'Could not save inquiry. Try again.');
        }

        $this->redirect('/business/' . $businessId);
    }

    public function handleAdClick() {
        $adName = $_GET['ad_name'] ?? 'general_banner';
        $businessModel = new Business();
        $businessModel->logAdClick($adName);

        // Simulated redirect to sponsor's website
        $this->redirect('/tourism');
    }

    public function handleUpgrade() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to manage business listings.');
            $this->redirect('/login');
        }

        $listingId = intval($_POST['listing_id'] ?? 0);
        $plan = $_POST['plan'] ?? 'premium';

        if ($listingId <= 0) {
            Session::setFlash('error', 'Invalid listing details.');
            $this->redirect('/');
        }

        $businessModel = new Business();
        $success = $businessModel->upgradePlan($listingId, $plan);

        if ($success) {
            Logger::log('Business Plan Upgraded', "Listing #$listingId upgraded to $plan");
            Session::setFlash('success', "Listing upgraded to $plan plan! Your featured listing is now live.");
        } else {
            Session::setFlash('error', 'Plan upgrade failed. Try again.');
        }

        $this->redirect('/');
    }
}
