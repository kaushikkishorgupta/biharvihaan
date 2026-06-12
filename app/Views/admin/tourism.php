<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-map-location-dot text-primary me-2"></i> Tourism Circuit Manager</h4>
            <p class="text-secondary small mb-0">Manage heritage sites, circuits, geographic coordinates, nearby attractions, and local travel tips.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal" onclick="clearTourismForm()"><i class="fa-solid fa-plus me-1"></i> Add Destination</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-secondary border-bottom border-secondary">
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Location / District</th>
                    <th>Circuits</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($destinations)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-secondary">No tourism destinations found.</td></tr>
                <?php else: foreach($destinations as $d): ?>
                    <tr>
                        <td class="py-3">
                            <img src="<?= !empty($d['image_url']) ? (strpos($d['image_url'], 'http') === 0 ? htmlspecialchars($d['image_url']) : BASE_URL . $d['image_url']) : 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=150' ?>" width="60" height="50" class="rounded object-fit-cover shadow-sm border border-secondary">
                        </td>
                        <td class="py-3">
                            <div class="fw-bold text-white"><?= htmlspecialchars($d['name']) ?></div>
                            <small class="text-secondary font-monospace" style="font-size:0.75rem;">/tourism/<?= htmlspecialchars($d['slug'] ?? '') ?></small>
                        </td>
                        <td class="py-3"><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2"><?= htmlspecialchars($d['category']) ?></span></td>
                        <td class="py-3 text-secondary">
                            <div><i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($d['location']) ?></div>
                            <small class="text-secondary" style="font-size:0.8rem;"><?= htmlspecialchars($d['district'] ?? 'Bihar') ?> District</small>
                        </td>
                        <td class="py-3 text-light small"><?= htmlspecialchars($d['circuits'] ?: 'Generic Circuit') ?></td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-1 text-warning">
                                <i class="fa-solid fa-star fs-7"></i>
                                <span class="fw-bold text-white" style="font-size:0.85rem;"><?= number_format($d['rating'] ?: 4.5, 1) ?></span>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light" onclick="editDestination(<?= htmlspecialchars(json_encode($d)) ?>)"><i class="fa-solid fa-edit"></i></button>
                                <form action="<?= BASE_URL ?>/admin/tourism/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove destination?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
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

<!-- Destination Add/Edit Modal -->
<div class="modal fade" id="tourismModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content bg-dark border border-secondary text-white" id="tourismForm" action="<?= BASE_URL ?>/admin/tourism/store" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Destination</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="tourism_id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Name</label>
                        <input type="text" name="name" id="tourism_name" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Mahabodhi Temple" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Slug (Custom URL Path)</label>
                        <input type="text" name="slug" id="tourism_slug" class="form-control bg-dark border-secondary text-white" placeholder="e.g. mahabodhi-temple">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <select name="category" id="tourism_category" class="form-select bg-dark border-secondary text-white" required>
                            <option value="Buddhist Circuit">Buddhist Circuit</option>
                            <option value="Sufi Circuit">Sufi Circuit</option>
                            <option value="Jain Circuit">Jain Circuit</option>
                            <option value="Ramayana Circuit">Ramayana Circuit</option>
                            <option value="Nature & Eco Tourism">Nature & Eco Tourism</option>
                            <option value="Heritage Sites">Heritage Sites</option>
                            <option value="Shakti Circuit">Shakti Circuit</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Location (City)</label>
                        <input type="text" name="location" id="tourism_location" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Bodh Gaya" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">District</label>
                        <input type="text" name="district" id="tourism_district" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Gaya" required>
                    </div>

                    <!-- Coordinates -->
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Latitude Coordinate</label>
                        <input type="number" step="0.000001" name="latitude" id="tourism_lat" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 24.6961">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Longitude Coordinate</label>
                        <input type="number" step="0.000001" name="longitude" id="tourism_lng" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 84.9913">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Initial Rating</label>
                        <input type="number" step="0.1" min="0" max="5" name="rating" id="tourism_rating" class="form-control bg-dark border-secondary text-white" value="4.8">
                    </div>

                    <!-- Circuits and Nearby -->
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Tourism Circuits (Comma Separated)</label>
                        <input type="text" name="circuits" id="tourism_circuits" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Buddhist Circuit, Heritage Trail">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Nearby Attractions (Comma Separated)</label>
                        <input type="text" name="nearby_attractions" id="tourism_nearby" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Dungeshwari Caves, Great Buddha Statue">
                    </div>

                    <!-- Images -->
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Featured Image (Upload)</label>
                        <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">OR Featured Image URL</label>
                        <input type="text" name="image_url" id="tourism_image_url" class="form-control bg-dark border-secondary text-white" placeholder="https://image.jpg">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Gallery Images (Multiple Upload)</label>
                        <input type="file" name="gallery_files[]" class="form-control bg-dark border-secondary text-white" accept="image/*" multiple>
                    </div>

                    <!-- Texts -->
                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Description</label>
                        <textarea name="description" id="tourism_desc" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Write full description..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">History & Cultural Context</label>
                        <textarea name="history" id="tourism_history" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Write historical significance..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Travel Tips (Packing, Dress code, Best season)</label>
                        <textarea name="travel_tips" id="tourism_tips" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="Inside travel tips..."></textarea>
                    </div>

                    <!-- SEO -->
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Title Tag</label>
                        <input type="text" name="meta_title" id="tourism_meta_title" class="form-control bg-dark border-secondary text-white" placeholder="Title for Google...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Description Tag</label>
                        <input type="text" name="meta_description" id="tourism_meta_desc" class="form-control bg-dark border-secondary text-white" placeholder="Snippet for Google...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Publish Status</label>
                        <select name="status" id="tourism_status" class="form-select bg-dark border-secondary text-white">
                            <option value="active">Active (Visible)</option>
                            <option value="inactive">Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Destination</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearTourismForm() {
        document.getElementById('tourismForm').reset();
        document.getElementById('tourism_id').value = '';
        document.getElementById('tourismForm').action = '<?= BASE_URL ?>/admin/tourism/store';
        document.getElementById('modalTitle').textContent = 'Add Destination';
    }

    function editDestination(data) {
        clearTourismForm();
        document.getElementById('tourism_id').value = data.id;
        document.getElementById('tourism_name').value = data.name;
        document.getElementById('tourism_slug').value = data.slug;
        document.getElementById('tourism_category').value = data.category;
        document.getElementById('tourism_location').value = data.location;
        document.getElementById('tourism_district').value = data.district;
        document.getElementById('tourism_lat').value = data.latitude;
        document.getElementById('tourism_lng').value = data.longitude;
        document.getElementById('tourism_rating').value = data.rating;
        document.getElementById('tourism_circuits').value = data.circuits;
        document.getElementById('tourism_nearby').value = data.nearby_attractions;
        document.getElementById('tourism_image_url').value = data.image_url;
        document.getElementById('tourism_desc').value = data.description;
        document.getElementById('tourism_history').value = data.history;
        document.getElementById('tourism_tips').value = data.travel_tips;
        document.getElementById('tourism_meta_title').value = data.meta_title;
        document.getElementById('tourism_meta_desc').value = data.meta_description;
        document.getElementById('tourism_status').value = data.status || 'active';

        document.getElementById('tourismForm').action = '<?= BASE_URL ?>/admin/tourism/update';
        document.getElementById('modalTitle').textContent = 'Update Destination';

        const modal = new bootstrap.Modal(document.getElementById('tourismModal'));
        modal.show();
    }
</script>
