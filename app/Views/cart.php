<?php include 'layout/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="fw-bold font-outfit mb-4">Shopping Cart</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-success-subtle bg-success-subtle text-success small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-cart-shopping fa-4x text-muted mb-3 opacity-50"></i>
            <h4 class="text-muted">Your cart is empty</h4>
            <p class="text-muted small mb-4">Looks like you haven't added anything to your cart yet.</p>
            <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary px-4 rounded-pill">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($items as $item): ?>
                            <li class="list-group-item p-4">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img src="<?= htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/100') ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div class="col-9 col-md-4">
                                        <h5 class="fw-bold font-outfit mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                                        <div class="small text-muted mb-2"><?= htmlspecialchars($item['category']) ?></div>
                                        <div class="fw-bold text-primary">₹<?= number_format($item['price'], 2) ?></div>
                                    </div>
                                    <div class="col-6 col-md-4 mt-3 mt-md-0">
                                        <form action="<?= BASE_URL ?>/cart/update" method="POST" class="d-flex align-items-center" style="max-width: 150px;">
                                            <input type="hidden" name="item_id" value="<?= $item['cart_item_id'] ?>">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.form.submit();">-</button>
                                            <input type="number" name="quantity" class="form-control form-control-sm text-center mx-1" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" onchange="this.form.submit();">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.form.submit();">+</button>
                                        </form>
                                    </div>
                                    <div class="col-6 col-md-2 mt-3 mt-md-0 text-end">
                                        <div class="fw-bold mb-2">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                        <form action="<?= BASE_URL ?>/cart/remove" method="POST">
                                            <input type="hidden" name="item_id" value="<?= $item['cart_item_id'] ?>">
                                            <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0" title="Remove" onclick="return confirm('Remove this item?');">
                                                <i class="fa-solid fa-trash"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold font-outfit mb-4">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success">Free</span>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                        
                        <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">Proceed to Checkout</a>
                        
                        <div class="mt-4 pt-3 border-top text-center text-muted small">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i> Secure Checkout with Razorpay
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>
