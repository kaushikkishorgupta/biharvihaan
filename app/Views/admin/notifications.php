<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-bell text-primary me-2"></i> Notifications Center</h4>
            <p class="text-secondary small mb-0 font-primary">Track customer transactions, new registered guides, local business listings reviews, and message feeds.</p>
        </div>
        <form action="<?= BASE_URL ?>/admin/notifications/read" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit" class="btn btn-outline-light rounded-pill px-4 btn-sm"><i class="fa-solid fa-envelope-open me-2"></i> Mark All as Read</button>
        </form>
    </div>

    <div class="d-flex flex-column gap-3">
        <?php if(empty($notifications)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="fa-regular fa-bell-slash fa-3x mb-3 text-secondary"></i>
                <h6 class="fw-bold">No notifications found</h6>
                <p class="small">You're all caught up! New notices will display here.</p>
            </div>
        <?php else: foreach($notifications as $n): 
            $isUnread = ($n['status'] === 'unread' || !$n['is_read']);
            
            // Map icons based on type/content
            $icon = 'fa-solid fa-circle-info text-info';
            $bgClass = 'bg-info bg-opacity-10';
            $type = strtolower($n['type'] ?? '');
            
            if (strpos($type, 'order') !== false) {
                $icon = 'fa-solid fa-shopping-cart text-success';
                $bgClass = 'bg-success bg-opacity-10';
            } elseif (strpos($type, 'user') !== false) {
                $icon = 'fa-solid fa-user-plus text-primary';
                $bgClass = 'bg-primary bg-opacity-10';
            } elseif (strpos($type, 'review') !== false) {
                $icon = 'fa-solid fa-star text-warning';
                $bgClass = 'bg-warning bg-opacity-10';
            } elseif (strpos($type, 'event') !== false) {
                $icon = 'fa-solid fa-calendar-plus text-danger';
                $bgClass = 'bg-danger bg-opacity-10';
            } elseif (strpos($type, 'contact') !== false || strpos($type, 'message') !== false) {
                $icon = 'fa-solid fa-envelope text-secondary';
                $bgClass = 'bg-secondary bg-opacity-10';
            }
        ?>
            <div class="p-3 rounded-4 border d-flex justify-content-between align-items-center <?= $isUnread ? 'bg-dark bg-opacity-40 border-primary shadow-sm' : 'bg-dark bg-opacity-10 border-secondary' ?>" style="transition: all 0.2s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 <?= $bgClass ?> d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                        <i class="<?= $icon ?> fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold text-white"><?= htmlspecialchars($n['title']) ?></h6>
                        <p class="text-secondary small mb-0 font-primary"><?= htmlspecialchars($n['message']) ?></p>
                        <small class="text-secondary font-monospace mt-1 d-block" style="font-size:0.7rem;"><?= date('M d, Y \a\t g:i A', strtotime($n['created_at'])) ?></small>
                    </div>
                </div>
                
                <?php if($isUnread): ?>
                    <form action="<?= BASE_URL ?>/admin/notifications/read" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="id" value="<?= $n['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fa-solid fa-check"></i> Read</button>
                    </form>
                <?php else: ?>
                    <span class="text-secondary small opacity-50"><i class="fa-solid fa-check-double text-success"></i> Read</span>
                <?php endif; ?>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>
