document.addEventListener('DOMContentLoaded', () => {
    
    let currentPage = 1;
    let currentCategory = 'All';
    let isLoading = false;
    let hasMore = true;
    let galleryData = [];

    const grid = document.getElementById('masonry-grid');
    const spinner = document.getElementById('loading-spinner');
    const filterBtns = document.querySelectorAll('.filter-btn');

    // Lightbox elements
    const lightbox = document.getElementById('lightbox');
    const lbImg = document.getElementById('lb-img');
    const lbTitle = document.getElementById('lb-title');
    const lbLoc = document.getElementById('lb-loc');
    const lbDesc = document.getElementById('lb-desc');
    const lbCat = document.getElementById('lb-cat');
    const lbPhotog = document.getElementById('lb-photog');
    let currentIndex = 0;

    // Load initial data into galleryData array for lightbox navigation
    document.querySelectorAll('.masonry-item').forEach((item, index) => {
        galleryData.push({
            id: item.dataset.id,
            image: item.dataset.img,
            title: item.dataset.title,
            location: item.dataset.loc,
            description: item.dataset.desc,
            category: item.dataset.cat,
            photographer: item.dataset.photog
        });
        
        item.addEventListener('click', (e) => {
            if(!e.target.closest('.action-btn')) {
                openLightbox(index);
            }
        });
    });

    // Filtering
    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            filterBtns.forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            
            currentCategory = e.target.dataset.filter;
            currentPage = 1;
            hasMore = true;
            galleryData = [];
            grid.innerHTML = '';
            
            loadImages();
        });
    });

    // Infinite Scroll using IntersectionObserver
    const observer = new IntersectionObserver((entries) => {
        if(entries[0].isIntersecting && !isLoading && hasMore) {
            currentPage++;
            loadImages();
        }
    }, { rootMargin: '200px' });
    
    observer.observe(spinner);

    // Fetch images via AJAX
    async function loadImages() {
        if(isLoading || !hasMore) return;
        isLoading = true;
        spinner.classList.add('active');

        try {
            const res = await fetch(`${window.baseUrl}/gallery/load?category=${encodeURIComponent(currentCategory)}&page=${currentPage}`);
            const data = await res.json();

            if(data.images && data.images.length > 0) {
                data.images.forEach((img) => {
                    const idx = galleryData.length;
                    galleryData.push({
                        id: img.id,
                        image: img.image,
                        title: img.title,
                        location: img.location,
                        description: img.description,
                        category: img.category,
                        photographer: img.photographer
                    });

                    const itemHtml = `
                    <div class="masonry-item" data-id="${img.id}" onclick="window.openLightbox(${idx})">
                        <span class="badge-category">${img.category}</span>
                        <div class="masonry-actions">
                            <button class="action-btn" onclick="event.stopPropagation(); alert('Saved to favorites!')"><i class="fa-regular fa-heart"></i></button>
                            <button class="action-btn" onclick="event.stopPropagation(); alert('Share linked copied!')"><i class="fa-solid fa-share-nodes"></i></button>
                        </div>
                        <img src="${img.image}" alt="${img.title}" loading="lazy" onerror="this.src='${window.baseUrl}/assets/images/fallback.jpg'">
                        <div class="masonry-overlay">
                            <div class="masonry-content">
                                <h4 class="masonry-title">${img.title}</h4>
                                <div class="masonry-location"><i class="fa-solid fa-location-dot"></i> ${img.location}</div>
                            </div>
                        </div>
                    </div>`;
                    
                    grid.insertAdjacentHTML('beforeend', itemHtml);
                });
            } else {
                hasMore = false;
            }
        } catch(e) {
            console.error('Error loading gallery images:', e);
        }

        isLoading = false;
        spinner.classList.remove('active');
    }

    // Lightbox Logic
    window.openLightbox = function(index) {
        currentIndex = index;
        updateLightboxContent();
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    function updateLightboxContent() {
        const item = galleryData[currentIndex];
        if(!item) return;
        
        lbImg.src = item.image;
        lbTitle.textContent = item.title;
        lbLoc.textContent = item.location;
        lbDesc.textContent = item.description || 'Discover the beauty of Bihar through this stunning visual capture.';
        lbCat.textContent = item.category;
        lbPhotog.textContent = 'By ' + (item.photographer || 'Bihar Vihaan Contributor');
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % galleryData.length;
        updateLightboxContent();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + galleryData.length) % galleryData.length;
        updateLightboxContent();
    }

    // Lightbox Event Listeners
    document.getElementById('lb-close').addEventListener('click', closeLightbox);
    document.getElementById('lb-next').addEventListener('click', nextImage);
    document.getElementById('lb-prev').addEventListener('click', prevImage);

    // Keyboard Navigation
    document.addEventListener('keydown', (e) => {
        if(!lightbox.classList.contains('active')) return;
        if(e.key === 'Escape') closeLightbox();
        if(e.key === 'ArrowRight') nextImage();
        if(e.key === 'ArrowLeft') prevImage();
    });

    // Close on backdrop click
    lightbox.addEventListener('click', (e) => {
        if(e.target === lightbox) closeLightbox();
    });
});
