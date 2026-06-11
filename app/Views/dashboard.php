<?php
use App\Core\Auth;
use App\Core\Session;
$csrfToken = Session::generateCsrfToken();
?>

<!-- Alerts Container -->
<div class="container" style="margin-top: 100px; margin-bottom: -50px; z-index: 10; position: relative;">
    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-success-subtle bg-success-subtle text-success small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= Session::getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-danger-subtle bg-danger-subtle text-danger small" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= Session::getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<div class="container-fluid" style="padding-top: 90px; min-height: 90vh;">
    <div class="row">
        
        <!-- Sidebar Navigation -->
        <nav class="col-lg-3 col-md-4 d-md-block border-end py-4 px-3 dashboard-sidebar" style="border-color: var(--border-color) !important;">
            <div class="d-flex flex-column align-items-center text-center pb-4 mb-4 border-bottom" style="border-color: var(--border-color) !important;">
                <div class="mb-3">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png"
                         alt="Bihar Vihaan Logo"
                         class="site-logo"
                         style="height: 40px;"
                         onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                </div>
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mb-2" style="width: 50px; height: 50px; font-weight: 700; font-size: 1.3rem;">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <h4 class="h6 fw-bold mb-1 font-outfit" style="color: var(--text-main);"><?= htmlspecialchars($user['name']) ?></h4>
                <span class="badge bg-primary px-3 py-1 rounded-pill small uppercase" style="font-size:0.65rem;"><?= htmlspecialchars(str_replace('_', ' ', $user['role_name'])) ?></span>
            </div>

            <ul class="nav nav-pills flex-column gap-1 dashboard-menu">
                <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link active" data-tab="profile-tab"><i class="fa-solid fa-user me-2"></i> Profile</a></li>
                <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="wishlist-tab"><i class="fa-solid fa-heart me-2"></i> Wishlist</a></li>
                <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="itinerary-tab"><i class="fa-solid fa-map-location-dot me-2"></i> Itineraries</a></li>
                <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="bookings-tab"><i class="fa-solid fa-bell-concierge me-2"></i> Bookings &amp; Tickets</a></li>
                <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="notifications-tab"><i class="fa-solid fa-bell me-2"></i> Alerts</a></li>

                <?php if (isset($artist_profile)): ?>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="artist-tab"><i class="fa-solid fa-guitar me-2"></i> Artist Panel</a></li>
                <?php endif; ?>

                <?php if ($user['role_name'] === 'business_manager' || Auth::hasRoles(['super_admin', 'admin'])): ?>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="business-owner-tab"><i class="fa-solid fa-briefcase me-2"></i> My Businesses</a></li>
                <?php endif; ?>

                <?php if (Auth::hasRoles(['super_admin', 'admin'])): ?>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="recruiter-tab"><i class="fa-solid fa-graduation-cap me-2"></i> Career Applications</a></li>
                <?php endif; ?>

                <?php if (Auth::hasRoles(['super_admin', 'content_manager', 'tourism_manager'])): ?>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="gallery-manager-tab"><i class="fa-solid fa-images me-2"></i> Gallery Manager</a></li>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="marketplace-manager-tab"><i class="fa-solid fa-store me-2"></i> Marketplace Admin</a></li>
                <?php endif; ?>

                <?php if (Auth::hasRoles(['super_admin', 'admin'])): ?>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="tourism-manager-tab"><i class="fa-solid fa-dharmachakra me-2"></i> Manage Tourism</a></li>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="events-organizer-tab"><i class="fa-solid fa-ticket me-2"></i> Manage Events</a></li>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="analytics-tab"><i class="fa-solid fa-chart-line me-2"></i> Analytics Dashboard</a></li>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="users-tab"><i class="fa-solid fa-users-gear me-2"></i> Users &amp; Roles</a></li>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="business-verifications-tab"><i class="fa-solid fa-circle-check me-2"></i> Directory Approvals</a></li>
                    <li class="nav-item"><a href="#" class="nav-link dashboard-menu-link" data-tab="logs-tab"><i class="fa-solid fa-shield-halved me-2"></i> Security Logs</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Main Panel Content -->
        <main class="col-lg-9 col-md-8 py-4 px-4 px-md-5">

            <!-- TAB 1: PROFILE -->
            <div id="profile-tab" class="dashboard-tab-content active">
                <h1 class="h2 fw-bold mb-4 font-outfit" style="color: var(--text-main);">Welcome, <?= htmlspecialchars($user['name']) ?></h1>
                
                <div class="card p-4 shadow max-w-xl" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <h3 class="h6 fw-bold mb-4 font-outfit uppercase border-bottom pb-2" style="color: var(--text-main); border-color: var(--border-color) !important;">Profile Specifications</h3>
                    <div class="row g-3 small">
                        <div class="col-md-4 fw-bold" style="color: var(--text-secondary);">FULL NAME:</div>
                        <div class="col-md-8 text-main" style="color: var(--text-main);"><?= htmlspecialchars($user['name']) ?></div>
                        
                        <div class="col-md-4 fw-bold" style="color: var(--text-secondary);">EMAIL ADDRESS:</div>
                        <div class="col-md-8 text-main" style="color: var(--text-main);"><?= htmlspecialchars($user['email']) ?></div>
                        
                        <div class="col-md-4 fw-bold" style="color: var(--text-secondary);">PHONE NUMBER:</div>
                        <div class="col-md-8 text-main" style="color: var(--text-main);"><?= htmlspecialchars($user['phone'] ?? 'Not set') ?></div>
                        
                        <div class="col-md-4 fw-bold" style="color: var(--text-secondary);">ROLE RANK:</div>
                        <div class="col-md-8 text-main" style="color: var(--text-main);"><span class="badge bg-primary"><?= htmlspecialchars($user['role_name']) ?></span></div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: WISHLIST -->
            <div id="wishlist-tab" class="dashboard-tab-content">
                <h2 class="h3 fw-bold mb-4 font-outfit" style="color: var(--text-main);">Saved Hotspots Wishlist</h2>
                <?php if (!empty($wishlist)): ?>
                    <div class="row g-4">
                        <?php foreach ($wishlist as $w): ?>
                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="custom-card flex-fill">
                                    <div class="custom-card-img-wrapper" style="height: 140px;">
                                        <img src="<?= htmlspecialchars($w['image_url']) ?>" class="custom-card-img" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                                    </div>
                                    <div class="custom-card-body p-3 text-center">
                                        <h3 class="h6 fw-bold mb-1" style="color: var(--text-main);"><?= htmlspecialchars($w['name']) ?></h3>
                                        <p class="small text-muted mb-3">📍 <?= htmlspecialchars($w['location']) ?></p>
                                        <a href="<?= BASE_URL ?>/tourism/<?= $w['id'] ?>" class="btn btn-primary btn-sm w-100">Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small">Wishlist is empty.</p>
                <?php endif; ?>
            </div>

            <!-- TAB 3: ITINERARIES -->
            <div id="itinerary-tab" class="dashboard-tab-content">
                <h2 class="h3 fw-bold mb-4 font-outfit" style="color: var(--text-main);">Travel Program Itineraries</h2>
                <?php if (!empty($my_itineraries)): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($my_itineraries as $itn): ?>
                            <div class="card p-4 shadow" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h3 class="h5 fw-bold mb-0 font-outfit" style="color: var(--text-main);"><?= htmlspecialchars($itn['title']) ?></h3>
                                    <span class="badge bg-primary"><?= $itn['duration_days'] ?> Days</span>
                                </div>
                                <p class="small text-muted mb-3"><?= htmlspecialchars($itn['description']) ?></p>
                                
                                <?php 
                                $db = \App\Core\Database::getInstance();
                                $days = $db->query("SELECT * FROM itinerary_days WHERE itinerary_id = ? ORDER BY day_number ASC", [$itn['id']]);
                                ?>
                                <div class="border-top pt-3 d-flex flex-column gap-2" style="border-color: var(--border-color) !important;">
                                    <?php foreach ($days as $day): ?>
                                        <div class="small">
                                            <span class="fw-bold text-primary">Day <?= $day['day_number'] ?>:</span>
                                            <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($day['activities'])) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small">No itineraries saved.</p>
                <?php endif; ?>
            </div>

            <!-- TAB 4: BOOKINGS -->
            <div id="bookings-tab" class="dashboard-tab-content">
                <h2 class="h3 fw-bold mb-4 font-outfit" style="color: var(--text-main);">Reservations &amp; Event Entries</h2>
                
                <h3 class="h6 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Accommodations &amp; Cabs</h3>
                <?php if (!empty($my_bookings)): ?>
                    <div class="table-custom-wrapper mb-4">
                        <table class="table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Item Name</th>
                                    <th>Date</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_bookings as $b): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary uppercase"><?= htmlspecialchars($b['booking_type']) ?></span></td>
                                        <td class="fw-bold" style="color: var(--text-main);"><?= htmlspecialchars($b['item_name']) ?></td>
                                        <td><?= date('d M Y', strtotime($b['start_date'])) ?></td>
                                        <td><?= $b['quantity_or_guests'] ?></td>
                                        <td class="text-primary fw-bold">₹<?= number_format($b['total_price']) ?></td>
                                        <td><span class="badge <?= $b['status'] === 'confirmed' ? 'bg-success' : 'bg-warning text-dark' ?>"><?= htmlspecialchars($b['status']) ?></span></td>
                                        <td>
                                            <?php if ($b['status'] === 'pending'): ?>
                                                <button class="btn btn-primary btn-sm py-0 px-2 small" onclick="window.initiatePayment('booking', <?= $b['id'] ?>, <?= $b['total_price'] ?>, '<?= addslashes($b['item_name']) ?>')">Pay</button>
                                            <?php else: ?>
                                                <span class="small text-muted">Ready</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-4">No reservations registered.</p>
                <?php endif; ?>

                <h3 class="h6 fw-bold mb-3 font-outfit" style="color: var(--text-main);">Event Passes</h3>
                <?php if (!empty($my_event_registrations)): ?>
                    <div class="table-custom-wrapper">
                        <table class="table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Venue &amp; Date</th>
                                    <th>Passes</th>
                                    <th>Rate</th>
                                    <th>Payment</th>
                                    <th>Entry QR Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_event_registrations as $er): ?>
                                    <tr>
                                        <td class="fw-bold" style="color: var(--text-main);"><?= htmlspecialchars($er['event_title']) ?></td>
                                        <td><?= htmlspecialchars($er['event_location']) ?><br><span class="xsmall text-muted"><?= date('d M Y', strtotime($er['event_date'])) ?></span></td>
                                        <td><?= $er['ticket_count'] ?> Passes</td>
                                        <td class="text-primary fw-bold">₹<?= number_format($er['total_price']) ?></td>
                                        <td><span class="badge <?= $er['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning text-dark' ?>"><?= htmlspecialchars($er['payment_status']) ?></span></td>
                                        <td>
                                            <?php if ($er['payment_status'] === 'paid'): ?>
                                                <code class="bg-primary-subtle text-primary p-1 rounded font-bold small"><?= $er['ticket_code'] ?></code>
                                            <?php else: ?>
                                                <button class="btn btn-primary btn-sm py-0 px-2 small" onclick="window.initiatePayment('event_registration', <?= $er['id'] ?>, <?= $er['total_price'] ?>, 'Tickets for <?= addslashes($er['event_title']) ?>')">Complete Payment</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted small">No tickets reserved.</p>
                <?php endif; ?>
            </div>

            <!-- TAB 5: ALERTS -->
            <div id="notifications-tab" class="dashboard-tab-content">
                <h2 class="h3 fw-bold mb-4 font-outfit" style="color: var(--text-main);">Notifications Center</h2>
                <?php if (!empty($notifications)): ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($notifications as $n): ?>
                            <div class="card p-3 rounded-3 position-relative small" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                                <span class="badge bg-secondary position-absolute top-0 end-0 m-3" style="font-size:0.65rem;"><?= htmlspecialchars($n['type']) ?></span>
                                <h4 class="h6 fw-bold mb-1" style="color: var(--text-main);"><?= htmlspecialchars($n['title']) ?></h4>
                                <p class="text-muted mb-1"><?= htmlspecialchars($n['message']) ?></p>
                                <span class="xsmall text-muted">Sent: <?= date('d M H:i', strtotime($n['created_at'])) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted small">No alerts logged.</p>
                <?php endif; ?>
            </div>

            <!-- TAB 6: ANALYTICS (SUPER ADMIN) -->
            <?php if (Auth::hasRoles(['super_admin', 'admin'])): ?>
                <div id="analytics-tab" class="dashboard-tab-content">
                    <h2 class="h3 fw-bold mb-4 font-outfit" style="color: var(--text-main);">System Operations &amp; Analytics</h2>
                    
                    <!-- Stats widgets -->
                    <div class="row g-3 mb-4 text-center">
                        <div class="col-md-4">
                            <div class="saas-stat-card shadow">
                                <span class="text-muted small uppercase">Gross Captured Revenue</span>
                                <div class="saas-stat-val text-primary">₹<?= number_format($total_revenue, 2) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="saas-stat-card shadow">
                                <span class="text-muted small uppercase">Directory Listings</span>
                                <div class="saas-stat-val"><?= count($all_listings) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="saas-stat-card shadow">
                                <span class="text-muted small uppercase">Gross Reservations</span>
                                <div class="saas-stat-val"><?= count($all_bookings) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- SVG Charts -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="card p-4 rounded-4 shadow" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                                <h3 class="h6 fw-bold mb-3" style="color: var(--text-main);">Hotspots Popularity (views)</h3>
                                <div class="bar-chart p-2 border-bottom d-flex align-items-end gap-3 justify-content-center" style="height: 200px; background-color: var(--bg-soft); border-color: var(--border-color) !important;">
                                    <?php foreach ($views_analytics as $view): 
                                        $percent = min(100, max(5, ($view['views_count'] / 6000) * 100));
                                    ?>
                                        <div class="bar-wrapper" style="height:100%; display:flex; flex-direction:column; justify-content:flex-end; align-items:center;">
                                            <div class="chart-bar" style="height: <?= $percent ?>%; background:var(--primary); width:30px; border-radius:4px 4px 0 0;" title="<?= $view['views_count'] ?> views"></div>
                                            <span class="xsmall text-muted text-truncate" style="max-width: 60px;"><?= htmlspecialchars($view['name']) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card p-4 rounded-4 shadow" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                                <h3 class="h6 fw-bold mb-3" style="color: var(--text-main);">Advertisement Click Rates</h3>
                                <?php if (!empty($ad_clicks)): ?>
                                    <div class="bar-chart p-2 border-bottom d-flex align-items-end gap-3 justify-content-center" style="height: 200px; background-color: var(--bg-soft); border-color: var(--border-color) !important;">
                                        <?php foreach ($ad_clicks as $ad): 
                                            $percent = min(100, max(5, ($ad['click_count'] / 50) * 100));
                                        ?>
                                            <div class="bar-wrapper" style="height:100%; display:flex; flex-direction:column; justify-content:flex-end; align-items:center;">
                                                <div class="chart-bar" style="height: <?= $percent ?>%; background:var(--primary); width:30px; border-radius:4px 4px 0 0;" title="<?= $ad['click_count'] ?> clicks"></div>
                                                <span class="xsmall text-muted text-truncate" style="max-width: 60px;"><?= htmlspecialchars($ad['ad_name']) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="small text-muted py-5 text-center">No ad clicks stats logged.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Ledger Transactions -->
                    <h3 class="h6 fw-bold mb-3 font-outfit uppercase border-bottom pb-2" style="color: var(--text-main); border-color: var(--border-color) !important;">Gateway Audit Trail</h3>
                    <?php if (!empty($transactions)): ?>
                        <div class="table-custom-wrapper">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>TXN ID</th>
                                        <th>User</th>
                                        <th>Reference</th>
                                        <th>Gross Amount</th>
                                        <th>Gateway</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $txn): ?>
                                        <tr>
                                            <td><code class="text-primary fw-bold" style="font-size: 13px;"><?= htmlspecialchars($txn['transaction_id']) ?></code></td>
                                            <td class="fw-bold" style="color: var(--text-main);"><?= htmlspecialchars($txn['user_name']) ?></td>
                                            <td><?= htmlspecialchars($txn['reference_type']) ?> #<?= $txn['reference_id'] ?></td>
                                            <td class="text-primary fw-bold">₹<?= number_format($txn['amount'], 2) ?></td>
                                            <td><?= htmlspecialchars($txn['gateway']) ?></td>
                                            <td><?= date('d M H:i', strtotime($txn['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small">No transactions recorded.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- TAB 7: USERS & ROLES MANAGER (ADMIN) -->
            <?php if (Auth::hasRoles(['super_admin', 'admin'])): ?>
                <div id="users-tab" class="dashboard-tab-content">
                    <h2 class="h3 fw-bold mb-4 font-outfit" style="color: var(--text-main);">Manage User Role Permissions</h2>
                    
                    <div class="table-custom-wrapper">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Authorization Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users_list as $usr): ?>
                                    <tr>
                                        <td class="fw-bold" style="color: var(--text-main);"><?= htmlspecialchars($usr['name']) ?></td>
                                        <td><?= htmlspecialchars($usr['email']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($usr['role_name']) ?></span></td>
                                        <td><span class="badge <?= $usr['status'] === 'active' ? 'bg-success' : 'bg-danger' ?>"><?= htmlspecialchars($usr['status']) ?></span></td>
                                        <td>
                                            <?php if (Auth::hasRole('super_admin') && $usr['id'] != Session::get('user_id')): ?>
                                                <form action="<?= BASE_URL ?>/dashboard/user-role" method="POST" class="d-inline-flex gap-2 align-items-center">
                                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                                    <input type="hidden" name="user_id" value="<?= $usr['id'] ?>">
                                                    
                                                    <select name="role_id" class="form-select form-select-sm py-0 small" style="width: auto;">
                                                        <option value="1" <?= $usr['role_id'] == 1 ? 'selected' : '' ?>>Super Admin</option>
                                                        <option value="2" <?= $usr['role_id'] == 2 ? 'selected' : '' ?>>Admin</option>
                                                        <option value="8" <?= $usr['role_id'] == 8 ? 'selected' : '' ?>>User</option>
                                                    </select>
                                                    
                                                    <select name="status" class="form-select form-select-sm py-0 small" style="width: auto;">
                                                        <option value="active" <?= $usr['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                                        <option value="suspended" <?= $usr['status'] === 'suspended' ? 'selected' : '' ?>>Suspend</option>
                                                    </select>
                                                    
                                                    <button type="submit" class="btn btn-primary btn-sm py-0 px-2 small">Update</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="xsmall text-muted">Locked</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (Auth::hasRoles(['super_admin', 'content_manager', 'tourism_manager'])): ?>
                <!-- Gallery Manager Tab -->
                <div id="gallery-manager-tab" class="dashboard-tab-content">
                    <h2 class="h4 fw-bold mb-4" style="color: var(--text-main);">Gallery Manager</h2>
                    
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#gallery-pending">Pending Uploads</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#gallery-active">Active Images</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Pending Images -->
                        <div id="gallery-pending" class="tab-pane fade show active">
                            <div class="row g-4">
                                <?php if(empty($gallery_pending)): ?>
                                    <div class="col-12"><p class="text-muted">No pending gallery images to review.</p></div>
                                <?php else: ?>
                                    <?php foreach($gallery_pending as $img): ?>
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <img src="<?= htmlspecialchars($img['image']) ?>" class="card-img-top" style="height: 150px; object-fit: cover;" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                                            <div class="card-body">
                                                <h5 class="card-title h6"><?= htmlspecialchars($img['title']) ?></h5>
                                                <p class="card-text small text-muted mb-1"><i class="fa-solid fa-tag"></i> <?= htmlspecialchars($img['category']) ?></p>
                                                <p class="card-text small text-muted mb-3"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($img['photographer']) ?></p>
                                                <form action="<?= BASE_URL ?>/dashboard/gallery-action" method="POST" class="d-flex gap-2">
                                                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm w-100">Approve</button>
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm w-100">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Active Images -->
                        <div id="gallery-active" class="tab-pane fade">
                            <div class="row g-4">
                                <?php if(empty($gallery_active)): ?>
                                    <div class="col-12"><p class="text-muted">No active images in the gallery.</p></div>
                                <?php else: ?>
                                    <?php foreach($gallery_active as $img): ?>
                                    <div class="col-md-3">
                                        <div class="card h-100">
                                            <img src="<?= htmlspecialchars($img['image']) ?>" class="card-img-top" style="height: 120px; object-fit: cover;" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                                            <div class="card-body p-2 text-center">
                                                <p class="mb-2 text-truncate small fw-bold"><?= htmlspecialchars($img['title']) ?></p>
                                                <form action="<?= BASE_URL ?>/dashboard/gallery-action" method="POST">
                                                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                                                    <button type="submit" name="action" value="delete" class="btn btn-outline-danger btn-sm py-0 w-100" onclick="return confirm('Delete this image?')">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Auth::hasRoles(['super_admin', 'content_manager', 'tourism_manager'])): ?>
                <!-- Marketplace Manager Tab -->
                <div id="marketplace-manager-tab" class="dashboard-tab-content">
                    <h2 class="h4 fw-bold mb-4" style="color: var(--text-main);">Marketplace Admin</h2>
                    
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#market-products">Products</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#market-artisans">Artisans</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#market-orders">Orders</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Products -->
                        <div id="market-products" class="tab-pane fade show active">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Manage Products</h5>
                                <button class="btn btn-primary btn-sm" onclick="alert('Feature coming soon')"><i class="fa-solid fa-plus"></i> Add Product</button>
                            </div>
                            <div class="table-responsive bg-white rounded shadow-sm border p-3">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Artisan</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($admin_products)): ?>
                                            <tr><td colspan="7" class="text-center">No products found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($admin_products as $p): ?>
                                            <tr>
                                                <td><?= $p['id'] ?></td>
                                                <td><img src="<?= htmlspecialchars($p['image_url']) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'"></td>
                                                <td><?= htmlspecialchars($p['name']) ?></td>
                                                <td><?= htmlspecialchars($p['artisan_name'] ?? 'Unknown') ?></td>
                                                <td>₹<?= number_format($p['price'], 2) ?></td>
                                                <td><?= $p['stock'] ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Artisans -->
                        <div id="market-artisans" class="tab-pane fade">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Manage Artisans</h5>
                                <button class="btn btn-primary btn-sm" onclick="alert('Feature coming soon')"><i class="fa-solid fa-plus"></i> Add Artisan</button>
                            </div>
                            <div class="table-responsive bg-white rounded shadow-sm border p-3">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Specialization</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($admin_artisans as $a): ?>
                                        <tr>
                                            <td><?= $a['id'] ?></td>
                                            <td><img src="<?= htmlspecialchars($a['image_url']) ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'"></td>
                                            <td><?= htmlspecialchars($a['name']) ?></td>
                                            <td><?= htmlspecialchars($a['specialization']) ?></td>
                                            <td><span class="badge bg-success">Verified</span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Orders -->
                        <div id="market-orders" class="tab-pane fade">
                            <div class="table-responsive bg-white rounded shadow-sm border p-3">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Delivery</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($admin_orders)): ?>
                                            <tr><td colspan="6" class="text-center">No orders found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($admin_orders as $o): ?>
                                            <tr>
                                                <td>BV-<?= str_pad($o['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                                <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                                                <td><?= htmlspecialchars($o['customer_name'] ?? $o['billing_name']) ?></td>
                                                <td>₹<?= number_format($o['total_price'], 2) ?></td>
                                                <td>
                                                    <span class="badge <?= $o['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning' ?>"><?= ucfirst($o['payment_status']) ?></span>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" style="width: 120px;">
                                                        <option value="processing" <?= $o['delivery_status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                                        <option value="shipped" <?= $o['delivery_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                                        <option value="delivered" <?= $o['delivery_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
