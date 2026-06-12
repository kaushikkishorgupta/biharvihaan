<?php include 'layout/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="fw-bold font-outfit mb-0">My Wishlist</h2>
        <span class="badge bg-primary rounded-pill px-3"><?= count($items) ?> Items</span>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-success-subtle bg-success-subtle text-success small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="fa-regular fa-heart fa-4x text-muted mb-3 opacity-50"></i>
            <h4 class="text-muted">Your wishlist is empty</h4>
            <p class="text-muted small mb-4">Browse our marketplace and save your favorite items.</p>
            <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary px-4 rounded-pill">Explore Marketplace</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($items as $item): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card group">
                    <div class="position-relative">
                        <img src="<?= htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/400x300?text=No+Image') ?>" class="card-img-top object-fit-cover" height="200" alt="<?= htmlspecialchars($item['name']) ?>">
                        
                        <!-- Remove from Wishlist Form -->
                        <form action="<?= BASE_URL ?>/wishlist/remove" method="POST" class="position-absolute top-0 end-0 m-2">
                            <input type="hidden" name="id" value="<?= $item['wishlist_id'] ?>">
                            <button type="submit" class="btn btn-light btn-sm text-danger rounded-circle shadow-sm" title="Remove" onclick="return confirm('Remove this item from your wishlist?');">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="small text-muted mb-1"><?= htmlspecialchars($item['category']) ?></div>
                        <h5 class="card-title fw-bold font-outfit text-truncate mb-2" title="<?= htmlspecialchars($item['name']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning small me-2">
                                <?php $rating = round($item['rating'] ?? 5); for($i=1; $i<=5; $i++) echo $i<=$rating ? '★' : '☆'; ?>
                            </div>
                            <span class="small text-muted">(<?= $item['reviews_count'] ?? 0 ?>)</span>
                        </div>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-end">
                            <div>
                                <div class="fw-bold fs-5 text-primary">₹<?= number_format($item['price'], 2) ?></div>
                            </div>
                            <!-- Add to Cart Form -->
                            <form action="<?= BASE_URL ?>/cart/add" method="POST">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary rounded-circle shadow-sm" style="width:40px; height:40px;" title="Add to Cart">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>
