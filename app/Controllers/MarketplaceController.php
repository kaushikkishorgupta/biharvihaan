<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;
use App\Core\Logger;
use App\Services\PaymentService;
use App\Models\MarketplaceModel;

class MarketplaceController extends Controller {
    private $db;
    private $paymentService;
    private $marketplaceModel;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->paymentService = new PaymentService();
        $this->marketplaceModel = new MarketplaceModel();
    }

    /**
     * Marketplace main page
     */
    public function index() {
        $categories = [
            'All', 'Madhubani Paintings', 'Handicrafts', 'Traditional Decor', 
            'Cultural Souvenirs', 'Books & Heritage Collections', 'Festival Collections'
        ];

        $initialProducts = $this->marketplaceModel->getFilteredProducts('All', 'newest', 12, 0);
        $bestSellers = $this->marketplaceModel->getBestSellers();
        $newArrivals = $this->marketplaceModel->getNewArrivals();
        $artisans = $this->marketplaceModel->getFeaturedArtisans();

        $this->render('marketplace', [
            'title' => 'Bihar Marketplace – Authentic Crafts, Art & Souvenirs',
            'categories' => $categories,
            'initialProducts' => $initialProducts,
            'bestSellers' => $bestSellers,
            'newArrivals' => $newArrivals,
            'artisans' => $artisans,
            'view_mode' => 'catalog'
        ]);
    }

    /**
     * AJAX endpoint for loading products via infinite scroll / filters
     */
    public function loadProducts() {
        header('Content-Type: application/json');
        
        $category = $_GET['category'] ?? 'All';
        $sort = $_GET['sort'] ?? 'newest';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $products = $this->marketplaceModel->getFilteredProducts($category, $sort, $limit, $offset);

        echo json_encode(['success' => true, 'products' => $products]);
        exit;
    }

    /**
     * AJAX endpoint for Quick View modal data
     */
    public function quickView() {
        header('Content-Type: application/json');
        $id = intval($_GET['id'] ?? 0);

        if ($id > 0) {
            $product = $this->marketplaceModel->getProductById($id);
            if ($product) {
                echo json_encode(['success' => true, 'product' => $product]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    /**
     * Cart Detail Page
     */
    public function cart() {
        $cartId = $this->getOrCreateCartId();
        
        $sql = "SELECT ci.*, p.name, p.price, p.image_url, p.gst_rate 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.id 
                WHERE ci.cart_id = ?";
        
        $items = $this->db->query($sql, [$cartId]);

        $subtotal = 0;
        $gstAmount = 0;
        foreach ($items as &$item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            $gst_info = $this->paymentService->calculateGst($itemTotal, $item['gst_rate'] ?? 18.00);
            $gstAmount += $gst_info['gst_amount'];
        }

        $this->render('marketplace', [
            'title' => 'Shopping Cart - Bihar Vihaan',
            'items' => $items,
            'subtotal' => $subtotal - $gstAmount,
            'gstAmount' => $gstAmount,
            'total' => $subtotal,
            'view_mode' => 'cart'
        ]);
    }

    public function addToCart() {
        $productId = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);

        if ($productId <= 0) {
            Session::setFlash('error', 'Invalid product selection.');
            $this->redirect('/marketplace');
        }

        $product = $this->db->queryRow("SELECT * FROM products WHERE id = ?", [$productId]);
        if (!$product || $product['stock'] < $quantity) {
            Session::setFlash('error', 'Requested quantity is out of stock.');
            $this->redirect('/marketplace');
        }

        $cartId = $this->getOrCreateCartId();
        $existing = $this->db->queryRow("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?", [$cartId, $productId]);
        
        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            if ($newQty > $product['stock']) $newQty = $product['stock'];
            $this->db->execute("UPDATE cart_items SET quantity = ? WHERE id = ?", [$newQty, $existing['id']]);
        } else {
            $this->db->execute("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)", [$cartId, $productId, $quantity]);
        }

        Session::setFlash('success', 'Product added to cart successfully!');
        $this->redirect('/marketplace/cart');
    }

    public function removeFromCart() {
        $routeParams = $GLOBALS['router']->getParams() ?? [];
        $productId = intval($routeParams['id'] ?? 0);

        if ($productId > 0) {
            $cartId = $this->getOrCreateCartId();
            $this->db->execute("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?", [$cartId, $productId]);
            Session::setFlash('success', 'Item removed from cart.');
        }
        $this->redirect('/marketplace/cart');
    }

    public function checkout() {
        $cartId = $this->getOrCreateCartId();
        
        $sql = "SELECT ci.*, p.name, p.price, p.gst_rate 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.id 
                WHERE ci.cart_id = ?";
        
        $items = $this->db->query($sql, [$cartId]);

        if (empty($items)) {
            Session::setFlash('error', 'Your cart is empty.');
            $this->redirect('/marketplace');
        }

        $subtotal = 0;
        $gstAmount = 0;
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            $gst_info = $this->paymentService->calculateGst($itemTotal, $item['gst_rate'] ?? 18.00);
            $gstAmount += $gst_info['gst_amount'];
        }

        $this->render('marketplace', [
            'title' => 'Checkout Order - Bihar Vihaan',
            'items' => $items,
            'subtotal' => $subtotal - $gstAmount,
            'gstAmount' => $gstAmount,
            'total' => $subtotal,
            'view_mode' => 'checkout'
        ]);
    }

    private function getOrCreateCartId() {
        $userId = Session::get('user_id');
        $sessionId = session_id();

        if ($userId) {
            $cart = $this->db->queryRow("SELECT id FROM carts WHERE user_id = ?", [$userId]);
            if ($cart) return $cart['id'];
            
            $guestCart = $this->db->queryRow("SELECT id FROM carts WHERE session_id = ? AND user_id IS NULL", [$sessionId]);
            if ($guestCart) {
                $this->db->execute("UPDATE carts SET user_id = ? WHERE id = ?", [$userId, $guestCart['id']]);
                return $guestCart['id'];
            }
            $this->db->execute("INSERT INTO carts (user_id, session_id) VALUES (?, ?)", [$userId, $sessionId]);
            return $this->db->lastInsertId();
        } else {
            $cart = $this->db->queryRow("SELECT id FROM carts WHERE session_id = ? AND user_id IS NULL", [$sessionId]);
            if ($cart) return $cart['id'];
            
            $this->db->execute("INSERT INTO carts (user_id, session_id) VALUES (NULL, ?)", [$sessionId]);
            return $this->db->lastInsertId();
        }
    }
}
