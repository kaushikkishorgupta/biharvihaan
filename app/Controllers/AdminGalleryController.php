<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminGalleryController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_gallery');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT * FROM gallery_images ORDER BY id DESC');
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/gallery', [
            'title' => 'Gallery Pinterest Manager | Bihar Vihaan',
            'items' => $items
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $title = $_POST['title'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . time();
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $location = $_POST['location'] ?? '';
        $photographer = $_POST['photographer'] ?? '';
        $status = $_POST['status'] ?? 'published';

        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('gallery_') . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image = $_POST['image_url'];
        }

        $stmt = $db->prepare("INSERT INTO gallery_images (title, slug, category, description, location, photographer, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $description, $location, $photographer, $image, $status]);
        
        Session::setFlash('success', 'Image added to gallery successfully.');
        $this->redirect('/admin/gallery');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $location = $_POST['location'] ?? '';
        $photographer = $_POST['photographer'] ?? '';
        $status = $_POST['status'] ?? 'published';

        // Fetch current image
        $currentStmt = $db->prepare("SELECT image FROM gallery_images WHERE id = ?");
        $currentStmt->execute([$id]);
        $image = $currentStmt->fetchColumn() ?: '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('gallery_') . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image = $_POST['image_url'];
        }

        $stmt = $db->prepare("UPDATE gallery_images SET title=?, category=?, description=?, location=?, photographer=?, image=?, status=? WHERE id=?");
        $stmt->execute([$title, $category, $description, $location, $photographer, $image, $status, $id]);
        
        Session::setFlash('success', 'Gallery image updated successfully.');
        $this->redirect('/admin/gallery');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM gallery_images WHERE id=?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Gallery image deleted.');
        }
        $this->redirect('/admin/gallery');
    }
}
