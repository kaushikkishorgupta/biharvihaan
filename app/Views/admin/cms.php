<div class="row g-4">
    <!-- Left Column: Pages List -->
    <div class="col-xl-4">
        <div class="glass-card mb-4">
            <h4 class="mb-3 fw-bold text-white"><i class="fa-solid fa-window-maximize text-primary me-2"></i> Static Pages CMS</h4>
            <p class="text-secondary small mb-4">Select a system page to customize metadata, HTML layout, and structure.</p>
            
            <div class="list-group list-group-flush bg-transparent">
                <?php foreach($pages as $page): ?>
                    <button class="list-group-item list-group-item-action bg-transparent border-secondary border-opacity-25 px-0 py-3 text-start d-flex justify-content-between align-items-center" onclick='selectPage(<?= json_encode($page) ?>)'>
                        <div>
                            <div class="fw-bold text-white"><?= htmlspecialchars($page['title']) ?></div>
                            <small class="text-secondary font-monospace" style="font-size:0.75rem;">/<?= htmlspecialchars($page['slug']) ?></small>
                        </div>
                        <i class="fa-solid fa-chevron-right text-secondary small"></i>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Page Editor -->
    <div class="col-xl-8">
        <!-- Editor Card -->
        <div class="glass-card mb-4 d-none" id="editor-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-white" id="editor-title"><i class="fa-solid fa-edit text-primary me-2"></i> Edit Page</h5>
                <span id="autosave-status" class="badge bg-secondary bg-opacity-10 text-secondary d-none">Auto-saving...</span>
            </div>

            <form id="cmsForm" action="<?= BASE_URL ?>/admin/cms/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="page_id" id="page_id">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Page Title</label>
                    <input type="text" name="title" id="page_title" class="form-control bg-dark border-secondary text-white" placeholder="Page Name" required>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label text-secondary small fw-bold mb-0">HTML Content</label>
                        <button type="button" class="btn btn-sm btn-outline-light border-0 py-0" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="renderLivePreview()"><i class="fa-solid fa-display me-1"></i> Live Preview</button>
                    </div>
                    <textarea name="content" id="page_content" rows="12" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="<section>...</section>"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Page Variables & Metadata (JSON)</label>
                    <textarea name="meta_data" id="page_meta_data" rows="5" class="form-control bg-dark border-secondary text-white font-monospace" placeholder='{"key": "value"}'></textarea>
                    <div class="form-text text-secondary" style="font-size:0.75rem;">Store dynamic properties here, e.g., hero titles or banner images. Must be valid JSON format.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill"><i class="fa-solid fa-save me-2"></i> Save Changes</button>
                    <button type="button" class="btn btn-outline-light rounded-pill" onclick="closeEditor()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Version History Card -->
        <div class="glass-card mb-4 d-none" id="version-history-card">
            <h5 class="text-white fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> Revision History</h5>
            <div id="versions-list" class="list-group list-group-flush bg-transparent" style="max-height: 250px; overflow-y: auto;">
                <!-- Versions will load here -->
            </div>
        </div>
        
        <!-- Placeholder View -->
        <div class="glass-card text-center py-5" id="editor-placeholder">
            <div class="py-4">
                <i class="fa-solid fa-window-restore text-secondary fs-1 mb-3 opacity-50"></i>
                <h5 class="text-white fw-bold">Select a Page to Edit</h5>
                <p class="text-secondary small">Choose a static page from the left list to start adjusting content, layout, metadata settings, and rollback history.</p>
            </div>
        </div>
    </div>
</div>

<!-- Live Preview Device Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark border border-secondary text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Responsive Page Live Preview</h5>
                <div class="btn-group ms-auto" role="group">
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="setDeviceMode('desktop')"><i class="fa-solid fa-desktop me-1"></i> Desktop</button>
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="setDeviceMode('tablet')"><i class="fa-solid fa-tablet-screen-button me-1"></i> Tablet</button>
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="setDeviceMode('mobile')"><i class="fa-solid fa-mobile-screen-button me-1"></i> Mobile</button>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 d-flex justify-content-center bg-black bg-opacity-40" style="min-height:500px;">
                <div id="device-preview-frame" class="bg-white text-dark p-4 m-3 overflow-y-auto" style="width: 100%; max-width: 100%; height: 550px; transition: all 0.3s ease; border-radius: 8px;">
                    <div id="preview-html-val">Loading preview...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectPage(data) {
        document.getElementById('editor-placeholder').classList.add('d-none');
        document.getElementById('editor-card').classList.remove('d-none');
        document.getElementById('version-history-card').classList.remove('d-none');

        document.getElementById('page_id').value = data.id;
        document.getElementById('page_title').value = data.title;
        document.getElementById('page_content').value = data.content;
        document.getElementById('page_meta_data').value = data.meta_data || '{}';

        document.getElementById('editor-title').innerHTML = '<i class="fa-solid fa-edit text-primary me-2"></i> Edit Page: ' + data.title;
        
        loadPageVersions(data.id);
    }

    function closeEditor() {
        document.getElementById('editor-card').classList.add('d-none');
        document.getElementById('version-history-card').classList.add('d-none');
        document.getElementById('editor-placeholder').classList.remove('d-none');
        document.getElementById('cmsForm').reset();
    }

    function loadPageVersions(id) {
        const list = document.getElementById('versions-list');
        list.innerHTML = '<div class="text-secondary small py-2">Loading revisions...</div>';

        $.ajax({
            url: '<?= BASE_URL ?>/admin/cms/versions',
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
                    const isAutosave = v.content_type === 'page_autosave';
                    const typeBadge = isAutosave ? '<span class="badge bg-warning bg-opacity-10 text-warning ms-2">Auto-save</span>' : '<span class="badge bg-info bg-opacity-10 text-info ms-2">Revision</span>';
                    
                    list.innerHTML += `
                        <div class="list-group-item bg-transparent border-secondary border-opacity-25 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white small fw-semibold">${date} ${typeBadge}</div>
                                <small class="text-secondary">Saved by Admin</small>
                            </div>
                            <form action="<?= BASE_URL ?>/admin/cms/rollback" method="POST" class="m-0">
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

    // Auto Save trigger (triggers every 30 seconds if a page ID exists and contents are edited)
    setInterval(function() {
        const id = document.getElementById('page_id').value;
        if (!id) return;

        const statusLabel = document.getElementById('autosave-status');
        statusLabel.classList.remove('d-none');
        statusLabel.textContent = 'Auto-saving...';

        $.ajax({
            url: '<?= BASE_URL ?>/admin/cms/autosave',
            type: 'POST',
            data: {
                csrf_token: '<?= $_SESSION['csrf_token'] ?>',
                page_id: id,
                title: document.getElementById('page_title').value,
                content: document.getElementById('page_content').value,
                meta_data: document.getElementById('page_meta_data').value
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

    // Live Preview
    function renderLivePreview() {
        const title = document.getElementById('page_title').value || 'Untitled Page';
        const content = document.getElementById('page_content').value || '<p class="text-muted">No content structured.</p>';
        document.getElementById('preview-html-val').innerHTML = `
            <div class="p-3">
                <h1 class="border-bottom pb-2 fw-bold">${title}</h1>
                <div class="mt-4">${content}</div>
            </div>
        `;
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
