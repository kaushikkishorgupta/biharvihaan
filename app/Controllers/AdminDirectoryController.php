<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminDirectoryController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_directory');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT * FROM businesses ORDER BY id DESC');
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/directory', [
            'title' => 'Business Directory Manager | Bihar Vihaan',
            'items' => $items
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $name = $_POST['name'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $website = $_POST['website'] ?? '';
        $image_url = $_POST['image_url'] ?? '';
        $is_verified = isset($_POST['is_verified']) ? 1 : 0;
        $rating = !empty($_POST['rating']) ? (float)$_POST['rating'] : 4.5;
        $status = $_POST['status'] ?? 'active';

        $stmt = $db->prepare("
            INSERT INTO businesses (name, slug, category, description, address, phone, email, website, image_url, is_verified, rating, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $slug, $category, $description, $address, $phone, $email, $website, $image_url, $is_verified, $rating, $status]);
        
        Session::setFlash('success', 'Business directory entry created.');
        $this->redirect('/admin/businesses');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $website = $_POST['website'] ?? '';
        $image_url = $_POST['image_url'] ?? '';
        $is_verified = isset($_POST['is_verified']) ? 1 : 0;
        $rating = !empty($_POST['rating']) ? (float)$_POST['rating'] : 4.5;
        $status = $_POST['status'] ?? 'active';

        $stmt = $db->prepare("
            UPDATE businesses 
            SET name=?, category=?, description=?, address=?, phone=?, email=?, website=?, image_url=?, is_verified=?, rating=?, status=? 
            WHERE id=?
        ");
        $stmt->execute([$name, $category, $description, $address, $phone, $email, $website, $image_url, $is_verified, $rating, $status, $id]);
        
        Session::setFlash('success', 'Business directory entry updated.');
        $this->redirect('/admin/businesses');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM businesses WHERE id=?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Business entry deleted.');
        }
        $this->redirect('/admin/businesses');
    }
}
