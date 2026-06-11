<?php
use App\Core\Auth;
use App\Core\Session;
$csrfToken = Session::generateCsrfToken();
?>

<div class="container py-5 mt-5">
    
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="display-5 fw-bold text-white font-outfit">Accommodation &amp; Travel Reservations</h1>
        <p class="text-muted">Direct bookings with local transport cabs, verified hotels, and tour packages in Bihar.</p>
    </div>

    <div class="row g-5">
        
        <!-- Catalog lists -->
        <div class="col-lg-8" data-aos="fade-right">
            <!-- Hotels -->
            <h2 class="h4 text-white fw-bold mb-4 font-outfit border-left border-primary ps-3" style="border-left-width: 4px;">Verified Hotel Partners</h2>
            <div class="row g-3 mb-5">
                <div class="col-md-6">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-white fw-bold mb-2 font-outfit">Hotel Bodhgaya Regency</h3>
                            <p class="small text-muted mb-4">Meditation halls, premium buffet dining, close to UNESCO Temple Gate.</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                            <span class="fw-bold text-primary fs-5">₹3,500 <span class="small text-muted fw-normal">/ night</span></span>
                            <button class="btn btn-outline-light btn-sm px-3 rounded-pill" onclick="populateBookingForm('hotel', 'Hotel Bodhgaya Regency', 3500)">Select Room</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-white fw-bold mb-2 font-outfit">The Maurya Patna</h3>
                            <p class="small text-muted mb-4">5-Star premium hotel located in central Gandhi Maidan Patna City.</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                            <span class="fw-bold text-primary fs-5">₹5,200 <span class="small text-muted fw-normal">/ night</span></span>
                            <button class="btn btn-outline-light btn-sm px-3 rounded-pill" onclick="populateBookingForm('hotel', 'The Maurya Patna', 5200)">Select Room</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guides -->
            <h2 class="h4 text-white fw-bold mb-4 font-outfit border-left border-secondary ps-3" style="border-left-width: 4px;">Local Tour Guides</h2>
            <div class="row g-3 mb-5">
                <div class="col-md-6">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-white fw-bold mb-2 font-outfit">Nalanda Archaeology Guide</h3>
                            <p class="small text-muted mb-4">15+ years experience walking scholars across red-brick monastery ruins.</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                            <span class="fw-bold text-primary fs-5">₹1,200 <span class="small text-muted fw-normal">/ day</span></span>
                            <button class="btn btn-outline-light btn-sm px-3 rounded-pill" onclick="populateBookingForm('guide', 'Nalanda Specialist Guide', 1200)">Hire Guide</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-white fw-bold mb-2 font-outfit">Rajgir Nature Safari Guide</h3>
                            <p class="small text-muted mb-4">Specialist in histories of Emperor Bimbisara and ropeway trails.</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                            <span class="fw-bold text-primary fs-5">₹1,000 <span class="small text-muted fw-normal">/ day</span></span>
                            <button class="btn btn-outline-light btn-sm px-3 rounded-pill" onclick="populateBookingForm('guide', 'Rajgir History Guide', 1000)">Hire Guide</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages -->
            <h2 class="h4 text-white fw-bold mb-4 font-outfit border-left border-primary ps-3" style="border-left-width: 4px;">Curated Travel Circuits</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-white fw-bold mb-2 font-outfit">Bodh Gaya-Rajgir-Nalanda Circuit</h3>
                            <p class="small text-muted mb-4">3D/2N circuit including private cab transport and rooms booking.</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                            <span class="fw-bold text-primary fs-5">₹11,999 <span class="small text-muted fw-normal">total</span></span>
                            <button class="btn btn-outline-light btn-sm px-3 rounded-pill" onclick="populateBookingForm('tour', 'Bodh Gaya-Rajgir-Nalanda Tour', 11999)">Book Package</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-white fw-bold mb-2 font-outfit">Valmiki Tiger Safari Trail</h3>
                            <p class="small text-muted mb-4">2D/1N package with eco safari tickets and jungle room logging.</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                            <span class="fw-bold text-primary fs-5">₹6,500 <span class="small text-muted fw-normal">total</span></span>
                            <button class="btn btn-outline-light btn-sm px-3 rounded-pill" onclick="populateBookingForm('tour', 'Valmiki Tiger Safari Tour', 6500)">Book Package</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Sidebar -->
        <div class="col-lg-4" data-aos="fade-left">
            <div class="card bg-card border-secondary-subtle rounded-4 p-4 shadow-lg sticky-top" style="top: 100px;">
                <h3 class="h5 text-white fw-bold mb-1 font-outfit">Reservation Desk</h3>
                <p class="text-muted small mb-4">Complete details to schedule rooms or guides.</p>

                <?php if (Auth::check()): ?>
                    <form action="<?= BASE_URL ?>/bookings/request" method="POST" id="booking-request-form">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Category</label>
                            <select name="booking_type" id="form-booking-type" class="form-select bg-dark border-secondary text-white" required>
                                <option value="hotel">Hotel Room</option>
                                <option value="tour">Tour Circuit</option>
                                <option value="guide">Guide Hire</option>
                                <option value="transport">Cab Rental</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Name / Item</label>
                            <input type="text" name="item_name" id="form-item-name" class="form-control bg-dark border-secondary text-white" placeholder="Select from left or enter name" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Start Date</label>
                                <input type="date" name="start_date" class="form-control bg-dark border-secondary text-white" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">End Date</label>
                                <input type="date" name="end_date" class="form-control bg-dark border-secondary text-white">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase" id="quantity-label">Number of Guests</label>
                            <input type="number" name="quantity_or_guests" id="form-quantity" class="form-control bg-dark border-secondary text-white" value="1" min="1" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Gross Total (INR)</label>
                            <input type="number" name="total_price" id="form-price" class="form-control bg-dark border-secondary text-primary fw-bold fs-5" value="0" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Submit Booking Request</button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-4 border border-secondary border-dashed rounded-3">
                        <p class="small text-muted mb-3">Login to book accommodations.</p>
                        <a href="<?= BASE_URL ?>/login" class="btn btn-primary btn-sm">Log In Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
let baseItemPrice = 0;

function populateBookingForm(type, name, basePrice) {
    const typeSelect = document.getElementById('form-booking-type');
    const nameInput = document.getElementById('form-item-name');
    const priceInput = document.getElementById('form-price');
    const quantityLabel = document.getElementById('quantity-label');

    if (typeSelect && nameInput && priceInput) {
        typeSelect.value = type;
        nameInput.value = name;
        baseItemPrice = basePrice;
        
        if (type === 'hotel') {
            quantityLabel.textContent = 'Number of Guests';
        } else if (type === 'guide') {
            quantityLabel.textContent = 'Number of Days';
        } else {
            quantityLabel.textContent = 'Number of Travelers';
        }

        calculateTotalPrice();
    }
}

function calculateTotalPrice() {
    const qtyInput = document.getElementById('form-quantity');
    const priceInput = document.getElementById('form-price');
    if (qtyInput && priceInput) {
        const qty = parseInt(qtyInput.value) || 1;
        priceInput.value = baseItemPrice * qty;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const qtyInput = document.getElementById('form-quantity');
    if (qtyInput) {
        qtyInput.addEventListener('input', calculateTotalPrice);
    }
});
</script>
