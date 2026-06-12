<?php
$logoUrl = BASE_URL . '/assets/images/logo.png';
$logoFallback = BASE_URL . '/assets/images/bihar-vihaan-logo.svg';
try {
    $db = \App\Core\Database::getInstance()->getConnection();
    if ($db) {
        $stmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'site_logo' LIMIT 1");
        if ($stmt) {
            $logoSetting = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($logoSetting && !empty($logoSetting['setting_value'])) {
                $logoUrl = BASE_URL . '/assets/images/' . $logoSetting['setting_value'];
            }
        }
    }
} catch (\Exception $e) {
    // Silently fallback to default logo
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bihar Vihaan Enterprise CMS</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $logoUrl ?>">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <script>
        // Apply theme early to prevent flash of wrong theme
        (function() {
            const theme = localStorage.getItem('admin_theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    
    <style>
        :root {
            --primary-color: #0B3D91;
            --accent-color: #FF9933;
            --success-color: #10B981;
            --bg-light: #F8F4F0;
            --bg-dark: #0F172A;
            --font-primary: 'Inter', sans-serif;
            --font-display: 'Outfit', sans-serif;
        }

        [data-bs-theme="dark"] {
            --admin-bg: var(--bg-dark);
            --admin-surface: rgba(30, 41, 59, 0.45);
            --admin-sidebar: #020617;
            --admin-sidebar-border: rgba(255, 255, 255, 0.08);
            --admin-text: #f8fafc;
            --admin-text-secondary: #94a3b8;
            --admin-border: rgba(255, 255, 255, 0.08);
            --admin-card-bg: rgba(30, 41, 59, 0.45);
            --admin-nav-bg: rgba(15, 23, 42, 0.8);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="light"] {
            --admin-bg: var(--bg-light);
            --admin-surface: rgba(255, 255, 255, 0.75);
            --admin-sidebar: #0B3D91;
            --admin-sidebar-border: rgba(255, 255, 255, 0.1);
            --admin-text: #1e293b;
            --admin-text-secondary: #64748b;
            --admin-border: rgba(11, 61, 145, 0.12);
            --admin-card-bg: rgba(255, 255, 255, 0.8);
            --admin-nav-bg: rgba(248, 244, 240, 0.85);
            --shadow-sm: 0 1px 3px rgba(11, 61, 145, 0.05);
            --shadow-md: 0 4px 12px rgba(11, 61, 145, 0.08);
            --shadow-lg: 0 12px 24px rgba(11, 61, 145, 0.12);
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--admin-bg);
            color: var(--admin-text);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        /* Sidebar Styling */
        #sidebar {
            width: 280px;
            background: var(--admin-sidebar);
            color: #fff;
            min-height: 100vh;
            position: fixed;
            z-index: 999;
            border-right: 1px solid var(--admin-sidebar-border);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        #sidebar .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid var(--admin-sidebar-border);
        }

        .sidebar-brand {
            font-family: var(--font-display);
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.25rem;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand span {
            color: var(--accent-color);
        }

        #sidebar .menu-section-title {
            padding: 16px 24px 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-uppercase: true;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.4);
        }

        #sidebar ul.components {
            padding: 10px 0 30px;
            flex-grow: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        #sidebar ul.components::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar ul.components::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        #sidebar ul li a {
            padding: 10px 24px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
        }

        #sidebar ul li a i {
            margin-right: 12px;
            width: 20px;
            font-size: 1.05rem;
            text-align: center;
            transition: transform 0.2s ease;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: var(--accent-color);
        }

        #sidebar ul li a:hover i {
            transform: scale(1.15);
        }

        #sidebar ul li.active > a {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            font-weight: 600;
            border-left-color: var(--accent-color);
        }

        /* Content Area */
        #content {
            width: calc(100% - 280px);
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Glassmorphism Navbar */
        .admin-navbar {
            background: var(--admin-nav-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--admin-border);
            padding: 16px 32px;
            position: sticky;
            top: 0;
            z-index: 998;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            transition: background-color 0.3s ease;
        }

        .search-wrapper {
            position: relative;
            width: 380px;
        }

        .search-wrapper input {
            background: rgba(0, 0, 0, 0.05);
            border: 1px solid var(--admin-border);
            color: var(--admin-text);
            padding: 10px 18px 10px 42px;
            border-radius: 30px;
            width: 100%;
            font-size: 0.88rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-bs-theme="dark"] .search-wrapper input {
            background: rgba(255, 255, 255, 0.04);
        }

        .search-wrapper input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(11, 61, 145, 0.15);
        }

        .search-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--admin-text-secondary);
        }

        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--admin-card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            margin-top: 8px;
            max-height: 350px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        }

        #search-results a {
            display: block;
            padding: 12px 18px;
            color: var(--admin-text);
            text-decoration: none;
            border-bottom: 1px solid var(--admin-border);
            transition: background 0.2s ease;
        }

        #search-results a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .admin-main {
            padding: 32px;
            flex-grow: 1;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: var(--admin-card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: var(--shadow-lg);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .badge-pulse {
            position: absolute;
            top: -2px;
            right: -2px;
            padding: 4px;
            border-radius: 50%;
            background-color: var(--accent-color);
            border: 2px solid var(--admin-bg);
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 153, 51, 0.7);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(255, 153, 51, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 153, 51, 0);
            }
        }

        /* Sidebar Brand Logo */
        .admin-logo {
            height: 54px; /* Desktop: 48px–60px */
            width: auto;
            object-fit: contain;
            image-rendering: -webkit-optimize-contrast;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), filter 0.3s ease;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .admin-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 12px rgba(11, 61, 145, 0.4));
        }

        /* Dark Mode Logo - convert to white */
        [data-bs-theme="dark"] .admin-logo {
            filter: brightness(0) invert(1);
        }

        [data-bs-theme="dark"] .admin-logo:hover {
            transform: scale(1.05);
            filter: brightness(0) invert(1) drop-shadow(0 0 12px rgba(255, 255, 255, 0.5));
        }

        /* Responsive Logo Sizes */
        @media (max-width: 991px) {
            .admin-logo {
                height: 40px; /* Tablet */
            }
        }
        @media (max-width: 576px) {
            .admin-logo {
                height: 32px; /* Mobile */
            }
        }

        /* Collapsed Sidebar Logic */
        #sidebar.collapsed .admin-brand-text {
            display: none;
        }
        #sidebar.collapsed .admin-logo {
            height: 32px;
            margin-bottom: 0 !important;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header text-center py-4">
            <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar-brand d-flex flex-column align-items-center text-decoration-none">
                <img src="<?= $logoUrl ?>" onerror="this.src='<?= $logoFallback ?>'" alt="Bihar Vihaan" class="admin-logo mb-2">
                <span class="admin-brand-text" style="font-size: 0.65rem; font-weight: 600; color: var(--admin-text-secondary); text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.4; text-align: center;">Official Digital Tourism &amp; Cultural Platform of Bihar</span>
            </a>
        </div>

        <ul class="list-unstyled components">
            <?php use App\Core\Auth; ?>

            <!-- Section 1: Core Operating Center — visible to all admin roles -->
            <div class="menu-section-title">Core Operating Center</div>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false || $_SERVER['REQUEST_URI'] === '/admin' || $_SERVER['REQUEST_URI'] === '/admin/' ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard Overview</a>
            </li>
            <?php if (Auth::can('view_activity_logs')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/logs') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/logs"><i class="fa-solid fa-receipt"></i> System Activity Logs</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('view_notifications')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/notifications') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/notifications"><i class="fa-solid fa-bell"></i> Notifications Center</a>
            </li>
            <?php endif; ?>

            <!-- Section 2: Content Experience -->
            <?php if (Auth::canAny(['manage_cms','manage_tourism','manage_gallery','manage_blogs','manage_events','manage_media'])): ?>
            <div class="menu-section-title">Content Experience</div>
            <?php if (Auth::can('manage_cms')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/cms') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/cms"><i class="fa-solid fa-window-maximize"></i> Homepage Elements</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_tourism')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/tourism') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/tourism"><i class="fa-solid fa-map-location-dot"></i> Tourism Circuit Manager</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_gallery')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/gallery') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/gallery"><i class="fa-solid fa-palette"></i> Gallery Pinterest Style</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_blogs')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/blogs') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/blogs"><i class="fa-solid fa-blog"></i> Blog &amp; Articles CMS</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_events')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/events') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/events"><i class="fa-solid fa-calendar-days"></i> Events &amp; Festivals</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_media')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/media') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/media"><i class="fa-solid fa-photo-film"></i> Drag-Drop Media Hub</a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Section 3: Intelligence & Feeds -->
            <?php if (Auth::canAny(['manage_tourism','manage_social'])): ?>
            <div class="menu-section-title">Intelligence &amp; Feeds</div>
            <?php if (Auth::can('manage_tourism')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/trip-planner') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/trip-planner"><i class="fa-solid fa-robot"></i> AI Planner Settings</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_social')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/social-feeds') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/social-feeds"><i class="fa-solid fa-hashtag"></i> YouTube &amp; Reels Feeds</a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Section 4: E-Commerce -->
            <?php if (Auth::canAny(['manage_marketplace','manage_directory'])): ?>
            <div class="menu-section-title">E-Commerce Ecosystem</div>
            <?php if (Auth::can('manage_marketplace')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/marketplace') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/marketplace"><i class="fa-solid fa-store"></i> Artisan Marketplace</a>
            </li>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/orders"><i class="fa-solid fa-box"></i> Orders &amp; Fulfilment</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_directory')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/businesses') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/directory') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/businesses"><i class="fa-solid fa-address-book"></i> Business Directory</a>
            </li>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/partners') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/partners"><i class="fa-solid fa-handshake-angle"></i> Partners Showcase</a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Section 5: Configurations — individually gated -->
            <?php if (Auth::canAny(['manage_settings','manage_users','manage_branding','manage_seo','manage_social'])): ?>
            <div class="menu-section-title">Configurations</div>
            <?php if (Auth::can('manage_branding')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/branding') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/branding"><i class="fa-solid fa-brush"></i> Brand Design Settings</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_social')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/social') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/social"><i class="fa-solid fa-share-nodes"></i> Social Links &amp; Card</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_seo')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/seo') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/seo"><i class="fa-solid fa-magnifying-glass-chart"></i> Metatags &amp; SEO Core</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_users')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/users"><i class="fa-solid fa-users-gear"></i> User Management</a>
            </li>
            <?php endif; ?>
            <?php if (Auth::can('manage_settings')): ?>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/roles') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/roles"><i class="fa-solid fa-shield-halved"></i> Role &amp; Permission Matrix</a>
            </li>
            <li class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/settings"><i class="fa-solid fa-sliders"></i> Global Variables</a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <li>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fa-solid fa-globe text-info"></i> Go to Live Site</a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/logout"><i class="fa-solid fa-sign-out-alt text-danger"></i> Secure Signout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Sticky Top Navigation -->
        <nav class="admin-navbar">
            <!-- Global Search -->
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="globalSearch" placeholder="Search destinations, blogs, transactions...">
                <div id="search-results"></div>
            </div>

            <div class="d-flex align-items-center gap-4">
                <!-- Theme Toggle -->
                <button class="btn btn-link text-secondary p-0 border-0 fs-5" id="theme-toggle" title="Toggle color theme" aria-label="Toggle color theme">
                    <i class="fa-solid fa-moon"></i>
                </button>

                <!-- Notifications Bell -->
                <div class="position-relative cursor-pointer" onclick="window.location.href='<?= BASE_URL ?>/admin/notifications'">
                    <i class="fa-solid fa-bell text-secondary fs-5"></i>
                    <span id="unread-count-badge" class="badge-pulse d-none"></span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="text-end d-none d-sm-block">
                        <p class="mb-0 fw-bold fs-7 text-white"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrator') ?></p>
                        <small class="text-secondary" style="font-size:0.75rem;"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Super Admin') ?></small>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'Admin') ?>&background=0B3D91&color=fff&bold=true" alt="Admin" class="rounded-circle" width="40" height="40">
                </div>
            </div>
        </nav>
        
        <div class="admin-main">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 bg-success bg-opacity-10 text-success" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 bg-danger bg-opacity-10 text-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>
