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

    <?php if ($view_mode === 'forums'): ?>
        <!-- ==========================================
             1. FORUMS DIRECTORY VIEW
             ========================================== -->
        <div class="text-center mb-5" data-aos="fade-down">
            <span class="badge rounded-pill bg-teal-subtle text-teal px-3 py-2 fs-6 mb-3 text-uppercase tracking-wider border border-teal-subtle">Bihar Vihaan Forums</span>
            <h1 class="display-4 font-outfit fw-extrabold text-transparent bg-clip-text bg-gradient-orange-teal">Travel & Culture Discussion Boards</h1>
            <p class="lead text-muted max-w-xl mx-auto">Ask travel questions, share itineraries, review local guides, and collaborate on artistic folk projects.</p>
        </div>

        <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
            <?php foreach ($forums as $f): ?>
                <div class="col-md-6">
                    <div class="card h-100 bg-glass-dark border border-secondary-subtle rounded-4 p-4 shadow-sm hover-translate-y">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="font-outfit fw-bold text-white mb-0"><?= htmlspecialchars($f['name']) ?></h3>
                            <span class="badge bg-teal bg-opacity-20 text-teal border border-teal-subtle"><?= $f['topics_count'] ?> Threads</span>
                        </div>
                        <p class="text-muted fs-7.5 mb-4 flex-grow-1"><?= htmlspecialchars($f['description']) ?></p>
                        <a href="<?= BASE_URL ?>/community/forum/<?= $f['slug'] ?>" class="btn btn-secondary btn-sm rounded-pill px-4 align-self-start shadow-sm">
                            Open Discussion Board <i class="fa-solid fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($view_mode === 'topics'): ?>
        <!-- ==========================================
             2. TOPICS LISTING VIEW
             ========================================== -->
        <div class="row g-4">
            <!-- Topics List Column -->
            <div class="col-lg-8" data-aos="fade-right">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm mb-4">
                    <a href="<?= BASE_URL ?>/community" class="btn btn-sm btn-outline-light rounded-pill px-3 mb-3">
                        <i class="fa-solid fa-arrow-left me-2"></i> Board Categories
                    </a>
                    <h2 class="font-outfit fw-extrabold text-white mb-2"><?= htmlspecialchars($forum['name']) ?></h2>
                    <p class="text-muted fs-7.5"><?= htmlspecialchars($forum['description']) ?></p>
                </div>

                <div class="card bg-glass border border-secondary-subtle rounded-4 overflow-hidden shadow">
                    <div class="card-header bg-dark bg-opacity-35 px-4 py-3 border-bottom border-secondary-subtle">
                        <h5 class="mb-0 text-white font-outfit fw-bold"><i class="fa-regular fa-comments me-2 text-teal"></i> Active Discussion Threads</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($topics)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2"></i>
                                <p>No active discussion threads have been created in this category. Start one now!</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush bg-transparent">
                                <?php foreach ($topics as $t): ?>
                                    <a href="<?= BASE_URL ?>/community/topic/<?= $t['id'] ?>" class="list-group-item list-group-item-action bg-transparent border-secondary-subtle px-4 py-3.5 hover-bg-glass d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white font-semibold mb-1"><?= htmlspecialchars($t['title']) ?></h6>
                                            <span class="fs-8 text-muted">Started by <strong><?= htmlspecialchars($t['author_name']) ?></strong> &bull; <?= date('d M Y, h:i A', strtotime($t['created_at'])) ?></span>
                                        </div>
                                        <span class="badge bg-secondary rounded-pill px-2.5 py-1.5 fs-8 text-teal"><i class="fa-regular fa-comment me-1"></i> <?= $t['posts_count'] ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Start New Topic Form Column -->
            <div class="col-lg-4" data-aos="fade-left" data-aos-delay="150">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow position-sticky" style="top: 100px;">
                    <h5 class="font-outfit fw-bold text-white mb-3"><i class="fa-solid fa-pen-nib text-primary me-2"></i> Post New Thread</h5>
                    <form action="<?= BASE_URL ?>/community/create-topic" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Session::getCsrfToken() ?>">
                        <input type="hidden" name="forum_id" value="<?= $forum['id'] ?>">

                        <div class="mb-3">
                            <label for="title" class="form-label fs-8 text-muted uppercase">Discussion Subject</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="title" name="title" required placeholder="e.g. Travel safety to Rajgir skywalk">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label fs-8 text-muted uppercase">Message Body</label>
                            <textarea class="form-control bg-dark border-secondary text-white" id="content" name="content" rows="4" required placeholder="Describe your question or travel recommendations in detail..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 shadow-sm font-semibold">
                            Publish Discussion
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <?php elseif ($view_mode === 'detail'): ?>
        <!-- ==========================================
             3. TOPIC POSTS THREAD DETAIL VIEW
             ========================================== -->
        <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm mb-4">
                    <a href="<?= BASE_URL ?>/community/forum/<?= $topic['forum_slug'] ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 mb-3">
                        <i class="fa-solid fa-arrow-left me-2"></i> back to <?= htmlspecialchars($topic['forum_name']) ?>
                    </a>
                    <h3 class="font-outfit fw-extrabold text-white mb-0"><?= htmlspecialchars($topic['title']) ?></h3>
                </div>

                <!-- Posts List -->
                <div class="d-flex flex-column gap-3 mb-4">
                    <?php foreach ($posts as $post): ?>
                        <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2.5 border-bottom border-secondary-subtle">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-teal fs-7.5"><?= htmlspecialchars($post['author_name']) ?></span>
                                    <span class="badge bg-secondary bg-opacity-50 text-white-50 fs-8 uppercase font-semibold"><?= str_replace('_', ' ', $post['author_role']) ?></span>
                                </div>
                                <span class="fs-8 text-muted"><?= date('d M Y, h:i A', strtotime($post['created_at'])) ?></span>
                            </div>
                            <p class="mb-0 text-white-85 fs-7.5 leading-relaxed"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Reply Form -->
                <div class="p-4 rounded-4 bg-glass border border-teal shadow-sm">
                    <h5 class="font-outfit fw-bold text-white mb-3">Post Reply Comment</h5>
                    <form action="<?= BASE_URL ?>/community/reply-topic" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Session::getCsrfToken() ?>">
                        <input type="hidden" name="topic_id" value="<?= $topic['id'] ?>">

                        <div class="mb-3">
                            <textarea class="form-control bg-dark border-secondary text-white" id="content" name="content" rows="4" required placeholder="Type your comment reply here..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2.5">
                            Post Comment Reply
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
