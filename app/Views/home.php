<?php
use App\Core\Session;
$viewMode = $view_mode ?? 'home';
$csrfToken = Session::generateCsrfToken();
?>

<!-- Alerts Container -->
<div class="container" style="margin-top: 100px; margin-bottom: -50px; z-index: 10; position: relative;">
    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-success-subtle bg-success-subtle text-success small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= Session::getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-danger-subtle bg-danger-subtle text-danger small" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= Session::getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<!-- 1. LOGIN MODE -->
<?php if ($viewMode === 'login'): ?>
    <div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 shadow-lg w-100" style="max-width: 450px; border-radius: var(--radius-md); background-color: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png"
                         alt="Bihar Vihaan Logo"
                         class="site-logo mx-auto mb-3"
                         style="height: 48px;"
                         onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                    <h2 class="fw-bold font-outfit h3" style="color: var(--text-main);">Sign In</h2>
                </div>
                
                <form action="<?= BASE_URL ?>/login" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. admin@biharvihaan.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="text-end mb-3">
                        <a href="<?= BASE_URL ?>/forgot-password" class="small fw-bold text-decoration-none" style="color: var(--primary);">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Log In</button>
                </form>

                <!-- Social Logins -->
                <div class="text-center my-4 small" style="color: var(--text-muted);">OR CONTINUE WITH</div>
                <div class="d-flex gap-2">
                    <a href="<?= BASE_URL ?>/login/google" class="btn btn-outline w-50 small d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-brands fa-google text-danger"></i> Google
                    </a>
                    <a href="<?= BASE_URL ?>/login/facebook" class="btn btn-outline w-50 small d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-brands fa-facebook text-primary"></i> Facebook
                    </a>
                </div>

                <p class="text-center small mt-4 mb-0" style="color: var(--text-secondary);">
                    Don't have an account? <a href="<?= BASE_URL ?>/register" class="fw-bold text-decoration-none" style="color: var(--primary);">Sign Up</a>
                </p>
            </div>
        </div>
    </div>

<!-- 2. REGISTER MODE -->
<?php elseif ($viewMode === 'register'): ?>
    <div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 shadow-lg w-100" style="max-width: 450px; border-radius: var(--radius-md); background-color: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png"
                         alt="Bihar Vihaan Logo"
                         class="site-logo mx-auto mb-3"
                         style="height: 48px;"
                         onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                    <h2 class="fw-bold font-outfit h3" style="color: var(--text-main);">Create Account</h2>
                </div>
                
                <form action="<?= BASE_URL ?>/register" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Rohan Verma" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. rohan@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +91 99999 88888">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Register</button>
                </form>

                <p class="text-center small mt-4 mb-0" style="color: var(--text-secondary);">
                    Already have an account? <a href="<?= BASE_URL ?>/login" class="fw-bold text-decoration-none" style="color: var(--primary);">Sign In</a>
                </p>
            </div>
        </div>
    </div>

<!-- 2.5 FORGOT PASSWORD MODE -->
<?php elseif ($viewMode === 'forgot_password'): ?>
    <div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 shadow-lg w-100" style="max-width: 450px; border-radius: var(--radius-md); background-color: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png"
                         alt="Bihar Vihaan Logo"
                         class="site-logo mx-auto mb-3"
                         style="height: 48px;"
                         onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                    <h2 class="fw-bold font-outfit h3" style="color: var(--text-main);">Reset Password</h2>
                </div>
                <p class="small text-center mb-4" style="color: var(--text-secondary);">Enter your registered email address to receive password reset instructions.</p>
                
                <form action="<?= BASE_URL ?>/forgot-password" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. user@gmail.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Send Instructions</button>
                </form>

                <p class="text-center small mt-4 mb-0" style="color: var(--text-secondary);">
                    Remember password? <a href="<?= BASE_URL ?>/login" class="fw-bold text-decoration-none" style="color: var(--primary);">Sign In</a>
                </p>
            </div>
        </div>
    </div>

<!-- 3. OTP / 2FA VERIFY MODE -->
<?php elseif ($viewMode === '2fa'): ?>
    <div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 shadow-lg w-100" style="max-width: 450px; border-radius: var(--radius-md); background-color: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="card-body text-center">
                <div class="text-center mb-3">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png"
                         alt="Bihar Vihaan Logo"
                         class="site-logo mx-auto mb-3"
                         style="height: 48px;"
                         onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                    <h2 class="fw-bold font-outfit h3" style="color: var(--text-main);">2-Factor OTP Code</h2>
                </div>
                <p class="small mb-4" style="color: var(--text-secondary);">Enter the 6-digit confirmation code dispatched to your phone.</p>
                
                <form action="<?= BASE_URL ?>/2fa" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-4">
                        <input type="text" name="code" class="form-control text-center fs-2 fw-bold" placeholder="123456" maxlength="6" style="letter-spacing: 10px;" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Confirm Code</button>
                </form>
                <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning small mt-4 mb-0 text-start" role="alert">
                    For demo credentials validation bypass, enter: <strong>123456</strong>
                </div>
            </div>
        </div>
    </div>

<!-- 4. STANDARD INDEX HOME VIEW -->
<?php else: ?>
    <section class="hero-video-section">

        <video class="hero-video"
               autoplay
               muted
               loop
               playsinline
               preload="auto"
               poster="<?= BASE_URL ?>/assets/images/hero-fallback.jpg">
            <source src="<?= BASE_URL ?><?= htmlspecialchars($sections['hero']['bg_video'] ?? '/assets/videos/bihar-hero.mp4') ?>" type="video/mp4">
        </video>

        <!-- Hero Overlay -->
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <span class="badge bg-primary text-white border border-primary px-3 py-2 rounded-pill mb-3 small fw-bold text-uppercase tracking-wider">Cradle of Ancient Civilizations</span>
            <h1><?= htmlspecialchars($sections['hero']['title'] ?? 'Discover the Real Bihar') ?></h1>
            <p class="hero-subtitle">
                <?= htmlspecialchars($sections['hero']['subtitle'] ?? 'Explore ancient empires, holy spiritual circuits, unique folk heritages, local businesses, and infinite travel opportunities across the heart of Bihar.') ?>
            </p>

            <!-- Interactive & Animated Search Bar -->
            <div class="search-wrapper position-relative mx-auto mb-5" style="width: 100%; max-width: 650px;">
                <div class="d-flex bg-surface border-0 p-2 rounded-pill shadow-lg search-bar-glow">
                    <input type="text" id="search-query" class="form-control border-0 px-4 fs-5 rounded-pill" placeholder="Search Bodh Gaya, Nalanda, Rajgir, Sasaram..." autocomplete="off" style="box-shadow: none !important; color: var(--text);">
                    <button class="btn btn-primary px-4 rounded-pill" style="min-width: 60px;"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div id="search-dropdown" class="search-results-dropdown w-100 bg-card rounded-4 border border-color shadow-lg mt-2 text-start"></div>
            </div>

            <!-- Primary Actions -->
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <a href="<?= BASE_URL ?><?= htmlspecialchars($sections['hero']['btn1_url'] ?? '/tourism') ?>" class="btn btn-primary btn-lg rounded-pill px-4">
                    <?= htmlspecialchars($sections['hero']['btn1_text'] ?? 'Explore Bihar') ?> <i class="fa-solid fa-compass ms-1"></i>
                </a>
                <a href="<?= BASE_URL ?>/directory" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    List Your Business <i class="fa-solid fa-briefcase ms-1"></i>
                </a>
            </div>

            <!-- Statistics Grid -->
            <div class="hero-stats-row d-flex justify-content-center gap-4 gap-md-5 flex-wrap">
                <div class="stat-item text-center">
                    <div class="stat-num"><?= htmlspecialchars($sections['stats']['stat1_num'] ?? '500+') ?></div>
                    <div class="stat-label text-uppercase"><?= htmlspecialchars($sections['stats']['stat1_lbl'] ?? 'Destinations') ?></div>
                </div>
                <div class="stat-item text-center">
                    <div class="stat-num"><?= htmlspecialchars($sections['stats']['stat2_num'] ?? '1,000+') ?></div>
                    <div class="stat-label text-uppercase"><?= htmlspecialchars($sections['stats']['stat2_lbl'] ?? 'Verified Businesses') ?></div>
                </div>
                <div class="stat-item text-center">
                    <div class="stat-num">50+</div>
                    <div class="stat-label text-uppercase">Festivals</div>
                </div>
                <div class="stat-item text-center">
                    <div class="stat-num">100K+</div>
                    <div class="stat-label text-uppercase">Visitors</div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="scroll-indicator-wrapper">
                <a href="#branding-visit" class="scroll-indicator-link">
                    <div class="scroll-indicator-mouse">
                        <div class="scroll-indicator-wheel"></div>
                    </div>
                </a>
            </div>
        </div>

    </section>

    <!-- Trending Hotspots Grid -->
    <section class="py-5" style="background-color: var(--bg-soft); border-top: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="row mb-5 align-items-end" data-aos="fade-right">
                <div class="col-md-8">
                    <h2 class="section-heading mb-2" style="color: var(--text-main);">Popular Bihar Landmarks</h2>
                    <p class="body-text-custom mb-0">Select recommended tourism hotspots approved by regional coordinators.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="<?= BASE_URL ?>/tourism" class="btn btn-outline">View Tourism Catalog <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>

            <div class="row g-4" data-aos="fade-up">
                <?php foreach ($trending as $dest): ?>
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="custom-card flex-fill">
                            <div class="custom-card-img-wrapper">
                                <img src="<?= htmlspecialchars($dest['image_url']) ?>" class="custom-card-img" alt="<?= htmlspecialchars($dest['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/hero-fallback.jpg';">
                                <span class="custom-card-badge"><?= htmlspecialchars($dest['category']) ?></span>
                            </div>
                            <div class="custom-card-body">
                                <div class="d-flex justify-content-between text-muted small mb-2">
                                    <span>📍 <?= htmlspecialchars($dest['location']) ?></span>
                                    <span class="text-warning fw-bold">★ <?= htmlspecialchars($dest['rating']) ?></span>
                                </div>
                                <h3 class="custom-card-title"><?= htmlspecialchars($dest['name']) ?></h3>
                                <p class="custom-card-desc"><?= htmlspecialchars($dest['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                                    <span class="small text-muted"><i class="fa-solid fa-eye me-1"></i> <?= $dest['views_count'] ?> views</span>
                                    <a href="<?= BASE_URL ?>/tourism/<?= $dest['id'] ?>" class="btn btn-primary btn-sm px-3">Explore <i class="fa-solid fa-chevron-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why Visit Bihar -->
    <section id="branding-visit" class="py-5" style="background-color: var(--bg-main);">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2 small fw-bold text-uppercase">Bihar Essence</span>
                <h2 class="section-heading mb-2">Why Visit Bihar?</h2>
                <p class="body-text-custom" style="max-width: 650px; margin: 0 auto;">Step into the birthplace of religions, ancient universities, sublime art forms, and a legendary cultural legacy.</p>
            </div>

            <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
                <div class="col-md-3 col-sm-6">
                    <div class="p-4 rounded-4 h-100 text-center shadow-sm card-hover-lift" style="background-color: var(--bg-soft); border: 1px solid var(--border-color); transition: var(--transition-smooth);">
                        <div class="fs-1 mb-3">☸️</div>
                        <h3 class="h5 fw-bold mb-2">Spiritual Cradle</h3>
                        <p class="small text-muted mb-0">The site of Buddha's enlightenment at Bodh Gaya and Lord Mahavira's nirvana at Pawapuri.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-4 rounded-4 h-100 text-center shadow-sm card-hover-lift" style="background-color: var(--bg-soft); border: 1px solid var(--border-color); transition: var(--transition-smooth);">
                        <div class="fs-1 mb-3">🎓</div>
                        <h3 class="h5 fw-bold mb-2">Intellectual Hub</h3>
                        <p class="small text-muted mb-0">Home to Nalanda and Vikramashila, the world's earliest international residential universities.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-4 rounded-4 h-100 text-center shadow-sm card-hover-lift" style="background-color: var(--bg-soft); border: 1px solid var(--border-color); transition: var(--transition-smooth);">
                        <div class="fs-1 mb-3">🎨</div>
                        <h3 class="h5 fw-bold mb-2">Folk Masterpieces</h3>
                        <p class="small text-muted mb-0">World-famous Madhubani paintings, Manjusha art, and intricate Sikki grass crafts.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-4 rounded-4 h-100 text-center shadow-sm card-hover-lift" style="background-color: var(--bg-soft); border: 1px solid var(--border-color); transition: var(--transition-smooth);">
                        <div class="fs-1 mb-3">🍲</div>
                        <h3 class="h5 fw-bold mb-2">Gourmet Legacy</h3>
                        <p class="small text-muted mb-0">Taste signature delicacies like Litti Chokha, Khaja, and GI-tagged Shahi Litchis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Bihar Vihaan Section Preview -->
    <section class="py-5" style="background-color: var(--bg-main); border-top: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2 small fw-bold text-uppercase">Who We Are</span>
                    <h2 class="section-heading mb-3 font-outfit" style="color: var(--text-main);">About Bihar Vihaan</h2>
                    <p class="body-text-custom mb-4" style="line-height: 1.7; font-size: 1.05rem;">
                        Bihar Vihaan was founded on <strong>1st April 2023</strong> by <strong>Kaushal Kishor Gupta</strong> with a bold vision: to illuminate the rich, vibrant, and often overlooked culture, history, tourism, and talent of Bihar.
                    </p>
                    <p class="body-text-custom mb-4" style="line-height: 1.7; font-size: 1.05rem;">
                        "Vihaan" means a new beginning — and that is exactly what we strive to bring for Bihar through digital media, community-driven content, tourism promotion, and technological innovation.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="<?= BASE_URL ?>/about" class="btn btn-primary rounded-pill px-4">Read Founding Story</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="p-4 rounded-4 shadow-sm" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                        <h3 class="h4 fw-bold font-outfit mb-3" style="color: var(--text-main);">Our Platform</h3>
                        <p class="small text-muted mb-0" style="line-height: 1.6; font-size: 0.95rem;">
                            Based in Patna, Bihar Vihaan is not just another media platform. It is a movement, a voice for the people, and a stage for talent that mainstream media often overlooks. We aim to bring forward the hidden heritage, diverse culture, literature, folk music, cuisine, and traditions that make Bihar unique.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bihar Heritage Timeline -->
    <section class="py-5" style="background-color: var(--bg-soft); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2 small fw-bold text-uppercase">Chronology</span>
                <h2 class="section-heading mb-2">Bihar Heritage Timeline</h2>
                <p class="body-text-custom" style="max-width: 650px; margin: 0 auto;">Trace the chronological evolution of Bihar through history's golden chapters.</p>
            </div>

            <div class="timeline-container" data-aos="fade-up">
                <div class="timeline-row">
                    <div class="timeline-node">
                        <div class="timeline-date">600 BCE</div>
                        <div class="timeline-card">
                            <h4 class="h6 fw-bold text-primary mb-1">Rise of Magadha &amp; Vaishali</h4>
                            <p class="small text-muted mb-0">Vaishali establishes the world's first republic. Magadha emerges as the center of political power.</p>
                        </div>
                    </div>
                    <div class="timeline-node">
                        <div class="timeline-date">528 BCE</div>
                        <div class="timeline-card">
                            <h4 class="h6 fw-bold text-primary mb-1">Buddha's Enlightenment</h4>
                            <p class="small text-muted mb-0">Siddhartha Gautama attains supreme enlightenment under the Bodhi tree in Bodh Gaya.</p>
                        </div>
                    </div>
                    <div class="timeline-node">
                        <div class="timeline-date">320 BCE</div>
                        <div class="timeline-card">
                            <h4 class="h6 fw-bold text-primary mb-1">Maurya Empire Glory</h4>
                            <p class="small text-muted mb-0">Chandragupta and Emperor Ashoka rule from Pataliputra, spreading non-violence across Asia.</p>
                        </div>
                    </div>
                    <div class="timeline-node">
                        <div class="timeline-date">5th Cent. CE</div>
                        <div class="timeline-card">
                            <h4 class="h6 fw-bold text-primary mb-1">Nalanda University Founded</h4>
                            <p class="small text-muted mb-0">The premier international seat of higher learning attracts scholars globally from China and Tibet.</p>
                        </div>
                    </div>
                    <div class="timeline-node">
                        <div class="timeline-date">1540 CE</div>
                        <div class="timeline-card">
                            <h4 class="h6 fw-bold text-primary mb-1">Sher Shah Suri Reign</h4>
                            <p class="small text-muted mb-0">Sher Shah Suri builds the Grand Trunk Road and establishes administrative reforms from Sasaram.</p>
                        </div>
                    </div>
                    <div class="timeline-node">
                        <div class="timeline-date">Modern Era</div>
                        <div class="timeline-card">
                            <h4 class="h6 fw-bold text-primary mb-1">Cultural Renaissance</h4>
                            <p class="small text-muted mb-0">Bihar enters the digital era, preserving its unique folk arts, eco-sanctuaries, and business hubs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bihar Interactive Tourism Map -->
    <section class="py-5" style="background-color: var(--bg-main);">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2 small fw-bold text-uppercase">Interactive Guide</span>
                <h2 class="section-heading mb-2">Bihar Tourism Map Circuits</h2>
                <p class="body-text-custom" style="max-width: 650px; margin: 0 auto;">Discover the historical networks connecting Bihar's prime archaeological and spiritual treasures.</p>
            </div>
            
            <div class="row g-4 align-items-center" data-aos="fade-up">
                <div class="col-lg-6">
                    <div class="p-4 rounded-4 shadow-sm" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                        <h3 class="h4 fw-bold mb-3 font-outfit" style="color: var(--text-main);"><i class="fa-solid fa-map-location-dot text-primary me-2"></i> Explore Active Circuits</h3>
                        <p class="small text-muted mb-4">Click the hotspots on the interactive map to preview ancient capitals and spiritual centers.</p>
                        
                        <div class="d-flex flex-column gap-3">
                            <div class="p-3 rounded-3" style="background-color: var(--bg-card); border-left: 4px solid var(--primary); border: 1px solid var(--border-color);">
                                <h4 class="h6 fw-bold mb-1" style="color: var(--text-main);">Buddhist Circuit</h4>
                                <p class="xsmall text-muted mb-0">Connecting Pataliputra, Bodh Gaya (Mahabodhi Temple), Nalanda Ruins, and the hot springs of Rajgir.</p>
                            </div>
                            <div class="p-3 rounded-3" style="background-color: var(--bg-card); border-left: 4px solid var(--text-secondary); border: 1px solid var(--border-color);">
                                <h4 class="h6 fw-bold mb-1" style="color: var(--text-main);">Sufi &amp; Heritage Trail</h4>
                                <p class="xsmall text-muted mb-0">Discover Sher Shah Suri's tomb in Sasaram, Jal Mandir in Pawapuri, and the ancient shrines of Patna Sahib.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center">
                    <div class="p-3 rounded-4 shadow-sm" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                        <svg viewBox="0 0 800 400" style="width: 100%; height: auto; max-height: 320px; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <!-- Circuits lines representation -->
                            <path d="M 150 200 L 300 280 L 450 220 L 400 240" fill="none" stroke="var(--primary)" stroke-width="4" stroke-dasharray="5,5" />
                            <path d="M 150 200 L 80 300" fill="none" stroke="var(--text-secondary)" stroke-width="4" stroke-dasharray="5,5" />
                            
                            <!-- Circuit nodes -->
                            <circle cx="150" cy="200" r="10" fill="var(--primary)" style="cursor: pointer;" onclick="alert('Patna: Capital city, historic Pataliputra.')" />
                            <text x="140" y="180" fill="var(--text-main)" font-size="12" font-weight="bold">Patna</text>
                            
                            <circle cx="300" cy="280" r="10" fill="var(--primary)" style="cursor: pointer;" onclick="alert('Bodh Gaya: Sacred site of Lord Buddha\'s enlightenment.')" />
                            <text x="270" y="305" fill="var(--text-main)" font-size="12" font-weight="bold">Bodh Gaya</text>
                            
                            <circle cx="450" cy="220" r="10" fill="var(--primary)" style="cursor: pointer;" onclick="alert('Nalanda: Ancient world university ruins.')" />
                            <text x="440" y="200" fill="var(--text-main)" font-size="12" font-weight="bold">Nalanda</text>
                            
                            <circle cx="400" cy="240" r="10" fill="var(--primary)" style="cursor: pointer;" onclick="alert('Rajgir: Hot springs, ropeways, and glass skywalk.')" />
                            <text x="390" y="265" fill="var(--text-main)" font-size="12" font-weight="bold">Rajgir</text>

                            <circle cx="80" cy="300" r="10" fill="var(--text-secondary)" style="cursor: pointer;" onclick="alert('Sasaram: Sher Shah Suri red sandstone mausoleum tomb.')" />
                            <text x="50" y="325" fill="var(--text-main)" font-size="12" font-weight="bold">Sasaram</text>
                        </svg>
                        <p class="xsmall text-muted mt-2 mb-0"><i class="fa-solid fa-circle-info me-1"></i> Interactive Node: Click circuit markers to view destination briefs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section Preview -->
    <section class="py-5" style="background-color: var(--bg-soft); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-2 small fw-bold text-uppercase">Solutions</span>
                <h2 class="section-heading mb-2">Our Services</h2>
                <p class="body-text-custom" style="max-width: 650px; margin: 0 auto;">Creative production, performance advertising, offline reach, and branding solutions to scale your presence.</p>
            </div>

            <div class="row g-4" data-aos="fade-up">
                <div class="col-md-4">
                    <div class="card p-4 rounded-4 shadow-sm h-100 card-hover-lift d-flex flex-column justify-content-between" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                        <div>
                            <div class="fs-2 mb-3 text-primary"><i class="fa-solid fa-video"></i></div>
                            <h3 class="h5 fw-bold font-outfit mb-2" style="color: var(--text-main);">Ad Production</h3>
                            <p class="small text-muted mb-3">Cinematic brand stories, commercial ads, videography, product photography, and media campaigns.</p>
                            <span class="small text-primary fw-bold">Starts from ₹9,999/-</span>
                        </div>
                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>/contact?service=ad-production" class="btn btn-outline btn-sm w-100 rounded-pill">Get Quote</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 rounded-4 shadow-sm h-100 card-hover-lift d-flex flex-column justify-content-between" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                        <div>
                            <div class="fs-2 mb-3 text-primary"><i class="fa-solid fa-chart-line"></i></div>
                            <h3 class="h5 fw-bold font-outfit mb-2" style="color: var(--text-main);">Online Advertising</h3>
                            <p class="small text-muted mb-3">Grow your digital footprint with performance PPC campaigns, social media marketing, ORM, and Web dev.</p>
                            <span class="small text-primary fw-bold">Starts from ₹4,999/-</span>
                        </div>
                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>/contact?service=online-advertising" class="btn btn-outline btn-sm w-100 rounded-pill">Get Quote</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 rounded-4 shadow-sm h-100 card-hover-lift d-flex flex-column justify-content-between" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                        <div>
                            <div class="fs-2 mb-3 text-primary"><i class="fa-solid fa-bullhorn"></i></div>
                            <h3 class="h5 fw-bold font-outfit mb-2" style="color: var(--text-main);">Branding &amp; Offline Ads</h3>
                            <p class="small text-muted mb-3">TV &amp; radio advertising, news ads, premium logos, outdoor hoardings, and complete brand positioning.</p>
                            <span class="small text-primary fw-bold">Custom rates available</span>
                        </div>
                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>/contact?service=branding-offline" class="btn btn-outline btn-sm w-100 rounded-pill">Get Quote</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?= BASE_URL ?>/services" class="btn btn-primary rounded-pill px-4">View All Services</a>
            </div>
        </div>
    </section>



    <!-- Spiritual/Cultural Festivals Calendar -->
    <section class="py-5" style="background-color: var(--bg-main);">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-heading mb-2" style="color: var(--text-main);">Upcoming Holiday Festivals</h2>
                <p class="body-text-custom">Plan your travels around Bihar's vibrant spiritual festivities.</p>
            </div>

            <div class="row g-4" data-aos="fade-up">
                <?php foreach ($festivals as $fest): ?>
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="custom-card flex-fill">
                            <div class="custom-card-img-wrapper">
                                <img src="<?= htmlspecialchars($fest['image_url']) ?>" class="custom-card-img" alt="<?= htmlspecialchars($fest['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/hero-fallback.jpg';">
                                <span class="custom-card-badge" style="background-color: var(--primary); color: white; border-color: var(--primary);"><?= date('d M Y', strtotime($fest['date'])) ?></span>
                            </div>
                            <div class="custom-card-body">
                                <div class="d-flex justify-content-between text-muted small mb-2">
                                    <span>📍 <?= htmlspecialchars($fest['location']) ?></span>
                                </div>
                                <h3 class="custom-card-title"><?= htmlspecialchars($fest['name']) ?></h3>
                                <p class="custom-card-desc"><?= htmlspecialchars($fest['description']) ?></p>
                                <div class="pt-3 border-top" style="border-color: var(--border-color) !important;">
                                    <a href="<?= BASE_URL ?>/events" class="btn btn-outline btn-sm w-100">View Events &amp; Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="<?= BASE_URL ?>/events" class="btn btn-secondary px-4">Open Festivals Calendar</a>
            </div>
        </div>
    </section>

    <!-- Premium Futuristic Enterprise Logo Showcase -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clients.css">
    
    <section class="clients-section">
        <!-- Animated Backgrounds -->
        <div class="client-bg-gradient"></div>
        <div class="client-particles"></div>
        
        <div class="container position-relative" style="z-index: 2;">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge text-uppercase px-3 py-2 rounded-pill mb-3 fw-bold" style="color: #0B3D91; border: 1px solid #0B3D91; background: rgba(11, 61, 145, 0.1);">Trusted By</span>
                <h2 class="section-heading mb-3" style="font-family: 'Outfit', sans-serif; color: #1F2937 !important;">Our Clients & Partners</h2>
                <p class="body-text-custom" style="max-width: 700px; margin: 0 auto; color: #6B7280 !important; font-size: 1.1rem;">
                    Trusted by tourism partners, local businesses and cultural organizations across Bihar.
                </p>
            </div>
            
            <div class="clients-marquee-wrapper" data-aos="fade-up" data-aos-delay="100">
                <!-- Two identical marquees for seamless infinite looping -->
                <div class="clients-marquee">
                    <?php
                    $clientsList = [
                        ['name' => 'Bihar Tourism', 'tag' => 'Tourism', 'img' => 'https://logo.clearbit.com/tourism.bihar.gov.in'],
                        ['name' => 'NTPC Kahalgaon', 'tag' => 'Enterprise', 'img' => 'https://logo.clearbit.com/ntpc.co.in'],
                        ['name' => 'Maurya Hotels', 'tag' => 'Hospitality', 'img' => 'https://logo.clearbit.com/maurya.com'],
                        ['name' => 'Sudha Dairy', 'tag' => 'Enterprise', 'img' => 'https://logo.clearbit.com/sudha.coop'],
                        ['name' => 'IIT Patna', 'tag' => 'Education', 'img' => 'https://logo.clearbit.com/iitp.ac.in'],
                        ['name' => 'NIFT Patna', 'tag' => 'Education', 'img' => 'https://logo.clearbit.com/nift.ac.in'],
                    ];
                    foreach ($clientsList as $clientData): ?>
                    <div class="client-card">
                        <img src="<?= $clientData['img'] ?>" alt="<?= $clientData['name'] ?>" loading="lazy" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('<?= $clientData['name'] ?>') + '&background=F8F4F0&color=1F2937&size=200&font-size=0.33';">
                        <div class="client-info">
                            <h5><?= $clientData['name'] ?></h5>
                            <span><?= $clientData['tag'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="clients-marquee" aria-hidden="true">
                    <?php foreach ($clientsList as $clientData): ?>
                    <div class="client-card">
                        <img src="<?= $clientData['img'] ?>" alt="<?= $clientData['name'] ?>" loading="lazy" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('<?= $clientData['name'] ?>') + '&background=F8F4F0&color=1F2937&size=200&font-size=0.33';">
                        <div class="client-info">
                            <h5><?= $clientData['name'] ?></h5>
                            <span><?= $clientData['tag'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <script src="<?= BASE_URL ?>/assets/js/clients.js"></script>

    <!-- Internship Section -->
    <section class="py-5" style="background-color: var(--bg-main); border-bottom: 1px solid var(--border-color);">
        <div class="container py-5">
            <div class="p-5 rounded-4 shadow-lg text-center" style="background: linear-gradient(135deg, var(--bg-soft) 0%, var(--bg-card) 100%); border: 1px solid var(--border-color);" data-aos="fade-up">
                <span class="badge bg-primary text-white border border-primary px-3 py-2 rounded-pill mb-3 small fw-bold text-uppercase">Careers &amp; Learning</span>
                <h2 class="display-5 fw-bold font-outfit mb-3" style="color: var(--text-main);">Join Bihar Vihaan Internship Program</h2>
                <p class="body-text-custom max-w-md mx-auto mb-4" style="max-width: 600px; margin: 0 auto; color: var(--text-secondary);">
                    Gain hands-on experience in content writing, digital journalism, historical research, folk arts coordination, media network campaigns, and web application design.
                </p>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfCn2Xk_A5ULelfMtp2h9ERsRxe1Mr6P53ecG4gpri_-YqlHA/viewform" target="_blank" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">
                    Apply for Internship <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size:0.85rem;"></i>
                </a>
            </div>
        </div>
    </section>

<?php endif; ?>

