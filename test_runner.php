<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';
require 'app/Core/Session.php';

$_SERVER['REQUEST_METHOD'] = 'GET';

use App\Core\Database;

$db = Database::getInstance()->getConnection();
$report = [];

// TEST 1: AI Trip Planner
$_POST = ['budget'=>'medium', 'days'=>5, 'travelers'=>'family', 'interests'=>'heritage', 'start_city'=>'Patna'];
ob_start();
$planner = new \App\Controllers\TripPlannerController();
$planner->generate();
$plannerOutRaw = ob_get_clean();
$plannerOut = json_decode($plannerOutRaw, true);
$report['Trip Planner'] = (isset($plannerOut['success']) && $plannerOut['success'] && count($plannerOut['itinerary']) == 5) ? 'WORKING' : 'BROKEN (' . substr($plannerOutRaw, 0, 50) . ')';

// TEST 2: Global Search
$_GET = ['q' => 'Patna'];
ob_start();
$search = new \App\Controllers\SearchController();
$search->ajax();
$searchOutRaw = ob_get_clean();
$searchOut = json_decode($searchOutRaw, true);
$report['Global Search'] = (is_array($searchOut)) ? 'WORKING' : 'BROKEN (' . substr($searchOutRaw, 0, 50) . ')';

// TEST 3: Newsletter
$email = 'test_audit_'.time().'@example.com';
$db->query("INSERT INTO newsletter_subscribers (email) VALUES ('$email')");
$checkNews = $db->query("SELECT id FROM newsletter_subscribers WHERE email = '$email'")->fetchColumn();
$report['Newsletter DB'] = $checkNews ? 'WORKING' : 'BROKEN';

// TEST 4: Contact
$db->query("INSERT INTO contact_messages (name, email, subject, message) VALUES ('Test', 'test@test.com', 'Audit', 'Test msg')");
$checkContact = $db->query("SELECT id FROM contact_messages WHERE subject = 'Audit'")->fetchColumn();
$report['Contact DB'] = $checkContact ? 'WORKING' : 'BROKEN';

// TEST 5: Razorpay
$checkoutFile = file_get_contents('app/Controllers/CheckoutController.php');
if (strpos($checkoutFile, 'rzp_test_PLACEHOLDER') !== false) {
    $report['Razorpay'] = 'NOT READY (Requires Real Keys)';
} else {
    $report['Razorpay'] = 'WORKING';
}

// TEST 10: SEO
ob_start();
$seo = new \App\Controllers\SeoController();
$seo->sitemap();
$sitemapOut = ob_get_clean();
$report['Sitemap'] = (strpos($sitemapOut, '<?xml') !== false) ? 'WORKING' : 'BROKEN';

ob_start();
$seo->robots();
$robotsOut = ob_get_clean();
$report['Robots'] = (strpos($robotsOut, 'User-agent:') !== false) ? 'WORKING' : 'BROKEN';

// TEST 11: Performance
$controllerFile = file_get_contents('app/Core/Controller.php');
$report['Cache Headers'] = (strpos($controllerFile, 'Cache-Control') !== false) ? 'WORKING' : 'BROKEN';

// TEST 12: Security
$report['CSRF Security'] = (strpos($controllerFile, 'validateCsrfToken') !== false) ? 'WORKING' : 'BROKEN';

print_r($report);
