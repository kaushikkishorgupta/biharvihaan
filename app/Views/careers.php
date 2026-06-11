<?php
use App\Core\Session;
$viewMode = $view_mode ?? 'list';
$csrfToken = Session::generateCsrfToken();
?>

<div class="container py-5 mt-5">
    
    <!-- 1. DETAILED VIEW -->
    <?php if ($viewMode === 'detail'): ?>
        <div class="row g-5" data-aos="fade-up">
            <div class="col-lg-8">
                <div class="card bg-card border-secondary-subtle p-4 p-md-5 rounded-4 shadow-lg">
                    <span class="badge bg-success px-3 py-2 rounded-pill mb-3 align-self-start small"><?= htmlspecialchars($internship['department']) ?></span>
                    <h1 class="display-6 fw-bold text-white mb-3 font-outfit"><?= htmlspecialchars($internship['title']) ?></h1>
                    
                    <div class="d-flex gap-4 text-muted small mb-4 border-bottom border-secondary-subtle pb-3">
                        <span>⏳ Duration: <?= htmlspecialchars($internship['duration']) ?></span>
                        <span>💰 Stipend: <?= htmlspecialchars($internship['stipend']) ?></span>
                        <span>📍 Location: Patna, Bihar</span>
                    </div>

                    <div class="mb-4">
                        <h3 class="h5 text-white fw-bold mb-2 font-outfit">Position Overview</h3>
                        <p class="text-muted lh-lg small"><?= nl2br(htmlspecialchars($internship['description'])) ?></p>
                    </div>

                    <div>
                        <h3 class="h5 text-white fw-bold mb-2 font-outfit">Applicant Requirements</h3>
                        <p class="text-muted lh-lg small"><?= nl2br(htmlspecialchars($internship['requirements'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Application Form Sidebar -->
            <div class="col-lg-4">
                <div class="card bg-card border-secondary-subtle rounded-4 p-4 shadow-lg sticky-top" style="top: 100px;">
                    <h3 class="h5 text-white fw-bold mb-1 font-outfit">Apply Online</h3>
                    <p class="text-muted small mb-4">Submit PDF CV files to register inside applicant tracking.</p>
                    
                    <form action="<?= BASE_URL ?>/careers/apply" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="internship_id" value="<?= $internship['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                            <input type="text" name="name" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Rohan Verma" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="e.g. rohan@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Phone Number</label>
                            <input type="text" name="phone" class="form-control bg-dark border-secondary text-white" placeholder="e.g. +91 99999 88888" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Upload CV (PDF only)</label>
                            <input type="file" name="resume" class="form-control bg-dark border-secondary text-white" accept=".pdf" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Statement / Cover Letter</label>
                            <textarea name="cover_letter" class="form-control bg-dark border-secondary text-white small" rows="4" placeholder="Briefly describe why you are a good fit..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-secondary w-100 fw-bold">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>

    <!-- 2. VACANCIES LISTING -->
    <?php else: ?>
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="display-5 fw-bold text-white font-outfit">Internship &amp; Career Portal</h1>
            <p class="text-muted">Explore careers with the Bihar Vihaan program, archives, and regional development boards.</p>
        </div>

        <div class="row g-4" data-aos="fade-up">
            <?php foreach ($internships as $job): ?>
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow flex-fill d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-success px-3 py-1 rounded-pill small"><?= htmlspecialchars($job['department']) ?></span>
                                <span class="small text-muted">⏳ <?= htmlspecialchars($job['duration']) ?></span>
                            </div>
                            <h3 class="h5 fw-bold text-white mb-2 font-outfit"><?= htmlspecialchars($job['title']) ?></h3>
                            <p class="small text-muted line-clamp-3 mb-4"><?= htmlspecialchars($job['description']) ?></p>
                        </div>
                        <div class="pt-3 border-top border-secondary-subtle d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary small"><?= htmlspecialchars($job['stipend']) ?></span>
                            <a href="<?= BASE_URL ?>/careers/<?= $job['id'] ?>" class="btn btn-outline-light btn-sm px-3 rounded-pill">Apply Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
