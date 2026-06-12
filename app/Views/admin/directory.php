<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-address-book text-primary me-2"></i> Business Directory Manager</h4>
            <p class="text-secondary small mb-0">Manage registered local hotels, restaurants, travel agencies, guides, and transportation providers in Bihar.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#businessModal" onclick="clearBusinessForm()"><i class="fa-solid fa-plus me-1"></i> Add Business</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-secondary border-bottom border-secondary">
                    <th>Name</th>
                    <th>Category</th>
                    <th>Contact Details</th>
                    <th>Rating</th>
                    <th>Verification</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($items)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-secondary">No businesses found in directory.</td></tr>
                <?php else: foreach($items as $d): ?>
                    <tr>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <img src="<?= !empty($d['image_url']) ? (strpos($d['image_url'], 'http') === 0 ? htmlspecialchars($d['image_url']) : BASE_URL . $d['image_url']) : 'https://images.unsplash.com/photo-1544717302-de2939b7ef71?w=100' ?>" width="50" height="40" class="rounded object-fit-cover me-3 border border-secondary shadow-sm">
                                <div>
                                    <div class="fw-bold text-white"><?= htmlspecialchars($d['name'] ?? '') ?></div>
                                    <small class="text-secondary font-monospace" style="font-size:0.75rem;">/directory/<?= htmlspecialchars($d['slug'] ?? '') ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3"><span class="badge bg-secondary bg-opacity-25 text-light"><?= htmlspecialchars($d['category'] ?? '') ?></span></td>
                        <td class="py-3 text-secondary small">
                            <div><i class="fa-solid fa-phone me-1 text-secondary"></i> <?= htmlspecialchars($d['phone'] ?? '') ?></div>
                            <div><i class="fa-solid fa-envelope me-1 text-secondary"></i> <?= htmlspecialchars($d['email'] ?? '') ?></div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center text-warning gap-1">
                                <i class="fa-solid fa-star fs-7"></i>
                                <span class="fw-bold text-white small"><?= number_format($d['rating'] ?: 4.5, 1) ?></span>
                            </div>
                        </td>
                        <td class="py-3">
                            <?php if($d['is_verified']): ?>
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-circle-check"></i> Verified</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Unverified</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <?php if(($d['status'] ?? 'active') === 'active'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light" onclick='editBusiness(<?= htmlspecialchars(json_encode($d)) ?>)'><i class="fa-solid fa-edit"></i></button>
                                <form action="<?= BASE_URL ?>/admin/businesses/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove business listing?');">
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

<!-- Business Add/Edit Modal -->
<div class="modal fade" id="businessModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content bg-dark border border-secondary text-white" id="businessForm" action="<?= BASE_URL ?>/admin/businesses/store" method="POST">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Directory Listing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="business_id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Name</label>
                        <input type="text" name="name" id="business_name" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Hotel Maurya" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <select name="category" id="business_category" class="form-select bg-dark border-secondary text-white" required>
                            <option value="Hotel">Hotel / Stay</option>
                            <option value="Restaurant">Restaurant / Cafe</option>
                            <option value="Travel Agency">Travel Agency</option>
                            <option value="Guide">Tour Guide</option>
                            <option value="Transport">Transport Service</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Phone Number</label>
                        <input type="text" name="phone" id="business_phone" class="form-control bg-dark border-secondary text-white" placeholder="+91 9999999999">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Email</label>
                        <input type="email" name="email" id="business_email" class="form-control bg-dark border-secondary text-white" placeholder="contact@hotel.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Website</label>
                        <input type="url" name="website" id="business_website" class="form-control bg-dark border-secondary text-white" placeholder="https://hotel.com">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Address</label>
                        <input type="text" name="address" id="business_address" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Fraser Road, Patna, Bihar">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label text-secondary small fw-bold">Image URL</label>
                        <input type="text" name="image_url" id="business_image" class="form-control bg-dark border-secondary text-white" placeholder="https://hotel-image.jpg">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Rating Value</label>
                        <input type="number" step="0.1" min="1" max="5" name="rating" id="business_rating" class="form-control bg-dark border-secondary text-white" value="4.5">
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-center">
                        <div class="form-check form-switch pt-3">
                            <input class="form-check-input cursor-pointer" type="checkbox" name="is_verified" id="business_verified">
                            <label class="form-check-label text-white small" for="business_verified">Verified Listing (Verification Badge)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Publish Status</label>
                        <select name="status" id="business_status" class="form-select bg-dark border-secondary text-white">
                            <option value="active">Active (Visible)</option>
                            <option value="inactive">Inactive (Hidden)</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Description</label>
                        <textarea name="description" id="business_desc" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Write full description of services..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Business</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearBusinessForm() {
        document.getElementById('businessForm').reset();
        document.getElementById('business_id').value = '';
        document.getElementById('businessForm').action = '<?= BASE_URL ?>/admin/businesses/store';
        document.getElementById('modalTitle').textContent = 'Add Directory Listing';
    }

    function editBusiness(data) {
        clearBusinessForm();
        document.getElementById('business_id').value = data.id;
        document.getElementById('business_name').value = data.name;
        document.getElementById('business_category').value = data.category;
        document.getElementById('business_phone').value = data.phone;
        document.getElementById('business_email').value = data.email;
        document.getElementById('business_website').value = data.website;
        document.getElementById('business_address').value = data.address;
        document.getElementById('business_image').value = data.image_url;
        document.getElementById('business_rating').value = data.rating;
        document.getElementById('business_verified').checked = parseInt(data.is_verified) === 1;
        document.getElementById('business_status').value = data.status || 'active';
        document.getElementById('business_desc').value = data.description;

        document.getElementById('businessForm').action = '<?= BASE_URL ?>/admin/businesses/update';
        document.getElementById('modalTitle').textContent = 'Update Directory Listing';

        const modal = new bootstrap.Modal(document.getElementById('businessModal'));
        modal.show();
    }
</script>