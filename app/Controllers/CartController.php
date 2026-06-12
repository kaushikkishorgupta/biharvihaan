<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use PDO;

class CartController extends Controller {

    private function getCartId() {
        $db = Database::getInstance()->getConnection();
        $userId = Session::isLoggedIn() ? Session::get('user_id') : null;
        $sessionId = session_id();

        // Find existing cart
        if ($userId) {
            $stmt = $db->prepare("SELECT id FROM carts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$userId]);
        } else {
            $stmt = $db->prepare("SELECT id FROM carts WHERE session_id = ? AND status = 'active'");
            $stmt->execute([$sessionId]);
        }
        
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return $cart['id'];
        }

        // Create new cart
        if ($userId) {
            $stmt = $db->prepare("INSERT INTO carts (user_id, status) VALUES (?, 'active')");
            $stmt->execute([$userId]);
        } else {
            $stmt = $db->prepare("INSERT INTO carts (session_id, status) VALUES (?, 'active')");
            $stmt->execute([$sessionId]);
        }
        return $db->lastInsertId();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $cartId = $this->getCartId();

        $stmt = $db->prepare("
            SELECT ci.id as cart_item_id, ci.quantity, p.* 
            FROM cart_items ci 
            JOIN products p ON ci.product_id = p.id 
            WHERE ci.cart_id = ?
        ");
        $stmt->execute([$cartId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $this->render('cart', [
            'items' => $items, 
            'subtotal' => $subtotal,
            'title' => 'Shopping Cart'
        ]);
    }

    public function add() {
        $db = Database::getInstance()->getConnection();
        $cartId = $this->getCartId();
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$productId || $quantity < 1) {
            $this->redirect('/marketplace?error=Invalid product');
            return;
        }

        // Check if item exists in cart
        $stmt = $db->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cartId, $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch product price
        $stmtPrice = $db->prepare("SELECT price FROM products WHERE id = ?");
        $stmtPrice->execute([$productId]);
        $product = $stmtPrice->fetch(PDO::FETCH_ASSOC);
        $price = $product ? $product['price'] : 0;

        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            $stmt = $db->prepare("UPDATE cart_items SET quantity = ?, price_at_addition = ? WHERE id = ?");
            $stmt->execute([$newQuantity, $price, $existing['id']]);
        } else {
            // Insert new item
            $stmt = $db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price_at_addition) VALUES (?, ?, ?, ?)");
            $stmt->execute([$cartId, $productId, $quantity, $price]);
        }

        $this->redirect('/cart?success=Item added to cart');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $cartId = $this->getCartId();
        
        $itemId = $_POST['item_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($itemId && $quantity > 0) {
            $stmt = $db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND cart_id = ?");
            $stmt->execute([$quantity, $itemId, $cartId]);
            $this->redirect('/cart?success=Cart updated');
        } elseif ($itemId && $quantity <= 0) {
            // Remove if 0
            $stmt = $db->prepare("DELETE FROM cart_items WHERE id = ? AND cart_id = ?");
            $stmt->execute([$itemId, $cartId]);
            $this->redirect('/cart?success=Item removed');
        } else {
            $this->redirect('/cart');
        }
    }

    public function remove() {
        $db = Database::getInstance()->getConnection();
        $cartId = $this->getCartId();
        $itemId = $_POST['item_id'] ?? null;

        if ($itemId) {
            $stmt = $db->prepare("DELETE FROM cart_items WHERE id = ? AND cart_id = ?");
            $stmt->execute([$itemId, $cartId]);
        }
        
        $this->redirect('/cart?success=Item removed from cart');
    }
}
