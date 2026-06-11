<?php include __DIR__ . '/layout/header.php'; ?>

<!-- Custom Marketplace CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/marketplace.css">

<?php if ($view_mode === 'catalog'): ?>

    <!-- Cinematic Hero -->
    <section class="shop-hero">
        <div class="shop-hero-content" data-aos="fade-up">
            <h1>Discover Authentic Bihar Crafts</h1>
            <p>Support local artisans and explore Bihar's rich artistic traditions through handcrafted products and heritage collections.</p>
            
            <a href="#shop-main" class="btn-shop-primary me-3">Shop Now <i class="fa-solid fa-arrow-right ms-2"></i></a>
            
            <div class="trust-badges">
                <span class="trust-badge"><i class="fa-solid fa-check-circle"></i> Authentic Bihar Products</span>
                <span class="trust-badge"><i class="fa-solid fa-hand-sparkles"></i> Handmade Verified</span>
                <span class="trust-badge"><i class="fa-solid fa-shield-halved"></i> Secure Payments</span>
                <span class="trust-badge"><i class="fa-solid fa-users"></i> Artisan Direct</span>
            </div>
        </div>
    </section>

    <!-- Support Local Artisans Banner -->
    <div class="container my-5">
        <div class="p-4 rounded-4 text-center" style="background-color: var(--shop-bg); border: 1px solid var(--shop-border);">
            <h3 style="color: var(--shop-primary); font-family: 'Outfit', sans-serif; font-weight: 700;">Every Purchase Supports Local Artisans</h3>
            <p class="text-muted mb-0">Bihar Vihaan empowers artists, preserves traditional crafts, and creates sustainable livelihoods directly in our villages.</p>
        </div>
    </div>

    <!-- Main Shop Layout -->
    <div class="container-fluid px-4 mb-5" id="shop-main">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-lg-3 col-xl-2 mb-4">
                <div class="shop-sidebar">
                    <h5 class="filter-title">Categories</h5>
                    <ul class="filter-list mb-4">
                        <?php foreach($categories as $cat): ?>
                            <li><a href="#" class="cat-filter <?= $cat === 'All' ? 'active' : '' ?>" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></a></li>
                        <?php endforeach; ?>
                    </ul>

                    <h5 class="filter-title">Sort By</h5>
                    <select class="form-select form-select-sm mb-4" id="sort-select">
                        <option value="newest">New Arrivals</option>
                        <option value="popular">Highest Rated</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                    
                    <h5 class="filter-title">Trust Filters</h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="handmadeCheck" checked>
                        <label class="form-check-label text-muted small" for="handmadeCheck">Handmade Only</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="verifiedCheck" checked>
                        <label class="form-check-label text-muted small" for="verifiedCheck">Verified Artisan</label>
                    </div>
                </div>
            </aside>

            <!-- Product Grid -->
            <main class="col-lg-9 col-xl-10">
                <div class="shop-masonry" id="shop-masonry">
                    <?php foreach($initialProducts as $p): ?>
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                                <div class="product-actions">
                                    <button class="product-btn" onclick="toggleWishlist(<?= $p['id'] ?>)"><i class="fa-regular fa-heart"></i></button>
                                    <button class="product-btn quick-view" onclick="openQuickView(<?= $p['id'] ?>)"><i class="fa-solid fa-eye"></i> Quick View</button>
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?= htmlspecialchars($p['category']) ?></div>
                                <h4 class="product-title"><?= htmlspecialchars($p['name']) ?></h4>
                                <div class="product-artisan"><i class="fa-solid fa-user-pen"></i> By <?= htmlspecialchars($p['artisan_name'] ?? 'Verified Artisan') ?></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="product-price">₹<?= number_format($p['price'], 2) ?></div>
                                    <div class="text-warning small"><i class="fa-solid fa-star"></i> <?= $p['rating'] ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="shop-loader" id="shop-loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Artisan Spotlight -->
    <section class="container mb-5">
        <h2 class="text-center mb-5" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--shop-primary);">Featured Artisan Spotlight</h2>
        <div class="row g-4 justify-content-center">
            <?php foreach($artisans as $a): ?>
            <div class="col-md-3">
                <div class="artisan-card">
                    <img src="<?= htmlspecialchars($a['image_url']) ?>" class="artisan-img" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                    <h5 class="mb-1 fw-bold"><?= htmlspecialchars($a['name']) ?></h5>
                    <p class="small text-muted mb-2"><?= htmlspecialchars($a['specialization']) ?></p>
                    <span class="badge bg-light text-dark border"><i class="fa-solid fa-certificate text-warning"></i> Verified Artisan</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Quick View Modal -->
    <div class="quickview-modal" id="quickview-modal">
        <div class="quickview-content">
            <button class="quickview-close" id="qv-close"><i class="fa-solid fa-xmark"></i></button>
            <div class="qv-img-side">
                <img src="" id="qv-img" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
            </div>
            <div class="qv-info-side">
                <h3 id="qv-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--shop-primary);">Product Name</h3>
                <p class="fs-4 fw-bold" style="color: var(--shop-accent);" id="qv-price">₹0.00</p>
                <p class="text-muted" id="qv-desc">Description</p>
                
                <hr class="my-4">
                
                <div class="mb-3">
                    <strong><i class="fa-solid fa-user-pen"></i> Artisan:</strong> <span id="qv-artisan"></span><br>
                    <small class="text-muted" id="qv-artisan-bio"></small>
                </div>
                <div class="mb-3">
                    <strong><i class="fa-solid fa-location-dot"></i> Region:</strong> <span id="qv-location"></span>
                </div>
                <div class="mb-4">
                    <strong><i class="fa-solid fa-leaf"></i> Materials:</strong> <span id="qv-materials"></span>
                </div>

                <form action="<?= BASE_URL ?>/marketplace/cart/add" method="POST" class="mt-auto d-flex gap-3">
                    <input type="hidden" name="product_id" id="qv-product-id" value="">
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="10" style="width: 80px;">
                    <button type="submit" class="btn-shop-primary flex-grow-1"><i class="fa-solid fa-cart-plus me-2"></i> Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/assets/js/marketplace.js"></script>

<?php elseif ($view_mode === 'cart'): ?>
    
    <!-- Cart UI Redesigned -->
    <div class="container" style="margin-top: 120px; margin-bottom: 80px;">
        <h2 class="mb-4" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--shop-primary);">Your Shopping Cart</h2>
        
        <?php if (empty($items)): ?>
            <div class="text-center p-5 bg-white rounded shadow-sm border">
                <i class="fa-solid fa-cart-shopping fs-1 text-muted mb-3"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Explore authentic Bihar crafts and add items to your cart.</p>
                <a href="<?= BASE_URL ?>/shop" class="btn-shop-primary mt-3">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-0">
                            <table class="table mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4 py-3">Product</th>
                                        <th class="border-0 py-3">Price</th>
                                        <th class="border-0 py-3">Qty</th>
                                        <th class="border-0 pe-4 py-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($item['image_url']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 fw-semibold text-secondary">₹<?= number_format($item['price'], 2) ?></td>
                                        <td class="py-4">
                                            <span class="badge bg-light text-dark border"><?= $item['quantity'] ?></span>
                                        </td>
                                        <td class="pe-4 py-4 text-end">
                                            <a href="<?= BASE_URL ?>/marketplace/cart/remove/<?= $item['product_id'] ?>" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal</span>
                                <span>₹<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>Estimated GST</span>
                                <span>₹<?= number_format($gstAmount, 2) ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-5" style="color: var(--shop-accent);">₹<?= number_format($total, 2) ?></span>
                            </div>
                            <a href="<?= BASE_URL ?>/marketplace/checkout" class="btn-shop-primary w-100 text-center text-white">Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i></a>
                            
                            <div class="mt-4 text-center text-muted small">
                                <i class="fa-solid fa-shield-halved me-1"></i> Secure Checkout Guaranteed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php elseif ($view_mode === 'checkout'): ?>

    <!-- Checkout UI Redesigned -->
    <div class="container" style="margin-top: 120px; margin-bottom: 80px;">
        <h2 class="mb-4" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--shop-primary);">Secure Checkout</h2>
        
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-4">Billing & Shipping Details</h5>
                    <form id="checkoutForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="billing_name" value="<?= htmlspecialchars(App\Core\Session::get('user_name') ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="billing_email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="billing_phone" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Complete Shipping Address</label>
                                <textarea class="form-control" name="billing_address" rows="3" required></textarea>
                            </div>
                        </div>
                        <div id="checkoutError" class="alert alert-danger mt-3 d-none"></div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-4">Your Order</h5>
                    
                    <?php foreach($items as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark border me-2"><?= $item['quantity'] ?>x</span>
                                <span class="text-truncate" style="max-width: 180px;"><?= htmlspecialchars($item['name']) ?></span>
                            </div>
                            <span class="text-secondary fw-semibold">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>GST</span>
                        <span>₹<?= number_format($gstAmount, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-3 border-bottom">
                        <span class="fw-bold fs-5">Total to Pay</span>
                        <span class="fw-bold fs-5" style="color: var(--shop-accent);">₹<?= number_format($total, 2) ?></span>
                    </div>
                    
                    <button type="button" id="payBtn" class="btn-shop-primary w-100 text-center"><i class="fa-solid fa-lock me-2"></i> Pay ₹<?= number_format($total, 2) ?></button>
                    
                    <div class="text-center mt-3">
                        <img src="<?= BASE_URL ?>/assets/images/razorpay-logo.svg" alt="Razorpay Secured" height="25" onerror="this.style.display='none'">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('payBtn').addEventListener('click', function() {
            const form = document.getElementById('checkoutForm');
            if(!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const btn = this;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            btn.disabled = true;
            document.getElementById('checkoutError').classList.add('d-none');
            
            const formData = new FormData(form);
            
            fetch('<?= BASE_URL ?>/marketplace/checkout/process', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    var options = {
                        "key": data.key,
                        "amount": data.amount * 100,
                        "currency": "INR",
                        "name": "Bihar Vihaan Marketplace",
                        "description": "Authentic Bihar Handicrafts Purchase",
                        "order_id": data.razorpay_order_id,
                        "handler": function (response){
                            verifyPayment(response, data.order_id);
                        },
                        "prefill": {
                            "name": data.billing_name,
                            "email": data.billing_email,
                            "contact": data.billing_phone
                        },
                        "theme": { "color": "#0B3D91" }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.on('payment.failed', function (response){
                        btn.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay Now';
                        btn.disabled = false;
                        document.getElementById('checkoutError').textContent = "Payment failed. Please try again.";
                        document.getElementById('checkoutError').classList.remove('d-none');
                    });
                    rzp1.open();
                } else {
                    btn.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay Now';
                    btn.disabled = false;
                    document.getElementById('checkoutError').textContent = data.message;
                    document.getElementById('checkoutError').classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.innerHTML = '<i class="fa-solid fa-lock me-2"></i> Pay Now';
                btn.disabled = false;
                document.getElementById('checkoutError').textContent = "An error occurred during checkout processing.";
                document.getElementById('checkoutError').classList.remove('d-none');
            });
        });

        function verifyPayment(response, orderId) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('razorpay_order_id', response.razorpay_order_id);
            formData.append('razorpay_payment_id', response.razorpay_payment_id);
            formData.append('razorpay_signature', response.razorpay_signature);

            fetch('<?= BASE_URL ?>/marketplace/payment/verify', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    alert("Payment verification failed: " + data.message);
                    window.location.reload();
                }
            });
        }
    </script>

<?php elseif ($view_mode === 'confirmation'): ?>

    <!-- Order Confirmation UI -->
    <div class="container text-center" style="margin-top: 150px; margin-bottom: 100px;">
        <div class="bg-white p-5 rounded-4 shadow border d-inline-block" style="max-width: 600px;">
            <div class="text-success mb-4" style="font-size: 5rem;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--shop-primary);">Order Confirmed!</h1>
            <p class="fs-5 text-muted mb-4">Thank you for supporting Bihar's artisans. Your authentic products are being prepared for dispatch.</p>
            
            <div class="bg-light p-4 rounded text-start mb-4">
                <p class="mb-1"><strong>Order ID:</strong> BV-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></p>
                <p class="mb-1"><strong>Transaction ID:</strong> <?= htmlspecialchars($order['razorpay_payment_id']) ?></p>
                <p class="mb-0"><strong>Total Paid:</strong> ₹<?= number_format($order['total_price'], 2) ?></p>
            </div>
            
            <a href="<?= BASE_URL ?>/shop" class="btn-shop-primary">Continue Shopping</a>
        </div>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>
