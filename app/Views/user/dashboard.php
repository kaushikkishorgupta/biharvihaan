<?php include __DIR__.'/../layout/header.php'; ?>

<div class="container py-5 mt-5">
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 bg-success bg-opacity-10 text-success mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 bg-danger bg-opacity-10 text-danger mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=0B3D91&color=fff&bold=true" class="rounded-circle mb-3 border" width="90" height="90">
                    <h5 class="fw-bold font-outfit mb-1"><?= htmlspecialchars($user['name'] ?? 'User') ?></h5>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-4">Role: <?= htmlspecialchars($user['role_name'] ?? 'User') ?></span>
                    
                    <div class="list-group list-group-flush text-start">
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=profile" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'profile') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-user me-2"></i> Profile Settings</a>
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=wishlist" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'wishlist') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-heart me-2"></i> Wishlist</a>
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=saved-destinations" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'saved-destinations') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-map-location-dot me-2"></i> Saved Destinations</a>
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=saved-trips" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'saved-trips') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-route me-2"></i> Saved Trips</a>
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=orders" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'orders') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-box me-2"></i> Orders</a>
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=reviews" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'reviews') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-star me-2"></i> Reviews</a>
                        <a href="<?= BASE_URL ?>/user/dashboard?tab=notifications" class="list-group-item list-group-item-action border-0 rounded px-3 py-2.5 mb-1 <?= ($tab === 'notifications') ? 'active fw-bold bg-primary text-white' : '' ?>"><i class="fa-solid fa-bell me-2"></i> Notifications</a>
                        
                        <a href="<?= BASE_URL ?>/logout" class="list-group-item list-group-item-action border-0 text-danger rounded px-3 py-2.5 mt-4"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Panel Content -->
        <div class="col-md-9">
            
            <!-- TAB: Profile Settings -->
            <?php if ($tab === 'profile'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-user-gear text-primary me-2"></i> Profile Settings</h5>
                        <p class="text-muted small mb-0 mt-1">Manage your official credentials, user particulars, and security options.</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/user/profile/update" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary">Full Name</label>
                                    <input type="text" name="name" class="form-control px-3 py-2" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary">Email Address</label>
                                    <input type="email" name="email" class="form-control px-3 py-2" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary">New Password (Optional)</label>
                                <input type="password" name="password" class="form-control px-3 py-2" placeholder="Leave blank to keep your current password">
                            </div>
                            
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2"><i class="fa-solid fa-save me-1"></i> Save Profile Details</button>
                        </form>
                    </div>
                </div>

            <!-- TAB: Wishlist -->
            <?php elseif ($tab === 'wishlist'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-heart text-danger me-2"></i> My Wishlist</h5>
                        <p class="text-muted small mb-0 mt-1">Items and authentic Bihar crafts saved for purchase.</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($wishlist)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-heart-broken fs-1 opacity-25 mb-3"></i>
                                <p class="mb-0">Your wishlist is empty. Discover items in the artisan shop catalog.</p>
                                <a href="<?= BASE_URL ?>/shop" class="btn btn-primary btn-sm rounded-pill mt-3 px-4 py-2">Go to Shop</a>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($wishlist as $w): 
                                    $img = !empty($w['image_url']) ? (strpos($w['image_url'], 'http') === 0 ? htmlspecialchars($w['image_url']) : BASE_URL . $w['image_url']) : 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=150';
                                ?>
                                    <div class="col-md-6">
                                        <div class="card h-100 border p-3 rounded-3 shadow-sm">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $img ?>" class="rounded object-fit-cover me-3 border" width="70" height="70">
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($w['name']) ?></h6>
                                                    <span class="fw-bold text-primary">₹<?= number_format($w['price'], 2) ?></span>
                                                </div>
                                                <div class="d-flex flex-column gap-2">
                                                    <a href="<?= BASE_URL ?>/shop" class="btn btn-sm btn-primary py-1 px-3 rounded-pill">View</a>
                                                    <form action="<?= BASE_URL ?>/wishlist/remove" method="POST" class="m-0" onsubmit="return confirm('Remove item from wishlist?');">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <input type="hidden" name="product_id" value="<?= $w['product_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="fa-solid fa-trash"></i> Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <!-- TAB: Saved Destinations -->
            <?php elseif ($tab === 'saved-destinations'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-map-location-dot text-primary me-2"></i> Saved Destinations</h5>
                        <p class="text-muted small mb-0 mt-1">Bookmarked tourist attractions and historical circuits across Bihar.</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($saved_destinations)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-map-pin fs-1 opacity-25 mb-3"></i>
                                <p class="mb-0">You have no saved destinations yet.</p>
                                <a href="<?= BASE_URL ?>/tourism" class="btn btn-primary btn-sm rounded-pill mt-3 px-4 py-2">Explore Destinations</a>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($saved_destinations as $d): 
                                    $img = !empty($d['image_url']) ? (strpos($d['image_url'], 'http') === 0 ? htmlspecialchars($d['image_url']) : BASE_URL . $d['image_url']) : 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=150';
                                ?>
                                    <div class="col-md-6">
                                        <div class="card h-100 border p-3 rounded-3 shadow-sm">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $img ?>" class="rounded object-fit-cover me-3 border" width="70" height="70">
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($d['name']) ?></h6>
                                                    <small class="text-muted d-block"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= htmlspecialchars($d['district'] ?? '') ?></small>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary small mt-1"><?= htmlspecialchars($d['category'] ?? '') ?></span>
                                                </div>
                                                <div class="d-flex flex-column gap-2">
                                                    <a href="<?= BASE_URL ?>/tourism/<?= $d['destination_id'] ?>" class="btn btn-sm btn-primary py-1 px-3 rounded-pill">Explore</a>
                                                    <a href="<?= BASE_URL ?>/tourism/unsave/<?= $d['destination_id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Unsave this place?');"><i class="fa-solid fa-trash"></i> Unsave</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <!-- TAB: Saved Trips -->
            <?php elseif ($tab === 'saved-trips'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-route text-success me-2"></i> Saved Trips</h5>
                        <p class="text-muted small mb-0 mt-1">AI-generated itineraries and customized travel circuits in Bihar.</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($saved_trips)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-robot fs-1 opacity-25 mb-3"></i>
                                <p class="mb-0">You have no saved itineraries yet.</p>
                                <a href="<?= BASE_URL ?>/tourism/ai-planner" class="btn btn-primary btn-sm rounded-pill mt-3 px-4 py-2">Create AI Trip</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="text-secondary small">
                                            <th>Trip Title</th>
                                            <th>Duration</th>
                                            <th>Date Generated</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($saved_trips as $t): ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($t['title']) ?></div>
                                                    <small class="text-muted text-truncate d-inline-block" style="max-width:300px;"><?= htmlspecialchars($t['description']) ?></small>
                                                </td>
                                                <td><span class="badge bg-success bg-opacity-10 text-success"><?= $t['duration_days'] ?> Days</span></td>
                                                <td class="small text-secondary"><?= date('d M Y, h:i A', strtotime($t['created_at'])) ?></td>
                                                <td class="text-end">
                                                    <a href="<?= BASE_URL ?>/tourism/ai-planner" class="btn btn-sm btn-light">Open Details</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <!-- TAB: Orders -->
            <?php elseif ($tab === 'orders'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-box text-primary me-2"></i> Transaction Orders</h5>
                        <p class="text-muted small mb-0 mt-1">Detailed history of purchases and product tracking codes.</p>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fs-1 opacity-25 mb-3"></i>
                                <p class="mb-0">No transaction records found.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="small text-secondary">
                                            <th class="ps-4">Order ID</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total Price</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($orders as $order): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold">#ORD-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                            <td><?= date('d M, Y', strtotime($order['created_at'])) ?></td>
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
                                                <a href="<?= BASE_URL ?>/user/track?id=<?= $order['id'] ?>" class="btn btn-sm btn-light">Track</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <!-- TAB: Reviews -->
            <?php elseif ($tab === 'reviews'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-star text-warning me-2"></i> My Reviews</h5>
                        <p class="text-muted small mb-0 mt-1">Review ratings and comments left by you.</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($reviews)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-star-half-stroke fs-1 opacity-25 mb-3"></i>
                                <p class="mb-0">You have not posted any feedback comments yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($reviews as $r): ?>
                                    <div class="list-group-item bg-transparent border-secondary border-opacity-10 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0 text-dark">Reviewed: <?= htmlspecialchars($r['item_name']) ?> <span class="badge bg-secondary bg-opacity-10 text-secondary small ms-2 text-uppercase" style="font-size:0.6rem;"><?= htmlspecialchars($r['reference_type']) ?></span></h6>
                                            <span class="small text-secondary"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
                                        </div>
                                        <div class="text-warning mb-2" style="font-size:0.85rem;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa-<?= ($i <= $r['rating']) ? 'solid' : 'regular' ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-muted small mb-0">"<?= htmlspecialchars($r['comment']) ?>"</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <!-- TAB: Notifications -->
            <?php elseif ($tab === 'notifications'): ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold font-outfit mb-0"><i class="fa-solid fa-bell text-primary me-2"></i> My Notifications</h5>
                        <p class="text-muted small mb-0 mt-1">System updates, order statuses, and alerts.</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($notifications)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-bell-slash fs-1 opacity-25 mb-3"></i>
                                <p class="mb-0">You have no notification logs at the moment.</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($notifications as $n): ?>
                                    <div class="list-group-item bg-transparent border-secondary border-opacity-10 px-0 py-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-circle-info text-primary me-3 mt-1 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($n['title']) ?></h6>
                                                    <span class="small text-secondary"><?= date('d M Y, h:i A', strtotime($n['created_at'])) ?></span>
                                                </div>
                                                <p class="text-muted small mb-0"><?= htmlspecialchars($n['message']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
