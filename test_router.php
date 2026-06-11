<?php
require_once __DIR__ . '/app/Config/config.php';
require_once __DIR__ . '/app/Core/Router.php';

use App\Core\Router;

$router = new Router();
$router->get('/tourism', 'TourismController', 'index');
$router->get('/superadmin/dashboard', 'SuperAdminController', 'dashboard');

// Mock a real request to /biharvihaan/tourism
$_SERVER['SCRIPT_NAME'] = '/biharvihaan/index.php';
$_SERVER['REQUEST_URI'] = '/biharvihaan/tourism';

echo "Testing /tourism:\n";
try {
    $router->dispatch($_SERVER['REQUEST_URI'], 'GET');
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

echo "Testing /superadmin/dashboard:\n";
$_SERVER['REQUEST_URI'] = '/biharvihaan/superadmin/dashboard';
try {
    $router->dispatch($_SERVER['REQUEST_URI'], 'GET');
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

