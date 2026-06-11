    <!-- Footer Section Redesign -->
    <footer style="background-color: #050816; color: #9ca3af; border-top: 1px solid #1f2937; padding-top: 80px; padding-bottom: 20px;">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <h3 class="mb-4 fw-bold" style="color: #ffffff; font-family: 'Outfit', sans-serif;">
                        <span style="color: #FF9933;">Bihar</span> Vihaan
                    </h3>
                    <p class="mb-4" style="line-height: 1.8;">
                        A premium enterprise platform dedicated to preserving Bihar's heritage, empowering local artisans, and providing world-class tourism experiences.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#1877F2'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#1DA1F2'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#E1306C'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; transition: 0.3s;" onmouseover="this.style.background='#0A66C2'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4 fw-bold font-outfit">Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="<?= BASE_URL ?>/" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/tourism" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Tourism</a></li>
                        <li><a href="<?= BASE_URL ?>/marketplace" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Marketplace</a></li>
                        <li><a href="<?= BASE_URL ?>/directory" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Directory</a></li>
                        <li><a href="<?= BASE_URL ?>/about" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">About Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white mb-4 fw-bold font-outfit">Support</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="<?= BASE_URL ?>/contact" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Contact Us</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">FAQs</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Shipping Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Return Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-white" style="transition: 0.3s;">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white mb-4 fw-bold font-outfit">Newsletter</h5>
                    <p class="mb-4">Subscribe to receive updates on new authentic crafts, special offers, and Bihar tourism news.</p>
                    <form action="#" method="POST" class="d-flex flex-column gap-3">
                        <div class="input-group">
                            <input type="email" class="form-control border-0 px-4 py-3" placeholder="Enter your email" style="background: rgba(255,255,255,0.05); color: #fff; border-radius: 8px 0 0 8px;">
                            <button class="btn btn-primary px-4 fw-bold" style="border-radius: 0 8px 8px 0;">Subscribe</button>
                        </div>
                    </form>
                    <div class="mt-4 pt-3 border-top border-secondary d-flex align-items-center gap-3">
                        <i class="fa-solid fa-envelope text-primary fs-4"></i>
                        <div>
                            <small class="d-block text-muted">Email Support</small>
                            <a href="mailto:support@biharvihaan.com" class="text-white text-decoration-none fw-bold">support@biharvihaan.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 border-top border-secondary mt-5 text-center text-md-start align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0 text-muted small">&copy; <?= date('Y') ?> Bihar Vihaan Enterprise. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <i class="fa-brands fa-cc-visa text-muted fs-4 me-2"></i>
                    <i class="fa-brands fa-cc-mastercard text-muted fs-4 me-2"></i>
                    <i class="fa-brands fa-cc-paypal text-muted fs-4 me-2"></i>
                    <i class="fa-brands fa-cc-stripe text-muted fs-4"></i>
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
</body>
</html>
