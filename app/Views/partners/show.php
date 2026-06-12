<?php require dirname(__DIR__) . '/layout/header.php'; ?>

<style>
    .partner-hero {
        background: linear-gradient(135deg, #0B3D91 0%, #1a237e 100%);
        color: white;
        padding: 100px 0 60px;
        position: relative;
    }
    .partner-hero::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 50px;
        background: var(--bg-main);
        clip-path: polygon(0 100%, 100% 100%, 100% 0);
    }
    .partner-logo-large {
        width: 150px;
        height: 150px;
        border-radius: 20px;
        background: white;
        padding: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        object-fit: contain;
        margin-top: -50px;
        position: relative;
        z-index: 10;
        border: 4px solid var(--bg-main);
    }
    .info-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: 1px solid #E5E7EB;
    }
    .info-card h4 {
        color: #0B3D91;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .contact-item i {
        color: #FF9933;
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }
    .gallery-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.3s;
    }
    .gallery-img:hover {
        transform: scale(1.05);
    }
</style>

<!-- SEO Schema.org -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "<?= htmlspecialchars($partner['name']) ?>",
  "url": "<?= htmlspecialchars($partner['website']) ?>",
  "logo": "<?= htmlspecialchars($partner['logo']) ?>",
  "description": "<?= htmlspecialchars($partner['short_description']) ?>",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "<?= htmlspecialchars($partner['phone']) ?>",
    "email": "<?= htmlspecialchars($partner['email']) ?>"
  }
}
</script>

<div class="partner-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <a href="<?= BASE_URL ?>/" class="btn btn-outline-light btn-sm rounded-pill mb-4">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
                <span class="badge bg-white text-primary rounded-pill mb-3 px-3 py-2 fw-bold text-uppercase"><?= htmlspecialchars($partner['category']) ?></span>
                <h1 class="display-4 fw-bold" style="font-family: 'Outfit', sans-serif;"><?= htmlspecialchars($partner['name']) ?></h1>
                <p class="lead mb-0" style="opacity: 0.9; max-width: 600px;"><?= htmlspecialchars($partner['short_description']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5" style="background-color: var(--bg-main);">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <img src="<?= htmlspecialchars($partner['logo']) ?>" alt="<?= htmlspecialchars($partner['name']) ?>" class="partner-logo-large mb-4">

            <div class="info-card" data-aos="fade-up">
                <h4><i class="bi bi-info-circle"></i> About Organization</h4>
                <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;"><?= nl2br(htmlspecialchars($partner['description'])) ?></p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-card h-100" data-aos="fade-up" data-aos-delay="100">
                        <h4><i class="bi bi-bullseye"></i> Our Mission</h4>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($partner['mission'])) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card h-100" data-aos="fade-up" data-aos-delay="200">
                        <h4><i class="bi bi-eye"></i> Our Vision</h4>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($partner['vision'])) ?></p>
                    </div>
                </div>
            </div>

            <?php if (!empty($gallery)): ?>
            <div class="mt-5" data-aos="fade-up">
                <h3 class="fw-bold mb-4" style="color: #1F2937;">Partner Gallery</h3>
                <div class="row g-4">
                    <?php foreach ($gallery as $img): ?>
                        <div class="col-md-4 col-sm-6">
                            <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="Gallery Image" class="gallery-img shadow-sm" data-bs-toggle="modal" data-bs-target="#galleryModal" onclick="document.getElementById('modalImage').src=this.src;">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="info-card sticky-top" style="top: 100px;" data-aos="fade-left">
                <h4><i class="bi bi-geo-alt"></i> Contact Information</h4>
                
                <?php if(!empty($partner['website'])): ?>
                <div class="contact-item">
                    <i class="bi bi-globe"></i>
                    <div>
                        <small class="text-muted d-block">Website</small>
                        <a href="<?= htmlspecialchars($partner['website']) ?>" target="_blank" class="text-decoration-none fw-bold"><?= htmlspecialchars(parse_url($partner['website'], PHP_URL_HOST)) ?></a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($partner['email'])): ?>
                <div class="contact-item">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <a href="mailto:<?= htmlspecialchars($partner['email']) ?>" class="text-decoration-none text-dark fw-bold"><?= htmlspecialchars($partner['email']) ?></a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($partner['phone'])): ?>
                <div class="contact-item">
                    <i class="bi bi-telephone"></i>
                    <div>
                        <small class="text-muted d-block">Phone</small>
                        <span class="text-dark fw-bold"><?= htmlspecialchars($partner['phone']) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($partner['address'])): ?>
                <div class="contact-item align-items-start mt-4 pt-4 border-top">
                    <i class="bi bi-map mt-1"></i>
                    <div>
                        <small class="text-muted d-block mb-1">Headquarters</small>
                        <span class="text-dark"><?= nl2br(htmlspecialchars($partner['address'])) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <hr class="my-4">
                <div class="d-grid">
                    <a href="/contact" class="btn btn-primary rounded-pill py-3 fw-bold">Become a Partner</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-header border-0">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Large Gallery" class="img-fluid rounded" style="max-height: 80vh;">
      </div>
    </div>
  </div>
</div>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
