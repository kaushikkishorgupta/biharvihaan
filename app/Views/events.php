<?php
use App\Core\Auth;
use App\Core\Session;

$db = \App\Core\Database::getInstance();
$festivals = $db->query("SELECT * FROM festivals ORDER BY date ASC");
$events = $db->query("SELECT e.*, u.name as organizer_name FROM events e JOIN users u ON e.organizer_id = u.id WHERE e.status = 'active' ORDER BY e.date ASC");
?>

<div class="container py-5 mt-5">
    
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="section-heading mb-3" style="color: var(--text-main);">Cultural Events &amp; Festivals</h1>
        <p class="body-text-custom max-w-xl mx-auto" style="max-width: 650px; margin: 0 auto;">Register for Madhubani arts workshops, Sufi recitals, and search traditional holiday calendars.</p>
    </div>

    <!-- Active Ticketed Events -->
    <h2 class="h3 fw-bold mb-4 font-outfit border-left border-primary ps-3" style="border-left-width: 4px; color: var(--text-main);">Book Event Ticket Passes</h2>
    <div class="row g-4 mb-5" data-aos="fade-up">
        <?php foreach ($events as $event): ?>
            <div class="col-lg-4 col-md-6 d-flex">
                <div class="custom-card flex-fill">
                    <div class="custom-card-img-wrapper" style="height: 200px;">
                        <img src="<?= htmlspecialchars($event['image_url']) ?>" class="custom-card-img" alt="<?= htmlspecialchars($event['title']) ?>" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
                        <span class="custom-card-badge" style="background-color: var(--primary); color: white; border-color: var(--primary);"><?= $event['price'] > 0 ? '₹' . number_format($event['price']) : 'FREE' ?></span>
                    </div>
                    <div class="custom-card-body">
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>📅 <?= date('d M Y', strtotime($event['date'])) ?></span>
                            <span class="text-warning fw-bold"><?= $event['available_tickets'] ?> passes left</span>
                        </div>
                        <h3 class="custom-card-title"><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="custom-card-desc"><?= htmlspecialchars($event['description']) ?></p>
                        
                        <div class="pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color: var(--border-color) !important;">
                            <span class="small text-muted">Host: <?= htmlspecialchars($event['organizer_name']) ?></span>
                            
                            <?php if (Auth::check()): ?>
                                <div class="d-flex gap-2 align-items-center">
                                    <select id="tickets-count-<?= $event['id'] ?>" class="form-select form-select-sm py-1" style="width: auto; height: 34px;">
                                        <option value="1">1 Ticket</option>
                                        <option value="2">2 Tickets</option>
                                        <option value="3">3 Tickets</option>
                                    </select>
                                    <button class="btn btn-primary btn-sm px-3 fw-bold" onclick="bookEventTicket(<?= $event['id'] ?>, <?= $event['price'] ?>, '<?= addslashes($event['title']) ?>')">Book</button>
                                </div>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm px-3 rounded-pill">Log in to Book</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Complete Festival Calendar -->
    <h2 class="h3 fw-bold mb-4 font-outfit border-left border-secondary ps-3" style="border-left-width: 4px; color: var(--text-main);">Bihar Traditional Festivals Calendar</h2>
    <div class="table-custom-wrapper" data-aos="fade-up">
        <table class="table-custom mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">Festival Name</th>
                    <th class="py-3">Region / Location</th>
                    <th class="py-3">Date Scheduled</th>
                    <th class="py-3">Seasonal Info</th>
                    <th class="py-3 text-end px-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($festivals as $fest): ?>
                    <tr>
                        <td class="py-3 px-4 fw-bold" style="color: var(--text-main);"><?= htmlspecialchars($fest['name']) ?></td>
                        <td class="py-3">📍 <?= htmlspecialchars($fest['location']) ?></td>
                        <td class="py-3 fw-bold text-primary"><?= date('d M Y', strtotime($fest['date'])) ?></td>
                        <td class="py-3"><span class="badge bg-success px-2 py-1 rounded-pill small"><?= htmlspecialchars($fest['season']) ?></span></td>
                        <td class="py-3 text-end px-4">
                            <button class="btn btn-outline btn-sm" onclick="alert('Notification alert scheduled for <?= htmlspecialchars($fest['name']) ?>!')">Set Alert</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function bookEventTicket(eventId, price, title) {
    const select = document.getElementById('tickets-count-' + eventId);
    const count = parseInt(select.value);
    const totalPrice = price * count;

    const formData = new FormData();
    formData.append('event_id', eventId);
    formData.append('ticket_count', count);
    formData.append('total_price', totalPrice);

    fetch('<?= BASE_URL ?>/api/event/register', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            window.initiatePayment(
                'event_registration',
                res.registration_id,
                totalPrice,
                `${count}x Entry Tickets for ${title}`
            );
        } else {
            alert("Booking failed: " + res.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Verification request failed.");
    });
}
</script>
