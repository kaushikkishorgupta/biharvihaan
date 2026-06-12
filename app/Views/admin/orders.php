<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4">
        <h4 class="mb-0 fw-bold">Orders Manager</h4>
        <p class="text-muted mb-0 small">Manage customer orders and shipments.</p>
    </div>
    <div class="card-body p-4">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover datatable align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($items)): ?>
                        <tr><td colspan="7" class="text-center text-muted">No orders found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($items as $order): ?>
                    <tr>
                        <td class="fw-bold">#ORD-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($order['billing_name']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($order['billing_email']) ?></div>
                        </td>
                        <td class="fw-bold text-primary">₹<?= number_format($order['total_price'], 2) ?></td>
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
                        <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/orders/view?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i> View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
