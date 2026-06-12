<?php include __DIR__.'/../layout/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <div class="list-group list-group-flush text-start">
                        <a href="<?= BASE_URL ?>/user/dashboard" class="list-group-item list-group-item-action border-0 rounded px-3 py-2 mb-1"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
                        <a href="<?= BASE_URL ?>/user/orders" class="list-group-item list-group-item-action border-0 active fw-bold rounded px-3 py-2 mb-1"><i class="fa-solid fa-box me-2"></i> My Orders</a>
                        <a href="<?= BASE_URL ?>/wishlist" class="list-group-item list-group-item-action border-0 rounded px-3 py-2 mb-1"><i class="fa-solid fa-heart me-2"></i> Wishlist</a>
                        <a href="<?= BASE_URL ?>/logout" class="list-group-item list-group-item-action border-0 text-danger rounded px-3 py-2 mt-3"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold font-outfit mb-0">My Orders</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Date</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th class="pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($orders)): ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">You have no orders.</td></tr>
                                <?php endif; ?>
                                <?php foreach($orders as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-bold">#ORD-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= date('d M, Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <span class="badge <?= $order['payment_status'] == 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' ?>">
                                            <?= ucfirst($order['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $badge = 'bg-secondary';
                                        if($order['delivery_status'] == 'delivered') $badge = 'bg-success';
                                        if($order['delivery_status'] == 'processing') $badge = 'bg-primary';
                                        if($order['delivery_status'] == 'shipped') $badge = 'bg-info text-dark';
                                        if($order['delivery_status'] == 'cancelled') $badge = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= ucfirst($order['delivery_status']) ?></span>
                                    </td>
                                    <td class="fw-bold">₹<?= number_format($order['total_price'], 2) ?></td>
                                    <td class="pe-4 text-end">
                                        <a href="<?= BASE_URL ?>/user/track?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Track Details</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
