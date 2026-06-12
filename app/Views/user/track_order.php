<?php include __DIR__.'/../layout/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="mb-4">
        <a href="<?= BASE_URL ?>/user/orders" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left me-2"></i> Back to Orders</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold font-outfit mb-1">Order #ORD-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h4>
                    <p class="text-muted small mb-0">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="text-end">
                    <span class="badge <?= $order['payment_status'] == 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' ?> fs-6 px-3 py-2">
                        <?= ucfirst($order['payment_status']) ?>
                    </span>
                </div>
            </div>

            <!-- Tracking Timeline -->
            <div class="position-relative mt-5 mb-5">
                <div class="progress position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 4px; z-index: 1;">
                    <?php 
                    $progress = 25;
                    if($order['delivery_status'] == 'processing') $progress = 50;
                    if($order['delivery_status'] == 'shipped') $progress = 75;
                    if($order['delivery_status'] == 'delivered') $progress = 100;
                    if($order['delivery_status'] == 'cancelled') $progress = 0;
                    ?>
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress ?>%;"></div>
                </div>
                
                <div class="d-flex justify-content-between position-relative" style="z-index: 2;">
                    <div class="text-center" style="width: 80px;">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="small fw-bold">Placed</span>
                    </div>
                    <div class="text-center" style="width: 80px;">
                        <div class="<?= $progress >= 50 ? 'bg-success text-white' : 'bg-light text-muted border' ?> rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <span class="small <?= $progress >= 50 ? 'fw-bold' : 'text-muted' ?>">Processing</span>
                    </div>
                    <div class="text-center" style="width: 80px;">
                        <div class="<?= $progress >= 75 ? 'bg-success text-white' : 'bg-light text-muted border' ?> rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <span class="small <?= $progress >= 75 ? 'fw-bold' : 'text-muted' ?>">Shipped</span>
                    </div>
                    <div class="text-center" style="width: 80px;">
                        <div class="<?= $progress >= 100 ? 'bg-success text-white' : 'bg-light text-muted border' ?> rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-house"></i>
                        </div>
                        <span class="small <?= $progress >= 100 ? 'fw-bold' : 'text-muted' ?>">Delivered</span>
                    </div>
                </div>
            </div>

            <?php if($order['delivery_status'] == 'cancelled'): ?>
                <div class="alert alert-danger mb-4">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> This order has been cancelled. 
                    <?php if(!empty($order['refund_notes'])): ?>
                        <br><small><strong>Refund Notes:</strong> <?= htmlspecialchars($order['refund_notes']) ?></small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($order['tracking_number'])): ?>
                <div class="bg-light p-3 rounded mb-4 border">
                    <div class="small text-muted text-uppercase fw-bold mb-1">Tracking Number</div>
                    <div class="fs-5 fw-bold font-monospace"><?= htmlspecialchars($order['tracking_number']) ?></div>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-md-8">
                    <h5 class="fw-bold font-outfit mb-3">Items Ordered</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <?php foreach($items as $item): ?>
                        <li class="list-group-item px-0 py-3">
                            <div class="d-flex align-items-center">
                                <img src="<?= htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/60') ?>" width="60" class="rounded me-3 object-fit-cover">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                                    <span class="small text-muted">Qty: <?= $item['quantity'] ?> × ₹<?= number_format($item['price'], 2) ?></span>
                                </div>
                                <div class="fw-bold">
                                    ₹<?= number_format($item['subtotal'], 2) ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold font-outfit mb-3">Billing Info</h5>
                    <div class="bg-light p-4 rounded">
                        <div class="mb-3">
                            <div class="small text-muted">Billed To</div>
                            <div class="fw-bold"><?= htmlspecialchars($order['billing_name']) ?></div>
                            <div class="small"><?= htmlspecialchars($order['billing_email']) ?></div>
                            <div class="small"><?= htmlspecialchars($order['billing_phone']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="small text-muted">Shipping Address</div>
                            <div class="small lh-sm"><?= nl2br(htmlspecialchars($order['billing_address'])) ?></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">₹<?= number_format($order['subtotal'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success">Free</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <span class="fw-bold text-dark fs-5">Total</span>
                            <span class="fw-bold text-primary fs-5">₹<?= number_format($order['total_price'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
