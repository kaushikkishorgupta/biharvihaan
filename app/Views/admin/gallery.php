<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-palette text-primary me-2"></i> Gallery Pinterest CMS</h4>
            <p class="text-secondary small mb-0 font-primary">Pinterest-style visual layout for tourism spots, local crafts, and cultural festivals gallery.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#galleryModal" onclick="clearGalleryForm()"><i class="fa-solid fa-plus me-1"></i> Add Image</button>
    </div>

    <!-- Gallery Filter Buttons -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <button class="btn btn-sm btn-outline-light rounded-pill px-3 active" onclick="filterGallery('all')">All Images</button>
        <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="filterGallery('Buddhist Circuit')">Buddhist Circuit</button>
        <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="filterGallery('Heritage Sites')">Heritage Sites</button>
        <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="filterGallery('Nature & Eco Tourism')">Nature & Eco</button>
        <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="filterGallery('Arts & Crafts')">Arts & Crafts</button>
        <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="filterGallery('Festivals')">Festivals</button>
    </div>

    <!-- Pinterest Masonry/Flex Layout Grid -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="gallery-masonry-grid">
        <?php if(empty($items)): ?>
            <div class="col-12 text-center py-5 text-secondary">
                <i class="fa-solid fa-images fa-3x mb-3"></i>
                <h6>No images found in gallery database.</h6>
            </div>
        <?php else: foreach($items as $d): 
            $category = htmlspecialchars($d['category'] ?? 'General');
            $imgUrl = !empty($d['image']) ? (strpos($d['image'], 'http') === 0 ? htmlspecialchars($d['image']) : BASE_URL . $d['image']) : 'https://images.unsplash.com/photo-1614050800720-3b02ddba753a?w=400';
        ?>
            <div class="col gallery-card-container" data-category="<?= $category ?>">
                <div class="card bg-dark bg-opacity-20 border border-secondary rounded-4 overflow-hidden h-100 shadow-sm media-card" style="transition: transform 0.2s ease;">
                    <div class="position-relative">
                        <img src="<?= $imgUrl ?>" class="card-img-top w-100 object-fit-cover" style="height: 200px;" loading="lazy" alt="<?= htmlspecialchars($d['title'] ?? '') ?>">
                        <span class="badge bg-dark bg-opacity-70 text-secondary border border-secondary position-absolute bottom-0 start-0 m-2" style="font-size:0.65rem;"><?= $category ?></span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="text-white fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($d['title'] ?? '') ?>"><?= htmlspecialchars($d['title'] ?? '') ?></h6>
                            <p class="text-secondary small mb-3 text-truncate-2" style="font-size:0.75rem; line-height:1.3;"><?= htmlspecialchars($d['description'] ?? 'No description provided.') ?></p>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2 border-top border-secondary pt-2">
                            <span class="text-secondary small" style="font-size:0.7rem;"><i class="fa-solid fa-camera me-1"></i> <?= htmlspecialchars($d['photographer'] ?: 'Admin') ?></span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-link p-0 text-info" onclick="editGallery(<?= htmlspecialchars(json_encode($d)) ?>)"><i class="fa-solid fa-edit"></i></button>
                                <form action="<?= BASE_URL ?>/admin/gallery/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove this image?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-link p-0 text-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<!-- Gallery Form Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content bg-dark border border-secondary text-white" id="galleryForm" action="<?= BASE_URL ?>/admin/gallery/store" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Gallery Image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="gallery_id">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Title</label>
                    <input type="text" name="title" id="gallery_title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Nalanda Pillars" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Category</label>
                    <select name="category" id="gallery_category" class="form-select bg-dark border-secondary text-white" required>
                        <option value="Buddhist Circuit">Buddhist Circuit</option>
                        <option value="Heritage Sites">Heritage Sites</option>
                        <option value="Nature & Eco Tourism">Nature & Eco Tourism</option>
                        <option value="Arts & Crafts">Arts & Crafts</option>
                        <option value="Festivals">Festivals</option>
                        <option value="Temples">Temples</option>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Location (City)</label>
                        <input type="text" name="location" id="gallery_location" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Nalanda">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Photographer</label>
                        <input type="text" name="photographer" id="gallery_photographer" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Ramesh Kumar">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Image (Upload File)</label>
                    <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">OR Image URL</label>
                    <input type="text" name="image_url" id="gallery_image_url" class="form-control bg-dark border-secondary text-white" placeholder="https://image-url.jpg">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Description</label>
                    <textarea name="description" id="gallery_desc" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Description of the capture..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Publish Status</label>
                    <select name="status" id="gallery_status" class="form-select bg-dark border-secondary text-white">
                        <option value="published">Published</option>
                        <option value="pending">Pending Review</option>
                        <option value="hidden">Hidden</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Image</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearGalleryForm() {
        document.getElementById('galleryForm').reset();
        document.getElementById('gallery_id').value = '';
        document.getElementById('galleryForm').action = '<?= BASE_URL ?>/admin/gallery/store';
        document.getElementById('modalTitle').textContent = 'Add Gallery Image';
    }

    function editGallery(data) {
        clearGalleryForm();
        document.getElementById('gallery_id').value = data.id;
        document.getElementById('gallery_title').value = data.title;
        document.getElementById('gallery_category').value = data.category;
        document.getElementById('gallery_location').value = data.location;
        document.getElementById('gallery_photographer').value = data.photographer;
        document.getElementById('gallery_image_url').value = data.image;
        document.getElementById('gallery_desc').value = data.description;
        document.getElementById('gallery_status').value = data.status || 'published';

        document.getElementById('galleryForm').action = '<?= BASE_URL ?>/admin/gallery/update';
        document.getElementById('modalTitle').textContent = 'Update Gallery Image';

        const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
        modal.show();
    }

    function filterGallery(category) {
        const cards = document.querySelectorAll('.gallery-card-container');
        cards.forEach(c => {
            if (category === 'all' || c.dataset.category === category) {
                c.classList.remove('d-none');
            } else {
                c.classList.add('d-none');
            }
        });

        // Toggle button states
        const buttons = document.querySelectorAll('.btn-outline-light');
        buttons.forEach(b => {
            b.classList.remove('active');
            if (b.innerText === category || (category === 'all' && b.innerText === 'All Images')) {
                b.classList.add('active');
            }
        });
    }
</script>