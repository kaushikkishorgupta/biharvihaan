<?php

namespace App\Core;

class Logger {
    public static function log($action, $details = null) {
        try {
            $db = Database::getInstance();
            $userId = Session::get('user_id', null);
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

            $sql = "INSERT INTO activity_logs (user_id, action, ip_address, user_agent, details) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $db->execute($sql, [$userId, $action, $ipAddress, $userAgent, $details]);
        } catch (\Exception $e) {
            // Silently ignore log errors to avoid breaking execution if DB connection is lost
            if (APP_DEBUG) {
                error_log("Logging failed: " . $e->getMessage());
            }
        }
    }
}
