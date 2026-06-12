<?php require dirname(__DIR__) . '/admin/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Enterprise Partners</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
            <i class="bi bi-plus-circle me-2"></i>Add Partner
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Partner List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="partnersTable">
                    <thead class="table-light">
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partners as $partner): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($partner['logo']) ?>" alt="Logo" width="50" height="50" class="rounded object-fit-contain bg-light p-1">
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($partner['name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($partner['slug']) ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($partner['category']) ?></span></td>
                            <td>
                                <?php if($partner['status'] === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" onclick="editPartner(<?= htmlspecialchars(json_encode($partner)) ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deletePartner(<?= $partner['id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($partners)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No partners found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Partner Modal -->
<div class="modal fade" id="partnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/admin/partners/store" method="POST" enctype="multipart/form-data" id="partnerForm">
                <!-- CSRF Token handled globally in router usually, but safe to include -->
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
                <input type="hidden" name="id" id="partner_id">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" id="partner_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category *</label>
                            <input type="text" class="form-control" name="category" id="partner_category" placeholder="e.g. Tourism Partner" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short Description *</label>
                            <textarea class="form-control" name="short_description" id="partner_short" rows="2" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Description</label>
                            <textarea class="form-control" name="description" id="partner_desc" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mission</label>
                            <textarea class="form-control" name="mission" id="partner_mission" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vision</label>
                            <textarea class="form-control" name="vision" id="partner_vision" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" id="partner_website">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="partner_email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="partner_phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="partner_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="partner_address">
                        </div>
                        <div class="col-12" id="logoUploadDiv">
                            <label class="form-label">Upload Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <small class="text-muted">For edit, leave blank to keep current logo. To change logo on edit, you'd need an update logic handling file.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="<?= BASE_URL ?>/admin/partners/delete" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
    <input type="hidden" name="id" id="delete_id">
</form>

<script>
    const partnerModal = new bootstrap.Modal(document.getElementById('partnerModal'));
    
    document.querySelector('[data-bs-target="#addPartnerModal"]').addEventListener('click', function() {
        document.getElementById('partnerForm').reset();
        document.getElementById('partner_id').value = '';
        document.getElementById('partnerForm').action = '<?= BASE_URL ?>/admin/partners/store';
        document.getElementById('modalTitle').innerText = 'Add Partner';
        document.getElementById('logoUploadDiv').style.display = 'block';
        partnerModal.show();
    });

    function editPartner(data) {
        document.getElementById('partner_id').value = data.id;
        document.getElementById('partner_name').value = data.name;
        document.getElementById('partner_category').value = data.category;
        document.getElementById('partner_short').value = data.short_description;
        document.getElementById('partner_desc').value = data.description;
        document.getElementById('partner_mission').value = data.mission;
        document.getElementById('partner_vision').value = data.vision;
        document.getElementById('partner_website').value = data.website;
        document.getElementById('partner_email').value = data.email;
        document.getElementById('partner_phone').value = data.phone;
        document.getElementById('partner_address').value = data.address;
        document.getElementById('partner_status').value = data.status;
        
        document.getElementById('partnerForm').action = '<?= BASE_URL ?>/admin/partners/update';
        document.getElementById('modalTitle').innerText = 'Edit Partner';
        document.getElementById('logoUploadDiv').style.display = 'none'; // Simple edit hides logo upload for now
        partnerModal.show();
    }

    function deletePartner(id) {
        if(confirm('Are you sure you want to delete this partner? This action cannot be undone.')) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php require dirname(__DIR__) . '/admin/layout/footer.php'; ?>
