<?php include 'layout/header.php'; ?>

<div class="container py-5 mt-5 text-center">
    <div class="card border-0 shadow-sm rounded-4 max-w-2xl mx-auto p-5">
        <i class="fa-solid fa-circle-check fa-5x text-success mb-4"></i>
        <h2 class="fw-bold font-outfit mb-3">Order Placed Successfully!</h2>
        <p class="text-muted mb-4">Thank you for your purchase. Your order has been placed and is currently being processed.</p>
        
        <?php if(isset($_GET['order_id'])): ?>
            <div class="bg-light p-3 rounded mb-4 d-inline-block">
                <span class="text-muted small">Order Reference:</span>
                <span class="fw-bold fs-5 d-block">#ORD-<?= str_pad(htmlspecialchars($_GET['order_id']), 6, '0', STR_PAD_LEFT) ?></span>
            </div>
        <?php endif; ?>

        <div>
            <?php if(App\Core\Session::isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/user/orders" class="btn btn-outline-primary px-4 me-2">View Orders</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary px-4">Continue Shopping</a>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
