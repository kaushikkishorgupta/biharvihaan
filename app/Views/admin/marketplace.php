<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-store text-primary me-2"></i> Artisan Marketplace</h4>
            <p class="text-secondary small mb-0 font-primary">Manage inventory of genuine Bihar handicrafts (Madhubani painting, Sikki grass, Sujani quilt) and track orders.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#productModal" onclick="clearProductForm()"><i class="fa-solid fa-plus me-1"></i> Add Product</button>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs border-secondary mb-4" id="marketplaceTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active text-light bg-transparent border-0 border-bottom border-primary pb-2 px-3 fw-bold" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-panel" type="button">Products Catalog</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-secondary bg-transparent border-0 pb-2 px-3" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-panel" type="button">Orders Tracker</button>
        </li>
    </ul>

    <div class="tab-content" id="marketplaceTabContent">
        <!-- PRODUCTS CATALOG -->
        <div class="tab-pane fade show active" id="products-panel">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-secondary border-bottom border-secondary">
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Artisan</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($products)): ?>
                            <tr><td colspan="7" class="text-center py-4 text-secondary">No products in catalog.</td></tr>
                        <?php else: foreach($products as $p): 
                            $img = !empty($p['image_url']) ? (strpos($p['image_url'], 'http') === 0 ? htmlspecialchars($p['image_url']) : BASE_URL . $p['image_url']) : 'https://images.unsplash.com/photo-1582201943021-e8e5b66d43cc?w=100';
                        ?>
                            <tr>
                                <td class="py-3">
                                    <img src="<?= $img ?>" width="50" height="40" class="rounded object-fit-cover border border-secondary shadow-sm">
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold text-white"><?= htmlspecialchars($p['name']) ?></div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary small"><?= htmlspecialchars($p['category']) ?></span>
                                </td>
                                <td class="py-3 text-secondary small"><?= htmlspecialchars($p['artisan_name'] ?: 'Independent Artisan') ?></td>
                                <td class="py-3 text-success fw-bold">₹<?= number_format($p['price'], 2) ?></td>
                                <td class="py-3">
                                    <span class="badge <?= $p['stock'] > 5 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' ?> px-2 py-1">
                                        <?= $p['stock'] ?> left
                                    </span>
                                </td>
                                <td class="py-3">
                                    <?php if(($p['status'] ?? 'active') === 'active'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-light" onclick='editProduct(<?= htmlspecialchars(json_encode($p)) ?>)'><i class="fa-solid fa-edit"></i></button>
                                        <form action="<?= BASE_URL ?>/admin/marketplace/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ORDERS TRACKER -->
        <div class="tab-pane fade" id="orders-panel">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-secondary border-bottom border-secondary">
                            <th>Order ID</th>
                            <th>Customer Info</th>
                            <th>Address</th>
                            <th>Total Price</th>
                            <th>Payment Status</th>
                            <th>Shipping State</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($orders)): ?>
                            <tr><td colspan="7" class="text-center py-4 text-secondary">No customer orders recorded yet.</td></tr>
                        <?php else: foreach($orders as $o): ?>
                            <tr>
                                <td class="py-3 fw-bold text-white">#<?= $o['id'] ?></td>
                                <td class="py-3 text-light small">
                                    <div><?= htmlspecialchars($o['billing_name']) ?></div>
                                    <small class="text-secondary"><?= htmlspecialchars($o['billing_email']) ?></small>
                                </td>
                                <td class="py-3 text-secondary small text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($o['billing_address']) ?>">
                                    <?= htmlspecialchars($o['billing_address']) ?>
                                </td>
                                <td class="py-3 text-success fw-bold">₹<?= number_format($o['total_price'], 2) ?></td>
                                <td class="py-3">
                                    <span class="badge <?= $o['payment_status'] === 'paid' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' ?>">
                                        <?= strtoupper(htmlspecialchars($o['payment_status'] ?? 'pending')) ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge <?= $o['delivery_status'] === 'delivered' ? 'bg-success bg-opacity-10 text-success' : 'bg-primary bg-opacity-10 text-primary' ?>">
                                        <?= strtoupper(htmlspecialchars($o['delivery_status'] ?? 'processing')) ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <button class="btn btn-sm btn-outline-light" onclick='openOrderModal(<?= htmlspecialchars(json_encode($o)) ?>)'><i class="fa-solid fa-truck"></i> Update Status</button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Product Add/Edit Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content bg-dark border border-secondary text-white" id="productForm" action="<?= BASE_URL ?>/admin/marketplace/store" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="product_id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Product Name</label>
                        <input type="text" name="name" id="product_name" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <select name="category" id="product_category" class="form-select bg-dark border-secondary text-white" required>
                            <option value="Madhubani Paintings">Madhubani Paintings</option>
                            <option value="Sikki Grass Crafts">Sikki Grass Crafts</option>
                            <option value="Sujani Embroidery">Sujani Embroidery</option>
                            <option value="Stone Crafts">Stone Crafts</option>
                            <option value="Traditional Decor">Traditional Decor</option>
                            <option value="Books & Literature">Books & Literature</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Price (INR)</label>
                        <input type="number" step="0.01" name="price" id="product_price" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Stock Count</label>
                        <input type="number" name="stock" id="product_stock" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Artisan Origin</label>
                        <select name="artisan_id" id="product_artisan_id" class="form-select bg-dark border-secondary text-white">
                            <option value="">Independent Artisan...</option>
                            <?php foreach($artisans as $art): ?>
                                <option value="<?= $art['id'] ?>"><?= htmlspecialchars($art['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Materials Used</label>
                        <input type="text" name="materials" id="product_materials" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Natural dyes, handmade paper">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Location (Origin)</label>
                        <input type="text" name="location" id="product_location" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Madhubani">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Featured Image (Upload)</label>
                        <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">OR Image URL</label>
                        <input type="text" name="image_url" id="product_image_url" class="form-control bg-dark border-secondary text-white" placeholder="https://image-link.jpg">
                    </div>

                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check form-switch pt-3">
                            <input class="form-check-input cursor-pointer" type="checkbox" name="is_handmade" id="product_handmade" checked>
                            <label class="form-check-label text-white small" for="product_handmade">100% Handmade</label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check form-switch pt-3">
                            <input class="form-check-input cursor-pointer" type="checkbox" name="is_bestseller" id="product_bestseller">
                            <label class="form-check-label text-white small" for="product_bestseller">Bestseller Badge</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Publish State</label>
                        <select name="status" id="product_status" class="form-select bg-dark border-secondary text-white">
                            <option value="active">Active (Visible)</option>
                            <option value="inactive">Inactive (Hidden)</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Description</label>
                        <textarea name="description" id="product_desc" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Description of the craft..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Order Edit Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content bg-dark border border-secondary text-white" action="<?= BASE_URL ?>/admin/marketplace/update_order" method="POST">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Update Shipping Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="order_id" id="order_id_field">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Order Reference</label>
                    <input type="text" id="order_ref_field" class="form-control bg-dark border-secondary text-white" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Payment State</label>
                    <select name="payment_status" id="order_payment_field" class="form-select bg-dark border-secondary text-white">
                        <option value="pending">PENDING</option>
                        <option value="paid">PAID</option>
                        <option value="failed">FAILED</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Shipping Delivery State</label>
                    <select name="delivery_status" id="order_delivery_field" class="form-select bg-dark border-secondary text-white">
                        <option value="processing">PROCESSING</option>
                        <option value="shipped">SHIPPED</option>
                        <option value="delivered">DELIVERED</option>
                        <option value="cancelled">CANCELLED</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Courier Tracking Number</label>
                    <input type="text" name="tracking_number" id="order_tracking_field" class="form-control bg-dark border-secondary text-white" placeholder="e.g. DTDC-88123-IN">
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Order Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearProductForm() {
        document.getElementById('productForm').reset();
        document.getElementById('product_id').value = '';
        document.getElementById('productForm').action = '<?= BASE_URL ?>/admin/marketplace/store';
        document.getElementById('modalTitle').textContent = 'Add Product';
    }

    function editProduct(data) {
        clearProductForm();
        document.getElementById('product_id').value = data.id;
        document.getElementById('product_name').value = data.name;
        document.getElementById('product_category').value = data.category;
        document.getElementById('product_price').value = data.price;
        document.getElementById('product_stock').value = data.stock;
        document.getElementById('product_artisan_id').value = data.artisan_id || '';
        document.getElementById('product_materials').value = data.materials;
        document.getElementById('product_location').value = data.location;
        document.getElementById('product_image_url').value = data.image_url;
        document.getElementById('product_handmade').checked = parseInt(data.is_handmade) === 1;
        document.getElementById('product_bestseller').checked = parseInt(data.is_bestseller) === 1;
        document.getElementById('product_status').value = data.status || 'active';
        document.getElementById('product_desc').value = data.description;

        document.getElementById('productForm').action = '<?= BASE_URL ?>/admin/marketplace/update';
        document.getElementById('modalTitle').textContent = 'Update Product';

        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function openOrderModal(data) {
        document.getElementById('order_id_field').value = data.id;
        document.getElementById('order_ref_field').value = 'Order #' + data.id + ' (' + data.billing_name + ')';
        document.getElementById('order_payment_field').value = data.payment_status || 'pending';
        document.getElementById('order_delivery_field').value = data.delivery_status || 'processing';
        document.getElementById('order_tracking_field').value = data.tracking_number || '';

        const modal = new bootstrap.Modal(document.getElementById('orderModal'));
        modal.show();
    }

    // Keep active tab state on redirect reload (using hashtag)
    document.addEventListener('DOMContentLoaded', function() {
        if(window.location.hash === '#orders-panel') {
            const tabEl = document.querySelector('#orders-tab');
            if(tabEl) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }
        
        // Custom styling change on tab select
        const tabs = document.querySelectorAll('#marketplaceTab button');
        tabs.forEach(t => {
            t.addEventListener('shown.bs.tab', function (e) {
                tabs.forEach(btn => {
                    btn.classList.remove('active', 'text-primary', 'border-bottom', 'border-primary', 'fw-bold');
                    btn.classList.add('text-secondary');
                });
                e.target.classList.remove('text-secondary');
                e.target.classList.add('active', 'text-primary', 'border-bottom', 'border-primary', 'fw-bold');
                
                // update hash
                window.location.hash = e.target.id === 'orders-tab' ? 'orders-panel' : '';
            });
        });
    });
</script>