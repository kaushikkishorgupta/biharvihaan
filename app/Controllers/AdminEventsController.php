<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Session;
use PDO;

class AdminEventsController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_events');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $events = $db->query("SELECT * FROM events ORDER BY start_date ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/events', [
            'title' => 'Manage Events & Festivals | Admin Panel',
            'events' => $events
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $title = $_POST['title'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $description = $_POST['description'];
        $location = $_POST['location'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category = $_POST['category'];
        $status = $_POST['status'];

        // Handle Image Upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = BASE_URL . '/uploads/' . $filename;
            }
        }

        $stmt = $db->prepare("INSERT INTO events (title, slug, description, location, start_date, end_date, image, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $slug, $description, $location, $start_date, $end_date, $image, $category, $status])) {
            Session::setFlash('success', 'Event created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create event.');
        }

        $this->redirect('/admin/events');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category = $_POST['category'];
        $status = $_POST['status'];

        $stmt = $db->prepare("UPDATE events SET title=?, description=?, location=?, start_date=?, end_date=?, category=?, status=? WHERE id=?");
        
        if ($stmt->execute([$title, $description, $location, $start_date, $end_date, $category, $status, $id])) {
            Session::setFlash('success', 'Event updated successfully.');
        } else {
            Session::setFlash('error', 'Failed to update event.');
        }

        $this->redirect('/admin/events');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'];

        $stmt = $db->prepare("DELETE FROM events WHERE id=?");
        if ($stmt->execute([$id])) {
            Session::setFlash('success', 'Event deleted successfully.');
        } else {
            Session::setFlash('error', 'Failed to delete event.');
        }

        $this->redirect('/admin/events');
    }
}
