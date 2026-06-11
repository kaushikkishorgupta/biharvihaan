<?php
use App\Core\Session;
?>
<div class="container py-5 mt-5">
    <!-- Flash Messages -->
    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success bg-success-subtle border-success text-success-emphasis alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?= htmlspecialchars(Session::getFlash('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger bg-danger-subtle border-danger text-danger-emphasis alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?= htmlspecialchars(Session::getFlash('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($view_mode === 'catalog'): ?>
        <!-- ==========================================
             1. CATALOG VIEW
             ========================================== -->
        <div class="text-center mb-5" data-aos="fade-down">
            <span class="badge rounded-pill bg-teal-subtle text-teal px-3 py-2 fs-6 mb-3 text-uppercase tracking-wider border border-teal-subtle">Bihar Learning Hub</span>
            <h1 class="display-4 font-outfit fw-extrabold text-transparent bg-clip-text bg-gradient-orange-teal">Tourism Training & Skill Development</h1>
            <p class="lead text-muted max-w-xl mx-auto">Get certified in cultural heritage, hospitality operations, and local arts, with certificates stamped securely on the blockchain ledger.</p>
        </div>

        <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
            <?php foreach ($courses as $c): ?>
                <?php 
                $progress = $progressMap[$c['id']]['progress'] ?? 0;
                $completed = $progressMap[$c['id']]['completed'] ?? 0;
                $certHash = $progressMap[$c['id']]['certificate_hash'] ?? null;
                ?>
                <div class="col-md-6">
                    <div class="card h-100 bg-glass-dark border border-secondary-subtle rounded-4 overflow-hidden shadow-sm hover-translate-y">
                        <div class="row g-0 h-100">
                            <div class="col-sm-5 position-relative overflow-hidden" style="min-height: 200px;">
                                <img src="<?= htmlspecialchars($c['image_url']) ?>" class="w-100 h-100 object-fit-cover position-absolute top-0 left-0" alt="">
                            </div>
                            <div class="col-sm-7 d-flex flex-column p-4">
                                <span class="fs-8 text-muted uppercase tracking-wider mb-2">Instructor: <?= htmlspecialchars($c['instructor']) ?></span>
                                <h4 class="font-outfit fw-bold text-white mb-3"><?= htmlspecialchars($c['title']) ?></h4>
                                <p class="text-muted fs-7.5 mb-4 flex-grow-1"><?= htmlspecialchars($c['description']) ?></p>
                                
                                <div class="mt-auto">
                                    <?php if ($progress > 0): ?>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between fs-8 text-muted mb-1">
                                                <span>Course Progress</span>
                                                <span class="text-teal font-semibold"><?= $progress ?>%</span>
                                            </div>
                                            <div class="progress bg-dark bg-opacity-50" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-teal" style="width: <?= $progress ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL ?>/learning/course/<?= $c['id'] ?>" class="btn btn-primary btn-sm rounded-pill px-4 py-2 shadow-sm">
                                            <?= ($progress > 0) ? 'Continue Learning' : 'Start Training' ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                        <?php if ($completed && $certHash): ?>
                                            <button onclick="viewCertificate('<?= htmlspecialchars($c['title']) ?>', '<?= Session::get('user_name') ?>', '<?= $certHash ?>')" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                                🎓 Certificate
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($view_mode === 'lessons'): ?>
        <!-- ==========================================
             2. LESSON PLAYER & QUIZ VIEW
             ========================================== -->
        <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-right">
                <!-- Course Video Header -->
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm mb-4">
                    <a href="<?= BASE_URL ?>/learning" class="btn btn-sm btn-outline-light rounded-pill px-3 mb-3">
                        <i class="fa-solid fa-arrow-left me-2"></i> Courses Catalog
                    </a>
                    <h2 class="font-outfit fw-extrabold text-white mb-2"><?= htmlspecialchars($course['title']) ?></h2>
                    <p class="text-muted fs-7.5">Learn direct from **<?= htmlspecialchars($course['instructor']) ?>**</p>
                </div>

                <!-- Video player list -->
                <?php if (empty($lessons)): ?>
                    <div class="p-5 rounded-4 bg-glass border border-secondary-subtle text-center text-muted">
                        <i class="fa-solid fa-clapperboard fs-1 mb-2"></i>
                        <p>No video lessons published yet for this skill program.</p>
                    </div>
                <?php else: ?>
                    <div class="p-3 rounded-4 bg-glass border border-secondary-subtle shadow-sm mb-4">
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                            <iframe src="<?= htmlspecialchars($lessons[0]['video_url']) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <h5 class="font-outfit fw-bold text-white mt-3 mb-1"><i class="fa-solid fa-circle-play text-primary me-2"></i> Playing: <?= htmlspecialchars($lessons[0]['title']) ?></h5>
                        <span class="fs-8 text-muted">Duration: <?= $lessons[0]['duration_mins'] ?> Mins</span>
                    </div>
                <?php endif; ?>

                <!-- Quiz panel (only if not already completed) -->
                <?php if (!$enrollment['completed'] && !empty($quizzes)): ?>
                    <div class="p-4 rounded-4 bg-glass border border-teal shadow-sm" data-aos="fade-up">
                        <h4 class="font-outfit fw-bold text-white mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-circle-question text-teal me-2 fs-4"></i> Certification Assessment Quiz
                        </h4>
                        <p class="text-muted fs-7.5 mb-4">Complete all questions correctly (100% score) to mint your verified completion credential onto the decentralized blockchain ledger.</p>

                        <form action="<?= BASE_URL ?>/learning/quiz/submit" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= Session::getCsrfToken() ?>">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">

                            <?php $qidx = 1; foreach ($quizzes as $q): ?>
                                <div class="p-3 bg-dark bg-opacity-40 border border-secondary-subtle rounded-3 mb-3">
                                    <h6 class="text-white mb-3">Q<?= $qidx++ ?>: <?= htmlspecialchars($q['question']) ?></h6>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="answers[<?= $q['id'] ?>]" id="optA_<?= $q['id'] ?>" value="A" required>
                                        <label class="form-check-label text-white-85 fs-7.5" for="optA_<?= $q['id'] ?>">A. <?= htmlspecialchars($q['option_a']) ?></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="answers[<?= $q['id'] ?>]" id="optB_<?= $q['id'] ?>" value="B">
                                        <label class="form-check-label text-white-85 fs-7.5" for="optB_<?= $q['id'] ?>">B. <?= htmlspecialchars($q['option_b']) ?></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="answers[<?= $q['id'] ?>]" id="optC_<?= $q['id'] ?>" value="C">
                                        <label class="form-check-label text-white-85 fs-7.5" for="optC_<?= $q['id'] ?>">C. <?= htmlspecialchars($q['option_c']) ?></label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="answers[<?= $q['id'] ?>]" id="optD_<?= $q['id'] ?>" value="D">
                                        <label class="form-check-label text-white-85 fs-7.5" for="optD_<?= $q['id'] ?>">D. <?= htmlspecialchars($q['option_d']) ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-secondary w-100 py-2.5 rounded-pill shadow fw-bold">
                                Submit Quiz Answers & Calculate Grade
                            </button>
                        </form>
                    </div>
                <?php elseif ($enrollment['completed'] && $enrollment['certificate_hash']): ?>
                    <!-- Completed block status -->
                    <div class="p-4 rounded-4 bg-glass border border-success text-center shadow" data-aos="fade-up">
                        <div class="display-4 text-success mb-2"><i class="fa-solid fa-graduation-cap"></i></div>
                        <h4 class="font-outfit fw-bold text-white mb-1">Course Completed Successfully!</h4>
                        <p class="text-muted fs-7.5 mb-4">Your digital credential has been validated on-chain.</p>
                        
                        <div class="bg-dark bg-opacity-40 border border-secondary-subtle p-3 rounded-3 mb-4 text-start font-monospace fs-8 text-break">
                            <strong>BLOCKCHAIN TX CERTIFICATE HASH:</strong><br>
                            <span class="text-teal"><?= $enrollment['certificate_hash'] ?></span>
                        </div>

                        <button onclick="viewCertificate('<?= htmlspecialchars($course['title']) ?>', '<?= Session::get('user_name') ?>', '<?= $enrollment['certificate_hash'] ?>')" class="btn btn-warning rounded-pill px-4 shadow">
                            Show Verified Digital Certificate
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Lessons playlist sidebar -->
            <div class="col-lg-4" data-aos="fade-left" data-aos-delay="150">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm position-sticky" style="top: 100px;">
                    <h5 class="font-outfit fw-bold text-white mb-3">Course Curriculum</h5>
                    <div class="list-group list-group-flush bg-transparent">
                        <?php $idx = 1; foreach ($lessons as $lesson): ?>
                            <div class="list-group-item bg-transparent border-secondary-subtle px-0 py-3 d-flex gap-2 align-items-center">
                                <span class="badge bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 25px; height: 25px;"><?= $idx++ ?></span>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-white fs-7.5 font-semibold"><?= htmlspecialchars($lesson['title']) ?></h6>
                                    <span class="fs-8 text-muted"><?= $lesson['duration_mins'] ?> Mins</span>
                                </div>
                                <i class="fa-regular fa-circle-play text-primary"></i>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Blockchain Certificate Modal -->
<div id="certificateModal" class="modal-overlay">
    <div class="premium-modal text-center text-dark bg-white rounded-4 p-5 shadow-2xl position-relative" style="max-width: 650px;">
        <button id="closeCertModal" class="position-absolute top-3 right-3 btn-close border-0" style="font-size: 1.2rem;"></button>
        
        <!-- Border Layout -->
        <div class="border-4 border-double border-warning p-4 rounded-3">
            <h4 class="text-warning font-outfit fw-bold tracking-widest uppercase mb-1">BIHAR VIHAAN ENTERPRISE</h4>
            <span class="fs-8 text-muted block mb-4">DECENTRALIZED SKILL LEDGER CERTIFICATE</span>
            
            <p class="text-muted italic fs-7 mb-2">This is to certify that</p>
            <h2 class="font-outfit fw-bold text-dark mb-3" id="certStudentName">Kaushik Verma</h2>
            <p class="text-muted fs-7 mb-3">has successfully completed all testing parameters for the program</p>
            
            <h4 class="font-outfit fw-bold text-teal mb-4" id="certCourseTitle">Madhubani Painting Masterclass</h4>
            
            <div class="d-flex justify-content-between align-items-end mt-5">
                <div class="text-start fs-8">
                    <strong>Date Verified:</strong> <?= date('d M Y') ?><br>
                    <strong>Platform Fee:</strong> Paid via UPI
                </div>
                <div class="text-end">
                    <span style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 1.6rem; color: #555;">Super Admin</span>
                    <hr class="my-1 border-dark" style="width: 120px;">
                    <span class="fs-8 text-muted">Authorized Signatory</span>
                </div>
            </div>

            <!-- Ledger verify hash footer -->
            <div class="mt-4 pt-3 border-top border-secondary text-start fs-8 font-monospace text-muted text-break">
                <strong>BLOCKCHAIN HASH VALIDATION ID:</strong><br>
                <span class="text-success" id="certHashText">sig_hash_00000_verified</span>
            </div>
        </div>
    </div>
</div>

<script>
    function viewCertificate(courseTitle, studentName, hash) {
        document.getElementById('certStudentName').innerText = studentName;
        document.getElementById('certCourseTitle').innerText = courseTitle;
        document.getElementById('certHashText').innerText = hash;
        
        const modal = document.getElementById('certificateModal');
        modal.classList.add('active');
    }

    document.getElementById('closeCertModal').addEventListener('click', () => {
        document.getElementById('certificateModal').classList.remove('active');
    });
</script>
