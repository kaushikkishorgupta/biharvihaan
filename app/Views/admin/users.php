<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-users-gear text-primary me-2"></i> User Directory & Roles</h4>
            <p class="text-secondary small mb-0 font-primary">Configure administrative team accounts and manage system role boundaries.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/roles" class="btn btn-outline-light rounded-pill px-3"><i class="fa-solid fa-shield-halved me-1"></i> Role Permissions</a>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#userModal" onclick="clearUserForm()"><i class="fa-solid fa-plus me-1"></i> Add Team Member</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-secondary border-bottom border-secondary">
                    <th>User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>RBAC Role</th>
                    <th>Account Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($users)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-secondary">No users found.</td></tr>
                <?php else: foreach($users as $u): 
                    $status = htmlspecialchars($u['status'] ?? 'active');
                ?>
                    <tr>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($u['name']) ?>&background=0B3D91&color=fff&bold=true" class="rounded-circle me-3" width="40" height="40">
                                <span class="fw-bold text-white"><?= htmlspecialchars($u['name']) ?></span>
                            </div>
                        </td>
                        <td class="py-3 text-secondary"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="py-3 text-secondary small"><?= htmlspecialchars($u['phone'] ?: 'N/A') ?></td>
                        <td class="py-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <?= htmlspecialchars($u['role_name'] ?: 'Viewer') ?>
                            </span>
                        </td>
                        <td class="py-3">
                            <?php if($status === 'active'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-circle-check"></i> Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-circle-xmark"></i> Suspended</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light" onclick='editUser(<?= htmlspecialchars(json_encode($u)) ?>)'><i class="fa-solid fa-user-pen"></i></button>
                                <form action="<?= BASE_URL ?>/admin/users/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove this user?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" <?= $u['id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>><i class="fa-solid fa-user-xmark"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content bg-dark border border-secondary text-white" id="userForm" action="<?= BASE_URL ?>/admin/users/store" method="POST">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Create Team Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="user_id">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Full Name</label>
                    <input type="text" name="name" id="user_name" class="form-control bg-dark border-secondary text-white" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Email Address</label>
                    <input type="email" name="email" id="user_email" class="form-control bg-dark border-secondary text-white" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Phone Number</label>
                    <input type="text" name="phone" id="user_phone" class="form-control bg-dark border-secondary text-white" placeholder="e.g. +91 9999999999">
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Password (Leave blank on edit to keep same)</label>
                    <input type="password" name="password" id="user_password" class="form-control bg-dark border-secondary text-white">
                </div>

                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">System Role (RBAC)</label>
                        <select name="role_id" id="user_role_id" class="form-select bg-dark border-secondary text-white" required>
                            <?php foreach($roles as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-secondary small fw-bold">Account Status</label>
                        <select name="status" id="user_status" class="form-select bg-dark border-secondary text-white" required>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Member</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearUserForm() {
        document.getElementById('userForm').reset();
        document.getElementById('user_id').value = '';
        document.getElementById('user_password').required = true;
        document.getElementById('userForm').action = '<?= BASE_URL ?>/admin/users/store';
        document.getElementById('modalTitle').textContent = 'Create Team Member';
    }

    function editUser(data) {
        clearUserForm();
        document.getElementById('user_id').value = data.id;
        document.getElementById('user_name').value = data.name;
        document.getElementById('user_email').value = data.email;
        document.getElementById('user_phone').value = data.phone;
        document.getElementById('user_role_id').value = data.role_id;
        document.getElementById('user_status').value = data.status || 'active';
        document.getElementById('user_password').required = false; // password not mandatory on edit

        document.getElementById('userForm').action = '<?= BASE_URL ?>/admin/users/update';
        document.getElementById('modalTitle').textContent = 'Edit Team Member';

        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
    }
</script>
