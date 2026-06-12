<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminNotificationController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('view_notifications');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // Fetch notifications (both global system ones and user-bound)
        $stmt = $db->query("
            SELECT * FROM notifications 
            ORDER BY id DESC LIMIT 100
        ");
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/notifications', [
            'title' => 'Notifications Center | Bihar Vihaan',
            'notifications' => $notifications
        ]);
    }

    // AJAX endpoint for unread counts
    public function getUnread() {
        $db = Database::getInstance()->getConnection();

        // Count unread system notifications (status = 'unread' or is_read = 0)
        $count = (int)$db->query("
            SELECT COUNT(*) 
            FROM notifications 
            WHERE status = 'unread' OR is_read = 0
        ")->fetchColumn();

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }

    // Mark notification as read
    public function markRead() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;

        if ($id) {
            $stmt = $db->prepare("UPDATE notifications SET status = 'read', is_read = 1 WHERE id = ?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Notification marked as read.');
        } else {
            // Mark all read
            $db->exec("UPDATE notifications SET status = 'read', is_read = 1");
            Session::setFlash('success', 'All notifications marked as read.');
        }

        $this->redirect('/admin/notifications');
    }
}
