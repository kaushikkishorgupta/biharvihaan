<?php 
// No explicit header/footer includes, Controller::render() handles it.
?>
<!-- Custom Marketplace CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/marketplace.css">

<?php if ($view_mode === 'catalog'): ?>

    <!-- SECTION 1 - HERO -->
    <section class="shop-hero" style="position: relative; background: url('<?= BASE_URL ?>/assets/images/marketplace-hero.jpg') center/cover; padding: 120px 0; overflow: hidden;">
        <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.15); backdrop-filter: brightness(1.15);"></div>
        <div class="container position-relative text-center shop-hero-content" data-aos="fade-up" style="z-index: 2;">
            <h1 class="display-3 fw-bold mb-3" style="color: #FFFFFF; text-shadow: 0 4px 15px rgba(0,0,0,0.3); font-family: 'Outfit', sans-serif;">Discover Authentic Bihar Crafts</h1>
            <p class="lead mb-4 mx-auto" style="color: #FFFFFF; text-shadow: 0 2px 10px rgba(0,0,0,0.3); max-width: 800px;">
                Support local artisans and explore Bihar's rich artistic traditions through handcrafted products and heritage collections.
            </p>
            
            <!-- SECTION 5 - SEARCH SYSTEM -->
            <div class="search-system mx-auto mb-4" style="max-width: 600px; position: relative;">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" class="form-control border-0" id="marketplace-search" placeholder="Search Bihar products (e.g. Madhubani Painting)...">
                    <button class="btn btn-primary px-4" type="button"><i class="fa-solid fa-search"></i></button>
                </div>
                <div id="search-suggestions" class="dropdown-menu w-100 shadow-sm border-0 mt-1"></div>
            </div>

            <div class="trust-badges d-flex justify-content-center gap-4 flex-wrap">
                <span class="badge bg-dark bg-opacity-50 border border-light text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-check-circle text-warning"></i> Authentic Bihar Products</span>
                <span class="badge bg-dark bg-opacity-50 border border-light text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-hand-sparkles text-warning"></i> Handmade Verified</span>
                <span class="badge bg-dark bg-opacity-50 border border-light text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-shield-halved text-warning"></i> Secure Payments</span>
                <span class="badge bg-dark bg-opacity-50 border border-light text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-users text-warning"></i> Artisan Direct</span>
            </div>
        </div>
    </section>

    <!-- SECTION 14 - SUPPORT LOCAL ARTISANS -->
    <div class="container my-5" data-aos="fade-up">
        <div class="p-5 rounded-4 text-center text-white support-artisans-banner" style="background: linear-gradient(135deg, #0B3D91, #1E40AF); box-shadow: 0 10px 30px rgba(11, 61, 145, 0.15);">
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; text-shadow: 0 2px 5px rgba(0,0,0,0.2);">Every Purchase Supports Bihar's Local Artisans</h2>
            <p class="lead mb-4 text-white-50">Empowering 500+ rural artisans, preserving heritage, and building sustainable livelihoods.</p>
            <div class="d-flex justify-content-center gap-5 flex-wrap">
                <div class="text-center"><h3 class="fw-bold text-warning">500+</h3><small class="text-uppercase text-white-50 fw-bold">Artisans</small></div>
                <div class="text-center"><h3 class="fw-bold text-warning">38</h3><small class="text-uppercase text-white-50 fw-bold">Districts</small></div>
                <div class="text-center"><h3 class="fw-bold text-warning">100%</h3><small class="text-uppercase text-white-50 fw-bold">Handmade</small></div>
            </div>
        </div>
    </div>

    <!-- SECTION 13 - HERITAGE COLLECTION -->
    <section class="container mb-5 pb-4 border-bottom">
        <h3 class="mb-4 fw-bold font-outfit" style="color: var(--text);">Premium Heritage Collections</h3>
        <div class="row g-3">
            <div class="col-md-3"><a href="#" class="collection-card" style="background: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80') center/cover;"><span>Chhath Collection</span></a></div>
            <div class="col-md-3"><a href="#" class="collection-card" style="background: url('https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&q=80') center/cover;"><span>Nalanda Collection</span></a></div>
            <div class="col-md-3"><a href="#" class="collection-card" style="background: url('https://images.unsplash.com/photo-1599839575945-a9e5af0c3fa5?auto=format&fit=crop&q=80') center/cover;"><span>Mithila Collection</span></a></div>
            <div class="col-md-3"><a href="#" class="collection-card" style="background: url('https://images.unsplash.com/photo-1582560475093-ba66accbc424?auto=format&fit=crop&q=80') center/cover;"><span>Bodh Gaya Collection</span></a></div>
        </div>
    </section>

    <!-- Main Shop Layout -->
    <div class="container-fluid px-lg-5 mb-5" id="shop-main">
        <div class="row">
            <!-- SECTION 6 - FILTER SIDEBAR -->
            <aside class="col-lg-3 col-xl-2 mb-4 d-none d-lg-block">
                <div class="shop-sidebar sticky-top" style="top: 100px; background: var(--surface); padding: 20px; border-radius: 12px; border: 1px solid var(--border);">
                    <h5 class="fw-bold mb-4 font-outfit">Filters</h5>
                    
                    <div class="accordion accordion-flush" id="filterAccordion">
                        <div class="accordion-item bg-transparent border-bottom">
                            <h2 class="accordion-header"><button class="accordion-button px-0 bg-transparent shadow-none fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories">Categories</button></h2>
                            <div id="collapseCategories" class="accordion-collapse collapse show"><div class="accordion-body px-0 pt-0">
                                <ul class="list-unstyled mb-0 filter-list">
                                    <?php foreach($categories as $cat): ?>
                                        <li class="mb-2"><a href="#" class="cat-filter text-decoration-none text-muted" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div></div>
                        </div>
                        
                        <div class="accordion-item bg-transparent border-bottom mt-2">
                            <h2 class="accordion-header"><button class="accordion-button px-0 bg-transparent shadow-none fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice">Price Range</button></h2>
                            <div id="collapsePrice" class="accordion-collapse collapse"><div class="accordion-body px-0 pt-0">
                                <input type="range" class="form-range" min="0" max="10000" id="priceRange">
                                <div class="d-flex justify-content-between text-muted small"><span>₹0</span><span>₹10,000+</span></div>
                            </div></div>
                        </div>

                        <div class="accordion-item bg-transparent border-bottom mt-2">
                            <h2 class="accordion-header"><button class="accordion-button px-0 bg-transparent shadow-none fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCollections">Collections</button></h2>
                            <div id="collapseCollections" class="accordion-collapse collapse"><div class="accordion-body px-0 pt-0">
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="chkHandmade" checked><label class="form-check-label text-muted small" for="chkHandmade">Handmade Only</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="chkVerified" checked><label class="form-check-label text-muted small" for="chkVerified">Verified Artisan</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" id="chkFestival"><label class="form-check-label text-muted small" for="chkFestival">Festival Collection</label></div>
                            </div></div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- SECTION 2 - PRODUCT GRID -->
            <main class="col-lg-9 col-xl-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 fw-bold font-outfit">All Products</h4>
                    <select class="form-select w-auto border-0 shadow-sm" id="sort-select">
                        <option value="newest">New Arrivals</option>
                        <option value="popular">Best Sellers</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="shop-masonry">
                    <?php 
                    // SECTION 3 - PRODUCT IMAGES (Bihar-themed products overrides for demonstration if original db returns nulls/placeholders)
                    $biharImages = [
                        'https://images.unsplash.com/photo-1578301978018-3005759f48f7?auto=format&fit=crop&q=80', // Art
                        'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&q=80', // Crafts
                        'https://images.unsplash.com/photo-1605814526046-24300fa1b61c?auto=format&fit=crop&q=80', // Pottery
                        'https://images.unsplash.com/photo-1605470258079-6cd0ce48da6e?auto=format&fit=crop&q=80'  // Embroidery
                    ];
                    foreach($initialProducts as $i => $p): 
                        $img = !empty($p['image_url']) && strpos($p['image_url'], 'ui-avatars') === false ? $p['image_url'] : $biharImages[$i % count($biharImages)];
                    ?>
                        <!-- SECTION 4 - PRODUCT CARD REDESIGN -->
                        <div class="col">
                            <div class="card product-card-premium h-100 border-0 shadow-sm bg-surface">
                                <div class="position-relative overflow-hidden product-card-img-wrapper" style="aspect-ratio: 1/1; border-radius: 12px 12px 0 0;">
                                    <img src="<?= htmlspecialchars($img) ?>" class="w-100 h-100 object-fit-cover product-img" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                    <div class="product-badges position-absolute top-0 start-0 p-2 w-100 d-flex justify-content-between">
                                        <span class="badge bg-dark bg-opacity-75 text-white small"><?= htmlspecialchars($p['category']) ?></span>
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm btn-wishlist" onclick="toggleWishlist(<?= $p['id'] ?>)"><i class="fa-regular fa-heart text-muted"></i></button>
                                    </div>
                                    <div class="product-overlay position-absolute bottom-0 start-0 w-100 p-3 d-flex justify-content-center gap-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); opacity: 0; transition: opacity 0.3s;">
                                        <button class="btn btn-light btn-sm rounded-pill px-3 shadow quick-view-btn" onclick="openQuickView(<?= $p['id'] ?>)"><i class="fa-solid fa-eye me-1"></i> Quick View</button>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h5 class="card-title mb-0 fs-6 fw-bold text-truncate" style="max-width: 80%;"><?= htmlspecialchars($p['name']) ?></h5>
                                        <span class="fw-bold" style="color: var(--shop-accent);">₹<?= number_format($p['price'], 0) ?></span>
                                    </div>
                                    <div class="small text-muted mb-2"><i class="fa-solid fa-user-pen text-primary"></i> <?= htmlspecialchars($p['artisan_name'] ?? 'Mithila Artisans') ?> &bull; <i class="fa-solid fa-location-dot"></i> Madhubani</div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="text-warning small me-2"><i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star-half-stroke"></i></div>
                                        <small class="text-muted">(<?= rand(10, 250) ?>)</small>
                                    </div>
                                    <form action="<?= BASE_URL ?>/marketplace/cart/add" method="POST" class="mt-auto">
                                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-outline-primary w-100 rounded-pill btn-sm fw-bold">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- SECTION 10 - FEATURED ARTISAN SECTION -->
    <section class="container mb-5 py-5 border-top">
        <h2 class="text-center mb-5" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text);">Meet Our Master Artisans</h2>
        <div class="row g-4 justify-content-center">
            <?php foreach($artisans as $a): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-surface h-100 text-center p-4 artisan-card-premium">
                    <img src="<?= htmlspecialchars($a['image_url']) ?>" class="rounded-circle mx-auto mb-3 object-fit-cover shadow-sm" style="width: 120px; height: 120px;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($a['name']) ?>&background=random'">
                    <h4 class="mb-1 fw-bold"><?= htmlspecialchars($a['name']) ?></h4>
                    <p class="text-primary mb-2 fw-semibold"><?= htmlspecialchars($a['specialization']) ?></p>
                    <p class="text-muted small mb-3"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($a['district'] ?? 'Bihar') ?> &bull; <?= rand(10, 40) ?> Years Exp.</p>
                    <p class="small text-muted flex-grow-1">"Preserving the ancient techniques passed down through generations to bring authentic Bihar art to the world."</p>
                    <a href="#" class="btn btn-light rounded-pill btn-sm mt-3 fw-bold border">View Collection</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SECTION 9 - QUICK VIEW MODAL -->
    <div class="modal fade" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg bg-surface" style="border-radius: 16px; overflow: hidden;">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index: 10;"></button>
                <div class="row g-0">
                    <div class="col-md-6 bg-light d-flex align-items-center justify-content-center">
                        <img src="" id="qv-img" class="w-100 h-100 object-fit-cover" style="max-height: 500px;">
                    </div>
                    <div class="col-md-6 p-4 p-lg-5 d-flex flex-column">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3 align-self-start" id="qv-category">Category</span>
                        <h2 class="fw-bold font-outfit mb-2" id="qv-title">Product Name</h2>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning small me-2"><i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i></div>
                            <small class="text-muted" id="qv-reviews">(128 Reviews)</small>
                        </div>
                        <h3 class="fw-bold mb-4" style="color: var(--shop-accent);" id="qv-price">₹0</h3>
                        <p class="text-muted mb-4 flex-grow-1" id="qv-desc">Product description goes here. This authentic piece tells a story of heritage and craftsmanship.</p>
                        
                        <div class="bg-light p-3 rounded-3 mb-4 small">
                            <div class="mb-2"><i class="fa-solid fa-user-pen text-primary w-20px"></i> <strong>Artisan:</strong> <span id="qv-artisan">Verified Craftsman</span></div>
                            <div class="mb-2"><i class="fa-solid fa-location-dot text-primary w-20px"></i> <strong>Region:</strong> <span id="qv-location">Bihar, India</span></div>
                            <div><i class="fa-solid fa-box text-primary w-20px"></i> <strong>Availability:</strong> <span class="text-success">In Stock</span></div>
                        </div>

                        <form action="<?= BASE_URL ?>/marketplace/cart/add" method="POST" class="d-flex gap-3">
                            <input type="hidden" name="product_id" id="qv-product-id">
                            <input type="number" name="quantity" class="form-control text-center fw-bold" value="1" min="1" max="10" style="width: 80px; border-radius: 8px;">
                            <button type="submit" class="btn btn-primary flex-grow-1 rounded-8 fw-bold py-2"><i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart</button>
                            <button type="button" class="btn btn-outline-secondary rounded-8 px-3"><i class="fa-regular fa-heart"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/assets/js/marketplace.js"></script>

<?php elseif ($view_mode === 'cart'): ?>
    
    <!-- Cart UI Redesigned -->
    <div class="container" style="margin-top: 120px; margin-bottom: 80px;">
        <h2 class="mb-4 fw-bold font-outfit">Your Shopping Cart</h2>
        
        <?php if (empty($items)): ?>
            <div class="text-center p-5 bg-surface rounded-4 shadow-sm border">
                <i class="fa-solid fa-cart-shopping fs-1 text-muted mb-3"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Explore authentic Bihar crafts and add items to your cart.</p>
                <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary rounded-pill px-4 mt-3">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <table class="table mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 ps-4 py-3 text-muted fw-semibold">Product</th>
                                        <th class="border-0 py-3 text-muted fw-semibold">Price</th>
                                        <th class="border-0 py-3 text-muted fw-semibold">Qty</th>
                                        <th class="border-0 pe-4 py-3 text-end text-muted fw-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($item['image_url']) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px;" class="me-3 shadow-sm" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                                                    <span class="badge bg-light text-muted border">Authentic Handmade</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 fw-bold" style="color: var(--shop-accent);">₹<?= number_format($item['price'], 2) ?></td>
                                        <td class="py-4">
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <button class="btn btn-outline-secondary" type="button">-</button>
                                                <input type="text" class="form-control text-center fw-bold" value="<?= $item['quantity'] ?>" readonly>
                                                <button class="btn btn-outline-secondary" type="button">+</button>
                                            </div>
                                        </td>
                                        <td class="pe-4 py-4 text-end">
                                            <a href="<?= BASE_URL ?>/marketplace/cart/remove/<?= $item['product_id'] ?>" class="btn btn-light text-danger rounded-circle p-2 shadow-sm"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 p-4 sticky-top" style="top: 100px;">
                        <h5 class="fw-bold mb-4 font-outfit">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">GST (18%)</span>
                            <span class="fw-bold">₹<?= number_format($gstAmount, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success fw-bold">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5" style="color: var(--shop-accent);">₹<?= number_format($total, 2) ?></span>
                        </div>
                        <a href="<?= BASE_URL ?>/marketplace/checkout" class="btn btn-primary w-100 rounded-pill py-3 fw-bold fs-6">Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i></a>
                        <p class="text-center text-muted small mt-3 mb-0"><i class="fa-solid fa-lock text-success me-1"></i> Secure Encrypted Checkout</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php elseif ($view_mode === 'checkout'): ?>
    <!-- Checkout simplified for brevity, similar styling applied -->
    <div class="container" style="margin-top: 120px; margin-bottom: 80px;">
        <h2 class="mb-4 fw-bold font-outfit">Checkout</h2>
        <div class="alert alert-info border-0 shadow-sm rounded-4"><i class="fa-solid fa-info-circle me-2"></i> Checkout integration will go here. Proceeding via Razorpay.</div>
    </div>
<?php elseif ($view_mode === 'confirmation'): ?>
    <div class="container text-center" style="margin-top: 150px; margin-bottom: 100px;">
        <div class="bg-surface p-5 rounded-4 shadow border d-inline-block" style="max-width: 600px;">
            <div class="text-success mb-4" style="font-size: 5rem;"><i class="fa-solid fa-circle-check"></i></div>
            <h1 class="fw-bold font-outfit mb-3">Order Confirmed!</h1>
            <p class="fs-5 text-muted mb-4">Thank you for supporting Bihar's artisans. Your authentic products are being prepared for dispatch.</p>
            <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary rounded-pill px-5 py-2">Continue Shopping</a>
        </div>
    </div>
<?php endif; ?>
