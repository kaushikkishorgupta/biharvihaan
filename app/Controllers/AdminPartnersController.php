<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Session;
use PDO;

class AdminPartnersController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_directory');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $partners = $db->query("SELECT * FROM partners ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/partners', [
            'title' => 'Manage Enterprise Partners | Admin Panel',
            'partners' => $partners
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $name = $_POST['name'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $category = $_POST['category'];
        $short_description = $_POST['short_description'];
        $description = $_POST['description'];
        $mission = $_POST['mission'];
        $vision = $_POST['vision'];
        $website = $_POST['website'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        // Handle Image Upload
        $logo = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                $logo = BASE_URL . '/uploads/' . $filename;
            }
        }

        $stmt = $db->prepare("INSERT INTO partners (name, slug, category, short_description, description, mission, vision, logo, website, email, phone, address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $slug, $category, $short_description, $description, $mission, $vision, $logo, $website, $email, $phone, $address, $status])) {
            Session::setFlash('success', 'Partner added successfully.');
        } else {
            Session::setFlash('error', 'Failed to add partner.');
        }

        $this->redirect('/admin/partners');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $short_description = $_POST['short_description'];
        $description = $_POST['description'];
        $mission = $_POST['mission'];
        $vision = $_POST['vision'];
        $website = $_POST['website'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $db->prepare("UPDATE partners SET name=?, category=?, short_description=?, description=?, mission=?, vision=?, website=?, email=?, phone=?, address=?, status=? WHERE id=?");
        if ($stmt->execute([$name, $category, $short_description, $description, $mission, $vision, $website, $email, $phone, $address, $status, $id])) {
            Session::setFlash('success', 'Partner updated successfully.');
        } else {
            Session::setFlash('error', 'Failed to update partner.');
        }

        $this->redirect('/admin/partners');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'];

        $stmt = $db->prepare("DELETE FROM partners WHERE id=?");
        if ($stmt->execute([$id])) {
            Session::setFlash('success', 'Partner deleted successfully.');
        } else {
            Session::setFlash('error', 'Failed to delete partner.');
        }

        $this->redirect('/admin/partners');
    }
}
