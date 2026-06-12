<div class="row g-4">
    <!-- Media Navigation Sidebar -->
    <div class="col-lg-3">
        <div class="glass-card">
            <h5 class="text-white fw-bold mb-3">Folders</h5>
            <div class="list-group list-group-flush bg-transparent">
                <a href="<?= BASE_URL ?>/admin/media?folder=all" class="list-group-item list-group-item-action bg-transparent text-light border-0 d-flex justify-content-between align-items-center py-2 px-0 <?= $currentFolder === 'all' ? 'active text-primary fw-bold' : '' ?>">
                    <span><i class="fa-solid fa-folder-open me-2 text-warning"></i> All Media</span>
                    <span class="badge bg-secondary bg-opacity-25 text-light"><?= $folders['all'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>/admin/media?folder=images" class="list-group-item list-group-item-action bg-transparent text-light border-0 d-flex justify-content-between align-items-center py-2 px-0 <?= $currentFolder === 'images' ? 'active text-primary fw-bold' : '' ?>">
                    <span><i class="fa-solid fa-image me-2 text-info"></i> Images</span>
                    <span class="badge bg-secondary bg-opacity-25 text-light"><?= $folders['images'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>/admin/media?folder=videos" class="list-group-item list-group-item-action bg-transparent text-light border-0 d-flex justify-content-between align-items-center py-2 px-0 <?= $currentFolder === 'videos' ? 'active text-primary fw-bold' : '' ?>">
                    <span><i class="fa-solid fa-video me-2 text-danger"></i> Videos</span>
                    <span class="badge bg-secondary bg-opacity-25 text-light"><?= $folders['videos'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>/admin/media?folder=documents" class="list-group-item list-group-item-action bg-transparent text-light border-0 d-flex justify-content-between align-items-center py-2 px-0 <?= $currentFolder === 'documents' ? 'active text-primary fw-bold' : '' ?>">
                    <span><i class="fa-solid fa-file-pdf me-2 text-success"></i> Documents</span>
                    <span class="badge bg-secondary bg-opacity-25 text-light"><?= $folders['documents'] ?></span>
                </a>
            </div>
            
            <div class="border-top border-secondary mt-4 pt-3">
                <h6 class="text-white fw-bold mb-2">Storage Usage</h6>
                <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 28%"></div>
                </div>
                <small class="text-secondary mt-2 d-block">284.5 MB used of 1 GB (28%)</small>
            </div>
        </div>
    </div>

    <!-- Media Files Workspace -->
    <div class="col-lg-9">
        <div class="glass-card mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 mb-4">
                <div>
                    <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-photo-film text-primary me-2"></i> Media Hub</h4>
                    <p class="text-secondary small mb-0">Drag and drop assets. Images are automatically optimized and compressed to WebP.</p>
                </div>
                
                <div class="d-flex gap-2">
                    <form action="<?= BASE_URL ?>/admin/media" method="GET" class="d-flex align-items-center bg-dark bg-opacity-20 rounded-pill border border-secondary px-3" style="width:250px;">
                        <input type="hidden" name="folder" value="<?= htmlspecialchars($currentFolder) ?>">
                        <i class="fa-solid fa-search text-secondary me-2"></i>
                        <input type="text" name="q" class="form-control bg-transparent border-0 text-white p-0 text-sm shadow-none" placeholder="Search files..." value="<?= htmlspecialchars($searchQuery) ?>">
                    </form>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa-solid fa-upload me-1"></i> Upload File</button>
                </div>
            </div>

            <!-- Drag and Drop Upload Panel -->
            <div id="drag-drop-zone" class="p-4 mb-4 rounded-3 border-2 border-dashed border-secondary text-center cursor-pointer bg-dark bg-opacity-10 d-flex flex-column align-items-center justify-content-center" style="height:150px; transition: all 0.3s ease;">
                <i class="fa-solid fa-cloud-arrow-up fa-3x text-secondary mb-2"></i>
                <h6 class="text-white fw-bold">Drag & Drop files here or click to upload</h6>
                <p class="text-secondary small mb-0">Supports JPG, PNG, WEBP, MP4, and PDF</p>
                <form id="drag-upload-form" action="<?= BASE_URL ?>/admin/media/upload" method="POST" enctype="multipart/form-data" class="d-none">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="file" name="file" id="drag-file-input" onchange="this.form.submit()">
                </form>
            </div>

            <!-- Media Library Grid -->
            <div class="row g-3">
                <?php if(empty($items)): ?>
                    <div class="col-12 text-center py-5 text-secondary">
                        <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary"></i>
                        <h6 class="fw-bold">No media assets found in this folder</h6>
                        <p class="small">Upload files above to populate your media library.</p>
                    </div>
                <?php else: foreach($items as $m): 
                    $fileType = htmlspecialchars($m['file_type']);
                    $isImage = in_array($fileType, ['jpg', 'jpeg', 'png', 'webp']);
                    $src = strpos($m['file_path'], 'http') === 0 ? $m['file_path'] : BASE_URL . $m['file_path'];
                ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card bg-dark bg-opacity-25 border border-secondary rounded-3 overflow-hidden h-100 shadow-sm position-relative media-card" style="transition: all 0.2s ease;">
                            <!-- Top Right Type Badge -->
                            <span class="badge bg-dark bg-opacity-70 text-secondary border border-secondary position-absolute top-0 end-0 m-2 text-uppercase" style="font-size:0.6rem; z-index: 5;"><?= $fileType ?></span>
                            
                            <!-- Card Body Image Preview -->
                            <div class="ratio ratio-4x3 bg-dark d-flex align-items-center justify-content-center border-bottom border-secondary overflow-hidden">
                                <?php if($isImage): ?>
                                    <img src="<?= $src ?>" alt="Preview" class="object-fit-cover w-100 h-100" loading="lazy">
                                <?php elseif($fileType === 'pdf'): ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center text-danger w-100 h-100 bg-danger bg-opacity-10">
                                        <i class="fa-solid fa-file-pdf fa-3x mb-1"></i>
                                        <span class="small font-monospace" style="font-size:0.7rem;">PDF DOCUMENT</span>
                                    </div>
                                <?php elseif($fileType === 'mp4'): ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center text-info w-100 h-100 bg-info bg-opacity-10">
                                        <i class="fa-solid fa-video fa-3x mb-1"></i>
                                        <span class="small font-monospace" style="font-size:0.7rem;">MP4 VIDEO</span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center text-secondary w-100 h-100">
                                        <i class="fa-solid fa-file fa-3x mb-1"></i>
                                        <span class="small font-monospace" style="font-size:0.7rem;"><?= strtoupper($fileType) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Card Footer Details -->
                            <div class="p-3">
                                <div class="text-white text-truncate fw-semibold small mb-1" title="<?= htmlspecialchars($m['file_name']) ?>"><?= htmlspecialchars($m['file_name']) ?></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-secondary" style="font-size:0.75rem;"><?= number_format(($m['file_size'] ?? 0) / 1024, 1) ?> KB</small>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-link p-0 text-info" onclick="navigator.clipboard.writeText('<?= $src ?>'); alert('File link copied to clipboard!');" title="Copy Link"><i class="fa-solid fa-copy"></i></button>
                                        <form action="<?= BASE_URL ?>/admin/media/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this media permanently?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-link p-0 text-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
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

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content bg-dark border border-secondary text-white" action="<?= BASE_URL ?>/admin/media/upload" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Upload Media File</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Select File</label>
                    <input type="file" name="file" class="form-control bg-dark border-secondary text-white" required accept=".jpg,.jpeg,.png,.webp,.mp4,.pdf,.doc,.docx,.xls,.xlsx">
                    <small class="text-secondary d-block mt-2">Allowed types: Images (JPG, PNG, WEBP), Videos (MP4), Documents (PDF, Word, Excel)</small>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload & Optimize</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drag-drop-zone');
        const fileInput = document.getElementById('drag-file-input');

        // Trigger file input click when zone clicked
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Dragover styling class toggles
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--primary-color)';
            dropZone.style.backgroundColor = 'rgba(11, 61, 145, 0.15)';
        });

        ['dragleave', 'dragend'].forEach(type => {
            dropZone.addEventListener(type, () => {
                dropZone.style.borderColor = 'var(--admin-border)';
                dropZone.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
            });
        });

        // Drop files
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--admin-border)';
            dropZone.style.backgroundColor = 'rgba(0, 0, 0, 0.1)';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileInput.form.submit();
            }
        });
    });
</script>