<?php
use App\Core\Auth;
use App\Core\Session;
$viewMode = $view_mode ?? 'list';
$csrfToken = Session::generateCsrfToken();
?>

<div class="container py-5 mt-5">

    <!-- 1. BUSINESS DETAILS VIEW -->
    <?php if ($viewMode === 'detail'): ?>
        <div class="row g-5" data-aos="fade-up">
            <div class="col-lg-8">
                <div class="card p-4 p-md-5 shadow-lg" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span class="badge bg-primary px-3 py-2 rounded-pill small"><?= htmlspecialchars(strtoupper($listing['category'])) ?></span>
                        
                        <div class="d-flex gap-2">
                            <?php if ($listing['plan'] !== 'free'): ?>
                                <span class="badge bg-warning text-dark border-warning px-2 py-1 rounded-pill small"><?= htmlspecialchars($listing['plan']) ?></span>
                            <?php endif; ?>
                            <?php if ($listing['verification_status'] === 'verified'): ?>
                                <span class="verified-badge">✓ Verified Listing</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h1 class="display-6 fw-bold mb-3 font-outfit" style="color: var(--text-main);"><?= htmlspecialchars($listing['name']) ?></h1>
                    
                    <div class="d-flex gap-4 text-muted small mb-4 border-bottom pb-3" style="border-color: var(--border-color) !important;">
                        <span>📍 Address: <?= htmlspecialchars($listing['address']) ?></span>
                        <span>📞 Phone: <?= htmlspecialchars($listing['phone']) ?></span>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 fw-bold mb-2 font-outfit" style="color: var(--text-main);">About Our Business</h3>
                        <p class="lh-lg small" style="color: var(--text-secondary);"><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
                    </div>

                    <!-- Connect Channels -->
                    <div class="p-4 rounded-4 mb-4" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                        <h3 class="h6 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Contact Channels</h3>
                        
                        <div class="d-flex gap-3 align-items-center flex-wrap">
                            <?php if ($listing['whatsapp_number']): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $listing['whatsapp_number']) ?>?text=Hello%20<?= urlencode($listing['name']) ?>,%20I%20saw%20your%20listing%20on%20Bihar%20Vihaan%20and%20would%20like%20to%20inquire." 
                                   target="_blank" 
                                   class="btn whatsapp-btn px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                                    <i class="fa-brands fa-whatsapp fs-5"></i> Connect on WhatsApp
                                </a>
                            <?php endif; ?>

                            <?php if ($listing['website']): ?>
                                <a href="<?= htmlspecialchars($listing['website']) ?>" target="_blank" class="btn btn-outline px-4">Visit Website <i class="fa-solid fa-square-arrow-up-right ms-1"></i></a>
                            <?php endif; ?>
                            
                            <span class="small text-muted ms-md-3">Email: <?= htmlspecialchars($listing['email']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inquiries Form Sidebar -->
            <div class="col-lg-4">
                <div class="card p-4 shadow-lg sticky-top" style="top: 100px; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-1 font-outfit" style="color: var(--text-main);">Send Inquiry</h3>
                    <p class="small mb-4" style="color: var(--text-secondary);">Submit booking quotes or catalog questions. The listing owner will be notified.</p>
                    
                    <form action="<?= BASE_URL ?>/business/lead" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="business_id" value="<?= $listing['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Your Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Kaushik Kumar" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. kaushik@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. +91 99999 55555" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Message / Inquiry</label>
                            <textarea name="message" class="form-control small" rows="5" placeholder="Specify dates or bulk order sizes..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Submit Inquiry</button>
                    </form>
                </div>
            </div>
        </div>

    <!-- 2. BUSINESS INDEX LIST -->
    <?php else: ?>
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 4rem; flex-wrap:wrap; gap:20px;" data-aos="fade-right">
            <div>
                <h1 class="display-5 fw-bold font-outfit" style="color: var(--text-main);">Local Business Directory</h1>
                <p class="body-text-custom">Explore verified hotel room listings, cab operations, and traditional art workshops.</p>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>/business" class="marketplace-filter-pill <?= !$selected_category ? 'active' : '' ?>">All</a>
                <a href="<?= BASE_URL ?>/business?category=hotel" class="marketplace-filter-pill <?= $selected_category === 'hotel' ? 'active' : '' ?>">Hotels</a>
                <a href="<?= BASE_URL ?>/business?category=agency" class="marketplace-filter-pill <?= $selected_category === 'agency' ? 'active' : '' ?>">Agencies</a>
                <a href="<?= BASE_URL ?>/business?category=handicrafts" class="marketplace-filter-pill <?= $selected_category === 'handicrafts' ? 'active' : '' ?>">Handicrafts</a>
            </div>
        </div>

        <div class="row g-4 mb-5" data-aos="fade-up">
            <?php foreach ($listings as $list): ?>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="custom-card flex-fill d-flex flex-column justify-content-between" style="border: <?= $list['plan'] === 'featured' ? '1px solid var(--primary) !important' : '1px solid var(--border-color)' ?>;">
                        <div class="p-4 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary px-3 py-1 rounded-pill small"><?= htmlspecialchars(strtoupper($list['category'])) ?></span>
                                
                                <div class="d-flex gap-1 align-items-center">
                                    <?php if ($list['plan'] !== 'free'): ?>
                                        <span class="badge bg-warning text-dark rounded-pill small" style="font-size:0.65rem;"><?= htmlspecialchars($list['plan']) ?></span>
                                    <?php endif; ?>
                                    <?php if ($list['verification_status'] === 'verified'): ?>
                                        <span class="verified-badge" style="font-size:0.65rem;">✓ Verified</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h3 class="custom-card-title" style="font-size: 20px;"><?= htmlspecialchars($list['name']) ?></h3>
                            <p class="custom-card-desc" style="font-size: 14px;"><?= htmlspecialchars($list['description']) ?></p>
                        </div>

                        <div class="p-4 pt-3 border-top d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                            <span class="small text-muted"><i class="fa-solid fa-map-location-dot me-1"></i> <?= htmlspecialchars($list['address']) ?></span>
                            <a href="<?= BASE_URL ?>/business/<?= $list['id'] ?>" class="btn btn-outline btn-sm px-3">Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Ad slots banner -->
        <div class="p-5 rounded-4 shadow-lg text-center position-relative overflow-hidden" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);" data-aos="fade-up">
            <span class="badge bg-warning text-dark border-warning px-3 py-2 rounded-pill mb-3 small fw-bold">SPONSORED CAMPAIGN</span>
            <h2 class="h3 fw-bold mb-2 font-outfit" style="color: var(--text-main);">Explore Ancient Rajgir Safaris</h2>
            <p class="small mb-4 max-w-2xl mx-auto" style="color: var(--text-secondary); max-width: 650px;">Book premium local cab systems directly via Bihar Vihaan and claim 15% discount coupons on holiday travel packages.</p>
            <a href="<?= BASE_URL ?>/business/ad-click?ad_name=rajgir_safari_banner" class="btn btn-primary px-4 fw-bold">Claim Discount Voucher</a>
        </div>
    <?php endif; ?>
</div>
