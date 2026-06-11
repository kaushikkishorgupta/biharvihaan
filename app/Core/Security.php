<?php

namespace App\Core;

class Security {
    
    /**
     * Generate a new CSRF token and store it in the session
     * @return string
     */
    public static function generateCsrfToken(): string {
        if (!Session::get('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return Session::get('csrf_token');
    }

    /**
     * Verify the provided CSRF token against the session
     * @param string|null $token
     * @return bool
     */
    public static function verifyCsrfToken(?string $token): bool {
        if (!$token || !Session::get('csrf_token')) {
            return false;
        }
        return hash_equals(Session::get('csrf_token'), $token);
    }

    /**
     * Get a hidden HTML input field containing the CSRF token
     * @return string
     */
    public static function csrfField(): string {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Sanitize user input recursively
     * @param mixed $data
     * @return mixed
     */
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } elseif (is_string($data)) {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}
