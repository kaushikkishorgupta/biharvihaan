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

    <?php if (!$artist): ?>
        <!-- ==========================================
             1. CREATOR APPLICATION REGISTRATION
             ========================================== -->
        <div class="row py-5" data-aos="fade-up">
            <div class="col-lg-6 offset-lg-3">
                <div class="p-5 rounded-4 bg-glass border border-secondary-subtle shadow-lg text-center">
                    <div class="display-3 text-primary mb-3">
                        <i class="fa-solid fa-feather-pointed"></i>
                    </div>
                    <h3 class="font-outfit fw-bold text-white mb-2">Apply for Creator Verification</h3>
                    <p class="text-muted mb-4">Earn verification badges, publish news reports, folk videos, blogs, and unlock automated revenue split commissions based on view counts.</p>
                    
                    <form action="<?= BASE_URL ?>/creators/apply" method="POST" class="text-start">
                        <input type="hidden" name="csrf_token" value="<?= Session::getCsrfToken() ?>">

                        <div class="mb-3">
                            <label for="stage_name" class="form-label fs-8 text-muted uppercase">Stage Name / Pen Name</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white py-2" id="stage_name" name="stage_name" required placeholder="e.g. Kaushik Writing Studio">
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fs-8 text-muted uppercase">Creator Category</label>
                            <select class="form-select bg-dark border-secondary text-white" id="category" name="category">
                                <option value="journalist">Journalist / Press Reporter</option>
                                <option value="folk_musician">Folk Musician / Singer</option>
                                <option value="photographer">Travel Photographer</option>
                                <option value="writer">Content Writer / Blogger</option>
                                <option value="other">Other Cultural Creator</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="portfolio_url" class="form-label fs-8 text-muted uppercase">Portfolio / Channel Link</label>
                            <input type="url" class="form-control bg-dark border-secondary text-white py-2" id="portfolio_url" name="portfolio_url" placeholder="e.g. https://youtube.com/my-channel">
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label fs-8 text-muted uppercase">Professional Bio / Experience</label>
                            <textarea class="form-control bg-dark border-secondary text-white" id="bio" name="bio" rows="3" required placeholder="Briefly describe your writing, news reporting, or folk singing experience..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 shadow-sm mt-3">
                            Submit Application Verification
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- ==========================================
             2. CREATOR STUDIO DASHBOARD
             ========================================== -->
        <div class="text-center mb-5" data-aos="fade-down">
            <span class="badge rounded-pill bg-teal-subtle text-teal px-3 py-2 fs-6 mb-3 text-uppercase tracking-wider border border-teal-subtle">Creator Studio</span>
            <h1 class="display-4 font-outfit fw-extrabold text-transparent bg-clip-text bg-gradient-orange-teal">Monetization & Press Newsroom</h1>
            <p class="lead text-muted max-w-xl mx-auto">Track article read metrics, configure revenue payout splits, and publish Bihar-centric updates supported by AI editors.</p>
        </div>

        <!-- Verification Indicator Alert -->
        <?php if ($artist['verification_status'] === 'pending'): ?>
            <div class="alert alert-warning bg-warning-subtle border-warning text-warning-emphasis rounded-3 px-4 py-3 mb-5" data-aos="fade-up">
                <i class="fa-solid fa-circle-notch fa-spin me-2"></i> <strong>Verification Pending:</strong> Your creator application is currently under review by our editors. You can draft and publish articles in preview mode.
            </div>
        <?php else: ?>
            <div class="alert alert-success bg-success-subtle border-success text-success-emphasis rounded-3 px-4 py-3 mb-5" data-aos="fade-up">
                <i class="fa-solid fa-certificate me-2"></i> <strong>Verified Creator Badge Active:</strong> You are an official verified Bihar Vihaan content publisher! Monetization split rates of 70% are actively tracked.
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Monetization Stats Widget -->
            <div class="col-lg-4" data-aos="fade-right">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow mb-4">
                    <h5 class="font-outfit fw-bold text-white mb-4 pb-2 border-bottom border-secondary-subtle">Monetization Ledger</h5>
                    
                    <div class="d-flex justify-content-between py-2.5 text-muted fs-7.5">
                        <span>Verified View Count:</span>
                        <strong class="text-white"><?= number_format($payoutSummary['total_views']) ?> Views</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2.5 text-muted fs-7.5">
                        <span>Monetization Rate:</span>
                        <strong class="text-white">₹0.15 / view</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2.5 text-muted fs-7.5">
                        <span>Gross Revenue:</span>
                        <strong class="text-white">₹<?= number_format($payoutSummary['total_earnings'], 2) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between py-2.5 text-muted fs-7.5 border-top border-secondary-subtle mt-2 pt-2.5">
                        <span>Platform Commission (30%):</span>
                        <strong class="text-danger">₹<?= number_format($payoutSummary['platform_split'], 2) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between py-3 fs-5 fw-extrabold text-teal border-top border-secondary-subtle mt-2 pt-3">
                        <span>Creator Net Payout (70%):</span>
                        <span>₹<?= number_format($payoutSummary['creator_split'], 2) ?></span>
                    </div>

                    <button class="btn btn-outline-warning w-100 rounded-pill py-2 shadow-sm font-semibold mt-3" disabled>
                        Request Payout Withdrawal (PATNA BANK)
                    </button>
                </div>

                <!-- Verified badge profile summary -->
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow">
                    <h5 class="font-outfit fw-bold text-white mb-3">Publisher Profile</h5>
                    <div class="fs-7.5">
                        <p class="mb-1 text-teal fw-bold"><?= htmlspecialchars($artist['stage_name']) ?></p>
                        <p class="text-muted mb-3 italic">Category: <?= str_replace('_', ' ', $artist['category']) ?></p>
                        <p class="text-muted leading-relaxed mb-0"><?= htmlspecialchars($artist['bio']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Write & Publish Article with AI draft generator -->
            <div class="col-lg-8" data-aos="fade-left" data-aos-delay="150">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm mb-4">
                    <h4 class="font-outfit fw-bold text-white mb-3"><i class="fa-solid fa-keyboard text-teal me-2"></i> Publish News & Video Blogs</h4>
                    
                    <!-- AI Writing Assistant Widget -->
                    <div class="bg-glass border border-teal p-3.5 rounded-3 mb-4">
                        <h6 class="text-teal mb-2"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> AI Article Writer Draft Generator</h6>
                        <div class="input-group">
                            <input type="text" id="aiTopic" class="form-control form-control-sm bg-dark border-secondary text-white" placeholder="Enter topic: e.g. Spiritual Tourism circuits in Bihar">
                            <button id="btnGenerateAiDraft" class="btn btn-primary btn-sm px-3 shadow" type="button">
                                Generate Draft
                            </button>
                        </div>
                        <div id="aiDraftStatus" class="small text-muted mt-2 d-none">
                            <i class="fa-solid fa-circle-notch fa-spin text-teal me-1"></i> AI Writer composing full draft...
                        </div>
                    </div>

                    <form action="<?= BASE_URL ?>/creators/publish" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Session::getCsrfToken() ?>">

                        <div class="mb-3">
                            <label for="title" class="form-label fs-7.5 text-muted uppercase">Article Title</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white py-2" id="title" name="title" required placeholder="e.g. Sonepur Mela Cultural Stage expansion details">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label fs-7.5 text-muted uppercase">News Category</label>
                                <select class="form-select bg-dark border-secondary text-white" id="category" name="category">
                                    <option value="Politics">Politics</option>
                                    <option value="Tourism" selected>Tourism</option>
                                    <option value="Education">Education</option>
                                    <option value="Startup">Startup</option>
                                    <option value="Culture">Culture</option>
                                    <option value="Sports">Sports</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="image_url" class="form-label fs-7.5 text-muted uppercase">Feature Scenery Image URL</label>
                                <input type="text" class="form-control bg-dark border-secondary text-white py-2" id="image_url" name="image_url" placeholder="Paste scenic image link">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fs-7.5 text-muted uppercase">Article Content (Markdown / HTML Supported)</label>
                            <textarea class="form-control bg-dark border-secondary text-white" id="content" name="content" rows="10" required placeholder="Write news contents or paste details here..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5 shadow font-semibold">
                            Publish Article to Portal <i class="fa-solid fa-paper-plane ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('btnGenerateAiDraft').addEventListener('click', function() {
                const topic = document.getElementById('aiTopic').value.trim();
                const statusDiv = document.getElementById('aiDraftStatus');
                const btn = this;
                
                if (topic === '') {
                    alert('Please enter a topic first.');
                    return;
                }

                btn.disabled = true;
                statusDiv.classList.remove('d-none');

                const fd = new FormData();
                fd.append('topic', topic);
                fd.append('csrf_token', '<?= Session::getCsrfToken() ?>');

                fetch(window.baseUrl + '/creators/ai-draft', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(data => {
                    btn.disabled = false;
                    statusDiv.classList.add('d-none');
                    if (data.success) {
                        document.getElementById('content').value = data.draft;
                    } else {
                        alert('AI Draft Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    btn.disabled = false;
                    statusDiv.classList.add('d-none');
                    alert('AI content generator failed.');
                });
            });
        </script>
    <?php endif; ?>
</div>
