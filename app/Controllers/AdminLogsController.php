<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use PDO;

class AdminLogsController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('view_activity_logs');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // Fetch logs (limit to last 200 items to keep view fast, with pagination potential)
        $stmt = $db->query("
            SELECT l.*, u.name as user_name 
            FROM activity_logs l
            LEFT JOIN users u ON l.user_id = u.id 
            ORDER BY l.created_at DESC 
            LIMIT 200
        ");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/logs', [
            'title' => 'System Audit Logs | Bihar Vihaan',
            'logs' => $logs
        ]);
    }
}
