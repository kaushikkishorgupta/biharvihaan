<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use PDO;

class UserController extends Controller {

    public function dashboard() {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $userId = Session::get('user_id');
        $tab = $_GET['tab'] ?? 'profile';

        // Load active user details
        $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $data = [
            'user' => $user,
            'tab' => $tab,
            'title' => 'User Dashboard | Bihar Vihaan'
        ];

        // Fetch datasets based on selected dashboard tab
        switch ($tab) {
            case 'orders':
                $stmtOrders = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                $stmtOrders->execute([$userId]);
                $data['orders'] = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'wishlist':
                $stmtWish = $db->prepare("
                    SELECT w.*, p.name, p.price, p.image_url, p.slug 
                    FROM wishlists w 
                    JOIN products p ON w.product_id = p.id 
                    WHERE w.user_id = ? 
                    ORDER BY w.created_at DESC
                ");
                $stmtWish->execute([$userId]);
                $data['wishlist'] = $stmtWish->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'saved-destinations':
                $stmtSaved = $db->prepare("
                    SELECT s.*, d.name, d.image_url, d.slug, d.district, d.category 
                    FROM saved_places s 
                    JOIN destinations d ON s.destination_id = d.id 
                    WHERE s.user_id = ? 
                    ORDER BY s.created_at DESC
                ");
                $stmtSaved->execute([$userId]);
                $data['saved_destinations'] = $stmtSaved->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'saved-trips':
                $stmtTrips = $db->prepare("SELECT * FROM itineraries WHERE user_id = ? ORDER BY created_at DESC");
                $stmtTrips->execute([$userId]);
                $data['saved_trips'] = $stmtTrips->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'reviews':
                $stmtRev = $db->prepare("
                    SELECT r.*, 
                           CASE 
                               WHEN r.reference_type = 'destination' THEN d.name
                               WHEN r.reference_type = 'product' THEN p.name
                               WHEN r.reference_type = 'business' THEN b.name
                               ELSE 'General'
                           END as item_name
                    FROM reviews r
                    LEFT JOIN destinations d ON r.reference_type = 'destination' AND r.reference_id = d.id
                    LEFT JOIN products p ON r.reference_type = 'product' AND r.reference_id = p.id
                    LEFT JOIN businesses b ON r.reference_type = 'business' AND r.reference_id = b.id
                    WHERE r.user_id = ?
                    ORDER BY r.created_at DESC
                ");
                $stmtRev->execute([$userId]);
                $data['reviews'] = $stmtRev->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'notifications':
                $stmtNotif = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
                $stmtNotif->execute([$userId]);
                $data['notifications'] = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'profile':
            default:
                // Profile view just renders the user record
                break;
        }

        $this->render('user/dashboard', $data);
    }

    public function updateProfile() {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $userId = Session::get('user_id');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email)) {
            Session::setFlash('error', 'Name and Email fields are required.');
            $this->redirect('/user/dashboard?tab=profile');
            return;
        }

        // Check unique email (except for current user)
        $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmtCheck->execute([$email, $userId]);
        if ($stmtCheck->fetch()) {
            Session::setFlash('error', 'The email address is already in use by another account.');
            $this->redirect('/user/dashboard?tab=profile');
            return;
        }

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $email, $hashedPassword, $userId]);
        } else {
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $userId]);
        }

        // Update session variables
        Session::set('user_name', $name);
        Session::set('user_email', $email);

        Session::setFlash('success', 'Profile updated successfully.');
        $this->redirect('/user/dashboard?tab=profile');
    }

    public function orders() {
        $this->redirect('/user/dashboard?tab=orders');
    }

    public function track() {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            $this->redirect('/user/dashboard?tab=orders');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $userId = Session::get('user_id');

        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $this->redirect('/user/dashboard?tab=orders&error=Order not found');
            return;
        }

        // Fetch items
        $stmtItems = $db->prepare("SELECT oi.*, p.name, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmtItems->execute([$orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        $this->render('user/track_order', ['order' => $order, 'items' => $items, 'title' => 'Track Order']);
    }
}
