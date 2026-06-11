<?php
use App\Core\Session;
?>
<div class="container py-5 mt-5">
    <!-- Flash Messages -->
    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success bg-success-subtle border-success text-success-emphasis alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?= htmlspecialchars(Session::getFlash('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-danger bg-danger-subtle border-danger text-danger-emphasis alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?= htmlspecialchars(Session::getFlash('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!$hasBusiness): ?>
        <!-- Warning when no business listings are owned by user -->
        <div class="row py-5" data-aos="fade-up">
            <div class="col-lg-6 offset-lg-3 text-center">
                <div class="p-5 rounded-4 bg-glass border border-secondary-subtle shadow-lg">
                    <div class="display-3 text-warning mb-3">
                        <i class="fa-solid fa-store-slash"></i>
                    </div>
                    <h3 class="font-outfit fw-bold text-white mb-2">No Active Business Listings</h3>
                    <p class="text-muted mb-4">You need to register at least one business listing (Hotels, Tours, Art shop, guide services) before you can access the lead tracker and follow-up CRM dashboard.</p>
                    <a href="<?= BASE_URL ?>/business" class="btn btn-primary rounded-pill px-4">
                        Register Business Listing <i class="fa-solid fa-plus ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    <?php elseif (isset($view_mode) && $view_mode === 'detail'): ?>
        <!-- ==========================================
             CRM LEAD DETAIL & TIMELINE VIEW
             ========================================== -->
        <div class="row g-4">
            <!-- Left Info Panel -->
            <div class="col-lg-5" data-aos="fade-right">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fs-8 text-muted uppercase tracking-wider">Lead Information</span>
                        <?php
                        $scoreColor = 'bg-danger';
                        if ($lead['score'] >= 75) $scoreColor = 'bg-success';
                        elseif ($lead['score'] >= 45) $scoreColor = 'bg-warning';
                        ?>
                        <span class="badge <?= $scoreColor ?> px-3 py-2 rounded-pill fs-7 fw-bold">Score: <?= $lead['score'] ?>/100</span>
                    </div>

                    <h3 class="font-outfit fw-extrabold text-white mb-2"><?= htmlspecialchars($lead['name']) ?></h3>
                    <p class="text-teal font-semibold mb-4"><i class="fa-solid fa-building me-1"></i> <?= htmlspecialchars($lead['business_name']) ?></p>
                    
                    <div class="border-top border-secondary-subtle pt-3 fs-7.5">
                        <div class="d-flex justify-content-between py-2 text-muted">
                            <span>Email:</span>
                            <span class="text-white"><?= htmlspecialchars($lead['email'] ?: 'N/A') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2 text-muted">
                            <span>Phone:</span>
                            <span class="text-white"><?= htmlspecialchars($lead['phone']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2 text-muted">
                            <span>Inquiry Source:</span>
                            <span class="badge bg-secondary text-capitalize fs-8"><?= str_replace('_', ' ', $lead['source']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2 text-muted">
                            <span>Current Status:</span>
                            <?php
                            $badge = 'bg-info';
                            if ($lead['status'] === 'contacted') $badge = 'bg-warning ';
                            elseif ($lead['status'] === 'qualified') $badge = 'bg-primary';
                            elseif ($lead['status'] === 'won') $badge = 'bg-success';
                            elseif ($lead['status'] === 'lost') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge ?> text-capitalize fs-8"><?= $lead['status'] ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2 text-muted">
                            <span>Acquired At:</span>
                            <span class="text-white"><?= date('d M Y, h:i A', strtotime($lead['created_at'])) ?></span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-secondary-subtle">
                        <a href="<?= $whatsappUrl ?>" target="_blank" class="btn btn-success w-100 py-2.5 rounded-pill shadow-sm fw-bold">
                            <i class="fa-brands fa-whatsapp me-2 fs-5"></i> Launch WhatsApp CRM Chat
                        </a>
                    </div>
                </div>

                <!-- Update Status & Add Note Form -->
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm">
                    <h5 class="font-outfit fw-bold text-white mb-3">Add Customer Interaction Log</h5>
                    <form action="<?= BASE_URL ?>/crm/add-note" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Session::getCsrfToken() ?>">
                        <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">

                        <div class="mb-3">
                            <label for="status" class="form-label fs-8 text-muted uppercase">Update Lead Stage</label>
                            <select class="form-select bg-dark border-secondary text-white" id="status" name="status">
                                <option value="new" <?= ($lead['status'] === 'new') ? 'selected' : '' ?>>New / Uncontacted</option>
                                <option value="contacted" <?= ($lead['status'] === 'contacted') ? 'selected' : '' ?>>Contacted (Awaiting Reply)</option>
                                <option value="qualified" <?= ($lead['status'] === 'qualified') ? 'selected' : '' ?>>Qualified Opportunity</option>
                                <option value="won" <?= ($lead['status'] === 'won') ? 'selected' : '' ?>>Closed Won (Deal Completed)</option>
                                <option value="lost" <?= ($lead['status'] === 'lost') ? 'selected' : '' ?>>Closed Lost</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="follow_up_date" class="form-label fs-8 text-muted uppercase">Set Follow-up Schedule</label>
                            <input type="date" class="form-control bg-dark border-secondary text-white" id="follow_up_date" name="follow_up_date">
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fs-8 text-muted uppercase">Interaction Notes / Summary</label>
                            <textarea class="form-control bg-dark border-secondary text-white" id="note" name="note" rows="3" required placeholder="Log details of call, meeting, or email conversation..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5">
                            Save Interaction Log
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Timeline Panel -->
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="font-outfit fw-bold text-white mb-0">Follow-up Timeline Log</h4>
                        <a href="<?= BASE_URL ?>/crm" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fa-solid fa-arrow-left me-2"></i> Leads List
                        </a>
                    </div>

                    <?php if (empty($notes)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-clock-rotate-left fs-2 mb-2"></i>
                            <p>No follow-up notes logged for this customer inquiry.</p>
                        </div>
                    <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($notes as $n): ?>
                                <div class="timeline-item pb-4 position-relative pl-4 border-l border-secondary-subtle">
                                    <div class="timeline-marker position-absolute top-1 left-0 rounded-circle bg-teal" style="width: 12px; height: 12px; transform: translateX(-6px);"></div>
                                    <div class="p-3 bg-glass-dark border border-secondary-subtle rounded-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fs-7.5 fw-bold text-teal"><?= htmlspecialchars($n['author_name']) ?></span>
                                            <span class="fs-8 text-muted"><?= date('d M Y, h:i A', strtotime($n['created_at'])) ?></span>
                                        </div>
                                        <p class="mb-0 text-white-85 fs-7.5 leading-relaxed"><?= nl2br(htmlspecialchars($n['note'])) ?></p>
                                        <?php if ($n['follow_up_date']): ?>
                                            <div class="mt-2.5 pt-2 border-top border-secondary-subtle fs-8 text-warning fw-bold">
                                                <i class="fa-solid fa-calendar-days me-1"></i> Next Follow-up Scheduled: <?= date('d M Y', strtotime($n['follow_up_date'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- ==========================================
             CRM LEADS OVERVIEW LIST DASHBOARD
             ========================================== -->
        <div class="text-center mb-5" data-aos="fade-down">
            <span class="badge rounded-pill bg-teal-subtle text-teal px-3 py-2 fs-6 mb-3 text-uppercase tracking-wider border border-teal-subtle">Business CRM</span>
            <h1 class="display-4 font-outfit fw-extrabold text-transparent bg-clip-text bg-gradient-orange-teal">Inquiry Tracker & Lead Manager</h1>
            <p class="lead text-muted max-w-xl mx-auto">Analyze incoming customer inquiries, score leads interest levels, schedule callbacks, and trigger WhatsApp notifications.</p>
        </div>

        <div class="card bg-glass border border-secondary-subtle rounded-4 overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header bg-dark bg-opacity-40 border-bottom border-secondary-subtle px-4 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-outfit fw-bold text-white"><i class="fa-solid fa-list me-2"></i> Active Leads Inquiries</h5>
                <span class="badge bg-teal bg-opacity-20 text-teal border border-teal-subtle"><?= count($leads) ?> Total Inquiries</span>
            </div>
            
            <div class="card-body p-0">
                <?php if (empty($leads)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-users-slash fs-1 text-muted mb-3"></i>
                        <h4>No Leads Logged Yet</h4>
                        <p class="text-muted">Inquiries sent by customers through directory pages will automatically appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead class="table-light  fs-7">
                                <tr>
                                    <th scope="col" class="ps-4">Client Name</th>
                                    <th scope="col">Business Listing</th>
                                    <th scope="col">Contact Info</th>
                                    <th scope="col" class="text-center">Channel</th>
                                    <th scope="col" class="text-center">Lead Score</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leads as $l): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-white"><?= htmlspecialchars($l['name']) ?></div>
                                            <span class="fs-8 text-muted">Acquired: <?= date('d M Y', strtotime($l['created_at'])) ?></span>
                                        </td>
                                        <td>
                                            <span class="text-teal font-semibold fs-7.5"><?= htmlspecialchars($l['business_name']) ?></span>
                                        </td>
                                        <td>
                                            <div class="fs-7.5 text-white"><?= htmlspecialchars($l['phone']) ?></div>
                                            <span class="fs-8 text-muted"><?= htmlspecialchars($l['email']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($l['source'] === 'whatsapp'): ?>
                                                <span class="badge bg-success bg-opacity-20 text-success border border-success-subtle px-2.5 py-1.5"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-50 text-white px-2.5 py-1.5"><i class="fa-solid fa-globe me-1"></i> Web Form</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="progress bg-dark bg-opacity-60 border border-secondary-subtle" style="width: 70px; height: 8px; border-radius: 4px;">
                                                    <?php
                                                    $barColor = 'bg-danger';
                                                    if ($l['score'] >= 75) $barColor = 'bg-success';
                                                    elseif ($l['score'] >= 45) $barColor = 'bg-warning';
                                                    ?>
                                                    <div class="progress-bar <?= $barColor ?>" role="progressbar" style="width: <?= $l['score'] ?>%" aria-valuenow="<?= $l['score'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="fs-8 fw-bold text-white"><?= $l['score'] ?>%</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $badge = 'bg-info';
                                            if ($l['status'] === 'contacted') $badge = 'bg-warning ';
                                            elseif ($l['status'] === 'qualified') $badge = 'bg-primary';
                                            elseif ($l['status'] === 'won') $badge = 'bg-success';
                                            elseif ($l['status'] === 'lost') $badge = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badge ?> text-capitalize fs-8 px-2.5 py-1.5"><?= $l['status'] ?></span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="<?= BASE_URL ?>/crm/detail/<?= $l['id'] ?>" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm">
                                                Manage Lead <i class="fa-solid fa-arrow-right ms-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
