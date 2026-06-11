<!-- Premium Redesigned Footer Section -->
<footer class="py-5 mt-5" style="background-color: var(--bg-footer); border-top: 1px solid var(--border-color);">
    <div class="container">
        <div class="row g-4">
            <!-- Column 1: About Bihar Vihaan -->
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png"
                         alt="Bihar Vihaan Logo"
                         class="site-logo"
                         style="height: 50px;"
                         onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/hero-fallback.jpg';">
                </div>
                <p class="small mb-4 text-justify" style="color: var(--text-secondary); line-height: 1.6;">
                    Bihar Vihaan is a digital storytelling and growth platform focused on Bihar's heritage, tourism, culture, services, and local business ecosystem.
                </p>
                <div class="d-flex gap-3">
                    <a href="https://www.facebook.com/biharvihaan/" target="_blank" class="social-link facebook" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://www.youtube.com/@BiharVihaan" target="_blank" class="social-link youtube" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://www.instagram.com/biharvihaan/" target="_blank" class="social-link instagram" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/company/biharvihaan/" target="_blank" class="social-link linkedin" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="https://in.pinterest.com/biharvihaan/" target="_blank" class="social-link pinterest" title="Pinterest"><i class="fa-brands fa-pinterest"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h4 class="h6 footer-heading mb-3" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Quick Links</h4>
                <ul class="list-unstyled footer-links-list small d-flex flex-column gap-2">
                    <li><a href="<?= BASE_URL ?>/" class="text-decoration-none">Home Page</a></li>
                    <li><a href="<?= BASE_URL ?>/about" class="text-decoration-none">About Us</a></li>
                    <li><a href="<?= BASE_URL ?>/services" class="text-decoration-none">Our Services</a></li>
                    <li><a href="<?= BASE_URL ?>/tourism" class="text-decoration-none">Tourism Catalog</a></li>
                    <li><a href="<?= BASE_URL ?>/bookings" class="text-decoration-none">Bookings Portal</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact Details -->
            <div class="col-lg-2 col-md-6 col-6">
                <h4 class="h6 footer-heading mb-3" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Contact Info</h4>
                <ul class="list-unstyled small d-flex flex-column gap-2" style="color: var(--text-secondary);">
                    <li><i class="fa-solid fa-phone me-1 text-primary"></i> +91 94300 41925</li>
                    <li><i class="fa-solid fa-envelope me-1 text-primary"></i> <a href="mailto:hello@biharvihaan.com" class="text-decoration-none">hello@biharvihaan.com</a></li>
                    <li><i class="fa-solid fa-location-dot me-1 text-primary"></i> Patna, Bihar</li>
                </ul>
            </div>

            <!-- Column 4: Legal Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h4 class="h6 footer-heading mb-3" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Legal Links</h4>
                <ul class="list-unstyled footer-links-list small d-flex flex-column gap-2">
                    <li><a href="#" class="text-decoration-none">Privacy Policy</a></li>
                    <li><a href="#" class="text-decoration-none">Terms &amp; Conditions</a></li>
                    <li><a href="#" class="text-decoration-none">Disclaimer</a></li>
                    <li><a href="<?= BASE_URL ?>/contact" class="text-decoration-none">Contact Us</a></li>
                </ul>
            </div>

            <!-- Column 5: Newsletter -->
            <div class="col-lg-3 col-md-6 col-6">
                <h4 class="h6 footer-heading mb-3" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Newsletter</h4>
                <p class="small mb-3" style="color: var(--text-secondary); line-height: 1.5;">Subscribe for weekly announcements on folk festivals, handloom drops, and local travel circuits.</p>
                <div class="input-group input-group-sm">
                    <input type="email" id="footer-newsletter-email" class="form-control" placeholder="Enter email address" style="border-radius: var(--radius-sm) 0 0 var(--radius-sm) !important;">
                    <button class="btn btn-primary btn-sm px-3" onclick="subscribeNewsletter()" style="border-radius: 0 var(--radius-sm) var(--radius-sm) 0 !important;">Join</button>
                </div>
                <div id="newsletter-response" class="small mt-2" style="display:none;"></div>
            </div>
        </div>
        
        <div class="text-center pt-4 mt-4 border-top border-secondary-subtle small" style="color: var(--text-muted);">
            <p class="mb-0">&copy; <?= date('Y') ?> Bihar Vihaan Enterprise. All Rights Reserved. Built with Bootstrap 5.3 &amp; PHP MVC.</p>
        </div>
    </div>
</footer>

<!-- Floating AI Chat Assistant Widget -->
<div id="ai-chat-widget" style="position: fixed; bottom: 30px; right: 30px; z-index: 2100;">
    <!-- Chat Button -->
    <button id="ai-chat-btn" class="btn btn-primary d-flex align-items-center justify-content-center shadow-lg" style="width: 60px; height: 60px; border-radius: 50%;">
        <i class="fa-solid fa-comments fs-4"></i>
    </button>

    <!-- Chat Window Card -->
    <div id="ai-chat-window" class="card shadow-lg" style="display: none; position: absolute; bottom: 80px; right: 0; width: 350px; background: var(--bg-card); border: 1px solid var(--border-color); overflow: hidden; border-radius: 16px;">
        <div class="card-header d-flex justify-content-between align-items-center border-bottom py-3" style="background-color: var(--bg-soft); color: var(--text-main); border-color: var(--border-color) !important;">
            <div class="d-flex align-items-center gap-2">
                <span style="color:var(--primary);"><i class="fa-solid fa-robot"></i></span>
                <span class="fw-bold small" style="color: var(--text-main);">Bihar Vihaan AI Guide</span>
            </div>
            <button id="close-ai-chat" class="btn-close" style="font-size:0.8rem;"></button>
        </div>
        
        <div id="ai-chat-logs" class="card-body overflow-y-auto" style="height: 250px; background: transparent; font-size: 0.85rem;">
            <div class="mb-2 text-start">
                <div class="p-2 rounded" style="max-width: 85%; background: var(--bg-soft); color: var(--text-secondary);">
                    Namaste! I am your AI Tourism Guide. Ask me about **Bodh Gaya**, **Nalanda**, **Rajgir**, or **Chhath Puja**!
                </div>
            </div>
        </div>

        <div class="card-footer p-2" style="background-color: var(--bg-soft); border-top: 1px solid var(--border-color);">
            <div class="input-group input-group-sm">
                <input type="text" id="ai-chat-input" class="form-control" placeholder="Ask AI Guide...">
                <button class="btn btn-primary" id="ai-send-btn">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts bundle (Bootstrap, Swiper, AOS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script src="<?= BASE_URL ?>/assets/js/payments.js"></script>

<!-- Initialize third-party plugins -->
<script>
    AOS.init({
        duration: 800,
        once: true
    });

    // PWA Service Worker Registration
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?= BASE_URL ?>/service-worker.js')
                .then(reg => console.log('SW Active', reg.scope))
                .catch(err => console.error('SW Active failure', err));
        });
    }

    // AI Chat Assistant Widget Interactivity
    const chatBtn = document.getElementById('ai-chat-btn');
    const chatWindow = document.getElementById('ai-chat-window');
    const closeChat = document.getElementById('close-ai-chat');
    const sendBtn = document.getElementById('ai-send-btn');
    const chatInput = document.getElementById('ai-chat-input');
    const chatLogs = document.getElementById('ai-chat-logs');

    chatBtn.addEventListener('click', () => {
        chatWindow.style.display = (chatWindow.style.display === 'none') ? 'block' : 'none';
    });

    closeChat.addEventListener('click', () => {
        chatWindow.style.display = 'none';
    });

    function appendMessage(text, isUser = true) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `mb-2 text-${isUser ? 'end' : 'start'}`;
        msgDiv.innerHTML = `
            <div class="p-2 rounded d-inline-block" style="max-width: 85%; ${isUser ? 'background:var(--primary); color:white;' : 'background:var(--bg-soft); color:var(--text-secondary);'}">
                ${text}
            </div>
        `;
        chatLogs.appendChild(msgDiv);
        chatLogs.scrollTop = chatLogs.scrollHeight;
    }

    sendBtn.addEventListener('click', processChatInput);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') processChatInput();
    });

    function processChatInput() {
        const query = chatInput.value.trim();
        if (query === '') return;

        appendMessage(query, true);
        chatInput.value = '';

        // Simulate AI processing loader
        const loader = document.createElement('div');
        loader.id = 'ai-loader';
        loader.className = 'text-start mb-2 small text-muted';
        loader.textContent = 'AI Guide typing...';
        chatLogs.appendChild(loader);

        const formData = new FormData();
        formData.append('message', query);

        fetch(window.baseUrl + '/api/chat', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('ai-loader').remove();
            if (data.success) {
                appendMessage(data.reply, false);
            } else {
                appendMessage("Sorry, I encountered an issue. Please try again.", false);
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById('ai-loader').remove();
            appendMessage("Pranam! I am currently operating in offline mode. Let's explore Bihar tourism!", false);
        });
    }

    // Newsletter Subscribe form handler
    function subscribeNewsletter() {
        const email = document.getElementById('footer-newsletter-email').value;
        const resDiv = document.getElementById('newsletter-response');
        if (!email) return;

        const formData = new FormData();
        formData.append('email', email);

        fetch('<?= BASE_URL ?>/api/subscribe', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            resDiv.style.display = 'block';
            resDiv.className = `small mt-2 text-${res.success ? 'success' : 'danger'}`;
            resDiv.textContent = res.message;
            if (res.success) {
                document.getElementById('footer-newsletter-email').value = '';
            }
        })
        .catch(err => {
            console.error(err);
            resDiv.style.display = 'block';
            resDiv.className = 'small mt-2 text-danger';
            resDiv.textContent = 'Subscription request failed.';
        });
    }
</script>
</body>
</html>
