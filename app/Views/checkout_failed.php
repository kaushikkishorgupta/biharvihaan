<?php include 'layout/header.php'; ?>

<div class="container py-5 mt-5 text-center">
    <div class="card border-0 shadow-sm rounded-4 max-w-2xl mx-auto p-5">
        <i class="fa-solid fa-circle-xmark fa-5x text-danger mb-4"></i>
        <h2 class="fw-bold font-outfit mb-3">Payment Failed</h2>
        <p class="text-muted mb-4">We're sorry, but your payment could not be processed. Please check your payment details and try again.</p>
        
        <div>
            <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary px-4 me-2">Try Again</a>
            <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-secondary px-4">Contact Support</a>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
