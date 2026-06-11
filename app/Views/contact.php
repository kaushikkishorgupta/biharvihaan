<!-- Contact Us Page - Bihar Vihaan Enterprise -->
<section class="py-5" style="margin-top: 50px; background-color: var(--bg-soft); border-bottom: 1px solid var(--border-color);">
    <div class="container py-5 text-center" data-aos="fade-up">
        <span class="badge bg-primary text-white border border-primary px-3 py-2 rounded-pill mb-3 small fw-bold text-uppercase tracking-wider">Get in Touch</span>
        <h1 class="display-3 fw-bold font-outfit" style="color: var(--text-main);">Contact Us</h1>
        <p class="body-text-custom max-w-lg mx-auto" style="max-width: 650px; margin: 0 auto;">Reach out to explore advertisements, listings, or share cultural updates.</p>
    </div>
</section>

<section class="py-5" style="background-color: var(--bg-main);">
    <div class="container py-4">
        
        <!-- Alerts Container -->
        <?php use App\Core\Session; ?>
        <div class="mb-4">
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

        <div class="row g-5">
            <!-- Left Side: Secure Contact Form -->
            <div class="col-lg-7" data-aos="fade-right">
                <div class="card p-4 p-md-5 shadow-sm rounded-4" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                    <h2 class="h4 fw-bold font-outfit mb-4" style="color: var(--text-main);">Send Us a Message</h2>
                    
                    <form action="<?= BASE_URL ?>/contact" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Session::generateCsrfToken() ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Kaushal Gupta" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. client@gmail.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Business Inquiry / Art Collaboration" value="<?= isset($_GET['service']) ? 'Inquiry: ' . ucwords(str_replace('-', ' ', $_GET['service'])) : '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Your Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Write details here..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Send Message <i class="fa-solid fa-paper-plane ms-1"></i></button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Quote & Info -->
            <div class="col-lg-5" data-aos="fade-left">
                <div class="d-flex flex-column gap-4">
                    <!-- Quote Card -->
                    <div class="card p-4 rounded-4 shadow-sm" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-left: 5px solid var(--primary);">
                        <blockquote class="mb-0" style="font-style: italic; line-height: 1.7; color: var(--text-secondary); font-size: 1.05rem;">
                            "To unfold the unrevealed sagas of Bihar’s legacy. We bring forward the hidden heritage, diverse culture, literature, folk music, tourism, theatre, cuisine, festivals, and traditions that make Bihar unique."
                        </blockquote>
                    </div>

                    <!-- Contact Details Card -->
                    <div class="card p-4 rounded-4 shadow-sm" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                        <h3 class="h5 fw-bold font-outfit mb-3" style="color: var(--text-main);">Contact Information</h3>
                        
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="fs-4 text-primary"><i class="fa-solid fa-phone"></i></div>
                                <div>
                                    <div class="small text-muted">Phone Number</div>
                                    <div class="small fw-bold" style="color: var(--text-main);">+91 94300 41925</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="fs-4 text-primary"><i class="fa-solid fa-envelope"></i></div>
                                <div>
                                    <div class="small text-muted">Email Address</div>
                                    <div class="small fw-bold"><a href="mailto:hello@biharvihaan.com" style="color: var(--text-main); text-decoration: none;">hello@biharvihaan.com</a></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3">
                                <div class="fs-4 text-primary mt-1"><i class="fa-solid fa-location-dot"></i></div>
                                <div>
                                    <div class="small text-muted">Our Office</div>
                                    <div class="small fw-bold" style="color: var(--text-main); line-height: 1.6;">
                                        Phulwari Sharif – Khagaul Road,<br>
                                        Sabajpura, Maulana Azad Nagar,<br>
                                        Khagaul, Patna,<br>
                                        Bihar – 801505
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
