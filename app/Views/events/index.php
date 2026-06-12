<!-- Events Page - Bihar Vihaan Enterprise -->
<section class="py-5" style="margin-top: 50px; background-color: var(--bg-soft); border-bottom: 1px solid var(--border-color);">
    <div class="container py-5 text-center" data-aos="fade-up">
        <span class="badge bg-primary text-white border border-primary px-3 py-2 rounded-pill mb-3 small fw-bold text-uppercase tracking-wider">Celebrate Culture</span>
        <h1 class="display-3 fw-bold font-outfit" style="color: var(--text-main);">Upcoming Holiday Festivals</h1>
        <p class="body-text-custom max-w-lg mx-auto" style="max-width: 650px; margin: 0 auto;">Experience the grandeur, spirituality, and heritage of Bihar's most prominent festivals and events.</p>
    </div>
</section>

<section class="py-5" style="background-color: var(--bg-main);">
    <div class="container py-4">
        
        <!-- Search and Filter Bar -->
        <div class="card p-4 rounded-4 shadow-sm mb-5 border-0" style="background-color: var(--bg-card);">
            <form action="<?= BASE_URL ?>/events" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search festivals..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Filter</button>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        <div class="row g-4">
            <?php if(empty($events)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fa-regular fa-calendar-xmark display-1 text-muted mb-3 opacity-50"></i>
                    <h3 class="text-muted">No festivals found</h3>
                    <p>Try adjusting your search filters.</p>
                    <a href="<?= BASE_URL ?>/events" class="btn btn-outline mt-2">Clear Filters</a>
                </div>
            <?php else: ?>
                <?php foreach($events as $event): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up">
                        <div class="card h-100 rounded-4 shadow-sm border-0 event-card" style="background-color: var(--bg-card); overflow: hidden;">
                            <div class="position-relative">
                                <img src="<?= htmlspecialchars($event['image'] ?? BASE_URL.'/assets/images/fallback.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($event['title']) ?>" style="height: 220px; object-fit: cover;">
                                <span class="badge bg-primary position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill"><?= htmlspecialchars($event['category']) ?></span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h4 class="h5 fw-bold font-outfit mb-3" style="color: var(--text-main);"><?= htmlspecialchars($event['title']) ?></h4>
                                <div class="d-flex align-items-center mb-2 text-muted small">
                                    <i class="fa-regular fa-calendar text-primary me-2"></i>
                                    <span><?= date('M d, Y', strtotime($event['start_date'])) ?> - <?= date('M d, Y', strtotime($event['end_date'])) ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-3 text-muted small">
                                    <i class="fa-solid fa-location-dot text-primary me-2"></i>
                                    <span><?= htmlspecialchars($event['location']) ?></span>
                                </div>
                                <p class="card-text text-muted mb-4 flex-grow-1" style="font-size: 0.95rem;">
                                    <?= htmlspecialchars(substr($event['description'], 0, 100)) ?>...
                                </p>
                                <button type="button" class="btn btn-outline w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#eventModal<?= $event['id'] ?>">View Details</button>
                            </div>
                        </div>
                    </div>

                    <!-- Event Detail Modal -->
                    <div class="modal fade" id="eventModal<?= $event['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg" style="background-color: var(--bg-card);">
                                <div class="modal-header border-0 pb-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 pt-0">
                                    <img src="<?= htmlspecialchars($event['image'] ?? BASE_URL.'/assets/images/fallback.jpg') ?>" class="w-100 rounded-3 mb-4" alt="<?= htmlspecialchars($event['title']) ?>" style="height: 300px; object-fit: cover;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h2 class="h3 fw-bold font-outfit" style="color: var(--text-main);"><?= htmlspecialchars($event['title']) ?></h2>
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><?= htmlspecialchars($event['category']) ?></span>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center bg-surface p-3 rounded-3">
                                                <i class="fa-regular fa-calendar fs-4 text-primary me-3"></i>
                                                <div>
                                                    <div class="small text-muted">Date</div>
                                                    <div class="fw-bold" style="color: var(--text-main);"><?= date('M d, Y', strtotime($event['start_date'])) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center bg-surface p-3 rounded-3">
                                                <i class="fa-solid fa-location-dot fs-4 text-primary me-3"></i>
                                                <div>
                                                    <div class="small text-muted">Location</div>
                                                    <div class="fw-bold" style="color: var(--text-main);"><?= htmlspecialchars($event['location']) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold font-outfit mb-3" style="color: var(--text-main);">About Festival</h5>
                                    <p class="text-muted" style="line-height: 1.8;"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button type="button" class="btn btn-outline px-4" data-bs-dismiss="modal">Close</button>
                                    <a href="<?= BASE_URL ?>/trip-planner" class="btn btn-primary px-4 fw-bold">Plan Trip</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .event-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
</style>
