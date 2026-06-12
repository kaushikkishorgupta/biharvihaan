<?php include 'layout/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="fw-bold font-outfit mb-4">Checkout</h2>

    <div class="row g-4">
        <!-- Billing Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold font-outfit mb-4">Billing Details</h5>
                    <form id="checkout-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Full Name</label>
                                <input type="text" id="billing_name" name="billing_name" class="form-control" required value="<?= App\Core\Session::isLoggedIn() ? htmlspecialchars(App\Core\Session::get('user_name')) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Email Address</label>
                                <input type="email" id="billing_email" name="billing_email" class="form-control" required value="<?= App\Core\Session::isLoggedIn() ? htmlspecialchars(App\Core\Session::get('user_email')) : '' ?>">
                            </div>
                            <div class="col-md-12">
                                <label>Phone Number</label>
                                <input type="tel" id="billing_phone" name="billing_phone" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label>Shipping Address</label>
                                <textarea id="billing_address" name="billing_address" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold font-outfit mb-4">Order Summary</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach ($items as $item): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="my-0"><?= htmlspecialchars($item['name']) ?></h6>
                                <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                            </div>
                            <span class="text-muted">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">₹<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary">₹<?= number_format($subtotal, 2) ?></span>
                    </div>
                    
                    <button id="rzp-button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">Pay ₹<?= number_format($subtotal, 2) ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Verification Form -->
<form id="verify-form" action="<?= BASE_URL ?>/checkout/verify" method="POST" style="display:none;">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button').onclick = function(e) {
    e.preventDefault();

    // Validate form
    const form = document.getElementById('checkout-form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);

    // Create Order
    fetch('<?= BASE_URL ?>/checkout/create_order', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            var options = {
                "key": "<?= $rzpKey ?>",
                "amount": data.amount,
                "currency": "INR",
                "name": "Bihar Vihaan Enterprise",
                "description": "Marketplace Order",
                "order_id": data.rzp_order_id,
                "handler": function (response){
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('verify-form').submit();
                },
                "prefill": {
                    "name": document.getElementById('billing_name').value,
                    "email": document.getElementById('billing_email').value,
                    "contact": document.getElementById('billing_phone').value
                },
                "theme": {
                    "color": "#e65c00"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        } else {
            alert('Error creating order: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while creating order.');
    });
}
</script>

<?php include 'layout/footer.php'; ?>
