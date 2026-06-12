<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class EventsController extends Controller {

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';

        $query = "SELECT * FROM events WHERE status = 'active'";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($category)) {
            $query .= " AND category = ?";
            $params[] = $category;
        }

        $query .= " ORDER BY start_date ASC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch categories for filter
        $categories = $db->query("SELECT DISTINCT category FROM events WHERE status = 'active' AND category IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

        $this->render('events/index', [
            'title' => 'Upcoming Holiday Festivals | Bihar Vihaan',
            'events' => $events,
            'categories' => $categories
        ]);
    }

    public function show() {
        $slug = $_GET['slug'] ?? '';
        
        if (empty($slug)) {
            $this->redirect('/events');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM events WHERE slug = ? AND status = 'active'");
        $stmt->execute([$slug]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $this->redirect('/events');
        }

        $this->render('events/show', [
            'title' => $event['title'] . ' | Bihar Vihaan',
            'event' => $event
        ]);
    }
}
