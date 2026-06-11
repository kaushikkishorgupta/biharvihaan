<?php
$report = [
    'routes' => [],
    'controllers' => [],
    'models' => [],
    'views' => [],
    'missing' => [
        'controllers' => [],
        'methods' => [],
        'views' => []
    ]
];

// 1. Scan Routes
$indexContents = file_get_contents('c:/xampp/htdocs/biharvihaan/index.php');
preg_match_all("/\\\$router->(get|post|put|delete)\(\s*'([^']+)',\s*'([^']+)',\s*'([^']+)'\s*\);/", $indexContents, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $method = $match[1];
    $path = $match[2];
    $controller = $match[3];
    $action = $match[4];
    
    $report['routes'][] = compact('method', 'path', 'controller', 'action');
    
    // Check if controller exists
    $controllerPath = 'c:/xampp/htdocs/biharvihaan/app/Controllers/' . $controller . '.php';
    if (!file_exists($controllerPath)) {
        if (!in_array($controller, $report['missing']['controllers'])) {
            $report['missing']['controllers'][] = $controller;
        }
    } else {
        // Check if method exists
        require_once $controllerPath;
        $className = "App\\Controllers\\" . $controller;
        if (class_exists($className)) {
            if (!method_exists($className, $action)) {
                $report['missing']['methods'][] = "$controller::$action";
            }
        }
    }
}

// 2. Scan Controllers
$controllersDir = 'c:/xampp/htdocs/biharvihaan/app/Controllers';
foreach (glob($controllersDir . '/*.php') as $file) {
    $report['controllers'][] = basename($file, '.php');
}

// 3. Scan Models
$modelsDir = 'c:/xampp/htdocs/biharvihaan/app/Models';
foreach (glob($modelsDir . '/*.php') as $file) {
    $report['models'][] = basename($file, '.php');
}

// 4. Scan Views
$viewsDir = 'c:/xampp/htdocs/biharvihaan/app/Views';
foreach (glob($viewsDir . '/*.php') as $file) {
    $report['views'][] = basename($file, '.php');
}

file_put_contents('c:/xampp/htdocs/biharvihaan/audit_report.json', json_encode($report, JSON_PRETTY_PRINT));
echo "Scan complete. Output to audit_report.json\n";
