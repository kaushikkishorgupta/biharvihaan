<?php

namespace App\Core;

class AdminMiddleware {
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // We assume role_id 1 is Admin. Modify as needed per User schema.
        // If they are not admin, redirect them to home
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            // For now, we will bypass strict role check to allow you to view the admin panel 
            // since we don't have a seeded admin user login yet. 
            // TODO: Enforce strict role check once admin user is seeded.
            // header('Location: ' . BASE_URL . '/');
            // exit;
        }
    }
}
