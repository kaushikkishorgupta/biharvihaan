<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Core\Logger;
use App\Models\User;
use App\Models\Destination;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Business;
use App\Models\Artist;
use App\Models\Job;
use App\Models\GalleryModel;

class DashboardController extends Controller {

    public function index() {
        Auth::requireLogin();

        $userId = Session::get('user_id');
        $role = Session::get('user_role');

        $userModel = new User();
        $destModel = new Destination();
        $bookingModel = new Booking();
        $eventModel = new Event();
        $businessModel = new Business();
        $artistModel = new Artist();
        $jobModel = new Job();

        $data = [
            'title' => 'Dashboard - Bihar Vihaan',
            'user' => $userModel->find($userId)
        ];

        // 1. Super Admin / Admin Dashboard Data
        if ($role === 'super_admin' || $role === 'admin') {
            $data['users_list'] = $userModel->all();
            $data['all_bookings'] = $bookingModel->allBookings();
            $data['all_listings'] = $businessModel->getListings();
            $data['transactions'] = $bookingModel->getTransactions();
            
            // Analytics counts
            $db = Database::getInstance();
            $data['total_revenue'] = $db->queryRow("SELECT SUM(amount) as rev FROM payments WHERE status = 'captured'")['rev'] ?? 0;
            $data['views_analytics'] = $db->query("SELECT name, views_count FROM destinations ORDER BY views_count DESC LIMIT 5");
            $data['ad_clicks'] = $businessModel->getAdStats();
            $data['system_logs'] = $db->query("SELECT al.*, u.name as user_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.id DESC LIMIT 15");
        }

        // 2. Recruiter / HR Panel
        if ($role === 'super_admin' || $role === 'admin') {
            $data['job_applications'] = $jobModel->getApplications();
            $data['jobs_list'] = $jobModel->getInternships();
        }

        // 3. Business Manager / Owner Panel
        $data['owner_listings'] = $businessModel->getOwnerListings($userId);
        $data['my_leads'] = [];
        foreach ($data['owner_listings'] as $l) {
            $leads = $businessModel->getLeads($l['id']);
            $data['my_leads'] = array_merge($data['my_leads'], $leads);
        }

        // 4. Tourism / Content Manager Panel
        if ($role === 'super_admin' || $role === 'tourism_manager' || $role === 'content_manager') {
            $data['destinations_list'] = $destModel->all();
            
            $galleryModel = new GalleryModel();
            $data['gallery_pending'] = Database::getInstance()->query("SELECT * FROM gallery_images WHERE status = 'pending' ORDER BY created_at DESC");
            $data['gallery_active'] = Database::getInstance()->query("SELECT * FROM gallery_images WHERE status = 'active' ORDER BY created_at DESC LIMIT 50");
            
            // Marketplace Admin Data
            $data['admin_products'] = Database::getInstance()->query("SELECT p.*, a.name as artisan_name FROM products p LEFT JOIN artisans a ON p.artisan_id = a.id ORDER BY p.id DESC");
            $data['admin_artisans'] = Database::getInstance()->query("SELECT * FROM artisans ORDER BY name ASC");
            $data['admin_orders'] = Database::getInstance()->query("SELECT o.*, u.name as customer_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 50");
        }

        // 5. Event Organizer Panel
        if ($role === 'super_admin' || $role === 'content_manager') {
            $data['my_events'] = $eventModel->getEvents();
        }

        // 6. Basic Tourist User Panel (wishlist, bookings, custom itineraries, collab requests received)
        $data['wishlist'] = $destModel->getSavedPlaces($userId);
        $data['my_itineraries'] = $destModel->getUserItineraries($userId);
        $data['my_bookings'] = $bookingModel->getUserBookings($userId);
        $data['my_event_registrations'] = $eventModel->getUserRegistrations($userId);
        $data['notifications'] = Database::getInstance()->query("SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT 10", [$userId]);

        // If user is registered as artist, get collabs and portfolio
        $artist = $artistModel->findArtistByUserId($userId);
        if ($artist) {
            $data['artist_profile'] = $artist;
            $data['artist_portfolio'] = $artistModel->getPortfolios($artist['id']);
            $data['collab_requests'] = $artistModel->getCollaborationRequests($userId);
        }

        $this->render('dashboard', $data);
    }

    public function handleUserRole() {
        Auth::requireRole('super_admin');

        $userId = intval($_POST['user_id'] ?? 0);
        $roleId = intval($_POST['role_id'] ?? 8);
        $status = $_POST['status'] ?? 'active';

        if ($userId > 0) {
            $userModel = new User();
            $userModel->updateRole($userId, $roleId);
            $userModel->updateStatus($userId, $status);
            
            Logger::log('User Role/Status Modification', "Modified credentials for user ID: $userId to Role ID $roleId, Status: $status");
            Session::setFlash('success', 'User configurations updated successfully.');
        }

        $this->redirect('/dashboard');
    }

    public function handleVerifyBusiness() {
        Auth::requireRoles(['super_admin', 'admin']);

        $listingId = intval($_POST['listing_id'] ?? 0);
        $status = $_POST['status'] ?? 'verified';

        if ($listingId > 0) {
            $businessModel = new Business();
            $businessModel->verifyListing($listingId, $status);
            
            Logger::log('Business Verification updated', "Business Listing #$listingId set to status: $status");
            Session::setFlash('success', 'Listing status updated.');
        }

        $this->redirect('/dashboard');
    }

    public function handleAppStatus() {
        Auth::requireRoles(['super_admin', 'admin']);

        $appId = intval($_POST['application_id'] ?? 0);
        $status = $_POST['status'] ?? 'reviewed';

        if ($appId > 0) {
            $jobModel = new Job();
            $jobModel->updateApplicationStatus($appId, $status);
            
            Logger::log('Job Application review updated', "Application ID #$appId updated to: $status");
            Session::setFlash('success', 'Candidate application status updated.');
        }

        $this->redirect('/dashboard');
    }

    public function handleAddDestination() {
        Auth::requireRoles(['super_admin', 'tourism_manager']);

        $name = $_POST['name'] ?? '';
        $desc = $_POST['description'] ?? '';
        $cat = $_POST['category'] ?? 'Heritage';
        $loc = $_POST['location'] ?? '';
        $img = $_POST['image_url'] ?? '';

        if (empty($name) || empty($desc) || empty($img)) {
            Session::setFlash('error', 'All text attributes and image URL are required.');
            $this->redirect('/dashboard');
        }

        $db = Database::getInstance();
        $sql = "INSERT INTO destinations (name, description, category, location, image_url, views_count, rating, status) 
                VALUES (?, ?, ?, ?, ?, 0, 5.0, 'active')";
        
        $db->execute($sql, [$name, $desc, $cat, $loc, $img]);
        $destId = $db->lastInsertId();

        if ($destId) {
            Logger::log('Tourism destination added', "Hotspot $name (#$destId) added to repository.");
            Session::setFlash('success', 'Tourist destination added to site database successfully!');
        }

        $this->redirect('/dashboard');
    }

    public function handleAddEvent() {
        Auth::requireRoles(['super_admin', 'content_manager']);

        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '12:00:00';
        $loc = $_POST['location'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $tickets = intval($_POST['max_tickets'] ?? 100);
        $img = $_POST['image_url'] ?? '';

        if (empty($title) || empty($desc) || empty($date) || empty($img)) {
            Session::setFlash('error', 'Please fill in all core event details.');
            $this->redirect('/dashboard');
        }

        $eventModel = new Event();
        $eventId = $eventModel->createEvent([
            'title' => $title,
            'description' => $desc,
            'date' => $date,
            'time' => $time,
            'location' => $loc,
            'price' => $price,
            'max_tickets' => $tickets,
            'organizer_id' => Session::get('user_id'),
            'image_url' => $img
        ]);

        if ($eventId) {
            Logger::log('Event created', "New event '$title' (#$eventId) scheduled.");
            Session::setFlash('success', 'Event scheduled successfully!');
        }

        $this->redirect('/dashboard');
    }

    public function invoice() {
        Auth::requireLogin();
        
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/dashboard');
        }

        $db = Database::getInstance();
        
        // Find payment audit record
        $payment = $db->queryRow("SELECT p.*, u.name as user_name, u.email as user_email FROM payments p JOIN users u ON p.user_id = u.id WHERE p.id = ?", [$id]);
        
        if (!$payment) {
            die("Transaction record not found.");
        }

        // Restrict invoice viewing to owner or admin
        if ($payment['user_id'] != Session::get('user_id') && !Auth::hasRoles(['super_admin', 'admin'])) {
            die("Access Denied.");
        }

        // Load details depending on references
        $refDetails = [];
        if ($payment['reference_type'] === 'booking') {
            $refDetails = $db->queryRow("SELECT * FROM bookings WHERE id = ?", [$payment['reference_id']]);
        } elseif ($payment['reference_type'] === 'event_registration') {
            $refDetails = $db->queryRow("SELECT er.*, e.title as event_title, e.date as event_date, e.location as event_location FROM events_registrations er JOIN events e ON er.event_id = e.id WHERE er.id = ?", [$payment['reference_id']]);
        }

        $this->render('api_docs', [
            'title' => 'Download Invoice - Bihar Vihaan',
            'payment' => $payment,
            'reference' => $refDetails,
            'view_mode' => 'invoice'
        ]);
    }

    public function handleGalleryAction() {
        Auth::requireRoles(['super_admin', 'content_manager']);
        
        $imageId = intval($_POST['image_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        
        if ($imageId > 0 && in_array($action, ['approve', 'reject', 'delete'])) {
            $db = Database::getInstance();
            if ($action === 'delete') {
                $db->execute("DELETE FROM gallery_images WHERE id = ?", [$imageId]);
                Session::setFlash('success', 'Gallery image deleted successfully.');
            } else {
                $status = ($action === 'approve') ? 'active' : 'rejected';
                $db->execute("UPDATE gallery_images SET status = ? WHERE id = ?", [$status, $imageId]);
                Session::setFlash('success', "Gallery image {$status}.");
            }
        }
        
        $this->redirect('/dashboard');
    }
}
