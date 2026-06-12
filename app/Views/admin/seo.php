<div class="row g-4">
    <!-- SEO Configurations List -->
    <div class="col-xl-7">
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-magnifying-glass-chart text-primary me-2"></i> SEO Manager</h4>
                <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#seoModal" onclick="clearSeoForm()"><i class="fa-solid fa-plus me-1"></i> Add Page SEO</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-secondary border-bottom border-secondary">
                            <th>Route</th>
                            <th>Meta Title</th>
                            <th>Robots</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($seoSettings)): ?>
                            <tr><td colspan="4" class="text-center py-4 text-secondary">No SEO routes configured.</td></tr>
                        <?php else: foreach($seoSettings as $s): ?>
                            <tr>
                                <td class="py-3 fw-bold text-white"><?= htmlspecialchars($s['route']) ?></td>
                                <td class="py-3 text-secondary"><?= htmlspecialchars($s['meta_title'] ?? '') ?></td>
                                <td class="py-3"><span class="badge bg-secondary"><?= htmlspecialchars($s['robots_settings'] ?? 'index, follow') ?></span></td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-light" onclick="editSeo(<?= htmlspecialchars(json_encode($s)) ?>)"><i class="fa-solid fa-edit"></i></button>
                                        <form action="<?= BASE_URL ?>/admin/seo/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove SEO settings for this page?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
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
    </div>

    <!-- Live Snippet Previews (Google & Social card) -->
    <div class="col-xl-5">
        <div class="glass-card sticky-top" style="top: 100px; z-index: 10;">
            <h5 class="mb-3 fw-bold text-white"><i class="fa-solid fa-eye me-2"></i> Live Snippet Previews</h5>
            
            <!-- Google Search Preview -->
            <div class="p-3 mb-4 rounded-3 border border-secondary" style="background:#f8f9fa;">
                <small class="text-muted d-block mb-2 font-primary" style="font-size:0.75rem;"><i class="fa-brands fa-google text-primary me-1"></i> Google Search Engine Mockup</small>
                <div class="google-preview" style="font-family: Arial, sans-serif; text-align: left;">
                    <div style="font-size: 12px; color: #202124; margin-bottom: 2px;">
                        www.biharvihaan.com<span id="google-route-preview">/</span>
                    </div>
                    <a href="#" style="font-size: 19px; color: #1a0dab; text-decoration: none; display: block; margin-bottom: 4px;" id="google-title-preview">Bihar Vihaan - Official Tourism Portal</a>
                    <p style="font-size: 14px; color: #4d5156; line-height: 1.4; margin: 0;" id="google-desc-preview">Explore historical sites, festivals, arts & crafts, and book custom tour packages in Bihar.</p>
                </div>
            </div>

            <!-- Facebook Social Preview -->
            <div class="rounded-3 border border-secondary overflow-hidden bg-white text-dark shadow-sm">
                <small class="text-muted d-block p-2 border-bottom font-primary" style="font-size:0.75rem;"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook Open Graph Mockup</small>
                <div id="fb-image-preview" style="height: 180px; background: #e9ebee url('https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=600') no-repeat center center; background-size: cover;"></div>
                <div class="p-3 font-primary">
                    <div class="text-uppercase text-muted" style="font-size: 10px; letter-spacing: 0.5px;">biharvihaan.com</div>
                    <div class="fw-bold mb-1" style="font-size: 14px; line-height: 1.3;" id="fb-title-preview">Bihar Vihaan - Official Tourism Portal</div>
                    <div class="text-muted small text-truncate-2" style="font-size: 12px; line-height: 1.4;" id="fb-desc-preview">Explore historical sites, festivals, arts & crafts, and book custom tour packages in Bihar.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEO Add/Edit Modal -->
<div class="modal fade" id="seoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content bg-dark border border-secondary text-white" id="seoForm" action="<?= BASE_URL ?>/admin/seo/store" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Configure Page SEO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="seo_id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Site Page Route</label>
                        <input type="text" name="route" id="seo_route" class="form-control bg-dark border-secondary text-white" placeholder="e.g. /tourism, /shop, /about" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Canonical URL (Override)</label>
                        <input type="url" name="canonical_url" id="seo_canonical" class="form-control bg-dark border-secondary text-white" placeholder="https://example.com/page">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Meta Title</label>
                        <input type="text" name="meta_title" id="seo_meta_title" class="form-control bg-dark border-secondary text-white" placeholder="Enter page meta title..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Keywords (Comma Separated)</label>
                        <input type="text" name="keywords" id="seo_keywords" class="form-control bg-dark border-secondary text-white" placeholder="bihar tourism, Bodh Gaya, handicraft">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Meta Description</label>
                        <textarea name="meta_description" id="seo_meta_description" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="Write description for search rankings..." required></textarea>
                    </div>

                    <!-- Open Graph -->
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Open Graph Image (Upload)</label>
                        <input type="file" name="og_image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">OR Open Graph Image URL</label>
                        <input type="text" name="og_image_url" id="seo_og_image_url" class="form-control bg-dark border-secondary text-white" placeholder="https://image-path.jpg">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Open Graph Title (Social Share Title)</label>
                        <input type="text" name="og_title" id="seo_og_title" class="form-control bg-dark border-secondary text-white" placeholder="Social display title...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Twitter Card Display</label>
                        <select name="twitter_card" id="seo_twitter_card" class="form-select bg-dark border-secondary text-white">
                            <option value="summary">Summary Small Card</option>
                            <option value="summary_large_image" selected>Summary Large Card</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Open Graph Description (Social Share Description)</label>
                        <textarea name="og_description" id="seo_og_description" rows="2" class="form-control bg-dark border-secondary text-white" placeholder="Social description..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Robots Policy Directive</label>
                        <select name="robots_settings" id="seo_robots" class="form-select bg-dark border-secondary text-white">
                            <option value="index, follow">index, follow (Standard)</option>
                            <option value="noindex, follow">noindex, follow</option>
                            <option value="index, nofollow">index, nofollow</option>
                            <option value="noindex, nofollow">noindex, nofollow</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Sitemap Priority / Freq</label>
                        <input type="text" name="sitemap_settings" id="seo_sitemap" class="form-control bg-dark border-secondary text-white" placeholder="weekly, 0.8">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">JSON-LD Schema Markup (Structured Data)</label>
                        <textarea name="schema_markup" id="seo_schema" rows="3" class="form-control bg-dark border-secondary text-white font-monospace" placeholder="{'@context': 'https://schema.org', '@type': 'WebPage'}"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save SEO Config</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const routeIn = document.getElementById('seo_route');
        const titleIn = document.getElementById('seo_meta_title');
        const descIn = document.getElementById('seo_meta_description');
        const ogUrlIn = document.getElementById('seo_og_image_url');
        
        const googleRoute = document.getElementById('google-route-preview');
        const googleTitle = document.getElementById('google-title-preview');
        const googleDesc = document.getElementById('google-desc-preview');
        const fbTitle = document.getElementById('fb-title-preview');
        const fbDesc = document.getElementById('fb-desc-preview');
        const fbImage = document.getElementById('fb-image-preview');

        function updatePreview() {
            googleRoute.textContent = routeIn.value ? '/' + routeIn.value.replace(/^\/+/, '') : '/';
            googleTitle.textContent = titleIn.value || 'Bihar Vihaan - Official Tourism Portal';
            googleDesc.textContent = descIn.value || 'Explore historical sites, festivals, arts & crafts, and book custom tour packages in Bihar.';
            
            fbTitle.textContent = document.getElementById('seo_og_title').value || titleIn.value || 'Bihar Vihaan - Official Tourism Portal';
            fbDesc.textContent = document.getElementById('seo_og_description').value || descIn.value || 'Explore historical sites, festivals, arts & crafts, and book custom tour packages in Bihar.';
            
            if (ogUrlIn.value) {
                fbImage.style.backgroundImage = `url('${ogUrlIn.value}')`;
            } else {
                fbImage.style.backgroundImage = "url('https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=600')";
            }
        }

        [routeIn, titleIn, descIn, ogUrlIn, document.getElementById('seo_og_title'), document.getElementById('seo_og_description')].forEach(input => {
            input.addEventListener('input', updatePreview);
        });
    });

    function clearSeoForm() {
        document.getElementById('seoForm').reset();
        document.getElementById('seo_id').value = '';
        document.getElementById('seoForm').action = '<?= BASE_URL ?>/admin/seo/store';
        document.getElementById('modalTitle').textContent = 'Configure Page SEO';
    }

    function editSeo(data) {
        clearSeoForm();
        document.getElementById('seo_id').value = data.id;
        document.getElementById('seo_route').value = data.route;
        document.getElementById('seo_canonical').value = data.canonical_url;
        document.getElementById('seo_meta_title').value = data.meta_title;
        document.getElementById('seo_keywords').value = data.keywords;
        document.getElementById('seo_meta_description').value = data.meta_description;
        document.getElementById('seo_og_image_url').value = data.open_graph_image;
        document.getElementById('seo_og_title').value = data.og_title;
        document.getElementById('seo_og_description').value = data.og_description;
        document.getElementById('seo_twitter_card').value = data.twitter_card || 'summary_large_image';
        document.getElementById('seo_robots').value = data.robots_settings || 'index, follow';
        document.getElementById('seo_sitemap').value = data.sitemap_settings;
        document.getElementById('seo_schema').value = data.schema_markup;

        document.getElementById('seoForm').action = '<?= BASE_URL ?>/admin/seo/update';
        document.getElementById('modalTitle').textContent = 'Update Page SEO';
        
        // Trigger preview update
        const event = new Event('input');
        document.getElementById('seo_route').dispatchEvent(event);

        const modal = new bootstrap.Modal(document.getElementById('seoModal'));
        modal.show();
    }
</script>
