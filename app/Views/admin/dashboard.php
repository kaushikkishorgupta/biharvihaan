

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 fw-bold text-white" style="font-family: var(--font-display);">Command Center Overview</h1>
        <p class="text-secondary mb-0">Live analytics and ecosystem statistics for Bihar Vihaan.</p>
    </div>
    <div>
        <button class="btn btn-outline-light rounded-pill px-4" onclick="window.print();"><i class="fa-solid fa-print me-2"></i> Print Report</button>
    </div>
</div>

<!-- Analytics Cards Grid (8 Cards) -->
<div class="row g-4 mb-4">
    <!-- Destinations -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Destinations</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['destinations'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-map-location-dot text-primary fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-arrow-up"></i> 8% growth</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">vs last quarter</span>
            </div>
        </div>
    </div>
    
    <!-- Events -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Upcoming Events</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['events'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-calendar-days text-warning fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-clock"></i> Active schedule</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">festivals synced</span>
            </div>
        </div>
    </div>

    <!-- Blogs -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Blogs & Articles</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['blogs'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-info bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-blog text-info fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-pen-nib"></i> Weekly updates</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">in English & Hindi</span>
            </div>
        </div>
    </div>

    <!-- Gallery Images -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Gallery Images</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['gallery_images'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-success bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-images text-success fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-arrow-up"></i> 14%</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">new uploads</span>
            </div>
        </div>
    </div>

    <!-- Videos -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Videos</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['videos'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-circle-play text-danger fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-bolt"></i> HD Streamed</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">reels and streams</span>
            </div>
        </div>
    </div>

    <!-- Marketplace Products -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Marketplace Products</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['products'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-store text-primary fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-arrow-up"></i> 5 new</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">added this week</span>
            </div>
        </div>
    </div>

    <!-- Businesses -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Registered Businesses</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['businesses'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-secondary bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-building-circle-check text-secondary fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa-solid fa-check-double"></i> Verified profiles</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">hotels & guides</span>
            </div>
        </div>
    </div>

    <!-- AI Planner Trips -->
    <div class="col-xl-3 col-md-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-secondary mb-1 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">AI Trips Generated</p>
                    <h3 class="mb-0 fw-bold text-white"><?= number_format($stats['ai_trips'] ?? 0) ?></h3>
                </div>
                <div class="p-3 bg-info bg-opacity-10 rounded-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-info fs-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-info bg-opacity-10 text-info"><i class="fa-solid fa-spinner fa-spin-slow"></i> AI Agent Online</span>
                <span class="text-secondary ms-2" style="font-size: 0.8rem;">recommending gems</span>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Charts Dashboard Grid -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Visitor Trends -->
    <div class="col-lg-8">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold text-white"><i class="fa-solid fa-chart-line text-primary me-2"></i>Visitor Analytics</h5>
                <small class="text-secondary">Last 7 Days (Realtime)</small>
            </div>
            <div style="height: 300px;">
                <canvas id="visitorChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: AI Planner Usage -->
    <div class="col-lg-4">
        <div class="glass-card h-100">
            <h5 class="mb-4 fw-bold text-white"><i class="fa-solid fa-brain text-info me-2"></i>AI Planner Travel Styles</h5>
            <div style="height: 250px;">
                <canvas id="plannerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 3: Tourism Growth -->
    <div class="col-md-4">
        <div class="glass-card h-100">
            <h5 class="mb-4 fw-bold text-white"><i class="fa-solid fa-arrow-trend-up text-warning me-2"></i>Monthly Tourism Growth</h5>
            <div style="height: 220px;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 4: Event Registrations -->
    <div class="col-md-4">
        <div class="glass-card h-100">
            <h5 class="mb-4 fw-bold text-white"><i class="fa-solid fa-ticket text-success me-2"></i>Festival Bookings</h5>
            <div style="height: 220px;">
                <canvas id="eventsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 5: Marketplace Revenue -->
    <div class="col-md-4">
        <div class="glass-card h-100">
            <h5 class="mb-4 fw-bold text-white"><i class="fa-solid fa-indian-rupee-sign text-success me-2"></i>Marketplace Revenue</h5>
            <div style="height: 220px;">
                <canvas id="marketplaceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold text-white"><i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Audit Trail & Logs</h5>
                <a href="<?= BASE_URL ?>/admin/logs" class="btn btn-sm btn-outline-light rounded-pill">View All Logs</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-secondary">
                            <th class="text-secondary pb-3">Operator</th>
                            <th class="text-secondary pb-3">Action Description</th>
                            <th class="text-secondary pb-3">IP Address</th>
                            <th class="text-secondary pb-3">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($activities)): ?>
                            <?php foreach($activities as $log): ?>
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fa-solid fa-user-shield text-primary"></i>
                                        </div>
                                        <span class="text-white fw-semibold"><?= htmlspecialchars($log['user_name'] ?? 'System') ?></span>
                                    </div>
                                </td>
                                <td class="py-3 text-light">
                                    <strong><?= htmlspecialchars($log['action']) ?></strong>
                                    <?php if($log['details']): ?>
                                        <div class="small text-secondary mt-1"><?= htmlspecialchars($log['details']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-secondary"><?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?></td>
                                <td class="py-3 text-secondary"><?= date('M d, Y \a\t g:i A', strtotime($log['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-secondary">No logs found in audit trail.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

