<div class="row g-4">
    <div class="col-lg-7">
        <div class="glass-card">
            <h4 class="mb-2 fw-bold text-white"><i class="fa-solid fa-share-nodes text-primary me-2"></i> Social Media Manager</h4>
            <p class="text-secondary small mb-4">Manage outbound links to official social profiles and toggle which icons are visible in the website footer.</p>
            
            <form action="<?= BASE_URL ?>/admin/social/update" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="d-flex flex-column gap-3 mb-4">
                    <?php if(!empty($socials)): foreach($socials as $s): 
                        $platform = htmlspecialchars($s['platform']);
                        $iconClass = 'fa-brands fa-link';
                        if ($platform === 'Facebook') $iconClass = 'fa-brands fa-facebook text-primary';
                        elseif ($platform === 'Instagram') $iconClass = 'fa-brands fa-instagram text-danger';
                        elseif ($platform === 'YouTube') $iconClass = 'fa-brands fa-youtube text-danger';
                        elseif ($platform === 'LinkedIn') $iconClass = 'fa-brands fa-linkedin text-info';
                        elseif ($platform === 'Pinterest') $iconClass = 'fa-brands fa-pinterest text-danger';
                        elseif ($platform === 'Twitter/X') $iconClass = 'fa-brands fa-x-twitter text-white';
                        elseif ($platform === 'WhatsApp') $iconClass = 'fa-brands fa-whatsapp text-success';
                    ?>
                        <div class="p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-white d-flex align-items-center gap-2">
                                    <i class="<?= $iconClass ?> fs-5"></i> <?= $platform ?>
                                </span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input social-toggle cursor-pointer" type="checkbox" name="enabled[<?= $platform ?>]" id="toggle_<?= $platform ?>" data-platform="<?= $platform ?>" <?= $s['is_enabled'] ? 'checked' : '' ?>>
                                    <label class="form-check-label text-secondary small" for="toggle_<?= $platform ?>">Enable Link</label>
                                </div>
                            </div>
                            <div>
                                <input type="url" name="urls[<?= $platform ?>]" id="url_<?= $platform ?>" class="form-control bg-dark border-secondary text-white social-input" placeholder="Enter profile link..." value="<?= htmlspecialchars($s['url'] ?? '') ?>" data-platform="<?= $platform ?>" <?= $s['is_enabled'] ? '' : 'disabled' ?>>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>

                <button type="submit" class="btn btn-primary px-5 rounded-pill"><i class="fa-solid fa-save me-2"></i> Save Social Profiles</button>
            </form>
        </div>
    </div>
    
    <!-- Real-time Social Preview -->
    <div class="col-lg-5">
        <div class="glass-card sticky-top" style="top: 100px; z-index: 10;">
            <h5 class="mb-3 fw-bold text-white"><i class="fa-solid fa-magnifying-glass-chart me-2"></i> Social Preview Card</h5>
            <p class="text-secondary small mb-4 font-primary">See how your connected social profiles will display in the main website's premium footer widget in real-time.</p>
            
            <!-- Footer Preview Widget Mock -->
            <div class="p-4 bg-dark bg-opacity-50 rounded-3 border border-secondary shadow-lg">
                <div class="border-bottom border-secondary pb-3 mb-3 text-center">
                    <h6 class="text-white fw-bold mb-1">BIHAR VIIHAAN</h6>
                    <small class="text-secondary" style="font-size:0.75rem;">Official Tourism & Cultural Portal</small>
                </div>
                
                <p class="text-secondary text-center small mb-3">Connect with us on our verified social media handles for daily cultural updates, itineraries, and festivals.</p>
                
                <!-- Social Icons Row (Previews toggle dynamically) -->
                <div class="d-flex justify-content-center gap-4 py-2 fs-4" id="preview-icons-container">
                    <i class="fa-brands fa-facebook text-primary d-none" id="preview_Facebook" title="Facebook"></i>
                    <i class="fa-brands fa-instagram text-danger d-none" id="preview_Instagram" title="Instagram"></i>
                    <i class="fa-brands fa-youtube text-danger d-none" id="preview_YouTube" title="YouTube"></i>
                    <i class="fa-brands fa-linkedin text-info d-none" id="preview_LinkedIn" title="LinkedIn"></i>
                    <i class="fa-brands fa-pinterest text-danger d-none" id="preview_Pinterest" title="Pinterest"></i>
                    <i class="fa-brands fa-x-twitter text-white d-none" id="preview_Twitter/X" title="Twitter/X"></i>
                    <i class="fa-brands fa-whatsapp text-success d-none" id="preview_WhatsApp" title="WhatsApp"></i>
                </div>
                
                <div class="mt-4 text-center">
                    <span class="badge bg-secondary bg-opacity-25 text-secondary px-3 py-2 font-primary" style="font-size:0.7rem; border: 1px solid rgba(255,255,255,0.05);">Verified Outbound Links</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.social-toggle');
        const inputs = document.querySelectorAll('.social-input');

        function updateIconsPreview() {
            toggles.forEach(t => {
                const platform = t.dataset.platform;
                const previewIcon = document.getElementById('preview_' + platform);
                const inputField = document.getElementById('url_' + platform);
                
                if (previewIcon) {
                    if (t.checked) {
                        previewIcon.classList.remove('d-none');
                        inputField.removeAttribute('disabled');
                    } else {
                        previewIcon.classList.add('d-none');
                        inputField.setAttribute('disabled', 'disabled');
                    }
                }
            });
        }

        // Add change events to toggles
        toggles.forEach(t => {
            t.addEventListener('change', updateIconsPreview);
        });

        // Initialize preview state on page load
        updateIconsPreview();
    });
</script>
