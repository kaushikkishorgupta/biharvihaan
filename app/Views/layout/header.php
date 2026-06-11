<?php
use App\Core\Auth;
use App\Core\Session;
Session::start();
$currentUri = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Bihar Vihaan Enterprise') ?></title>
    
    <!-- SEO & Performance Optimization -->
    <meta name="description" content="Bihar Vihaan Enterprise Portal - Bihar's largest digital ecosystem for Tourism, Careers, Media Network, verified local directory, and AI Travel Planner.">
    <meta name="keywords" content="Bihar Tourism, Nalanda Ruins, Bodh Gaya, AI Travel Planner Bihar, Bihar Business Directory, Rajgir Glass Skywalk, Chhath Puja">
    
    <!-- Open Graph (Facebook / LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Bihar Vihaan Enterprise') ?>">
    <meta property="og:description" content="Embark on a personalized journey through Bihar's ancient cultural landmarks, events, local artists, and premium listings.">
    <meta property="og:image" content="https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=1200">
    <meta property="og:url" content="<?= BASE_URL ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($title ?? 'Bihar Vihaan Enterprise') ?>">
    <meta name="twitter:description" content="Embark on a personalized journey through Bihar's ancient cultural landmarks, events, local artists, and premium listings.">
    
    <!-- PWA Manifest link -->
    <link rel="manifest" href="<?= BASE_URL ?>/manifest.json">
    <meta name="theme-color" content="#14b8a6">

    <!-- CDNs: Bootstrap 5.3, Font Awesome, Swiper Slider, AOS animations -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom stylesheet override -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <script>
        window.baseUrl = "<?= BASE_URL ?>";
        // Dark mode persistence - prevent white flash
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
    </script>

    <!-- Structured SEO JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Bihar Vihaan Enterprise",
      "url": "<?= BASE_URL ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= BASE_URL ?>/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <?php if (isset($view_mode) && $view_mode === 'catalog' && !empty($products)): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ItemList",
      "itemListElement": [
        <?php 
        $listItems = [];
        $idx = 1;
        foreach (array_slice($products, 0, 5) as $prod) {
            $listItems[] = '{
              "@type": "ListItem",
              "position": ' . $idx++ . ',
              "url": "' . BASE_URL . '/marketplace",
              "name": "' . htmlspecialchars($prod['name']) . '"
            }';
        }
        echo implode(',', $listItems);
        ?>
      ]
    }
    </script>
    <?php endif; ?>
</head>
<body class="bg-main">

<!-- Header Navigation -->
<header class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between h-100">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/">
            <img src="<?= BASE_URL ?>/assets/images/logo.png"
                 alt="Bihar Vihaan Logo"
                 class="site-logo"
                 onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
        </a>
        
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation" style="border: 1px solid var(--border-color); color: var(--text-main); padding: 5px 10px; border-radius: 8px;">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse-custom d-none d-lg-flex align-items-center gap-4">
            <nav class="d-flex align-items-center gap-2">
                <?php 
                $staticMenus = [
                    ['url' => '/', 'label' => 'Home'],
                    ['url' => '/tourism', 'label' => 'Tourism'],
                    ['url' => '/directory', 'label' => 'Directory'],
                    ['url' => '/gallery', 'label' => 'Gallery'],
                    ['url' => '/shop', 'label' => 'Marketplace'],
                    ['url' => '/contact', 'label' => 'Contact']
                ];
                foreach ($staticMenus as $menuItem): 
                ?>
                    <a class="nav-link <?= (strpos($currentUri, $menuItem['url']) !== false && $menuItem['url'] !== '/') || ($currentUri === '/' && $menuItem['url'] === '/') ? 'active' : '' ?>" href="<?= BASE_URL ?><?= htmlspecialchars($menuItem['url']) ?>">
                        <?= htmlspecialchars($menuItem['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="d-flex align-items-center gap-2">
                <!-- Marketplace Icons -->
                <a href="<?= BASE_URL ?>/marketplace/wishlist" class="btn btn-light btn-sm position-relative rounded-circle me-1" title="Wishlist" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-regular fa-heart text-muted"></i>
                </a>
                <a href="<?= BASE_URL ?>/marketplace/cart" class="btn btn-light btn-sm position-relative rounded-pill px-3 me-2" title="Cart" style="display: flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-cart-shopping text-primary"></i> <span class="fw-bold">Cart (0)</span>
                </a>

                <?php if (Auth::check()): ?>
                    <span class="text-main small fw-bold me-2">Hi, <?= htmlspecialchars(Session::get('user_name') ?? 'User') ?></span>
                    <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm px-3">Log Out</a>
                <?php else: ?>
                    <a class="nav-link <?= (strpos($currentUri, '/login') !== false) ? 'active' : '' ?>" href="<?= BASE_URL ?>/login">Sign In</a>
                    <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-sm px-3">Sign Up</a>
                <?php endif; ?>

                <!-- Language Selector -->
                <div class="dropdown">
                    <button class="btn btn-outline btn-sm py-1 px-2 d-flex align-items-center gap-1 dropdown-toggle" type="button" id="langSelect" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 13px;">
                        <i class="fa-solid fa-globe"></i> EN
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langSelect" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <li><a class="dropdown-item text-main" href="#" onclick="alert('Language changed to English');">English</a></li>
                        <li><a class="dropdown-item text-main" href="#" onclick="alert('Language changed to Hindi (हिन्दी)');">Hindi</a></li>
                    </ul>
                </div>

                <!-- Theme Switcher -->
                <button id="theme-toggle" title="Toggle Theme">
                    <i id="theme-icon" class="fa-solid fa-moon"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="collapse d-lg-none" id="mainNavbar" style="position: fixed; top: 85px; left: 0; right: 0; background: var(--bg-card); z-index: 999; border-bottom: 1px solid var(--border-color); padding: 1.5rem; max-height: calc(100vh - 85px); overflow-y: auto;">
    <div class="d-flex flex-column gap-3">
        <?php foreach ($staticMenus as $menuItem): ?>
            <a class="nav-link <?= (strpos($currentUri, $menuItem['url']) !== false && $menuItem['url'] !== '/') || ($currentUri === '/' && $menuItem['url'] === '/') ? 'active' : '' ?>" href="<?= BASE_URL ?><?= htmlspecialchars($menuItem['url']) ?>">
                <?= htmlspecialchars($menuItem['label']) ?>
            </a>
        <?php endforeach; ?>
        <hr style="border-color: var(--border-color); margin: 0.5rem 0;">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <?php if (Auth::check()): ?>
                <span class="text-main small fw-bold me-2">Hi, <?= htmlspecialchars(Session::get('user_name') ?? 'User') ?></span>
                <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm px-3">Log Out</a>
            <?php else: ?>
                <a class="nav-link me-2" href="<?= BASE_URL ?>/login">Sign In</a>
                <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-sm px-3">Sign Up</a>
            <?php endif; ?>
            <!-- Mobile Language Switcher -->
            <select class="form-select form-select-sm" style="width: auto; height: 34px;" onchange="alert('Language updated!');">
                <option value="en">English (EN)</option>
                <option value="hi">Hindi (HI)</option>
            </select>
        </div>
    </div>
</div>

<main style="min-height: 80vh; padding-top: 75px;">


