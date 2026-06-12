<?php

namespace App\Core;

class RoleMiddleware {
    
    public static function handle($url) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Normalize URL path
        $url = parse_url($url, PHP_URL_PATH);
        
        // Default route context if deployed in subfolders (e.g. /biharvihaan/admin -> /admin)
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($scriptName !== '/') {
            $url = preg_replace('#^' . preg_quote($scriptName, '#') . '#', '', $url);
        }
        
        if (empty($url)) {
            $url = '/';
        }
        
        // Check routes starting with /admin or /superadmin
        if (strpos($url, '/admin') === 0 || strpos($url, '/superadmin') === 0) {
            // Check if logged in
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['flash_error'] = 'Please log in to access the administration panel.';
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
            
            // Administrative roles list
            $allowedRoles = ['super_admin', 'admin', 'editor', 'contributor', 'Super Admin', 'Admin', 'Editor', 'Contributor'];
            $allowedIds = [1, 2, 3, 4];
            
            $userRole = $_SESSION['user_role'] ?? '';
            $userRoleId = $_SESSION['user_role_id'] ?? null;
            
            if (!in_array($userRole, $allowedRoles) && !in_array($userRoleId, $allowedIds)) {
                $_SESSION['flash_error'] = 'Access Denied: You do not have authorization to access the admin panel.';
                
                // If standard user, redirect to User Dashboard, else to Homepage
                if ($userRole === 'user' || $userRoleId == 8) {
                    header('Location: ' . BASE_URL . '/user/dashboard');
                } else {
                    header('Location: ' . BASE_URL . '/');
                }
                exit;
            }
        }
    }
}
