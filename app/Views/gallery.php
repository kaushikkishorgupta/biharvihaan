<!-- Include Custom Gallery CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gallery.css">

<!-- Hero Section -->
<section class="gallery-hero">
    <div class="gallery-hero-content" data-aos="fade-up">
        <h1>Explore Bihar Through Images</h1>
        <p>Discover the heritage, culture, festivals, nature, and hidden gems of Bihar through our curated visual gallery.</p>
        
        <div class="gallery-search">
            <input type="text" placeholder="Search destinations, festivals, temples, food, culture...">
        </div>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button class="btn-gallery-primary">Explore Gallery</button>
            <button class="btn-gallery-outline" onclick="alert('Upload feature pending admin configuration')">Upload Your Photo</button>
        </div>
    </div>
</section>

<!-- Filter Categories -->
<div class="container-fluid mb-4">
    <div class="gallery-filters text-center" data-aos="fade-in" data-aos-delay="200">
        <?php foreach($categories as $index => $cat): ?>
            <button class="filter-btn <?= $index === 0 ? 'active' : '' ?>" data-filter="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
        <?php endforeach; ?>
    </div>
</div>

<!-- Masonry Gallery -->
<div class="container-fluid mb-5">
    <div class="masonry-grid" id="masonry-grid">
        <?php foreach($initialImages as $index => $img): ?>
            <div class="masonry-item" 
                 data-id="<?= $img['id'] ?>" 
                 data-img="<?= htmlspecialchars($img['image']) ?>"
                 data-title="<?= htmlspecialchars($img['title']) ?>"
                 data-loc="<?= htmlspecialchars($img['location']) ?>"
                 data-desc="<?= htmlspecialchars($img['description']) ?>"
                 data-cat="<?= htmlspecialchars($img['category']) ?>"
                 data-photog="<?= htmlspecialchars($img['photographer']) ?>">
                
                <span class="badge-category"><?= htmlspecialchars($img['category']) ?></span>
                
                <div class="masonry-actions">
                    <button class="action-btn" onclick="event.stopPropagation(); alert('Saved to favorites!')"><i class="fa-regular fa-heart"></i></button>
                    <button class="action-btn" onclick="event.stopPropagation(); alert('Share link copied!')"><i class="fa-solid fa-share-nodes"></i></button>
                </div>

                <img src="<?= htmlspecialchars($img['image']) ?>" alt="<?= htmlspecialchars($img['title']) ?>" loading="lazy" onerror="this.src='<?= BASE_URL ?>/assets/images/fallback.jpg'">
                
                <div class="masonry-overlay">
                    <div class="masonry-content">
                        <h4 class="masonry-title"><?= htmlspecialchars($img['title']) ?></h4>
                        <div class="masonry-location"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($img['location']) ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Infinite Scroll Spinner -->
    <div class="loading-spinner" id="loading-spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<!-- Featured Destinations Section -->
<section class="container py-5 border-top" style="border-color: var(--gal-border) !important;">
    <h3 class="mb-4" style="color: var(--gal-primary); font-weight: 700;">Featured Destinations</h3>
    <div class="row g-4">
        <?php 
        $featured = ['Bodh Gaya', 'Nalanda', 'Rajgir', 'Vaishali', 'Patna Sahib', 'Vikramshila'];
        foreach($featured as $feat): ?>
        <div class="col-md-4 col-sm-6">
            <div class="p-3 bg-surface rounded shadow-sm text-center border" style="border-color: var(--gal-border);">
                <h5 class="mb-0 "><?= $feat ?></h5>
                <small class="text-muted">Explore Photos</small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- User Generated Content Teaser -->
<section class="container py-5 mb-5 text-center bg-light rounded" style="background-color: var(--gal-bg) !important; border: 1px dashed var(--gal-accent);">
    <h3 class="mb-3" style="color: var(--gal-primary); font-weight: 700;">Share Your Bihar Experience</h3>
    <p class="text-muted mb-4">Contribute your stunning captures to the Bihar Vihaan Enterprise gallery.</p>
    <button class="btn-gallery-primary" onclick="alert('UGC Upload Module will be activated by Admin.')">Upload Image</button>
</section>

<!-- Lightbox Modal -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lb-close"><i class="fa-solid fa-xmark"></i></button>
    <button class="lightbox-nav lightbox-prev" id="lb-prev"><i class="fa-solid fa-chevron-left"></i></button>
    
    <div class="lightbox-content">
        <div class="lightbox-img-container">
            <img src="" id="lb-img" alt="Gallery Image">
        </div>
        <div class="lightbox-sidebar">
            <span class="badge bg-primary mb-3 d-inline-block" style="width: fit-content;" id="lb-cat">Category</span>
            <h3 id="lb-title" style="color: var(--gal-primary); font-weight: 700; margin-bottom: 5px;">Title</h3>
            <p class="text-muted mb-4"><i class="fa-solid fa-location-dot"></i> <span id="lb-loc">Location</span></p>
            
            <p id="lb-desc" style="line-height: 1.6;">Description goes here.</p>
            
            <hr class="my-4" style="border-color: var(--gal-border);">
            
            <p class="fw-bold mb-1" id="lb-photog">By Photographer</p>
            <p class="small text-muted mb-4">Bihar Vihaan Contributor</p>
            
            <div class="d-grid gap-2 mt-auto">
                <button class="btn btn-outline-primary"><i class="fa-solid fa-download"></i> Download HD</button>
                <button class="btn btn-primary" style="background-color: var(--gal-accent); border-color: var(--gal-accent);"><i class="fa-solid fa-share"></i> Share Image</button>
            </div>
        </div>
    </div>

    <button class="lightbox-nav lightbox-next" id="lb-next"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<!-- Include Custom Gallery JS -->
<script src="<?= BASE_URL ?>/assets/js/gallery.js"></script>

