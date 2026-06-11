<?php

namespace App\Core;

use App\Models\User;

class Auth {
    
    public static function attempt($email, $password) {
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if ($user && $user['status'] === 'active' && password_verify($password, $user['password'])) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role_name']);
            Session::set('user_role_id', $user['role_id']);
            
            // Log this action
            Logger::log('User Login Successful', "User ID {$user['id']} logged in successfully.");
            return $user;
        }
        
        Logger::log('User Login Failed', "Failed login attempt for email: $email");
        return false;
    }

    public static function check() {
        return Session::has('user_id');
    }

    public static function logout() {
        if (self::check()) {
            Logger::log('User Logout', "User ID " . Session::get('user_id') . " logged out.");
            Session::destroy();
        }
    }

    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        $userModel = new User();
        return $userModel->find(Session::get('user_id'));
    }

    public static function hasRole($roleName) {
        if (!self::check()) {
            return false;
        }
        return Session::get('user_role') === $roleName;
    }

    public static function hasRoles($roles = []) {
        if (!self::check()) {
            return false;
        }
        return in_array(Session::get('user_role'), $roles);
    }

    public static function hasPermission($permissionName) {
        if (!self::check()) {
            return false;
        }
        
        $db = Database::getInstance();
        $sql = "SELECT p.name FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                WHERE rp.role_id = ? AND p.name = ?";
        
        $perm = $db->queryRow($sql, [Session::get('user_role_id'), $permissionName]);
        return !empty($perm);
    }

    // Role verification middleware redirection helpers
    public static function requireLogin() {
        if (!self::check()) {
            Session::setFlash('error', 'Please log in to access this page.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            Session::setFlash('error', 'You do not have authorization to view that resource.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public static function requireRoles($roles = []) {
        self::requireLogin();
        if (!self::hasRoles($roles)) {
            Session::setFlash('error', 'You do not have authorization to view that resource.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public static function requirePermission($permission) {
        self::requireLogin();
        if (!self::hasPermission($permission)) {
            Session::setFlash('error', 'You do not have the required permissions to perform this action.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }
}
