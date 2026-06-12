<div class="mb-4">
    <a href="<?= BASE_URL ?>/admin/orders" class="text-decoration-none text-muted"><i class="fa-solid fa-arrow-left me-2"></i> Back to Orders</a>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<div class="row g-4">
    <!-- Order Details -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Order #ORD-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h5>
                <span class="badge <?= $order['payment_status'] == 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' ?>">
                    Payment: <?= ucfirst($order['payment_status']) ?>
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="pe-4 text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/40') ?>" width="40" class="rounded me-3">
                                    <?= htmlspecialchars($item['name']) ?>
                                </div>
                            </td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td class="pe-4 text-end fw-bold">₹<?= number_format($item['subtotal'], 2) ?></td>
                        </tr>
                        <?php endendforeach; ?>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                            <td class="pe-4 text-end fw-bold">₹<?= number_format($order['subtotal'], 2) ?></td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="pe-4 text-end fw-bold text-primary fs-5">₹<?= number_format($order['total_price'], 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Logs -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold">Payment Logs</h5>
            </div>
            <div class="card-body p-4">
                <?php if(empty($payments)): ?>
                    <p class="text-muted mb-0">No payment logs found.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach($payments as $pay): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="fw-bold"><?= htmlspecialchars($pay['gateway']) ?></span>
                                    <br><small class="text-muted">TXN: <?= htmlspecialchars($pay['transaction_id']) ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success-subtle text-success"><?= htmlspecialchars($pay['status']) ?></span>
                                    <br><small class="text-muted"><?= date('d M Y H:i', strtotime($pay['created_at'])) ?></small>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Update Status Sidebar -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold">Update Order</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/admin/orders/update" method="POST">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    
                    <div class="mb-3">
                        <label>Delivery Status</label>
                        <select name="delivery_status" class="form-select">
                            <option value="processing" <?= $order['delivery_status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $order['delivery_status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $order['delivery_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $order['delivery_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control" value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>" placeholder="e.g. AWB123456789">
                    </div>

                    <div class="mb-3">
                        <label>Refund Notes (if cancelled)</label>
                        <textarea name="refund_notes" class="form-control" rows="3"><?= htmlspecialchars($order['refund_notes'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Update Order</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold">Customer Details</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <small class="text-muted d-block">Name</small>
                    <span class="fw-bold"><?= htmlspecialchars($order['billing_name']) ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Email</small>
                    <span><?= htmlspecialchars($order['billing_email']) ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Phone</small>
                    <span><?= htmlspecialchars($order['billing_phone']) ?></span>
                </div>
                <div>
                    <small class="text-muted d-block">Address</small>
                    <span class="lh-sm d-block"><?= nl2br(htmlspecialchars($order['billing_address'])) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
