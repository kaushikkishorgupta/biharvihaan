<div class="row g-4">
    <!-- Main Blog CRUD List -->
    <div class="col-xl-7">
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-blog text-primary me-2"></i> Blog & Articles CMS</h4>
                <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="clearBlogForm()"><i class="fa-solid fa-plus me-1"></i> Write Article</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-secondary border-bottom border-secondary">
                            <th>Post</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($blogs)): ?>
                            <tr><td colspan="5" class="text-center py-4 text-secondary">No blog posts created yet.</td></tr>
                        <?php else: foreach($blogs as $b): 
                            $status = htmlspecialchars($b['status'] ?? 'draft');
                            $img = !empty($b['image_url']) ? (strpos($b['image_url'], 'http') === 0 ? htmlspecialchars($b['image_url']) : BASE_URL . $b['image_url']) : 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=100';
                        ?>
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $img ?>" width="50" height="40" class="rounded object-fit-cover me-3 border border-secondary shadow-sm">
                                        <div>
                                            <div class="fw-bold text-white"><?= htmlspecialchars($b['title']) ?></div>
                                            <small class="text-secondary font-monospace" style="font-size:0.75rem;">/blog/<?= htmlspecialchars($b['slug'] ?? '') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-25 text-light">
                                        <?= htmlspecialchars($b['category_id'] ? 'Seeded Cat #'.$b['category_id'] : 'General') ?>
                                    </span>
                                </td>
                                <td class="py-3 text-secondary small"><?= htmlspecialchars($b['author_name'] ?: 'Super Admin') ?></td>
                                <td class="py-3">
                                    <?php if($status === 'published'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Published</span>
                                    <?php elseif($status === 'scheduled'): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info">Scheduled</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-light" onclick='editBlog(<?= htmlspecialchars(json_encode($b)) ?>)'><i class="fa-solid fa-edit"></i></button>
                                        <form action="<?= BASE_URL ?>/admin/blogs/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this article?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Blog Editor Panel (Right Column) -->
    <div class="col-xl-5">
        <div class="glass-card mb-4" id="editor-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-white" id="editor-title"><i class="fa-solid fa-pen-nib text-primary me-2"></i> Write Post</h5>
                <span id="autosave-status" class="badge bg-secondary bg-opacity-10 text-secondary d-none">Auto-saving...</span>
            </div>
            
            <form id="blogForm" action="<?= BASE_URL ?>/admin/blogs/store" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="blog_id">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Title</label>
                    <input type="text" name="title" id="blog_title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Exploring Rohtasgarh Fort" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Slug</label>
                        <input type="text" name="slug" id="blog_slug" class="form-control bg-dark border-secondary text-white" placeholder="exploring-rohtasgarh">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <select name="category_id" id="blog_category_id" class="form-select bg-dark border-secondary text-white">
                            <option value="">Select Category...</option>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Simple Rich Text Editor Toolbar Mock -->
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Content</label>
                    <div class="d-flex gap-2 bg-dark bg-opacity-50 p-2 border border-secondary border-bottom-0 rounded-top-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="insertTag('**', '**')"><i class="fa-solid fa-bold"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="insertTag('*', '*')"><i class="fa-solid fa-italic"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="insertTag('### ', '')"><i class="fa-solid fa-heading"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="insertTag('- ', '')"><i class="fa-solid fa-list-ul"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-white" onclick="insertTag('[Link Name](', ')')"><i class="fa-solid fa-link"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 text-white ms-auto" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="renderLivePreview()"><i class="fa-solid fa-display me-1"></i> Live Preview</button>
                    </div>
                    <textarea name="content" id="blog_content" rows="6" class="form-control bg-dark border-secondary text-white rounded-top-0 rounded-bottom-3 font-monospace" placeholder="Start writing article content..." required></textarea>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Featured Image</label>
                        <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">OR Image URL</label>
                        <input type="text" name="image_url" id="blog_image_url" class="form-control bg-dark border-secondary text-white" placeholder="https://image-link.jpg">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Tags (Comma Separated)</label>
                        <input type="text" name="tags" id="blog_tags" class="form-control bg-dark border-secondary text-white" placeholder="historical, fort, weekend-gateway">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Status</label>
                        <select name="status" id="blog_status" class="form-select bg-dark border-secondary text-white" onchange="toggleScheduleField()">
                            <option value="draft">Draft</option>
                            <option value="published">Publish Immediately</option>
                            <option value="scheduled">Schedule Post</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 d-none" id="schedule-date-container">
                    <label class="form-label text-secondary small fw-bold">Publish Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" id="blog_scheduled_at" class="form-control bg-dark border-secondary text-white">
                </div>

                <!-- SEO Details -->
                <div class="border-top border-secondary pt-3 mt-4 mb-4">
                    <h6 class="text-white fw-bold mb-3"><i class="fa-solid fa-search text-info me-2"></i> Page SEO Metatags</h6>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">SEO Meta Title</label>
                        <input type="text" name="meta_title" id="blog_meta_title" class="form-control bg-dark border-secondary text-white" placeholder="Search result title...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">SEO Meta Description</label>
                        <textarea name="meta_description" id="blog_meta_description" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="Search result snippet..."></textarea>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill"><i class="fa-solid fa-save me-2"></i> Save Post</button>
                    <button type="button" class="btn btn-outline-light rounded-pill" onclick="clearBlogForm()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Version History Card -->
        <div class="glass-card mt-4 d-none" id="version-history-card">
            <h5 class="text-white fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> Version History</h5>
            <div id="versions-list" class="list-group list-group-flush bg-transparent" style="max-height: 250px; overflow-y: auto;">
                <!-- Versions will load here -->
            </div>
        </div>
    </div>
</div>

<!-- Live Preview Device Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark border border-secondary text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Responsive Live Preview</h5>
                <div class="btn-group ms-auto" role="group">
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="setDeviceMode('desktop')"><i class="fa-solid fa-desktop me-1"></i> Desktop</button>
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="setDeviceMode('tablet')"><i class="fa-solid fa-tablet-screen-button me-1"></i> Tablet</button>
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="setDeviceMode('mobile')"><i class="fa-solid fa-mobile-screen-button me-1"></i> Mobile</button>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center bg-black bg-opacity-40" style="min-height:500px;">
                <div id="device-preview-frame" class="bg-white text-dark p-4 m-3 overflow-y-auto" style="width: 100%; max-width: 100%; height: 550px; transition: all 0.3s ease; border-radius: 8px;">
                    <h1 class="fw-bold" id="preview-title-val">Title</h1>
                    <div class="text-muted small mb-4" id="preview-tags-val">Tags</div>
                    <div id="preview-content-val" style="line-height:1.6; white-space: pre-wrap;">Content</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleScheduleField() {
        const status = document.getElementById('blog_status').value;
        const container = document.getElementById('schedule-date-container');
        if (status === 'scheduled') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }

    function clearBlogForm() {
        document.getElementById('blogForm').reset();
        document.getElementById('blog_id').value = '';
        document.getElementById('blogForm').action = '<?= BASE_URL ?>/admin/blogs/store';
        document.getElementById('editor-title').innerHTML = '<i class="fa-solid fa-pen-nib text-primary me-2"></i> Write Post';
        document.getElementById('version-history-card').classList.add('d-none');
        toggleScheduleField();
    }

    function editBlog(data) {
        clearBlogForm();
        document.getElementById('blog_id').value = data.id;
        document.getElementById('blog_title').value = data.title;
        document.getElementById('blog_slug').value = data.slug;
        document.getElementById('blog_category_id').value = data.category_id || '';
        document.getElementById('blog_content').value = data.content;
        document.getElementById('blog_image_url').value = data.image_url;
        document.getElementById('blog_tags').value = data.tags;
        document.getElementById('blog_status').value = data.status || 'draft';
        document.getElementById('blog_scheduled_at').value = data.scheduled_at ? data.scheduled_at.replace(' ', 'T') : '';
        document.getElementById('blog_meta_title').value = data.meta_title;
        document.getElementById('blog_meta_description').value = data.meta_description;

        document.getElementById('blogForm').action = '<?= BASE_URL ?>/admin/blogs/update';
        document.getElementById('editor-title').innerHTML = '<i class="fa-solid fa-pen-nib text-primary me-2"></i> Edit Post #' + data.id;
        document.getElementById('version-history-card').classList.remove('d-none');
        loadBlogVersions(data.id);
        toggleScheduleField();
    }

    function loadBlogVersions(id) {
        const list = document.getElementById('versions-list');
        list.innerHTML = '<div class="text-secondary small py-2">Loading revisions...</div>';
        
        $.ajax({
            url: '<?= BASE_URL ?>/admin/blogs/versions',
            type: 'GET',
            data: { id: id },
            success: function(versions) {
                list.innerHTML = '';
                if (versions.length === 0) {
                    list.innerHTML = '<div class="text-secondary small py-2">No revisions found.</div>';
                    return;
                }
                versions.forEach(v => {
                    const date = new Date(v.created_at).toLocaleString();
                    const isAutosave = v.content_type === 'blog_autosave';
                    const typeBadge = isAutosave ? '<span class="badge bg-warning bg-opacity-10 text-warning ms-2">Auto-save</span>' : '<span class="badge bg-info bg-opacity-10 text-info ms-2">Revision</span>';
                    
                    list.innerHTML += `
                        <div class="list-group-item bg-transparent border-secondary border-opacity-25 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white small fw-semibold">${date} ${typeBadge}</div>
                                <small class="text-secondary">Saved by Admin</small>
                            </div>
                            <form action="<?= BASE_URL ?>/admin/blogs/rollback" method="POST" class="m-0">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="version_id" value="${v.id}">
                                <button type="submit" class="btn btn-sm btn-outline-warning py-1 px-2 rounded-pill" style="font-size: 0.75rem;"><i class="fa-solid fa-rotate-left"></i> Restore</button>
                            </form>
                        </div>
                    `;
                });
            },
            error: function() {
                list.innerHTML = '<div class="text-danger small py-2">Error loading revisions.</div>';
            }
        });
    }

    // Auto Save trigger (triggers every 30 seconds if a blog ID exists and contents are edited)
    setInterval(function() {
        const id = document.getElementById('blog_id').value;
        if (!id) return; // Only autosave edits, not new creation drafts

        const statusLabel = document.getElementById('autosave-status');
        statusLabel.classList.remove('d-none');
        statusLabel.textContent = 'Auto-saving...';

        const formData = new FormData(document.getElementById('blogForm'));
        
        // Append fields for AJAX endpoint
        $.ajax({
            url: '<?= BASE_URL ?>/admin/blogs/autosave',
            type: 'POST',
            data: {
                csrf_token: '<?= $_SESSION['csrf_token'] ?>',
                id: id,
                title: document.getElementById('blog_title').value,
                slug: document.getElementById('blog_slug').value,
                content: document.getElementById('blog_content').value,
                category_id: document.getElementById('blog_category_id').value,
                tags: document.getElementById('blog_tags').value,
                status: document.getElementById('blog_status').value,
                scheduled_at: document.getElementById('blog_scheduled_at').value,
                meta_title: document.getElementById('blog_meta_title').value,
                meta_description: document.getElementById('blog_meta_description').value,
                image_url: document.getElementById('blog_image_url').value
            },
            success: function(response) {
                if (response && response.success) {
                    statusLabel.textContent = 'Saved at ' + response.time;
                    statusLabel.className = 'badge bg-success bg-opacity-10 text-success';
                } else {
                    statusLabel.textContent = 'Autosave failed';
                    statusLabel.className = 'badge bg-danger bg-opacity-10 text-danger';
                }
                setTimeout(() => statusLabel.classList.add('d-none'), 3000);
            },
            error: function() {
                statusLabel.textContent = 'Save error';
                statusLabel.className = 'badge bg-danger bg-opacity-10 text-danger';
                setTimeout(() => statusLabel.classList.add('d-none'), 3000);
            }
        });
    }, 30000);

    // Helpers to insert Rich Text Tag Markup
    function insertTag(startTag, endTag) {
        const textarea = document.getElementById('blog_content');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selection = text.substring(start, end);
        const replacement = startTag + selection + endTag;
        textarea.value = text.substring(0, start) + replacement + text.substring(end, text.length);
        textarea.focus();
        textarea.selectionStart = start + startTag.length;
        textarea.selectionEnd = end + startTag.length;
    }

    // Live Preview
    function renderLivePreview() {
        document.getElementById('preview-title-val').textContent = document.getElementById('blog_title').value || 'Untitled Post';
        document.getElementById('preview-tags-val').textContent = document.getElementById('blog_tags').value ? 'Tags: ' + document.getElementById('blog_tags').value : '';
        document.getElementById('preview-content-val').textContent = document.getElementById('blog_content').value || 'No content written yet.';
    }

    function setDeviceMode(mode) {
        const frame = document.getElementById('device-preview-frame');
        if (mode === 'mobile') {
            frame.style.maxWidth = '375px';
            frame.style.height = '500px';
        } else if (mode === 'tablet') {
            frame.style.maxWidth = '768px';
            frame.style.height = '500px';
        } else {
            frame.style.maxWidth = '100%';
            frame.style.height = '550px';
        }
    }
</script>
