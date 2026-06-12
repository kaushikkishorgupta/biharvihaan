<div class="row g-4">
    <div class="col-lg-7">
        <div class="glass-card">
            <h4 class="mb-2 fw-bold text-white"><i class="fa-solid fa-brush text-primary me-2"></i> Branding Manager</h4>
            <p class="text-secondary small mb-4">Manage site-wide visual assets, primary & secondary brand colors, and official typography.</p>
            
            <form action="<?= BASE_URL ?>/admin/branding/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <!-- General Branding -->
                <div class="mb-4">
                    <h6 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Identity</h6>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Site Name</label>
                        <input type="text" name="site_name" id="site_name_input" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($branding['site_name'] ?? 'Bihar Vihaan') ?>" required>
                    </div>
                </div>

                <!-- Asset Uploads -->
                <div class="mb-4">
                    <h6 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Assets & Logos</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Site Logo (Dark Header)</label>
                            <input type="file" name="logo" class="form-control bg-dark border-secondary text-white" accept="image/*">
                            <?php if(!empty($branding['logo_url'])): ?>
                                <div class="mt-2 small"><a href="<?= BASE_URL . $branding['logo_url'] ?>" target="_blank" class="text-info text-decoration-none"><i class="fa-solid fa-eye me-1"></i> Current Logo</a></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Site Favicon</label>
                            <input type="file" name="favicon" class="form-control bg-dark border-secondary text-white" accept="image/*">
                            <?php if(!empty($branding['favicon_url'])): ?>
                                <div class="mt-2 small"><a href="<?= BASE_URL . $branding['favicon_url'] ?>" target="_blank" class="text-info text-decoration-none"><i class="fa-solid fa-eye me-1"></i> Current Favicon</a></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Footer Logo</label>
                            <input type="file" name="footer_logo" class="form-control bg-dark border-secondary text-white" accept="image/*">
                            <?php if(!empty($branding['footer_logo_url'])): ?>
                                <div class="mt-2 small"><a href="<?= BASE_URL . $branding['footer_logo_url'] ?>" target="_blank" class="text-info text-decoration-none"><i class="fa-solid fa-eye me-1"></i> Current Footer Logo</a></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Hero Overlay Logo</label>
                            <input type="file" name="hero_logo" class="form-control bg-dark border-secondary text-white" accept="image/*">
                            <?php if(!empty($branding['hero_logo_url'])): ?>
                                <div class="mt-2 small"><a href="<?= BASE_URL . $branding['hero_logo_url'] ?>" target="_blank" class="text-info text-decoration-none"><i class="fa-solid fa-eye me-1"></i> Current Hero Logo</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Theme Colors -->
                <div class="mb-4">
                    <h6 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Color Palette (Enterprise Scheme)</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">Primary Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="primary_color" id="primary_color_input" class="form-control form-control-color border-secondary" style="width: 50px; height: 38px;" value="<?= htmlspecialchars($branding['primary_color'] ?? '#0B3D91') ?>">
                                <input type="text" class="form-control bg-dark border-secondary text-white text-uppercase" style="font-size:0.85rem;" id="primary_hex" value="<?= htmlspecialchars($branding['primary_color'] ?? '#0B3D91') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">Accent Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="accent_color" id="accent_color_input" class="form-control form-control-color border-secondary" style="width: 50px; height: 38px;" value="<?= htmlspecialchars($branding['accent_color'] ?? '#FF9933') ?>">
                                <input type="text" class="form-control bg-dark border-secondary text-white text-uppercase" style="font-size:0.85rem;" id="accent_hex" value="<?= htmlspecialchars($branding['accent_color'] ?? '#FF9933') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">Success Alert</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="success_color" id="success_color_input" class="form-control form-control-color border-secondary" style="width: 50px; height: 38px;" value="<?= htmlspecialchars($branding['success_color'] ?? '#10B981') ?>">
                                <input type="text" class="form-control bg-dark border-secondary text-white text-uppercase" style="font-size:0.85rem;" id="success_hex" value="<?= htmlspecialchars($branding['success_color'] ?? '#10B981') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Light Canvas BG</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="background_color" id="background_color_input" class="form-control form-control-color border-secondary" style="width: 50px; height: 38px;" value="<?= htmlspecialchars($branding['background_color'] ?? '#F8F4F0') ?>">
                                <input type="text" class="form-control bg-dark border-secondary text-white text-uppercase" style="font-size:0.85rem;" id="background_hex" value="<?= htmlspecialchars($branding['background_color'] ?? '#F8F4F0') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold">Dark Mode BG</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="dark_mode_bg" id="dark_mode_bg_input" class="form-control form-control-color border-secondary" style="width: 50px; height: 38px;" value="<?= htmlspecialchars($branding['dark_mode_bg'] ?? '#0F172A') ?>">
                                <input type="text" class="form-control bg-dark border-secondary text-white text-uppercase" style="font-size:0.85rem;" id="dark_mode_hex" value="<?= htmlspecialchars($branding['dark_mode_bg'] ?? '#0F172A') ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Typography -->
                <div class="mb-4">
                    <h6 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Typography</h6>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Font Family</label>
                        <select name="font_family" id="font_family_select" class="form-select bg-dark border-secondary text-white">
                            <option value="Inter" <?= ($branding['font_family']??'') === 'Inter' ? 'selected' : '' ?>>Inter (Recommended for SaaS UI)</option>
                            <option value="'Outfit', sans-serif" <?= ($branding['font_family']??'') === "'Outfit', sans-serif" ? 'selected' : '' ?>>Outfit (Modern Display)</option>
                            <option value="'Roboto', sans-serif" <?= ($branding['font_family']??'') === "'Roboto', sans-serif" ? 'selected' : '' ?>>Roboto (Corporate Style)</option>
                            <option value="system-ui" <?= ($branding['font_family']??'') === 'system-ui' ? 'selected' : '' ?>>Native System UI</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-5 rounded-pill"><i class="fa-solid fa-save me-2"></i> Save Branding Config</button>
            </form>
        </div>
    </div>
    
    <!-- Live Preview (Right column) -->
    <div class="col-lg-5">
        <div class="glass-card sticky-top" style="top: 100px; z-index: 10;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-white"><i class="fa-solid fa-desktop me-2"></i> Real-time Preview</h5>
                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-circle-notch fa-spin me-1"></i> Interactive</span>
            </div>
            <p class="text-secondary small mb-4">This panel mimics the active frontend layout using the color palette configurations selected on the left.</p>
            
            <div id="mock-site-container" class="rounded-3 border border-secondary overflow-hidden shadow-lg" style="height: 380px; transition: all 0.3s ease;">
                <!-- Header -->
                <div id="mock-header" class="p-3 d-flex justify-content-between align-items-center text-white" style="transition: all 0.3s ease;">
                    <span id="mock-site-name" class="fw-bold" style="font-family: var(--font-display);"><?= htmlspecialchars($branding['site_name'] ?? 'Bihar Vihaan') ?></span>
                    <div class="d-flex gap-2 text-white small opacity-75">
                        <span>Home</span>
                        <span>Tourism</span>
                        <span>Shop</span>
                    </div>
                </div>
                
                <!-- Hero section -->
                <div class="p-4 text-center bg-dark bg-opacity-10 d-flex flex-column justify-content-center align-items-center" style="height: calc(100% - 110px);">
                    <h4 class="fw-extrabold text-white mb-2" id="mock-hero-title">Experience the Magic of Bihar</h4>
                    <p class="small text-secondary px-3 mb-4">Discover the ancient land of Buddha, beautiful arts, and spiritual heritage.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button id="mock-btn-primary" class="btn btn-sm text-white px-4 rounded-pill shadow-sm" style="transition: all 0.3s ease;">Book Tour</button>
                        <button id="mock-btn-secondary" class="btn btn-sm btn-outline-secondary px-4 rounded-pill">Explore Art</button>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="p-3 bg-dark text-center text-secondary small border-top border-secondary" style="font-size:0.75rem;">
                    &copy; <?= date('Y') ?> <span id="mock-footer-site-name"><?= htmlspecialchars($branding['site_name'] ?? 'Bihar Vihaan') ?></span>. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const primaryInput = document.getElementById('primary_color_input');
        const accentInput = document.getElementById('accent_color_input');
        const successInput = document.getElementById('success_color_input');
        const bgInput = document.getElementById('background_color_input');
        const nameInput = document.getElementById('site_name_input');
        const fontSelect = document.getElementById('font_family_select');
        
        const mockSite = document.getElementById('mock-site-container');
        const mockHeader = document.getElementById('mock-header');
        const mockName = document.getElementById('mock-site-name');
        const mockFooterName = document.getElementById('mock-footer-site-name');
        const mockBtn = document.getElementById('mock-btn-primary');

        function updatePreview() {
            // Hex text updates
            document.getElementById('primary_hex').value = primaryInput.value;
            document.getElementById('accent_hex').value = accentInput.value;
            document.getElementById('success_hex').value = successInput.value;
            document.getElementById('background_hex').value = bgInput.value;
            document.getElementById('dark_mode_hex').value = document.getElementById('dark_mode_bg_input').value;

            // Mock Site styling
            mockHeader.style.backgroundColor = primaryInput.value;
            mockBtn.style.backgroundColor = accentInput.value;
            mockBtn.style.borderColor = accentInput.value;
            mockSite.style.backgroundColor = bgInput.value;
            mockName.textContent = nameInput.value;
            mockFooterName.textContent = nameInput.value;
            
            // Fonts
            mockSite.style.fontFamily = fontSelect.value;
        }

        // Add event listeners
        [primaryInput, accentInput, successInput, bgInput, nameInput, fontSelect].forEach(element => {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        });

        // Initialize preview on load
        updatePreview();
    });
</script>
