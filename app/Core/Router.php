<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function add($method, $route, $controller, $action = null) {
        if ($action === null && strpos($controller, '@') !== false) {
            list($controller, $action) = explode('@', $controller, 2);
        }
        // Convert route like '/tourism/{id}' to regex '/tourism/(?P<id>[^/]+)'
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route);
        $route = '#^' . $route . '$#';
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'route' => $route,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($route, $controller, $action = null) {
        $this->add('GET', $route, $controller, $action);
    }

    public function post($route, $controller, $action = null) {
        $this->add('POST', $route, $controller, $action);
    }

    public function dispatch($url, $requestMethod) {
        // Remove query parameters from URL: /tourism?page=1 -> /tourism
        $url = parse_url($url, PHP_URL_PATH);
        
        // Strip trailing slash unless it's just '/'
        if ($url !== '/' && substr($url, -1) === '/') {
            $url = rtrim($url, '/');
        }

        // Default route context if deployed in subfolders (e.g. /biharvihaan/tourism -> /tourism)
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($scriptName !== '/') {
            $url = preg_replace('#^' . preg_quote($scriptName, '#') . '#', '', $url);
        }
        if (empty($url)) {
            $url = '/';
        }

        foreach ($this->routes as $routeInfo) {
            if ($routeInfo['method'] === $requestMethod && preg_match($routeInfo['route'], $url, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                $controllerClass = "App\\Controllers\\" . $routeInfo['controller'];
                $action = $routeInfo['action'];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $action)) {
                        call_user_func_array([$controller, $action], [$params]);
                        return;
                    } else {
                        $this->abort("Action '$action' not found in controller '$controllerClass'", 500);
                        return;
                    }
                } else {
                    $this->abort("Controller class '$controllerClass' not found", 500);
                    return;
                }
            }
        }

        $this->abort("Page not found", 404);
    }

    protected function abort($message = "Not Found", $code = 404) {
        http_response_code($code);
        
        // If it is an API request, return JSON
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'status' => $code,
                'message' => $message
            ]);
            exit;
        }

        // Otherwise show 404 view
        $errorTitle = ($code === 404) ? "404 - Page Not Found" : "500 - Internal Server Error";
        $errorMsg = htmlspecialchars($message);
        
        // Basic styled error page
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>{$errorTitle}</title>
            <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap' rel='stylesheet'>
            <style>
                body { background: #0f172a; color: #f8fafc; font-family: 'Outfit', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; text-align: center; }
                .card { background: #1e293b; padding: 3rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
                h1 { color: #f97316; font-size: 3rem; margin: 0 0 1rem; }
                p { color: #94a3b8; font-size: 1.1rem; line-height: 1.5; margin-bottom: 2rem; }
                a { background: #14b8a6; color: white; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; transition: background 0.2s; }
                a:hover { background: #0d9488; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h1>{$code}</h1>
                <p>{$errorMsg}</p>
                <a href='" . BASE_URL . "/'>Back to Safety</a>
            </div>
        </body>
        </html>
        ";
        exit;
    }
}
