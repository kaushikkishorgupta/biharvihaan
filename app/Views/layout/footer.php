    <!-- Footer Section Redesign -->
    <footer style="background-color: #050816; color: #ffffff; border-top: 1px solid #1f2937; padding-top: 80px; padding-bottom: 20px;">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="mb-4">
                        <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Bihar Vihaan" style="height: 60px; object-fit: contain;" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                    </div>
                    <p class="mb-4 text-white" style="line-height: 1.8;">
                        A premium enterprise platform dedicated to preserving Bihar's heritage, empowering local artisans, and providing world-class tourism experiences.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="https://www.facebook.com/biharvihaan/" target="_blank" rel="noopener noreferrer" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#1877F2'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='none'"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/biharvihaan/" target="_blank" rel="noopener noreferrer" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='none'"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@BiharVihaan" target="_blank" rel="noopener noreferrer" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#FF0000'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='none'"><i class="fa-brands fa-youtube"></i></a>
                        <a href="https://www.linkedin.com/company/biharvihaan/posts/?feedView=all" target="_blank" rel="noopener noreferrer" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#0A66C2'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='none'"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="https://in.pinterest.com/biharvihaan/" target="_blank" rel="noopener noreferrer" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#E60023'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='none'"><i class="fa-brands fa-pinterest-p"></i></a>
                        <a href="https://wa.me/919430041925" target="_blank" rel="noopener noreferrer" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#25D366'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='none'"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4 fw-bold font-outfit">Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="<?= BASE_URL ?>/" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/tourism" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Tourism</a></li>
                        <li><a href="<?= BASE_URL ?>/marketplace" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Marketplace</a></li>
                        <li><a href="<?= BASE_URL ?>/directory" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Directory</a></li>
                        <li><a href="<?= BASE_URL ?>/about" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">About Us</a></li>
                        <li>
                            <?php if (!\App\Core\Auth::check()): ?>
                                <a href="<?= BASE_URL ?>/login" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">
                                    <i class="bi bi-shield-lock me-2"></i>Admin Login
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/admin/dashboard" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">
                                    <i class="bi bi-speedometer2 me-2"></i>Admin Portal
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4 fw-bold font-outfit">Support</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="<?= BASE_URL ?>/contact" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Contact Us</a></li>
                        <li><a href="#" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">FAQs</a></li>
                        <li><a href="#" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Shipping Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Return Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-white hover-white" style="transition: 0.3s;">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white mb-4 fw-bold font-outfit">Newsletter</h5>
                    <p class="mb-4 text-white">Subscribe to receive updates on new authentic crafts, special offers, and Bihar tourism news.</p>
                    <form action="<?= BASE_URL ?>/newsletter/subscribe" method="POST" class="d-flex flex-column gap-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control border-0 px-4 py-3" placeholder="Enter your email" style="background: rgba(255,255,255,0.05); color: #fff; border-radius: 8px 0 0 8px;" required>
                            <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 0 8px 8px 0;">Subscribe</button>
                        </div>
                    </form>
                    <div class="mt-4 pt-3 border-top border-secondary d-flex align-items-center gap-3">
                        <i class="fa-solid fa-envelope text-primary fs-4"></i>
                        <div>
                            <small class="d-block text-white">Email Support</small>
                            <a href="mailto:support@biharvihaan.com" class="text-white text-decoration-none fw-bold">support@biharvihaan.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 border-top border-secondary mt-5 text-center text-md-start align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0 text-white small">&copy; <?= date('Y') ?> Bihar Vihaan Enterprise. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <i class="fa-brands fa-cc-visa text-white fs-4 me-2"></i>
                    <i class="fa-brands fa-cc-mastercard text-white fs-4 me-2"></i>
                    <i class="fa-brands fa-cc-paypal text-white fs-4 me-2"></i>
                    <i class="fa-brands fa-cc-stripe text-white fs-4"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 50
                });
            }
        });
    </script>
    
    <!-- Global App JS -->
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>

    <style>
        footer .hover-white:hover {
            color: #ffffff !important;
        }
    </style>

    <!-- Global Search Modal -->
    <div class="modal fade" id="globalSearchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top" style="margin-top: 80px;">
            <div class="modal-content border-0 shadow-lg rounded-4" style="background-color: var(--bg-card);">
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center bg-surface border rounded-pill px-3 py-2 mb-3">
                        <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
                        <input type="text" id="global-search-input" class="form-control border-0 shadow-none bg-transparent" placeholder="Search Destinations, Businesses, Products..." autocomplete="off">
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="global-search-results" class="list-group list-group-flush rounded-3">
                        <!-- Results injected here via AJAX -->
                        <div class="text-center text-muted small py-3" id="global-search-placeholder">Start typing to search...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('global-search-input');
            const resultsContainer = document.getElementById('global-search-results');
            const placeholder = document.getElementById('global-search-placeholder');

            if(searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();
                    
                    if(query.length < 2) {
                        resultsContainer.innerHTML = '';
                        resultsContainer.appendChild(placeholder);
                        placeholder.style.display = 'block';
                        return;
                    }

                    debounceTimer = setTimeout(() => {
                        fetch('<?= BASE_URL ?>/search/ajax?q=' + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(data => {
                                resultsContainer.innerHTML = '';
                                if(data.length > 0) {
                                    data.forEach(item => {
                                        resultsContainer.innerHTML += `
                                            <a href="${item.url}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-bottom">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">${item.name}</h6>
                                                </div>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">${item.type}</span>
                                            </a>
                                        `;
                                    });
                                } else {
                                    resultsContainer.innerHTML = '<div class="text-center text-muted small py-3">No results found for "'+query+'"</div>';
                                }
                            })
                            .catch(err => console.error(err));
                    }, 300);
                });
            }
        });
    </script>
</body>
</html>
