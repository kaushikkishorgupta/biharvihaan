<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-hashtag text-primary me-2"></i> YouTube & Reels Manager</h4>
            <p class="text-secondary small mb-0">Manage embedded video and reel feeds shown on the main tourism site. Feature videos to put them in the homepage hero carousel.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#feedModal" onclick="clearFeedForm()"><i class="fa-solid fa-plus me-1"></i> Add Video / Reel</button>
    </div>

    <!-- Platform Tabs -->
    <ul class="nav nav-tabs border-secondary mb-4" id="platformTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-light bg-transparent border-0 border-bottom border-primary pb-2 px-3 fw-bold" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-panel" type="button">All Feeds</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-secondary bg-transparent border-0 pb-2 px-3" id="youtube-tab" data-bs-toggle="tab" data-bs-target="#youtube-panel" type="button"><i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Videos</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-secondary bg-transparent border-0 pb-2 px-3" id="instagram-tab" data-bs-toggle="tab" data-bs-target="#instagram-panel" type="button"><i class="fa-brands fa-instagram text-danger me-1"></i> Instagram Reels</button>
        </li>
    </ul>

    <div class="tab-content" id="platformTabContent">
        <!-- ALL FEEDS TAB -->
        <div class="tab-pane fade show active" id="all-panel">
            <div class="row g-4">
                <?php if(empty($feeds)): ?>
                    <div class="col-12 text-center py-5 text-secondary">
                        <i class="fa-solid fa-video-slash fa-3x mb-3"></i>
                        <h6>No embedded feeds found. Add one above!</h6>
                    </div>
                <?php else: foreach($feeds as $f): 
                    $platform = htmlspecialchars($f['platform']);
                    $videoId = htmlspecialchars($f['video_id']);
                ?>
                    <div class="col-xl-4 col-md-6 feed-item" data-platform="<?= $platform ?>">
                        <div class="card bg-dark bg-opacity-25 border border-secondary rounded-3 overflow-hidden h-100 shadow-sm">
                            <!-- Video Embed Header -->
                            <div class="ratio ratio-16x9 bg-black border-bottom border-secondary">
                                <?php if($platform === 'youtube'): ?>
                                    <iframe src="https://www.youtube.com/embed/<?= $videoId ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                <?php else: ?>
                                    <!-- Instagram Embed Mockup -->
                                    <div class="d-flex flex-column align-items-center justify-content-center text-light bg-dark h-100">
                                        <i class="fa-brands fa-instagram fa-3x text-danger mb-2"></i>
                                        <span class="small font-monospace">INSTAGRAM REEL</span>
                                        <a href="https://instagram.com/reel/<?= $videoId ?>" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3 mt-2" style="font-size:0.75rem;"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Reel</a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Info -->
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-secondary text-light"><?= htmlspecialchars($f['category']) ?></span>
                                    <?php if($f['is_featured']): ?>
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-star"></i> Featured</span>
                                    <?php endif; ?>
                                </div>
                                <h6 class="text-white fw-bold text-truncate mb-3"><?= htmlspecialchars($f['title'] ?: 'Untitled Clip') ?></h6>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-secondary text-uppercase" style="font-size:0.75rem;">
                                        <?php if($platform === 'youtube'): ?>
                                            <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube
                                        <?php else: ?>
                                            <i class="fa-brands fa-instagram text-danger me-1"></i> Instagram
                                        <?php endif; ?>
                                    </small>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-light" onclick="editFeed(<?= htmlspecialchars(json_encode($f)) ?>)"><i class="fa-solid fa-edit"></i></button>
                                        <form action="<?= BASE_URL ?>/admin/social-feeds/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove this social feed element?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $f['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit Social Embeds -->
<div class="modal fade" id="feedModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content bg-dark border border-secondary text-white" id="feedForm" action="<?= BASE_URL ?>/admin/social-feeds/store" method="POST">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Embed Social Feed</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="feed_id">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Social Platform</label>
                    <select name="platform" id="feed_platform" class="form-select bg-dark border-secondary text-white">
                        <option value="youtube">YouTube Video</option>
                        <option value="instagram">Instagram Reel</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Platform URL / Share Link</label>
                    <input type="url" name="url" id="feed_url" class="form-control bg-dark border-secondary text-white" placeholder="https://youtube.com/watch?v=... OR https://instagram.com/reel/..." required>
                    <small class="text-secondary d-block mt-1">Accepts full share URLs. The system automatically extracts the ID.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Title (Short Caption)</label>
                    <input type="text" name="title" id="feed_title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Sonepur Fair Highlights" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <input type="text" name="category" id="feed_category" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Festivals, Handicraft">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-center">
                        <div class="form-check form-switch pt-3">
                            <input class="form-check-input cursor-pointer" type="checkbox" name="is_featured" id="feed_featured">
                            <label class="form-check-label text-white small" for="feed_featured">Feature Video</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Feed</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearFeedForm() {
        document.getElementById('feedForm').reset();
        document.getElementById('feed_id').value = '';
        document.getElementById('feedForm').action = '<?= BASE_URL ?>/admin/social-feeds/store';
        document.getElementById('modalTitle').textContent = 'Embed Social Feed';
    }

    function editFeed(data) {
        clearFeedForm();
        document.getElementById('feed_id').value = data.id;
        document.getElementById('feed_platform').value = data.platform;
        
        let mockUrl = '';
        if (data.platform === 'youtube') {
            mockUrl = 'https://www.youtube.com/watch?v=' + data.video_id;
        } else {
            mockUrl = 'https://www.instagram.com/reel/' + data.video_id;
        }
        document.getElementById('feed_url').value = mockUrl;
        document.getElementById('feed_title').value = data.title;
        document.getElementById('feed_category').value = data.category;
        document.getElementById('feed_featured').checked = parseInt(data.is_featured) === 1;

        document.getElementById('feedForm').action = '<?= BASE_URL ?>/admin/social-feeds/update';
        document.getElementById('modalTitle').textContent = 'Update Social Feed';

        const modal = new bootstrap.Modal(document.getElementById('feedModal'));
        modal.show();
    }

    // Client-side quick tabs filter
    document.getElementById('all-tab').addEventListener('click', () => filterItems('all'));
    document.getElementById('youtube-tab').addEventListener('click', () => filterItems('youtube'));
    document.getElementById('instagram-tab').addEventListener('click', () => filterItems('instagram'));

    function filterItems(platform) {
        const items = document.querySelectorAll('.feed-item');
        items.forEach(item => {
            if (platform === 'all' || item.dataset.platform === platform) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });
        
        // Active tab outline style fix
        const links = document.querySelectorAll('#platformTab .nav-link');
        links.forEach(l => {
            l.classList.remove('active', 'text-primary', 'border-bottom', 'border-primary', 'fw-bold');
            l.classList.add('text-secondary');
        });
        
        const activeLink = document.getElementById(platform + '-tab');
        activeLink.classList.remove('text-secondary');
        activeLink.classList.add('active', 'text-primary', 'border-bottom', 'border-primary', 'fw-bold');
    }
</script>
