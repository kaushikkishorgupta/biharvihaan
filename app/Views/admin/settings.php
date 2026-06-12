<div class="row g-4">
    <div class="col-12">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Global Settings -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h4 class="mb-0 fw-bold">Global Settings</h4>
                <p class="text-muted mb-0 small">Manage site-wide configuration.</p>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/admin/settings/update_global" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    
                    <h6 class="fw-bold mb-3">General</h6>
                    <div class="mb-3">
                        <label>Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($globalSettings['site_name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label>Logo URL</label>
                        <input type="text" name="site_logo" class="form-control" value="<?= htmlspecialchars($globalSettings['site_logo'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label>Favicon URL</label>
                        <input type="text" name="site_favicon" class="form-control" value="<?= htmlspecialchars($globalSettings['site_favicon'] ?? '') ?>">
                    </div>

                    <h6 class="fw-bold mb-3 mt-4">Contact Info</h6>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?= htmlspecialchars($globalSettings['contact_phone'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?= htmlspecialchars($globalSettings['contact_email'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="contact_address" class="form-control" rows="2"><?= htmlspecialchars($globalSettings['contact_address'] ?? '') ?></textarea>
                    </div>

                    <h6 class="fw-bold mb-3 mt-4">Social Links</h6>
                    <div class="mb-3">
                        <label>Facebook</label>
                        <input type="text" name="social_facebook" class="form-control" value="<?= htmlspecialchars($globalSettings['social_facebook'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label>Twitter/X</label>
                        <input type="text" name="social_twitter" class="form-control" value="<?= htmlspecialchars($globalSettings['social_twitter'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label>Instagram</label>
                        <input type="text" name="social_instagram" class="form-control" value="<?= htmlspecialchars($globalSettings['social_instagram'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Global Settings</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SEO Manager -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold">SEO Manager</h4>
                    <p class="text-muted mb-0 small">Manage meta tags for routes.</p>
                </div>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSeoModal"><i class="fa-solid fa-plus"></i> Add SEO</button>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Route</th>
                                <th>Meta Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($seoSettings)): ?>
                                <tr><td colspan="3" class="text-center text-muted">No SEO rules found.</td></tr>
                            <?php endif; ?>
                            <?php if(isset($seoSettings)) { foreach($seoSettings as $seo): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($seo['route'] ?? '') ?></td>
                                <td><?= htmlspecialchars($seo['meta_title'] ?? '') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSeoModal<?= $seo['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                                    <form action="<?= BASE_URL ?>/admin/settings/delete_seo" method="POST" class="d-inline" onsubmit="return confirm('Delete this SEO rule?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $seo['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit SEO Modal -->
                            <div class="modal fade" id="editSeoModal<?= $seo['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form class="modal-content" action="<?= BASE_URL ?>/admin/settings/update_seo" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit SEO Rule</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $seo['id'] ?>">
                                            
                                            <div class="mb-3">
                                                <label>Route (e.g., /home, /tourism)</label>
                                                <input type="text" name="route" class="form-control" value="<?= htmlspecialchars($seo['route'] ?? '') ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Meta Title</label>
                                                <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($seo['meta_title'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label>Meta Description</label>
                                                <textarea name="meta_description" class="form-control" rows="3"><?= htmlspecialchars($seo['meta_description'] ?? '') ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Open Graph Image URL</label>
                                                <input type="text" name="open_graph_image" class="form-control" value="<?= htmlspecialchars($seo['open_graph_image'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add SEO Modal -->
<div class="modal fade" id="addSeoModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= BASE_URL ?>/admin/settings/store_seo" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add SEO Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="mb-3">
                    <label>Route (e.g., /home, /tourism)</label>
                    <input type="text" name="route" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label>Open Graph Image URL</label>
                    <input type="text" name="open_graph_image" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add SEO Rule</button>
            </div>
        </form>
    </div>
</div>