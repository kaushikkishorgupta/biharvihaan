<?php
/**
 * Bihar Vihaan Enterprise Configuration and Autoloader
 */

// Helper to retrieve .env variables
if (!function_exists('env')) {
    function env($key, $default = null) {
        static $env = null;
        if ($env === null) {
            $env = [];
            $path = dirname(__DIR__, 2) . '/.env';
            if (file_exists($path)) {
                $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    // Skip comments and empty lines
                    if (empty($line) || strpos($line, '#') === 0) {
                        continue;
                    }
                    if (strpos($line, '=') !== false) {
                        list($name, $value) = explode('=', $line, 2);
                        $env[trim($name)] = trim($value);
                    }
                }
            }
        }
        return $env[$key] ?? getenv($key) ?: $default;
    }
}

// Check if setup is needed
if (!file_exists(dirname(__DIR__, 2) . '/.env') && basename($_SERVER['PHP_SELF']) !== 'install.php') {
    header('Location: install.php');
    exit;
}

// Global Configuration Constants
define('APP_ENV', env('APP_ENV', 'production'));
define('APP_DEBUG', env('APP_DEBUG', 'false') === 'true');
define('BASE_URL', rtrim(env('APP_URL', 'http://localhost/biharvihaan'), '/'));

// DB Settings
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_DATABASE', env('DB_DATABASE', 'biharvihaan_db'));
define('DB_USERNAME', env('DB_USERNAME', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', ''));

// API Integrations
define('RAZORPAY_KEY_ID', env('RAZORPAY_KEY_ID', 'rzp_test_BiharVihaan'));
define('RAZORPAY_KEY_SECRET', env('RAZORPAY_KEY_SECRET', ''));
define('GOOGLE_MAPS_KEY', env('GOOGLE_MAPS_KEY', ''));

// SMTP Email Configurations
define('SMTP_HOST', env('SMTP_HOST', 'smtp.hostinger.com'));
define('SMTP_PORT', env('SMTP_PORT', '465'));
define('SMTP_USER', env('SMTP_USER', 'hello@biharvihaan.com'));
define('SMTP_PASS', env('SMTP_PASS', ''));

// Error Reporting Config
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__, 2) . '/error.log');
} else {
    error_reporting(E_ALL); // Log all errors in production but don't display
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__, 2) . '/error.log');
}

// Timezone Setting
date_default_timezone_set('Asia/Kolkata');

// Custom PSR-4 Compliant Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
