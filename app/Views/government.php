<div class="container py-5 mt-5">
    <div class="text-center mb-5" data-aos="fade-down">
        <span class="badge rounded-pill bg-teal-subtle text-teal px-3 py-2 fs-6 mb-3 text-uppercase tracking-wider border border-teal-subtle">Government & Institutional Portal</span>
        <h1 class="display-4 font-outfit fw-extrabold text-transparent bg-clip-text bg-gradient-orange-teal">Public Announcements & NGO Networks</h1>
        <p class="lead text-muted max-w-xl mx-auto">Access official circulars from the Department of Tourism, check public safety alerts, and explore NGO events across Bihar.</p>
    </div>

    <div class="row g-4">
        <!-- Announcements Feed Column -->
        <div class="col-lg-8" data-aos="fade-right">
            <h4 class="font-outfit fw-bold text-white mb-4 d-flex align-items-center">
                <i class="fa-solid fa-bullhorn text-primary me-2 fs-5"></i> Departmental Circulars & Safety Alerts
            </h4>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($announcements as $announce): ?>
                    <div class="p-4 rounded-4 bg-glass border border-secondary-subtle shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-secondary bg-opacity-50 text-teal fs-8 px-2 py-1 border border-teal-subtle"><?= htmlspecialchars($announce['dept']) ?></span>
                            <span class="fs-8 text-muted"><?= htmlspecialchars($announce['date']) ?></span>
                        </div>
                        <h5 class="font-outfit fw-bold text-white mb-3"><?= htmlspecialchars($announce['title']) ?></h5>
                        <p class="mb-0 text-muted fs-7.5 leading-relaxed"><?= htmlspecialchars($announce['details']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Institutional Events Column -->
        <div class="col-lg-4" data-aos="fade-left" data-aos-delay="150">
            <h4 class="font-outfit fw-bold text-white mb-4 d-flex align-items-center">
                <i class="fa-solid fa-ribbon text-teal me-2 fs-5"></i> Free Public Awareness Events
            </h4>

            <?php if (empty($officialEvents)): ?>
                <div class="p-4 rounded-4 bg-glass border border-secondary-subtle text-center text-muted">
                    <p>No active free public events scheduled at this time.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($officialEvents as $event): ?>
                        <div class="p-3 bg-glass-dark border border-secondary-subtle rounded-3 shadow-sm">
                            <span class="badge bg-success bg-opacity-20 text-success border border-success-subtle mb-2.5 fs-8">Free Public Event</span>
                            <h6 class="font-outfit fw-bold text-white mb-1.5"><?= htmlspecialchars($event['title']) ?></h6>
                            <p class="fs-8 text-muted mb-2"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($event['location']) ?></p>
                            <span class="fs-8 text-white-50"><i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y', strtotime($event['date'])) ?> &bull; <?= $event['time'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
