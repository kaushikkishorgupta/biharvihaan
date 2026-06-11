<?php
use App\Core\Auth;
?>
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8">
            <img src="<?= htmlspecialchars($event['image_url']) ?>" class="img-fluid rounded mb-4 w-100" style="object-fit: cover; max-height: 400px;" alt="<?= htmlspecialchars($event['title']) ?>" onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/fallback.jpg';">
            <h1 class="fw-bold mb-3" style="color: var(--text-main);"><?= htmlspecialchars($event['title']) ?></h1>
            <p class="text-muted"><i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($event['date'])) ?> &nbsp;&nbsp; <i class="bi bi-clock"></i> <?= htmlspecialchars($event['time']) ?> &nbsp;&nbsp; <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($event['location']) ?></p>
            
            <div class="mt-4">
                <h3 class="fw-bold mb-3">About the Event</h3>
                <p style="white-space: pre-line; color: var(--text-secondary);"><?= htmlspecialchars($event['description']) ?></p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="custom-card p-4 sticky-top" style="top: 100px;">
                <h3 class="fw-bold mb-3" style="color: var(--text-main);">Ticket Booking</h3>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Price:</span>
                    <span class="fw-bold" style="color: var(--primary);"><?= $event['price'] > 0 ? '₹' . number_format($event['price']) : 'FREE' ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4 border-bottom pb-2">
                    <span class="text-muted">Available:</span>
                    <span class="fw-bold text-warning"><?= $event['available_tickets'] ?> passes left</span>
                </div>

                <?php if (Auth::check()): ?>
                    <label for="ticket-count" class="form-label text-muted small">Select Quantity:</label>
                    <select id="ticket-count" class="form-select mb-4">
                        <option value="1">1 Ticket</option>
                        <option value="2">2 Tickets</option>
                        <option value="3">3 Tickets</option>
                        <option value="4">4 Tickets</option>
                        <option value="5">5 Tickets</option>
                    </select>
                    <button class="btn btn-primary w-100 py-2 fw-bold" onclick="bookEventDetailTicket(<?= $event['id'] ?>, <?= $event['price'] ?>, '<?= addslashes($event['title']) ?>')">Proceed to Checkout</button>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="btn btn-outline w-100 py-2 fw-bold rounded-pill" style="border-color: var(--primary); color: var(--primary);">Log in to Book</a>
                <?php endif; ?>
                
                <p class="small text-muted mt-3 text-center mb-0">Organized by <?= htmlspecialchars($event['organizer_name']) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function bookEventDetailTicket(eventId, price, title) {
    const count = parseInt(document.getElementById('ticket-count').value);
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
