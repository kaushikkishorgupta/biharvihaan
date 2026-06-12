<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use PDO;

class CheckoutController extends Controller {

    private $rzpKey = RAZORPAY_KEY_ID;
    private $rzpSecret = RAZORPAY_KEY_SECRET;

    private function getCartId() {
        $db = Database::getInstance()->getConnection();
        $userId = Session::isLoggedIn() ? Session::get('user_id') : null;
        $sessionId = session_id();

        if ($userId) {
            $stmt = $db->prepare("SELECT id FROM carts WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$userId]);
        } else {
            $stmt = $db->prepare("SELECT id FROM carts WHERE session_id = ? AND status = 'active'");
            $stmt->execute([$sessionId]);
        }
        
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cart ? $cart['id'] : null;
    }

    public function index() {
        $cartId = $this->getCartId();
        if (!$cartId) {
            $this->redirect('/cart');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT ci.quantity, p.* 
            FROM cart_items ci 
            JOIN products p ON ci.product_id = p.id 
            WHERE ci.cart_id = ?
        ");
        $stmt->execute([$cartId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($items)) {
            $this->redirect('/cart');
            return;
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $this->render('checkout', [
            'items' => $items, 
            'subtotal' => $subtotal,
            'title' => 'Checkout',
            'rzpKey' => $this->rzpKey
        ]);
    }

    public function create_order() {
        $cartId = $this->getCartId();
        if (!$cartId) {
            echo json_encode(['success' => false, 'message' => 'Cart empty']);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT ci.product_id, ci.quantity, p.price, p.name 
            FROM cart_items ci 
            JOIN products p ON ci.product_id = p.id 
            WHERE ci.cart_id = ?
        ");
        $stmt->execute([$cartId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Cart empty']);
            return;
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        // Create Razorpay Order via cURL
        $amountInPaise = intval($subtotal * 100);
        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_USERPWD, $this->rzpKey . ":" . $this->rzpSecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'receipt' => 'receipt_' . time()
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        $rzpOrder = json_decode($response, true);

        if (isset($rzpOrder['id'])) {
            // Create Local Order
            $userId = Session::isLoggedIn() ? Session::get('user_id') : null;
            $billingName = $_POST['billing_name'] ?? 'Guest';
            $billingEmail = $_POST['billing_email'] ?? 'guest@example.com';
            $billingPhone = $_POST['billing_phone'] ?? '0000000000';
            $billingAddress = $_POST['billing_address'] ?? '';

            $stmt = $db->prepare("INSERT INTO orders (user_id, billing_name, billing_email, billing_phone, billing_address, subtotal, gst_amount, total_price, payment_status, razorpay_order_id, delivery_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, 'processing')");
            $stmt->execute([
                $userId, $billingName, $billingEmail, $billingPhone, $billingAddress,
                $subtotal, 0, $subtotal, $rzpOrder['id']
            ]);
            $orderId = $db->lastInsertId();

            // Insert Order Items
            foreach ($items as $item) {
                $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmtItem->execute([$orderId, $item['product_id'], $item['quantity'], $item['price'], ($item['price'] * $item['quantity'])]);
            }

            echo json_encode([
                'success' => true, 
                'rzp_order_id' => $rzpOrder['id'],
                'amount' => $amountInPaise,
                'local_order_id' => $orderId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create Razorpay order', 'debug' => $rzpOrder]);
        }
    }

    public function verify() {
        $rzpPaymentId = $_POST['razorpay_payment_id'] ?? '';
        $rzpOrderId = $_POST['razorpay_order_id'] ?? '';
        $rzpSignature = $_POST['razorpay_signature'] ?? '';

        $generatedSignature = hash_hmac('sha256', $rzpOrderId . '|' . $rzpPaymentId, $this->rzpSecret);

        if ($generatedSignature === $rzpSignature) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE orders SET payment_status = 'paid', razorpay_payment_id = ?, razorpay_signature = ? WHERE razorpay_order_id = ?");
            $stmt->execute([$rzpPaymentId, $rzpSignature, $rzpOrderId]);

            // Clear Cart
            $cartId = $this->getCartId();
            if ($cartId) {
                $db->prepare("DELETE FROM cart_items WHERE cart_id = ?")->execute([$cartId]);
                $db->prepare("UPDATE carts SET status = 'completed' WHERE id = ?")->execute([$cartId]);
            }

            // Record Payment
            $stmtOrder = $db->prepare("SELECT * FROM orders WHERE razorpay_order_id = ?");
            $stmtOrder->execute([$rzpOrderId]);
            $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                $stmtPay = $db->prepare("INSERT INTO payments (user_id, order_id, transaction_id, amount, gateway, status, reference_type, reference_id) VALUES (?, ?, ?, ?, 'razorpay', 'captured', 'marketplace', ?)");
                $stmtPay->execute([$order['user_id'] ?? 0, $order['id'], $rzpPaymentId, $order['total_price'], $order['id']]);
            }

            $this->redirect('/checkout/success?order_id=' . $order['id']);
        } else {
            $this->redirect('/checkout/failed');
        }
    }

    public function success() {
        $this->render('checkout_success', ['title' => 'Order Success']);
    }

    public function failed() {
        $this->render('checkout_failed', ['title' => 'Payment Failed']);
    }
}
