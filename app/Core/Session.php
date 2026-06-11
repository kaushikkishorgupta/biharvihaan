<?php

namespace App\Core;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Force cookie parameters for security
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            
            session_start();
        }

        // Detect potential Session Hijacking
        if (!isset($_SESSION['user_agent'])) {
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        } else {
            if ($_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '') || 
                $_SESSION['user_ip'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
                self::destroy();
                session_start();
            }
        }

        // Regenerate session ID periodically
        if (!isset($_SESSION['created_time'])) {
            $_SESSION['created_time'] = time();
        } elseif (time() - $_SESSION['created_time'] > 1800) { // 30 mins
            session_regenerate_id(true);
            $_SESSION['created_time'] = time();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    // CSRF Protection
    public static function generateCsrfToken() {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    public static function validateCsrfToken($token) {
        if (!self::has('csrf_token') || empty($token)) {
            return false;
        }
        return hash_equals(self::get('csrf_token'), $token);
    }

    // Flash Messages
    public static function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }

    public static function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }

    // Session-based request Rate Limiting
    public static function checkRateLimit($maxRequests = 60, $timeWindow = 60) {
        $now = time();
        if (!self::has('rate_limit_hits')) {
            self::set('rate_limit_hits', []);
        }

        $hits = self::get('rate_limit_hits');
        // Filter out old hits
        $hits = array_filter($hits, function ($timestamp) use ($now, $timeWindow) {
            return ($now - $timestamp) < $timeWindow;
        });

        if (count($hits) >= $maxRequests) {
            return false;
        }

        $hits[] = $now;
        self::set('rate_limit_hits', $hits);
        return true;
    }
}

