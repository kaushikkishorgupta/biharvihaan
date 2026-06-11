<?php
use App\Core\Auth;
use App\Core\Session;
$viewMode = $view_mode ?? 'list';
$csrfToken = Session::generateCsrfToken();
?>

<div class="container py-5 mt-5">

    <!-- 1. ARTIST PORTFOLIO DETAILS -->
    <?php if ($viewMode === 'detail'): ?>
        <div class="row g-5" data-aos="fade-up">
            <div class="col-lg-8">
                <div class="card bg-card border-secondary-subtle p-4 p-md-5 rounded-4 shadow-lg">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 align-self-start small"><?= htmlspecialchars(str_replace('_', ' ', $artist['category'])) ?></span>
                    <h1 class="display-6 fw-bold text-white mb-2 font-outfit"><?= htmlspecialchars($artist['stage_name']) ?></h1>
                    
                    <div class="d-flex gap-4 text-muted small mb-4 border-bottom border-secondary-subtle pb-3">
                        <span>👤 Artist Name: <?= htmlspecialchars($artist['user_name']) ?></span>
                        <span class="text-success fw-bold"><i class="fa-solid fa-square-check"></i> Verified Creator</span>
                    </div>

                    <div class="mb-5">
                        <h3 class="h5 text-white fw-bold mb-2 font-outfit">Biography</h3>
                        <p class="text-muted lh-lg small"><?= nl2br(htmlspecialchars($artist['bio'])) ?></p>
                    </div>

                    <h3 class="h4 text-white fw-bold mb-4 font-outfit border-bottom border-secondary-subtle pb-2">Portfolio Showcase</h3>
                    <div class="d-flex flex-column gap-4">
                        <?php if (!empty($portfolios)): ?>
                            <?php foreach ($portfolios as $media): ?>
                                <div class="p-4 bg-dark border border-secondary-subtle rounded-3">
                                    <h4 class="h6 text-primary fw-bold mb-2"><?= htmlspecialchars($media['title']) ?></h4>
                                    <?php if ($media['description']): ?>
                                        <p class="small text-muted mb-3"><?= htmlspecialchars($media['description']) ?></p>
                                    <?php endif; ?>

                                    <?php if ($media['media_type'] === 'video'): ?>
                                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden" style="max-width: 600px;">
                                            <iframe src="<?= htmlspecialchars($media['media_url']) ?>" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?= htmlspecialchars($media['media_url']) ?>" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                                            <i class="fa-solid fa-link me-1"></i> Open Media Link
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="small text-muted">No portfolio assets uploaded yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Collaboration request form sidebar -->
            <div class="col-lg-4">
                <div class="card bg-card border-secondary-subtle rounded-4 p-4 shadow-lg sticky-top" style="top: 100px;">
                    <h3 class="h5 text-white fw-bold mb-1 font-outfit">Collaborate</h3>
                    <p class="text-muted small mb-4">Send a networking message to organize joint folklore shows or art workshops.</p>
                    
                    <?php if (Auth::check()): ?>
                        <form action="<?= BASE_URL ?>/talent/collab" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <input type="hidden" name="receiver_user_id" value="<?= $artist['user_id'] ?>">
                            
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Your Message / Offer</label>
                                <textarea name="message" class="form-control bg-dark border-secondary text-white small" rows="5" placeholder="State your proposed date, budget or scope..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Send Collaboration Request</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4 border border-secondary border-dashed rounded-3">
                            <p class="small text-muted mb-3">Login to propose collaborations.</p>
                            <a href="<?= BASE_URL ?>/login" class="btn btn-primary btn-sm">Log In</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <!-- 2. ARTISTS LISTING -->
    <?php else: ?>
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 4rem; flex-wrap:wrap; gap:20px;" data-aos="fade-right">
            <div>
                <h1 class="display-5 fw-bold text-white font-outfit">Bihar Talent Showcase</h1>
                <p class="text-muted">Connecting folk musicians, Madhubani painters, and cultural photographers.</p>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>/talent" class="btn btn-outline-light btn-sm <?= !$selected_category ? 'btn-primary text-white border-primary' : '' ?>">All</a>
                <a href="<?= BASE_URL ?>/talent?category=folk_musician" class="btn btn-outline-light btn-sm <?= $selected_category === 'folk_musician' ? 'btn-primary text-white border-primary' : '' ?>">Musicians</a>
                <a href="<?= BASE_URL ?>/talent?category=photographer" class="btn btn-outline-light btn-sm <?= $selected_category === 'photographer' ? 'btn-primary text-white border-primary' : '' ?>">Photographers</a>
                <a href="<?= BASE_URL ?>/talent?category=writer" class="btn btn-outline-light btn-sm <?= $selected_category === 'writer' ? 'btn-primary text-white border-primary' : '' ?>">Writers</a>
                <a href="<?= BASE_URL ?>/talent?category=content_creator" class="btn btn-outline-light btn-sm <?= $selected_category === 'content_creator' ? 'btn-primary text-white border-primary' : '' ?>">Content Creators</a>
            </div>
        </div>

        <div class="row g-4" data-aos="fade-up">
            <?php foreach ($artists as $art): ?>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow flex-fill d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary px-3 py-1 rounded-pill small"><?= htmlspecialchars(str_replace('_', ' ', $art['category'])) ?></span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small rounded-pill">Verified</span>
                            </div>
                            
                            <h3 class="h5 fw-bold text-white mb-2 font-outfit"><?= htmlspecialchars($art['stage_name']) ?></h3>
                            <p class="small text-muted line-clamp-3 mb-4"><?= htmlspecialchars($art['bio']) ?></p>
                        </div>

                        <div class="pt-3 border-top border-secondary-subtle d-flex justify-content-between align-items-center">
                            <span class="small text-muted"><i class="fa-solid fa-envelope me-1"></i> <?= htmlspecialchars($art['user_email']) ?></span>
                            <a href="<?= BASE_URL ?>/talent/<?= $art['id'] ?>" class="btn btn-outline-primary btn-sm px-3 rounded-pill">View Portfolio</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
