<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminTourismController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_tourism');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM destinations ORDER BY id DESC");
        $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/tourism', [
            'title' => 'Tourism Destination Manager | Bihar Vihaan',
            'destinations' => $destinations
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        $category = $_POST['category'] ?? '';
        $location = $_POST['location'] ?? '';
        $district = $_POST['district'] ?? '';
        $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
        $description = $_POST['description'] ?? '';
        $history = $_POST['history'] ?? '';
        $travel_tips = $_POST['travel_tips'] ?? '';
        $rating = !empty($_POST['rating']) ? (float)$_POST['rating'] : 4.5;
        $status = $_POST['status'] ?? 'active';
        $circuits = $_POST['circuits'] ?? '';
        $nearby_attractions = $_POST['nearby_attractions'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';

        // Handle Image URL upload or text input
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image_url = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image_url = $_POST['image_url'];
        }

        // Handle Gallery Multi-Image Upload
        $gallery = [];
        if (isset($_FILES['gallery_files'])) {
            $files = $_FILES['gallery_files'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $filename = uniqid('gal_') . '.' . $ext;
                    $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
                    if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                        $gallery[] = '/uploads/' . $filename;
                    }
                }
            }
        }
        $gallery_json = json_encode($gallery);

        $stmt = $db->prepare("
            INSERT INTO destinations (
                name, slug, category, location, district, latitude, longitude, 
                description, history, travel_tips, rating, status, circuits, 
                nearby_attractions, image_url, gallery_json, meta_title, meta_description
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $name, $slug, $category, $location, $district, $latitude, $longitude,
            $description, $history, $travel_tips, $rating, $status, $circuits,
            $nearby_attractions, $image_url, $gallery_json, $meta_title, $meta_description
        ]);
        
        Session::setFlash('success', 'Destination created successfully.');
        $this->redirect('/admin/tourism');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        $category = $_POST['category'] ?? '';
        $location = $_POST['location'] ?? '';
        $district = $_POST['district'] ?? '';
        $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
        $description = $_POST['description'] ?? '';
        $history = $_POST['history'] ?? '';
        $travel_tips = $_POST['travel_tips'] ?? '';
        $rating = !empty($_POST['rating']) ? (float)$_POST['rating'] : 4.5;
        $status = $_POST['status'] ?? 'active';
        $circuits = $_POST['circuits'] ?? '';
        $nearby_attractions = $_POST['nearby_attractions'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';

        // Fetch current values
        $currentStmt = $db->prepare("SELECT image_url, gallery_json FROM destinations WHERE id = ?");
        $currentStmt->execute([$id]);
        $current = $currentStmt->fetch(PDO::FETCH_ASSOC);
        $image_url = $current['image_url'] ?? '';
        $gallery = json_decode($current['gallery_json'] ?? '[]', true) ?: [];

        // Image file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image_url = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image_url = $_POST['image_url'];
        }

        // Gallery multi uploads
        if (isset($_FILES['gallery_files'])) {
            $files = $_FILES['gallery_files'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $filename = uniqid('gal_') . '.' . $ext;
                    $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
                    if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                        $gallery[] = '/uploads/' . $filename;
                    }
                }
            }
        }
        $gallery_json = json_encode($gallery);

        $stmt = $db->prepare("
            UPDATE destinations 
            SET name=?, slug=?, category=?, location=?, district=?, latitude=?, longitude=?, 
                description=?, history=?, travel_tips=?, rating=?, status=?, circuits=?, 
                nearby_attractions=?, image_url=?, gallery_json=?, meta_title=?, meta_description=?
            WHERE id=?
        ");
        
        $stmt->execute([
            $name, $slug, $category, $location, $district, $latitude, $longitude,
            $description, $history, $travel_tips, $rating, $status, $circuits,
            $nearby_attractions, $image_url, $gallery_json, $meta_title, $meta_description,
            $id
        ]);
        
        Session::setFlash('success', 'Destination updated successfully.');
        $this->redirect('/admin/tourism');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM destinations WHERE id=?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Destination deleted successfully.');
        }
        $this->redirect('/admin/tourism');
    }
}
