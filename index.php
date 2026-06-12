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
$router->get('/reset-password', 'HomeController', 'resetPassword');
$router->post('/reset-password', 'HomeController', 'handleResetPassword');
$router->get('/logout', 'HomeController', 'logout');
$router->get('/2fa', 'HomeController', 'verify2FA');
$router->post('/2fa', 'HomeController', 'handle2FA');
$router->get('/media', 'HomeController', 'media');
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

$router->add('GET', '/community', 'CommunityController@index');
$router->add('GET', '/community/post', 'CommunityController@post');
$router->add('POST', '/community/store_post', 'CommunityController@store_post');

// Phase 6A Routes
$router->add('GET', '/cart', 'CartController@index');
$router->add('POST', '/cart/add', 'CartController@add');
$router->add('POST', '/cart/update', 'CartController@update');
$router->add('POST', '/cart/remove', 'CartController@remove');

$router->add('GET', '/wishlist', 'WishlistController@index');
$router->add('POST', '/wishlist/toggle', 'WishlistController@toggle');
$router->add('POST', '/wishlist/remove', 'WishlistController@remove');

$router->add('GET', '/checkout', 'CheckoutController@index');
$router->add('POST', '/checkout/create_order', 'CheckoutController@create_order');
$router->add('POST', '/checkout/verify', 'CheckoutController@verify');
$router->add('GET', '/checkout/success', 'CheckoutController@success');
$router->add('GET', '/checkout/failed', 'CheckoutController@failed');

$router->add('GET', '/user/dashboard', 'UserController@dashboard');
$router->add('GET', '/user/orders', 'UserController@orders');
$router->add('GET', '/user/track', 'UserController@track');

$router->add('GET', '/admin/orders', 'AdminOrdersController@index');
$router->add('GET', '/admin/orders/view', 'AdminOrdersController@view');
$router->add('POST', '/admin/orders/update', 'AdminOrdersController@update');
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

// ========================================================
// 9. SUPER ADMIN CMS ROUTES (PHASE 4)
// ========================================================
$router->get('/admin', 'AdminDashboardController', 'index');
$router->get('/admin/dashboard', 'AdminDashboardController', 'index');
$router->get('/admin/search', 'AdminDashboardController', 'globalSearch');
$router->get('/admin/cms', 'AdminCmsController', 'index');
$router->post('/admin/cms/update', 'AdminCmsController', 'update');
$router->post('/admin/cms/autosave', 'AdminCmsController', 'autosave');
$router->post('/admin/cms/rollback', 'AdminCmsController', 'rollback');
$router->get('/admin/cms/versions', 'AdminCmsController', 'versions');
$router->get('/admin/trip-planner', 'AdminTripPlannerController', 'index');
$router->post('/admin/trip-planner/store', 'AdminTripPlannerController', 'store');
$router->post('/admin/trip-planner/update', 'AdminTripPlannerController', 'update');
$router->post('/admin/trip-planner/delete', 'AdminTripPlannerController', 'delete');
$router->get('/admin/tourism', 'AdminTourismController', 'index');
$router->post('/admin/tourism/store', 'AdminTourismController', 'store');
$router->post('/admin/tourism/update', 'AdminTourismController', 'update');
$router->post('/admin/tourism/delete', 'AdminTourismController', 'delete');

$router->get('/admin/directory', 'AdminDirectoryController', 'index');
$router->post('/admin/directory/store', 'AdminDirectoryController', 'store');
$router->post('/admin/directory/update', 'AdminDirectoryController', 'update');
$router->post('/admin/directory/delete', 'AdminDirectoryController', 'delete');

$router->get('/admin/marketplace', 'AdminMarketplaceController', 'index');
$router->post('/admin/marketplace/store', 'AdminMarketplaceController', 'store');
$router->post('/admin/marketplace/update', 'AdminMarketplaceController', 'update');
$router->post('/admin/marketplace/delete', 'AdminMarketplaceController', 'delete');

$router->get('/admin/gallery', 'AdminGalleryController', 'index');
$router->post('/admin/gallery/store', 'AdminGalleryController', 'store');
$router->post('/admin/gallery/update', 'AdminGalleryController', 'update');
$router->post('/admin/gallery/delete', 'AdminGalleryController', 'delete');

$router->get('/admin/media', 'AdminMediaController', 'index');
$router->post('/admin/media/upload', 'AdminMediaController', 'upload');
$router->post('/admin/media/delete', 'AdminMediaController', 'delete');

$router->get('/admin/settings', 'AdminSettingsController', 'index');
$router->post('/admin/settings/store_seo', 'AdminSettingsController', 'store_seo');
$router->post('/admin/settings/update_seo', 'AdminSettingsController', 'update_seo');
$router->post('/admin/settings/delete_seo', 'AdminSettingsController', 'delete_seo');
$router->post('/admin/settings/update_global', 'AdminSettingsController', 'update_global');

// CMS 7.0 Upgrade Module Routes
$router->get('/admin/branding', 'AdminBrandingController', 'index');
$router->post('/admin/branding/update', 'AdminBrandingController', 'update');

$router->get('/admin/social', 'AdminSocialController', 'index');
$router->post('/admin/social/update', 'AdminSocialController', 'update');

$router->get('/admin/seo', 'AdminSeoController', 'index');
$router->post('/admin/seo/store', 'AdminSeoController', 'store');
$router->post('/admin/seo/update', 'AdminSeoController', 'update');
$router->post('/admin/seo/delete', 'AdminSeoController', 'delete');

$router->get('/admin/social-feeds', 'AdminSocialEmbedsController', 'index');
$router->post('/admin/social-feeds/store', 'AdminSocialEmbedsController', 'store');
$router->post('/admin/social-feeds/update', 'AdminSocialEmbedsController', 'update');
$router->post('/admin/social-feeds/delete', 'AdminSocialEmbedsController', 'delete');

$router->get('/admin/blogs', 'AdminBlogsController', 'index');
$router->post('/admin/blogs/store', 'AdminBlogsController', 'store');
$router->post('/admin/blogs/update', 'AdminBlogsController', 'update');
$router->post('/admin/blogs/delete', 'AdminBlogsController', 'delete');
$router->post('/admin/blogs/autosave', 'AdminBlogsController', 'autosave');
$router->post('/admin/blogs/rollback', 'AdminBlogsController', 'rollback');
$router->get('/admin/blogs/versions', 'AdminBlogsController', 'versions');

$router->get('/admin/businesses', 'AdminDirectoryController', 'index');
$router->post('/admin/businesses/store', 'AdminDirectoryController', 'store');
$router->post('/admin/businesses/update', 'AdminDirectoryController', 'update');
$router->post('/admin/businesses/delete', 'AdminDirectoryController', 'delete');

$router->get('/admin/users', 'AdminUsersController', 'index');
$router->post('/admin/users/store', 'AdminUsersController', 'store');
$router->post('/admin/users/update', 'AdminUsersController', 'update');
$router->post('/admin/users/delete', 'AdminUsersController', 'delete');

$router->get('/admin/roles', 'AdminRolesController', 'index');
$router->post('/admin/roles/update', 'AdminRolesController', 'update');

$router->get('/admin/logs', 'AdminLogsController', 'index');

$router->get('/admin/notifications', 'AdminNotificationController', 'index');
$router->get('/admin/notifications/unread', 'AdminNotificationController', 'getUnread');
$router->post('/admin/notifications/read', 'AdminNotificationController', 'markRead');

// Phase 6A Routes
$router->get('/cart', 'CartController', 'index');
$router->post('/cart/add', 'CartController', 'add');
$router->post('/cart/update', 'CartController', 'update');
$router->post('/cart/remove', 'CartController', 'remove');

$router->get('/wishlist', 'WishlistController', 'index');
$router->post('/wishlist/toggle', 'WishlistController', 'toggle');
$router->post('/wishlist/remove', 'WishlistController', 'remove');

$router->get('/checkout', 'CheckoutController', 'index');
$router->post('/checkout/create_order', 'CheckoutController', 'create_order');
$router->post('/checkout/verify', 'CheckoutController', 'verify');
$router->get('/checkout/success', 'CheckoutController', 'success');
$router->get('/checkout/failed', 'CheckoutController', 'failed');

$router->get('/user/dashboard', 'UserController', 'dashboard');
$router->get('/user/orders', 'UserController', 'orders');
$router->get('/user/track', 'UserController', 'track');
$router->post('/user/profile/update', 'UserController', 'updateProfile');

$router->get('/admin/orders', 'AdminOrdersController', 'index');
$router->get('/admin/orders/view', 'AdminOrdersController', 'view');
$router->post('/admin/orders/update', 'AdminOrdersController', 'update');

$router->get('/admin/events', 'AdminEventsController', 'index');
$router->post('/admin/events/store', 'AdminEventsController', 'store');
$router->post('/admin/events/update', 'AdminEventsController', 'update');
$router->post('/admin/events/delete', 'AdminEventsController', 'delete');

// Phase 6B Routes (Tourism Intelligence & SEO)
$router->get('/trip-planner', 'TripPlannerController', 'index');
$router->post('/trip-planner/generate', 'TripPlannerController', 'generate');

$router->get('/events', 'EventsController', 'index');
$router->get('/events/show', 'EventsController', 'show');

$router->get('/partner-details/{slug}', 'PartnersController', 'show');

$router->get('/admin/partners', 'AdminPartnersController', 'index');
$router->post('/admin/partners/store', 'AdminPartnersController', 'store');
$router->post('/admin/partners/update', 'AdminPartnersController', 'update');
$router->post('/admin/partners/delete', 'AdminPartnersController', 'delete');
$router->post('/admin/partners/gallery/store', 'AdminPartnersController', 'storeGallery');

$router->get('/search/ajax', 'SearchController', 'ajax');

$router->post('/contact', 'ContactController', 'submit');

$router->post('/newsletter/subscribe', 'NewsletterController', 'subscribe');
$router->get('/newsletter/unsubscribe', 'NewsletterController', 'unsubscribe');

$router->get('/sitemap.xml', 'SeoController', 'sitemap');
$router->get('/robots.txt', 'SeoController', 'robots');

// Dispatch incoming request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
