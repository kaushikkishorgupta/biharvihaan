<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminMarketplaceController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_marketplace');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // Products
        $stmt = $db->query('SELECT p.*, a.name as artisan_name FROM products p LEFT JOIN artisans a ON p.artisan_id = a.id ORDER BY p.id DESC');
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Artisans list for dropdowns
        $stmtArt = $db->query('SELECT id, name FROM artisans ORDER BY name ASC');
        $artisans = $stmtArt->fetchAll(PDO::FETCH_ASSOC);

        // Orders List
        $stmtOrd = $db->query('SELECT * FROM orders ORDER BY id DESC');
        $orders = $stmtOrd->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/marketplace', [
            'title' => 'Artisan Marketplace Manager | Bihar Vihaan',
            'products' => $products,
            'artisans' => $artisans,
            'orders' => $orders
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $name = $_POST['name'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $materials = $_POST['materials'] ?? '';
        $price = !empty($_POST['price']) ? (float)$_POST['price'] : 0.00;
        $stock = !empty($_POST['stock']) ? (int)$_POST['stock'] : 0;
        $artisan_id = !empty($_POST['artisan_id']) ? (int)$_POST['artisan_id'] : null;
        $location = $_POST['location'] ?? '';
        $image_url = $_POST['image_url'] ?? '';
        $is_handmade = isset($_POST['is_handmade']) ? 1 : 0;
        $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
        $status = $_POST['status'] ?? 'active';

        // Check file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image_url = '/uploads/' . $filename;
            }
        }

        $stmt = $db->prepare("
            INSERT INTO products (
                artisan_id, category, name, slug, description, materials, 
                price, stock, location, image_url, is_handmade, is_bestseller, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $artisan_id, $category, $name, $slug, $description, $materials,
            $price, $stock, $location, $image_url, $is_handmade, $is_bestseller, $status
        ]);

        Session::setFlash('success', 'Marketplace product added successfully.');
        $this->redirect('/admin/marketplace');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $materials = $_POST['materials'] ?? '';
        $price = !empty($_POST['price']) ? (float)$_POST['price'] : 0.00;
        $stock = !empty($_POST['stock']) ? (int)$_POST['stock'] : 0;
        $artisan_id = !empty($_POST['artisan_id']) ? (int)$_POST['artisan_id'] : null;
        $location = $_POST['location'] ?? '';
        $image_url = $_POST['image_url'] ?? '';
        $is_handmade = isset($_POST['is_handmade']) ? 1 : 0;
        $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
        $status = $_POST['status'] ?? 'active';

        // Retrieve current image if not replaced
        $currentStmt = $db->prepare("SELECT image_url FROM products WHERE id = ?");
        $currentStmt->execute([$id]);
        $currImg = $currentStmt->fetchColumn() ?: '';
        if (empty($image_url)) {
            $image_url = $currImg;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image_url = '/uploads/' . $filename;
            }
        }

        $stmt = $db->prepare("
            UPDATE products 
            SET artisan_id=?, category=?, name=?, slug=?, description=?, materials=?, 
                price=?, stock=?, location=?, image_url=?, is_handmade=?, is_bestseller=?, status=?
            WHERE id=?
        ");
        $stmt->execute([
            $artisan_id, $category, $name, $slug, $description, $materials,
            $price, $stock, $location, $image_url, $is_handmade, $is_bestseller, $status, $id
        ]);

        Session::setFlash('success', 'Product details updated.');
        $this->redirect('/admin/marketplace');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM products WHERE id=?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Product deleted from catalog.');
        }
        $this->redirect('/admin/marketplace');
    }

    // Update order shipping status and tracking numbers
    public function update_order() {
        $db = Database::getInstance()->getConnection();
        $order_id = $_POST['order_id'] ?? null;
        $delivery_status = $_POST['delivery_status'] ?? 'processing';
        $tracking_number = $_POST['tracking_number'] ?? '';
        $payment_status = $_POST['payment_status'] ?? 'pending';

        if ($order_id) {
            $stmt = $db->prepare("UPDATE orders SET delivery_status = ?, tracking_number = ?, payment_status = ? WHERE id = ?");
            $stmt->execute([$delivery_status, $tracking_number, $payment_status, $order_id]);
            Session::setFlash('success', 'Order status updated successfully.');
        }
        $this->redirect('/admin/marketplace#orders-panel');
    }
}
