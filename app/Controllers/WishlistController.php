<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use PDO;

class WishlistController extends Controller {

    public function index() {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login?redirect=/wishlist');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $userId = Session::get('user_id');

        $stmt = $db->prepare("
            SELECT w.id as wishlist_id, p.* 
            FROM wishlists w 
            JOIN products p ON w.product_id = p.id 
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('wishlist', ['items' => $items, 'title' => 'My Wishlist']);
    }

    public function toggle() {
        if (!Session::isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Please login to use wishlist', 'redirect' => '/login']);
            return;
        }

        $productId = $_POST['product_id'] ?? null;
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $userId = Session::get('user_id');

        // Check if already in wishlist
        $stmt = $db->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        
        if ($stmt->fetch()) {
            // Remove
            $stmt = $db->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);
            echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Removed from wishlist']);
        } else {
            // Add
            $stmt = $db->prepare("INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$userId, $productId]);
            echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Added to wishlist']);
        }
    }

    public function remove() {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
            return;
        }

        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance()->getConnection();
            $userId = Session::get('user_id');
            $stmt = $db->prepare("DELETE FROM wishlists WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);
        }
        $this->redirect('/wishlist?success=Item removed from wishlist');
    }
}
