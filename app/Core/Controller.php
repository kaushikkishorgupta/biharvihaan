<?php

namespace App\Core;

class Controller {
    public function __construct() {
        // 1. Session Rate Limiting Check
        if (!Session::checkRateLimit(120, 60)) {
            http_response_code(429);
            die("Too Many Requests. Rate limit exceeded (120 requests/min).");
        }

        // 2. CSRF Token Validation for non-API POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, '/api/') === false && strpos($uri, '/bookings/pay') === false) {
                $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
                if (!Session::validateCsrfToken($token)) {
                    http_response_code(403);
                    die("CSRF Verification Failed. Suspicious request blocked.");
                }
            }
        }
    }

    // Renders web page layouts
    public function render($view, $data = []) {
        // Globally fetch settings and menus if not provided
        if (!isset($data['settings'])) {
            $data['settings'] = [];
        }
        if (!isset($data['menus'])) {
            $data['menus'] = [];
        }
        
        // Extract variables to page scope
        extract($data);
        
        $viewPath = dirname(__DIR__) . "/Views/$view.php";
        $headerPath = dirname(__DIR__) . "/Views/layout/header.php";
        $footerPath = dirname(__DIR__) . "/Views/layout/footer.php";

        if (file_exists($viewPath)) {
            // Include layouts if they exist, else just the view
            if (file_exists($headerPath) && file_exists($footerPath) && strpos($view, 'api_docs') === false) {
                require $headerPath;
                require $viewPath;
                require $footerPath;
            } else {
                require $viewPath;
            }
        } else {
            die("View template '$view' not found at path: $viewPath");
        }
    }

    // Return structured JSON for REST API
    public function json($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // Redirect to a specific site URL
    public function redirect($path) {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    // Basic sanitization wrapper
    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
}
