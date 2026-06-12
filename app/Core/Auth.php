<?php

namespace App\Core;

use App\Models\User;

class Auth {

    // ─────────────────────────────────────────────────────────
    // ATTEMPT LOGIN — caches permissions into session
    // ─────────────────────────────────────────────────────────
    public static function attempt($email, $password) {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && $user['status'] === 'active' && password_verify($password, $user['password'])) {
            Session::set('user_id',      $user['id']);
            Session::set('user_name',    $user['name']);
            Session::set('user_email',   $user['email']);
            Session::set('user_role',    $user['role_name']);
            Session::set('user_role_id', $user['role_id']);

            // Cache permissions into session (avoids per-request DB queries)
            self::cachePermissions($user['role_id']);

            Logger::log('User Login Successful', "User ID {$user['id']} logged in successfully.");
            return $user;
        }

        Logger::log('User Login Failed', "Failed login attempt for email: $email");
        return false;
    }

    // ─────────────────────────────────────────────────────────
    // CACHE PERMISSIONS — called on login, stored in session
    // ─────────────────────────────────────────────────────────
    private static function cachePermissions(int $roleId): void {
        // Super Admin (role_id=1) gets a special '*' wildcard — no DB needed
        if ($roleId === 1) {
            Session::set('user_permissions', ['*']);
            return;
        }

        $db = Database::getInstance();
        $sql = "SELECT p.slug FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                WHERE rp.role_id = ?";

        $rows = $db->query($sql, [$roleId]);
        $slugs = array_column($rows, 'slug');
        Session::set('user_permissions', $slugs);
    }

    // ─────────────────────────────────────────────────────────
    // AUTH::CAN — the primary permission check helper
    // ─────────────────────────────────────────────────────────
    public static function can(string $permission): bool {
        if (!self::check()) {
            return false;
        }

        $perms = Session::get('user_permissions') ?? [];

        // Super Admin wildcard
        if (in_array('*', $perms)) {
            return true;
        }

        return in_array($permission, $perms);
    }

    // ─────────────────────────────────────────────────────────
    // AUTH::CAN ANY — returns true if any of the given perms match
    // ─────────────────────────────────────────────────────────
    public static function canAny(array $permissions): bool {
        foreach ($permissions as $perm) {
            if (self::can($perm)) {
                return true;
            }
        }
        return false;
    }

    // ─────────────────────────────────────────────────────────
    // BASIC CHECKS
    // ─────────────────────────────────────────────────────────
    public static function check(): bool {
        return Session::has('user_id');
    }

    public static function logout(): void {
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

    public static function hasRole(string $roleName): bool {
        if (!self::check()) return false;
        return Session::get('user_role') === $roleName;
    }

    public static function hasRoles(array $roles = []): bool {
        if (!self::check()) return false;
        return in_array(Session::get('user_role'), $roles);
    }

    // Legacy DB-query version (kept for backward compat, prefer can())
    public static function hasPermission(string $permissionName): bool {
        return self::can($permissionName);
    }

    // ─────────────────────────────────────────────────────────
    // MIDDLEWARE GUARDS
    // ─────────────────────────────────────────────────────────

    /**
     * Require any authenticated admin (role_id 1-4).
     * Use this for the dashboard (visible to all admin roles).
     */
    public static function requireAdmin(): void {
        self::requireLogin();
        $roleId = (int) Session::get('user_role_id');
        if (!in_array($roleId, [1, 2, 3, 4])) {
            Session::setFlash('error', 'You do not have administrative access.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    /**
     * Require a specific permission slug.
     * Super Admin (role_id=1) always passes.
     */
    public static function requirePermission(string $permission): void {
        self::requireLogin();
        if (!self::can($permission)) {
            Session::setFlash('error', 'You do not have permission to access this module.');
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
    }

    /**
     * Require at least one of the given permissions.
     */
    public static function requireAnyPermission(array $permissions): void {
        self::requireLogin();
        if (!self::canAny($permissions)) {
            Session::setFlash('error', 'You do not have permission to access this module.');
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
    }

    /**
     * Require Super Admin (role_id=1) only.
     */
    public static function requireSuperAdmin(): void {
        self::requireLogin();
        if ((int) Session::get('user_role_id') !== 1) {
            Session::setFlash('error', 'This area is restricted to Super Administrators only.');
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            Session::setFlash('error', 'Please log in to access this page.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireRole(string $role): void {
        self::requireLogin();
        if (!self::hasRole($role)) {
            Session::setFlash('error', 'You do not have authorization to view that resource.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public static function requireRoles(array $roles = []): void {
        self::requireLogin();
        if (!self::hasRoles($roles)) {
            Session::setFlash('error', 'You do not have authorization to view that resource.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public static function requirePermissionLegacy(string $permission): void {
        self::requirePermission($permission);
    }
}
