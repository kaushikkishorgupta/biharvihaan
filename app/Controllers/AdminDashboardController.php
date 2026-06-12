<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use PDO;

class AdminDashboardController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requireAdmin();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        // CMS Analytics stats for Enterprise CMS 7.0 (8 cards)
        $stats = [
            'destinations' => (int)$db->query("SELECT COUNT(*) FROM destinations")->fetchColumn(),
            'events' => (int)$db->query("SELECT COUNT(*) FROM events")->fetchColumn(),
            'blogs' => (int)$db->query("SELECT COUNT(*) FROM blogs")->fetchColumn(),
            'gallery_images' => (int)$db->query("SELECT COUNT(*) FROM gallery_images")->fetchColumn(),
            'videos' => (int)$db->query("SELECT COUNT(*) FROM media_library WHERE file_type IN ('mp4', 'avi', 'mov', 'webm', 'video/mp4')")->fetchColumn(),
            'products' => (int)$db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'businesses' => (int)$db->query("SELECT COUNT(*) FROM businesses")->fetchColumn(),
            'ai_trips' => (int)$db->query("SELECT COUNT(*) FROM itineraries")->fetchColumn(),
        ];

        // Fetch recent activities
        $activities = $db->query("
            SELECT a.*, u.name as user_name 
            FROM activities a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/dashboard', [
            'title' => 'CMS Dashboard | Bihar Vihaan Enterprise',
            'stats' => $stats,
            'activities' => $activities
        ]);
    }

    public function globalSearch() {
        header('Content-Type: application/json');
        
        $query = $_GET['q'] ?? '';
        if (strlen($query) < 2) {
            echo json_encode([]);
            exit;
        }

        $db = Database::getInstance()->getConnection();
        $term = "%$query%";
        $results = [];

        // Search Destinations
        $destStmt = $db->prepare("SELECT id, name as title, 'Destination' as type, CONCAT('/admin/tourism') as url FROM destinations WHERE name LIKE ? LIMIT 3");
        $destStmt->execute([$term]);
        $results = array_merge($results, $destStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Pages
        $pageStmt = $db->prepare("SELECT id, title, 'Page' as type, CONCAT('/admin/cms') as url FROM cms_pages WHERE title LIKE ? LIMIT 3");
        $pageStmt->execute([$term]);
        $results = array_merge($results, $pageStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Events
        $eventStmt = $db->prepare("SELECT id, title, 'Event' as type, '/admin/events' as url FROM events WHERE title LIKE ? LIMIT 3");
        $eventStmt->execute([$term]);
        $results = array_merge($results, $eventStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Partners
        $partnerStmt = $db->prepare("SELECT id, name as title, 'Partner' as type, '/admin/partners' as url FROM partners WHERE name LIKE ? LIMIT 3");
        $partnerStmt->execute([$term]);
        $results = array_merge($results, $partnerStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Blogs (NEW)
        $blogStmt = $db->prepare("SELECT id, title, 'Blog' as type, '/admin/blogs' as url FROM blogs WHERE title LIKE ? LIMIT 3");
        $blogStmt->execute([$term]);
        $results = array_merge($results, $blogStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Products (NEW)
        $prodStmt = $db->prepare("SELECT id, name as title, 'Product' as type, '/admin/marketplace' as url FROM products WHERE name LIKE ? LIMIT 3");
        $prodStmt->execute([$term]);
        $results = array_merge($results, $prodStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Businesses (NEW)
        $bizStmt = $db->prepare("SELECT id, name as title, 'Business' as type, '/admin/businesses' as url FROM businesses WHERE name LIKE ? LIMIT 3");
        $bizStmt->execute([$term]);
        $results = array_merge($results, $bizStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Users (NEW)
        $userStmt = $db->prepare("SELECT id, name as title, 'User' as type, '/admin/users' as url FROM users WHERE name LIKE ? OR email LIKE ? LIMIT 3");
        $userStmt->execute([$term, $term]);
        $results = array_merge($results, $userStmt->fetchAll(PDO::FETCH_ASSOC));

        // Search Media Library (NEW)
        $mediaStmt = $db->prepare("SELECT id, file_name as title, 'Media' as type, '/admin/media' as url FROM media_library WHERE file_name LIKE ? LIMIT 3");
        $mediaStmt->execute([$term]);
        $results = array_merge($results, $mediaStmt->fetchAll(PDO::FETCH_ASSOC));

        echo json_encode($results);
        exit;
    }
}
