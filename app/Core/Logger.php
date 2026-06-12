<?php

namespace App\Core;

class Logger {
    public static function log($action, $details = null, $module = 'System') {
        try {
            $db = Database::getInstance();
            $userId = Session::get('user_id', null);
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

            $sql = "INSERT INTO activities (user_id, action, module, details, ip_address) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $db->execute($sql, [$userId, $action, $module, $details, $ipAddress]);
        } catch (\Exception $e) {
            // Silently ignore log errors to avoid breaking execution if DB connection is lost
            if (APP_DEBUG) {
                error_log("Logging failed: " . $e->getMessage());
            }
        }
    }
}
