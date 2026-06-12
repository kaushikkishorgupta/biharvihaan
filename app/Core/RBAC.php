<?php

namespace App\Core;

class RBAC {
    public static function can($permissionSlug) {
        $userId = Session::get('user_id');
        if (!$userId) return false;

        $db = Database::getInstance()->getConnection();

        // If Super Admin, allow all
        $stmt = $db->prepare("SELECT role_id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) return false;

        $roleStmt = $db->prepare("SELECT slug FROM roles WHERE id = ?");
        $roleStmt->execute([$user['role_id']]);
        $role = $roleStmt->fetch();

        if ($role && $role['slug'] === 'super_admin') {
            return true;
        }

        // Check specific permission
        $permStmt = $db->prepare("
            SELECT p.id 
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = ? AND p.slug = ?
        ");
        $permStmt->execute([$user['role_id'], $permissionSlug]);
        
        return $permStmt->rowCount() > 0;
    }

    public static function requirePermission($permissionSlug) {
        if (!self::can($permissionSlug)) {
            Session::setFlash('error', 'You do not have permission to perform this action.');
            $router = new Router();
            header("Location: " . BASE_URL . "/admin/dashboard");
            exit;
        }
    }
}
