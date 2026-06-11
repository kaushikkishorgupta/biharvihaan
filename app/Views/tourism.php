<?php
use App\Core\Auth;
use App\Core\Session;
$viewMode = $view_mode ?? 'list';
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

<!-- ==========================================
     1. DETAIL VIEW MODE
     ========================================== -->
<?php if ($viewMode === 'detail'): ?>
    <div class="container py-5 mt-5">
        <div class="row g-5">
            <!-- Left Side: Detail & Reviews -->
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card p-4 p-md-5 shadow-lg" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <img src="<?= htmlspecialchars($destination['image_url']) ?>" class="w-100 rounded-3 mb-4 object-fit-cover" style="height: 400px;" alt="<?= htmlspecialchars($destination['name']) ?>">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <span class="badge bg-primary px-3 py-2 rounded-pill small"><?= htmlspecialchars($destination['category']) ?></span>
                            <button onclick="readAloudDestination()" class="btn btn-outline btn-sm px-3 rounded-pill ms-2"><i class="fa-solid fa-volume-high me-1"></i> Speak Assistant</button>
                        </div>
                        
                        <?php if (Auth::check()): ?>
                            <?php if ($is_saved): ?>
                                <a href="<?= BASE_URL ?>/tourism/unsave/<?= $destination['id'] ?>" class="btn btn-outline btn-sm px-3 rounded-pill" style="color: var(--status-danger); border-color: var(--status-danger);"><i class="fa-solid fa-heart me-1"></i> Saved</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/tourism/save/<?= $destination['id'] ?>" class="btn btn-primary btn-sm px-3 rounded-pill"><i class="fa-regular fa-heart me-1"></i> Wishlist</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm px-3 rounded-pill">Wishlist</a>
                        <?php endif; ?>
                    </div>

                    <h1 class="display-5 fw-bold mb-3 font-outfit" style="color: var(--text-main);"><?= htmlspecialchars($destination['name']) ?></h1>
                    
                    <div class="d-flex gap-4 text-muted small mb-4 border-bottom pb-3" style="border-color: var(--border-color) !important;">
                        <span>📍 <?= htmlspecialchars($destination['location']) ?></span>
                        <span>⭐ <?= htmlspecialchars($destination['rating']) ?> Rating</span>
                        <span>👁 <?= htmlspecialchars($destination['views_count']) ?> views</span>
                    </div>

                    <p id="destinationDesc" class="lead lh-lg mb-5 body-text-custom" style="font-size: 1.05rem;">
                         <?= nl2br(htmlspecialchars($destination['description'])) ?>
                    </p>

                    <!-- Attractions -->
                    <h3 class="h4 fw-bold mb-4 font-outfit border-bottom pb-2" style="color: var(--text-main); border-color: var(--border-color) !important;">Nearby Attractions</h3>
                    <div class="row g-3 mb-5">
                        <?php if (!empty($attractions)): ?>
                            <?php foreach ($attractions as $att): ?>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 h-100" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                                        <h4 class="h6 fw-bold mb-2" style="color: var(--primary);"><?= htmlspecialchars($att['name']) ?></h4>
                                        <p class="small mb-3" style="color: var(--text-secondary);"><?= htmlspecialchars($att['description']) ?></p>
                                        <span class="small text-warning"><i class="fa-solid fa-car me-1"></i> <?= $att['distance_km'] ?> km away</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12"><p class="small text-muted">No nearby landmarks registered.</p></div>
                        <?php endif; ?>
                    </div>

                    <!-- Reviews Section -->
                    <h3 class="h4 fw-bold mb-4 font-outfit border-bottom pb-2" style="color: var(--text-main); border-color: var(--border-color) !important;">Reviews &amp; Ratings Feed</h3>
                    
                    <!-- Write review -->
                    <?php if (Auth::check()): ?>
                        <div class="p-3 rounded-3 mb-4" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                            <h4 class="h6 fw-bold mb-3" style="color: var(--text-main);">Write Feedback</h4>
                            <form id="review-submission-form">
                                <input type="hidden" name="csrf_token" id="review-csrf" value="<?= $csrfToken ?>">
                                <input type="hidden" name="reference_type" value="destination">
                                <input type="hidden" name="reference_id" value="<?= $destination['id'] ?>">
                                <div class="mb-3">
                                    <label class="small text-muted">Rating (1 to 5 Stars)</label>
                                    <select name="rating" class="form-select w-25">
                                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4)</option>
                                        <option value="3">⭐⭐⭐ (3)</option>
                                        <option value="2">⭐⭐ (2)</option>
                                        <option value="1">⭐ (1)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                                </div>
                                <button type="button" onclick="submitUserReview()" class="btn btn-primary btn-sm">Publish Review</button>
                            </form>
                            <div id="review-msg" class="small mt-2" style="display:none;"></div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-column gap-3" id="reviews-feed">
                        <?php 
                        $db = \App\Core\Database::getInstance();
                        $reviews = $db->query("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.reference_type = 'destination' AND r.reference_id = ? ORDER BY r.id DESC", [$destination['id']]);
                        if (!empty($reviews)): 
                            foreach ($reviews as $rev):
                        ?>
                            <div class="p-3 rounded-3" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold small" style="color: var(--text-main);"><?= htmlspecialchars($rev['user_name']) ?></span>
                                    <span class="text-warning small"><?= str_repeat('★', $rev['rating']) ?></span>
                                </div>
                                <p class="small mb-0" style="color: var(--text-secondary);"><?= htmlspecialchars($rev['comment']) ?></p>
                            </div>
                        <?php endforeach; else: ?>
                            <p class="small text-muted" id="no-reviews-txt">No reviews submitted yet. Be the first!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Side: sidebar Map -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card p-4 shadow-lg mb-4" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Simulated Coordinates</h3>
                    <div class="rounded-3 d-flex flex-column align-items-center justify-content-center text-center p-4" style="height: 220px; background-color: var(--bg-soft); border: 1px solid var(--border-color); background-image: radial-gradient(circle, var(--border-color) 8%, transparent 9%); background-size:15px 15px;">
                        <span class="fs-1 text-primary">📍</span>
                        <div class="fw-bold mt-2 small" style="color: var(--text-main);"><?= htmlspecialchars($destination['name']) ?></div>
                        <div class="small text-muted">GPS: <?= $destination['latitude'] ?>, <?= $destination['longitude'] ?></div>
                    </div>
                </div>

                <div class="card p-4 shadow-lg mb-4" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-3 font-outfit" style="color: var(--text-main);">AR 360 Scenery Preview</h3>
                    <div class="position-relative overflow-hidden rounded-3 border" style="height: 180px; border-color: var(--border-color) !important;">
                        <div id="panoramic-preview" style="width: 1200px; height: 100%; background-image: url('<?= htmlspecialchars($destination['image_url']) ?>'); background-size: cover; background-position: center; transition: background-position 0.1s; cursor: grab;"></div>
                        <span class="position-absolute bottom-3 left-3 badge bg-dark bg-opacity-75 fs-8" style="left: 10px; bottom: 10px;"><i class="fa-solid fa-arrows-left-right me-1"></i> Drag to pan 360°</span>
                    </div>
                </div>

                <div class="card p-4 shadow-lg" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Book Your Journey</h3>
                    <p class="small mb-4" style="color: var(--text-secondary);">Reserve tour cars, local guides, or rooms near Bodh Gaya / Nalanda instantly.</p>
                    <a href="<?= BASE_URL ?>/bookings" class="btn btn-primary w-100">Schedule Reservations</a>
                </div>
            </div>
        </div>
    </div>

<!-- ==========================================
     2. AI TRAVEL PLANNER FORM
     ========================================== -->
<?php elseif ($viewMode === 'ai_planner'): ?>
    <div class="container py-5 mt-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 shadow-lg w-100" style="max-width: 550px; border-radius: var(--radius-md); background-color: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="card-body">
                <div class="text-center mb-4">
                    <span class="fs-1 text-primary">🧠</span>
                    <h2 class="fw-bold mt-2 font-outfit h3" style="color: var(--text-main);">AI Travel Planner</h2>
                    <p class="small" style="color: var(--text-secondary);">Answer questions to compile a calculated travel itinerary.</p>
                </div>

                <form action="<?= BASE_URL ?>/tourism/ai-planner" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-3">
                        <label class="form-label">Travel Style</label>
                        <select name="style" class="form-select">
                            <option value="Pilgrimage">Pilgrimage (Sacred Routes)</option>
                            <option value="Solo">Solo Explorer</option>
                            <option value="Family">Family Vacation</option>
                            <option value="Student">Student Field Trip</option>
                            <option value="Adventure">Wildlife &amp; Adventure</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pricing Class</label>
                        <select name="budget" class="form-select">
                            <option value="low">Budget / Backpacker (Low Cost)</option>
                            <option value="medium" selected>Standard Premium (Mid Cost)</option>
                            <option value="luxury">Luxury Elite (High Cost)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Trip Duration (Days)</label>
                        <select name="duration" class="form-select">
                            <option value="1">1 Day</option>
                            <option value="2">2 Days</option>
                            <option value="3" selected>3 Days</option>
                            <option value="4">4 Days</option>
                            <option value="5">5 Days</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Generate AI Itinerary</button>
                </form>
            </div>
        </div>
    </div>

<!-- ==========================================
     3. AI PLANNER OUTCOME RESULTS
     ========================================== -->
<?php elseif ($viewMode === 'ai_planner_results'): ?>
    <div class="container py-5 mt-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">AI COMPILED TRIP</span>
            <h1 class="display-5 fw-bold font-outfit" style="color: var(--text-main);"><?= $duration ?>-Day <?= htmlspecialchars($style) ?> Trip Itinerary</h1>
            <p class="body-text-custom">Personalized cost estimation and suggestions for your Bihar experience.</p>
        </div>

        <div class="row g-4">
            <!-- Cost Summaries sidebar -->
            <div class="col-lg-4" data-aos="fade-right">
                <div class="card p-4 rounded-4 shadow-lg mb-4" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Trip Summary</h3>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Travel Style:</span>
                        <span class="fw-bold small" style="color: var(--text-main);"><?= htmlspecialchars($style) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Duration:</span>
                        <span class="fw-bold small" style="color: var(--text-main);"><?= $duration ?> Days</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Budget Tier:</span>
                        <span class="badge bg-warning uppercase small"><?= htmlspecialchars($budget) ?></span>
                    </div>
                    
                    <div class="border-top pt-3 text-center" style="border-color: var(--border-color) !important;">
                        <div class="text-muted small uppercase">Estimated Gross Cost</div>
                        <div class="display-6 fw-extrabold text-primary my-2">₹<?= number_format($total_cost) ?></div>
                        <p class="xsmall text-muted mb-0">*Includes accommodation estimate, transport, &amp; guide rates.</p>
                    </div>
                </div>

                <div class="card p-4 rounded-4 shadow-lg" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Ready to Book?</h3>
                    <p class="small mb-4" style="color: var(--text-secondary);">You can reserve these hotels, guides, or taxis in one click.</p>
                    <a href="<?= BASE_URL ?>/bookings" class="btn btn-primary w-100">Reserve Journey Plan</a>
                </div>
            </div>

            <!-- Daywise Itinerary lists -->
            <div class="col-lg-8" data-aos="fade-left">
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($day_plans as $dayNum => $day): ?>
                        <div class="card rounded-4 overflow-hidden p-4" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <img src="<?= htmlspecialchars($day['image']) ?>" class="w-100 rounded-3 object-fit-cover" style="height: 120px;" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                                </div>
                                <div class="col-md-9">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary-subtle text-primary fw-bold">DAY <?= $dayNum ?></span>
                                        <span class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($day['destination_name']) ?></span>
                                    </div>
                                    <h3 class="h5 fw-bold mb-2 font-outfit" style="color: var(--text-main);"><?= htmlspecialchars($day['destination_name']) ?> Sightseeing</h3>
                                    <p class="small mb-3" style="color: var(--text-secondary);"><?= htmlspecialchars($day['activities']) ?></p>
                                    <div class="alert alert-info border-info-subtle bg-info-subtle text-info small py-2 mb-0" role="alert">
                                        <i class="fa-solid fa-lightbulb me-1"></i> <strong>Tip:</strong> <?= htmlspecialchars($day['travel_tip']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<!-- ==========================================
     3.5 SEARCH RESULTS VIEW MODE
     ========================================== -->
<?php elseif ($viewMode === 'search_results'): ?>
    <div class="container py-5 mt-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">SEARCH RESULTS</span>
            <h1 class="display-5 fw-bold font-outfit" style="color: var(--text-main);">Matching Destinations</h1>
            <p class="body-text-custom">Found <?= count($destinations) ?> matching results for query "<strong><?= htmlspecialchars($query) ?></strong>"</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (empty($destinations)): ?>
                <div class="col-md-8 text-center py-5">
                    <div class="p-5 rounded-4" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                        <i class="fa-solid fa-face-frown fs-1 text-muted mb-3"></i>
                        <h3 style="color: var(--text-main);">No Landmarks Found</h3>
                        <p class="body-text-custom">We couldn't find any destinations matching your search parameters. Try searching for "Gaya", "Nalanda", or "Temple".</p>
                        <a href="<?= BASE_URL ?>/tourism" class="btn btn-primary mt-3">Reset Catalog Filter</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($destinations as $dest): ?>
                    <div class="col-lg-4 col-md-6 d-flex">
                        <div class="custom-card flex-fill">
                            <div class="custom-card-img-wrapper">
                                <img src="<?= htmlspecialchars($dest['image_url']) ?>" class="custom-card-img" alt="<?= htmlspecialchars($dest['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/hero-fallback.jpg';">
                                <span class="custom-card-badge"><?= htmlspecialchars($dest['category']) ?></span>
                            </div>
                            <div class="custom-card-body">
                                <div class="d-flex justify-content-between text-muted small mb-2">
                                    <span>📍 <?= htmlspecialchars($dest['location']) ?></span>
                                    <span class="text-warning">★ <?= htmlspecialchars($dest['rating'] ?? '5.0') ?></span>
                                </div>
                                <h3 class="custom-card-title"><?= htmlspecialchars($dest['name']) ?></h3>
                                <p class="custom-card-desc"><?= htmlspecialchars($dest['description'] ?? '') ?></p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                                    <span class="small text-muted"><i class="fa-solid fa-eye me-1"></i> <?= $dest['views_count'] ?? 0 ?> views</span>
                                    <a href="<?= BASE_URL ?>/tourism/<?= $dest['id'] ?>" class="btn btn-primary btn-sm px-3">Details <i class="fa-solid fa-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

<!-- ==========================================
     4. INDEX CATALOG (DEFAULT)
     ========================================== -->
<?php else: ?>
    <div class="container py-5 mt-5">
        <div class="row mb-5 align-items-end" data-aos="fade-right">
            <div class="col-md-7">
                <h1 class="display-5 fw-bold font-outfit" style="color: var(--text-main);">Tourism Catalog</h1>
                <p class="body-text-custom">Match historic ruins, Buddhist stupas, and wildlife safaris.</p>
            </div>
            <!-- Category buttons -->
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div class="d-flex gap-2 justify-content-md-end flex-wrap mb-3">
                    <a href="<?= BASE_URL ?>/tourism" class="btn btn-outline btn-sm <?= !$selected_category ? 'btn-primary text-white border-primary' : '' ?>">All</a>
                    <a href="<?= BASE_URL ?>/tourism?category=Spiritual" class="btn btn-outline btn-sm <?= $selected_category === 'Spiritual' ? 'btn-primary text-white border-primary' : '' ?>">Spiritual</a>
                    <a href="<?= BASE_URL ?>/tourism?category=Heritage" class="btn btn-outline btn-sm <?= $selected_category === 'Heritage' ? 'btn-primary text-white border-primary' : '' ?>">Heritage</a>
                    <a href="<?= BASE_URL ?>/tourism?category=Nature" class="btn btn-outline btn-sm <?= $selected_category === 'Nature' ? 'btn-primary text-white border-primary' : '' ?>">Nature</a>
                    
                    <a href="<?= BASE_URL ?>/tourism/ai-planner" class="btn btn-primary btn-sm"><i class="fa-solid fa-brain me-1"></i> AI Planner</a>
                </div>
                <!-- Voice search bar -->
                <div class="input-group input-group-sm max-w-xs ms-md-auto" style="max-width: 250px;">
                    <input type="text" id="tourismSearch" class="form-control" placeholder="Search hotspots...">
                    <button class="btn btn-primary btn-sm" type="button" onclick="startVoiceSearch()"><i class="fa-solid fa-microphone"></i> Voice</button>
                </div>
            </div>
        </div>

        <!-- Interactive Map Widget -->
        <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="col-12 text-center p-4 rounded-4 shadow-sm" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                <h5 class="font-outfit mb-3" style="color: var(--primary);"><i class="fa-solid fa-map-location-dot me-2"></i> Interactive Bihar Tourism Circuit Map</h5>
                <div class="d-flex justify-content-center overflow-x-auto">
                    <svg viewBox="0 0 800 400" style="width: 100%; max-width: 600px; height: 300px; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px;">
                        <!-- Circuits representation -->
                        <!-- Buddhist Circuit (Patna - Bodh Gaya - Nalanda - Rajgir) -->
                        <path d="M 150 200 L 300 280 L 450 220 L 400 240" fill="none" stroke="#ff4f00" stroke-width="4" stroke-dasharray="5,5" />
                        <!-- Heritage Trail (Patna - Sasaram) -->
                        <path d="M 150 200 L 80 300" fill="none" stroke="#605d52" stroke-width="4" stroke-dasharray="5,5" />
                        
                        <!-- Circuit nodes -->
                        <circle cx="150" cy="200" r="10" fill="#ff4f00" style="cursor: pointer;" onclick="alert('Patna: Capital city, gateway to all circuits, historic Pataliputra.')" />
                        <text x="140" y="180" fill="var(--text-main)" font-size="12" font-weight="bold">Patna</text>
                        
                        <circle cx="300" cy="280" r="10" fill="#ff4f00" style="cursor: pointer;" onclick="alert('Bodh Gaya: Sacred site of Lord Buddha\'s enlightenment.')" />
                        <text x="270" y="305" fill="var(--text-main)" font-size="12" font-weight="bold">Bodh Gaya</text>
                        
                        <circle cx="450" cy="220" r="10" fill="#ff4f00" style="cursor: pointer;" onclick="alert('Nalanda: Ancient world university ruins.')" />
                        <text x="440" y="200" fill="var(--text-main)" font-size="12" font-weight="bold">Nalanda</text>
                        
                        <circle cx="400" cy="240" r="10" fill="#ff4f00" style="cursor: pointer;" onclick="alert('Rajgir: Hot springs, ropeways, and glass skywalk.')" />
                        <text x="390" y="265" fill="var(--text-main)" font-size="12" font-weight="bold">Rajgir</text>

                        <circle cx="80" cy="300" r="10" fill="#605d52" style="cursor: pointer;" onclick="alert('Sasaram: Sher Shah Suri red sandstone mausoleum tomb.')" />
                        <text x="50" y="325" fill="var(--text-main)" font-size="12" font-weight="bold">Sasaram</text>
                    </svg>
                </div>
                <p class="small text-muted mt-3 mb-0">Hover or click on the nodes to inspect destinations and active tourism circuits.</p>
            </div>
        </div>

        <div class="row g-5">
            <!-- Catalog Cards Grid -->
            <div class="col-lg-8" data-aos="fade-up">
                <div class="row g-4">
                    <?php foreach ($destinations as $dest): ?>
                        <div class="col-md-6 d-flex">
                            <div class="custom-card flex-fill">
                                <div class="custom-card-img-wrapper">
                                    <img src="<?= htmlspecialchars($dest['image_url']) ?>" class="custom-card-img" alt="<?= htmlspecialchars($dest['name']) ?>" loading="lazy" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/hero-fallback.jpg';">
                                    <span class="custom-card-badge"><?= htmlspecialchars($dest['category']) ?></span>
                                </div>
                                <div class="custom-card-body">
                                    <div class="d-flex justify-content-between text-muted small mb-2">
                                        <span>📍 <?= htmlspecialchars($dest['location']) ?></span>
                                        <span class="text-warning">★ <?= htmlspecialchars($dest['rating']) ?></span>
                                    </div>
                                    <h3 class="custom-card-title"><?= htmlspecialchars($dest['name']) ?></h3>
                                    <p class="custom-card-desc"><?= htmlspecialchars($dest['description']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                                        <span class="small text-muted"><i class="fa-solid fa-eye me-1"></i> <?= $dest['views_count'] ?> views</span>
                                        <a href="<?= BASE_URL ?>/tourism/<?= $dest['id'] ?>" class="btn btn-primary btn-sm px-3">Details <i class="fa-solid fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Custom Itinerary Planner Sidebar -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card p-4 rounded-4 shadow-lg" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h5 fw-bold mb-1 font-outfit" style="color: var(--text-main);">Itinerary Planner</h3>
                    <p class="small mb-4" style="color: var(--text-secondary);">Generate and save custom multi-day travel guides.</p>
                    
                    <?php if (Auth::check()): ?>
                        <form action="<?= BASE_URL ?>/tourism/itinerary/create" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <div class="mb-3">
                                <label class="small text-muted uppercase">Plan Title</label>
                                <input type="text" name="title" class="form-control" value="My Trip to Bodh Gaya &amp; Rajgir" required>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted uppercase">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Family tour program">
                            </div>

                            <div id="itinerary-days-container">
                                <div class="p-3 rounded-3 mb-3 day-box" style="background-color: var(--bg-soft); border: 1px solid var(--border-color);">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small fw-bold" style="color: var(--primary);">Day 1 Activities</span>
                                    </div>
                                    <textarea name="days[]" class="form-control small" rows="3" placeholder="Sights and transport timings..." required></textarea>
                                </div>
                            </div>

                            <button type="button" id="add-day-btn" class="btn btn-outline btn-sm w-100 mb-3">+ Add Another Day</button>
                            <button type="submit" class="btn btn-primary w-100">Save Itinerary Plan</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4 border border-dashed rounded-3" style="border-color: var(--border-color) !important;">
                            <p class="small text-muted mb-3">Login to save trip itineraries.</p>
                            <a href="<?= BASE_URL ?>/login" class="btn btn-primary btn-sm">Log In Now</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- User review post JS (Enterprise 3.0) -->
<script>
function submitUserReview() {
    const form = document.getElementById('review-submission-form');
    const msg = document.getElementById('review-msg');
    if (!form) return;

    const formData = new FormData(form);

    fetch('<?= BASE_URL ?>/api/reviews', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        msg.style.display = 'block';
        msg.className = `small mt-2 text-${res.success ? 'success' : 'danger'}`;
        msg.textContent = res.message;
        if (res.success) {
            // Append review dynamically
            const feed = document.getElementById('reviews-feed');
            const noRev = document.getElementById('no-reviews-txt');
            if (noRev) noRev.remove();

            const ratingVal = parseInt(form.querySelector('select[name="rating"]').value);
            const commentVal = form.querySelector('textarea[name="comment"]').value;

            const div = document.createElement('div');
            div.className = 'p-3 rounded-3';
            div.style.backgroundColor = 'var(--bg-soft)';
            div.style.border = '1px solid var(--border-color)';
            div.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold small" style="color: var(--text-main);"><?= Session::get('user_name') ?></span>
                    <span class="text-warning small">${'★'.repeat(ratingVal)}</span>
                </div>
                <p class="small mb-0" style="color: var(--text-secondary);">${commentVal}</p>
            `;
            feed.prepend(div);
            form.querySelector('textarea[name="comment"]').value = '';
        }
    })
    .catch(err => {
        console.error(err);
        msg.style.display = 'block';
        msg.className = 'small mt-2 text-danger';
        msg.textContent = 'Could not post review feedback.';
    });
}

// 1. Voice Assistant Read Aloud
function readAloudDestination() {
    const synth = window.speechSynthesis;
    if (!synth) {
        alert('Speech synthesis not supported in this browser.');
        return;
    }
    if (synth.speaking) {
        synth.cancel();
        return;
    }
    const textDesc = document.getElementById('destinationDesc').innerText;
    const utterance = new SpeechSynthesisUtterance(textDesc);
    utterance.lang = 'en-IN';
    synth.speak(utterance);
}

// 2. Voice Search Listener
function startVoiceSearch() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert('Voice Speech recognition is not supported in this browser.');
        return;
    }
    const recognition = new SpeechRecognition();
    recognition.lang = 'en-IN';
    recognition.start();
    
    recognition.onresult = function(event) {
        const text = event.results[0][0].transcript;
        const inputField = document.getElementById('tourismSearch');
        if (inputField) {
            inputField.value = text;
        }
        window.location.href = window.baseUrl + '/search?q=' + encodeURIComponent(text);
    };
}

// 3. AR 360 Panoramic drag logic
document.addEventListener('DOMContentLoaded', () => {
    const pan = document.getElementById('panoramic-preview');
    if (!pan) return;
    
    let isDragging = false;
    let startX;
    let backgroundPos = 0;

    pan.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        pan.style.cursor = 'grabbing';
    });

    window.addEventListener('mouseup', () => {
        isDragging = false;
        if (pan) pan.style.cursor = 'grab';
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const x = e.pageX;
        const walk = (x - startX) * 0.5;
        backgroundPos += walk;
        pan.style.backgroundPositionX = backgroundPos + 'px';
        startX = x;
    });
});
</script>
