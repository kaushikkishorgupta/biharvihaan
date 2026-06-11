<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Business;

class ApiController extends Controller {

    // Renders the API documentation view page
    public function docs() {
        $this->render('api_docs', [
            'title' => 'REST API Reference - Bihar Vihaan Enterprise',
            'view_mode' => 'docs'
        ]);
    }

    // GET /api/destinations
    public function getDestinations() {
        $category = $_GET['category'] ?? null;
        $destModel = new Destination();
        $destinations = $destModel->all($category);

        $this->json([
            'success' => true,
            'count' => count($destinations),
            'data' => $destinations
        ]);
    }

    // GET /api/destinations/{id}
    public function getDestinationDetail($params) {
        $id = intval($params['id'] ?? 0);
        
        if ($id <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Invalid destination ID'
            ], 400);
        }

        $destModel = new Destination();
        $destination = $destModel->find($id);

        if (!$destination) {
            $this->json([
                'success' => false,
                'message' => 'Destination not found'
            ], 404);
        }

        $attractions = $destModel->getAttractions($id);
        $destination['attractions'] = $attractions;

        // Fetch multi-image gallery & video links (Enterprise 3.0)
        $db = Database::getInstance();
        $destination['images'] = $db->query("SELECT image_url FROM destination_images WHERE destination_id = ?", [$id]);
        $destination['videos'] = $db->query("SELECT video_url FROM destination_videos WHERE destination_id = ?", [$id]);
        $destination['reviews'] = $db->query("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.reference_type = 'destination' AND r.reference_id = ? ORDER BY r.id DESC", [$id]);

        $this->json([
            'success' => true,
            'data' => $destination
        ]);
    }

    // GET /api/search?q=...
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json([
                'success' => true,
                'data' => []
            ]);
        }

        $destModel = new Destination();
        $results = $destModel->search($query);

        $this->json([
            'success' => true,
            'count' => count($results),
            'data' => $results
        ]);
    }

    // GET /api/events
    public function getEvents() {
        $eventModel = new Event();
        $events = $eventModel->getEvents();

        $this->json([
            'success' => true,
            'count' => count($events),
            'data' => $events
        ]);
    }

    // GET /api/businesses
    public function getBusinesses() {
        $category = $_GET['category'] ?? null;
        $businessModel = new Business();
        $listings = $businessModel->getListings($category, true);

        $this->json([
            'success' => true,
            'count' => count($listings),
            'data' => $listings
        ]);
    }

    // POST /api/event/register
    public function registerEventTicket() {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Login required'], 401);
        }

        $eventId = intval($_POST['event_id'] ?? 0);
        $count = intval($_POST['ticket_count'] ?? 1);
        $totalPrice = floatval($_POST['total_price'] ?? 0);

        if ($eventId <= 0 || $count <= 0 || $totalPrice <= 0) {
            $this->json(['success' => false, 'message' => 'Invalid parameters'], 400);
        }

        $eventModel = new Event();
        $event = $eventModel->findEvent($eventId);
        if (!$event || $event['available_tickets'] < $count) {
            $this->json(['success' => false, 'message' => 'Tickets not available'], 400);
        }

        $userId = Session::get('user_id');
        $regInfo = $eventModel->registerForEvent($userId, $eventId, $count, $totalPrice, 'pending');

        $this->json([
            'success' => true,
            'registration_id' => $regInfo['registration_id'],
            'ticket_code' => $regInfo['ticket_code']
        ]);
    }

    // ========================================================
    // ENTERPRISE 3.0 REST ENDPOINTS
    // ========================================================

    // GET /api/news
    public function getNews() {
        $category = $_GET['category'] ?? null;
        $db = Database::getInstance();
        
        if ($category) {
            $sql = "SELECT * FROM news WHERE category = ? ORDER BY id DESC";
            $news = $db->query($sql, [$category]);
        } else {
            $sql = "SELECT * FROM news ORDER BY id DESC";
            $news = $db->query($sql);
        }

        $this->json([
            'success' => true,
            'count' => count($news),
            'data' => $news
        ]);
    }

    // POST /api/reviews
    public function postReview() {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Authentication required'], 401);
        }

        $refType = $_POST['reference_type'] ?? '';
        $refId = intval($_POST['reference_id'] ?? 0);
        $rating = intval($_POST['rating'] ?? 5);
        $comment = $_POST['comment'] ?? '';

        if (empty($refType) || $refId <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
            $this->json(['success' => false, 'message' => 'Invalid review details'], 400);
        }

        $db = Database::getInstance();
        $userId = Session::get('user_id');

        $sql = "INSERT INTO reviews (reference_type, reference_id, user_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
        $db->execute($sql, [$refType, $refId, $userId, $rating, $comment]);

        // Dynamically recalculate destination/business ratings (simulated simplicity)
        if ($refType === 'destination') {
            $avg = $db->queryRow("SELECT AVG(rating) as average FROM reviews WHERE reference_type = 'destination' AND reference_id = ?", [$refId])['average'];
            $db->execute("UPDATE destinations SET rating = ? WHERE id = ?", [round($avg, 1), $refId]);
        }

        $this->json([
            'success' => true,
            'message' => 'Review published successfully!'
        ]);
    }

    // POST /api/subscribe
    public function newsletterSubscribe() {
        $email = $_POST['email'] ?? '';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Valid email required'], 400);
        }

        try {
            $db = Database::getInstance();
            $sql = "INSERT INTO newsletter_subscribers (email) VALUES (?)";
            $db->execute($sql, [$email]);
            $this->json(['success' => true, 'message' => 'Subscribed successfully!']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Email is already subscribed.'], 400);
        }
    }

    // POST /api/contact
    public function submitContact() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $this->json(['success' => false, 'message' => 'All fields are required.'], 400);
        }

        $db = Database::getInstance();
        $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $db->execute($sql, [$name, $email, $subject, $message]);

        $this->json(['success' => true, 'message' => 'Message sent successfully!']);
    }

    // POST /api/chat
    public function chatReply() {
        $message = $_POST['message'] ?? '';
        
        $aiService = new \App\Services\AiService();
        $reply = $aiService->chat($message);
        
        $this->json([
            'success' => true,
            'reply' => $reply
        ]);
    }
}
