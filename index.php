<?php
/**
 * Bihar Vihaan Enterprise - Entry Point & Router Dispatcher
 */

require_once __DIR__ . '/app/Config/config.php';

use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// ========================================================
// 1. HOME, AUTH & PHASE 1 CORE ROUTES
// ========================================================
$router->get('/', 'HomeController', 'index');
$router->get('/login', 'HomeController', 'login');
$router->post('/login', 'HomeController', 'handleLogin');
$router->get('/register', 'HomeController', 'register');
$router->post('/register', 'HomeController', 'handleRegister');
$router->get('/forgot-password', 'HomeController', 'forgotPassword');
$router->post('/forgot-password', 'HomeController', 'handleForgotPassword');
$router->get('/logout', 'HomeController', 'logout');
$router->get('/2fa', 'HomeController', 'verify2FA');
$router->post('/2fa', 'HomeController', 'handle2FA');
$router->get('/login/google', 'HomeController', 'googleLogin');
$router->get('/login/facebook', 'HomeController', 'facebookLogin');
$router->get('/media', 'HomeController', 'media');
$router->get('/clients', 'HomeController', 'clients');
$router->get('/about', 'HomeController', 'about');
$router->get('/services', 'HomeController', 'services');
$router->get('/contact', 'HomeController', 'contact');
$router->post('/contact', 'HomeController', 'handleContact');


// Tourism Routes

// Directory Routes

// Marketplace Routes


// Fallback for SPA routes or React (if applicable)
// Phase 1 Additional Core Routes
$router->get('/gallery', 'GalleryController', 'index');
$router->get('/gallery/load', 'GalleryController', 'loadMore');

// ========================================================
// 2. TOURISM INTELLIGENCE MODULE ROUTES
// ========================================================
$router->get('/tourism', 'TourismController', 'index');
$router->get('/search', 'TourismController', 'search');
$router->get('/tourism/recommend', 'TourismController', 'recommendations');
$router->post('/tourism/recommend', 'TourismController', 'handleRecommendations');
$router->get('/tourism/{id}', 'TourismController', 'detail');
$router->get('/tourism/save/{id}', 'TourismController', 'savePlace');
$router->get('/tourism/unsave/{id}', 'TourismController', 'unsavePlace');
$router->post('/tourism/itinerary/create', 'TourismController', 'handleCreateItinerary');
$router->get('/tourism/ai-planner', 'TourismController', 'aiPlanner');
$router->post('/tourism/ai-planner', 'TourismController', 'handleAiPlanner');

// ========================================================
// 3. ADVANCED BUSINESS DIRECTORY ROUTES
// ========================================================
$router->get('/business', 'BusinessController', 'index');
$router->get('/directory', 'BusinessController', 'index');
$router->get('/business/{id}', 'BusinessController', 'detail');
$router->get('/business/ad-click', 'BusinessController', 'handleAdClick');
$router->post('/business/upgrade', 'BusinessController', 'handleUpgrade');

// ========================================================
// 5. MARKETPLACE / SHOP MODULE ROUTES
// ========================================================
$router->get('/shop', 'MarketplaceController', 'index');
$router->get('/shop/load', 'MarketplaceController', 'loadProducts');
$router->get('/shop/quick-view', 'MarketplaceController', 'quickView');
$router->get('/marketplace/cart', 'MarketplaceController', 'cart');
$router->post('/marketplace/cart/add', 'MarketplaceController', 'addToCart');
$router->get('/marketplace/cart/remove/{id}', 'MarketplaceController', 'removeFromCart');
$router->get('/marketplace/checkout', 'MarketplaceController', 'checkout');
$router->post('/marketplace/checkout/process', 'MarketplaceController', 'handleCheckout');
$router->post('/marketplace/payment/verify', 'MarketplaceController', 'paymentVerify');
$router->get('/marketplace/order-confirmation/{id}', 'MarketplaceController', 'orderConfirmation');

// ========================================================
// MODULES REMOVED UNTIL FULLY DEVELOPED (PHASE 2+)
// ========================================================
/*
$router->get('/events', 'EventController@index');
$router->get('/events/{id}', 'EventController@detail');

$router->get('/bookings', 'BookingController', 'index');
$router->post('/bookings/request', 'BookingController', 'handleRequest');
$router->post('/bookings/pay', 'BookingController', 'handlePayment');

$router->get('/careers', 'CareerController', 'index');
$router->get('/careers/{id}', 'CareerController', 'detail');
$router->post('/careers/apply', 'CareerController', 'handleApply');

$router->get('/talent', 'TalentController', 'index');
$router->get('/talent/{id}', 'TalentController', 'detail');
$router->post('/talent/collab', 'TalentController', 'handleCollab');
$router->post('/talent/portfolio/add', 'TalentController', 'handleAddPortfolio');

$router->post('/business/lead', 'CrmController', 'submitLead');
$router->get('/crm', 'CrmController', 'index');
$router->get('/crm/detail/{id}', 'CrmController', 'detail');
$router->post('/crm/add-note', 'CrmController', 'addNote');

$router->get('/learning', 'LearningController', 'index');
$router->get('/learning/course/{id}', 'LearningController', 'course');
$router->post('/learning/quiz/submit', 'LearningController', 'submitQuiz');

$router->get('/community', 'CommunityController', 'index');
$router->get('/community/forum/{slug}', 'CommunityController', 'forum');
$router->get('/community/topic/{id}', 'CommunityController', 'topic');
$router->post('/community/create-topic', 'CommunityController', 'createTopic');
$router->post('/community/reply-topic', 'CommunityController', 'replyTopic');

$router->get('/creators', 'CreatorController', 'index');
$router->post('/creators/apply', 'CreatorController', 'applyVerification');
$router->post('/creators/publish', 'CreatorController', 'publishContent');
$router->post('/creators/ai-draft', 'CreatorController', 'aiGenerateDraft');

$router->get('/government', 'GovController', 'index');

// ========================================================
// 7. SUPER ADMIN DASHBOARD MODULE ROUTES
// ========================================================
$router->get('/invoice', 'DashboardController', 'invoice');

// ========================================================
// 8. REST API & DOCUMENTATION MODULE ROUTES
// ========================================================
$router->get('/api-docs', 'ApiController', 'docs');
$router->get('/api/destinations', 'ApiController', 'getDestinations');
$router->get('/api/destinations/{id}', 'ApiController', 'getDestinationDetail');
$router->get('/api/search', 'ApiController', 'search');
$router->get('/api/events', 'ApiController', 'getEvents');
$router->get('/api/businesses', 'ApiController', 'getBusinesses');
$router->post('/api/event/register', 'ApiController', 'registerEventTicket');
$router->get('/api/news', 'ApiController', 'getNews');
$router->post('/api/reviews', 'ApiController', 'postReview');
$router->post('/api/subscribe', 'ApiController', 'newsletterSubscribe');
$router->post('/api/contact', 'ApiController', 'submitContact');
$router->post('/api/chat', 'ApiController', 'chatReply');
*/

// Dispatch incoming request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
