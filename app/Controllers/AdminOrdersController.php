<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use PDO;

class AdminOrdersController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_marketplace');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->query("
            SELECT o.*, u.name as user_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/orders', ['items' => $orders]);
    }

    public function view() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/orders');
            return;
        }

        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $this->redirect('/admin/orders?error=Order not found');
            return;
        }

        $stmtItems = $db->prepare("SELECT oi.*, p.name, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmtItems->execute([$id]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        // Fetch payment logs
        $stmtPayments = $db->prepare("SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC");
        $stmtPayments->execute([$id]);
        $payments = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/order_details', ['order' => $order, 'items' => $items, 'payments' => $payments]);
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        $delivery_status = $_POST['delivery_status'] ?? 'processing';
        $tracking_number = $_POST['tracking_number'] ?? '';
        $refund_notes    = $_POST['refund_notes'] ?? '';

        if ($id) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE orders SET delivery_status = ?, tracking_number = ?, refund_notes = ? WHERE id = ?");
            $stmt->execute([$delivery_status, $tracking_number, $refund_notes, $id]);
            $this->redirect('/admin/orders/view?id=' . $id . '&success=Order updated');
        } else {
            $this->redirect('/admin/orders');
        }
    }
}
