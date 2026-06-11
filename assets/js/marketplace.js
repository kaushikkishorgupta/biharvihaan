document.addEventListener('DOMContentLoaded', () => {
    
    let currentPage = 1;
    let currentCategory = 'All';
    let currentSort = 'newest';
    let isLoading = false;
    let hasMore = true;

    const grid = document.getElementById('shop-masonry');
    const loader = document.getElementById('shop-loader');
    const categoryLinks = document.querySelectorAll('.cat-filter');
    const sortSelect = document.getElementById('sort-select');

    // Filter by Category
    categoryLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            categoryLinks.forEach(l => l.classList.remove('active'));
            e.target.classList.add('active');
            
            currentCategory = e.target.dataset.cat;
            resetGrid();
        });
    });

    // Sort by Dropdown
    if(sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            currentSort = e.target.value;
            resetGrid();
        });
    }

    function resetGrid() {
        currentPage = 1;
        hasMore = true;
        grid.innerHTML = '';
        loadProducts();
    }

    // Infinite Scroll
    if(loader) {
        const observer = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting && !isLoading && hasMore) {
                currentPage++;
                loadProducts();
            }
        }, { rootMargin: '200px' });
        observer.observe(loader);
    }

    async function loadProducts() {
        if(isLoading || !hasMore) return;
        isLoading = true;
        loader.classList.add('active');

        try {
            const res = await fetch(`${window.baseUrl}/shop/load?category=${encodeURIComponent(currentCategory)}&sort=${currentSort}&page=${currentPage}`);
            const data = await res.json();

            if(data.products && data.products.length > 0) {
                data.products.forEach(p => {
                    const priceFormatted = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(p.price);
                    
                    const html = `
                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="${p.image_url}" alt="${p.name}" loading="lazy" onerror="this.src='${window.baseUrl}/assets/images/fallback.jpg'">
                            <div class="product-actions">
                                <button class="product-btn" onclick="toggleWishlist(${p.id})"><i class="fa-regular fa-heart"></i></button>
                                <button class="product-btn quick-view" onclick="openQuickView(${p.id})"><i class="fa-solid fa-eye"></i> Quick View</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">${p.category}</div>
                            <h4 class="product-title">${p.name}</h4>
                            <div class="product-artisan"><i class="fa-solid fa-user-pen"></i> By ${p.artisan_name || 'Verified Artisan'}</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">${priceFormatted}</div>
                                <div class="text-warning small"><i class="fa-solid fa-star"></i> ${p.rating}</div>
                            </div>
                        </div>
                    </div>`;
                    grid.insertAdjacentHTML('beforeend', html);
                });
            } else {
                hasMore = false;
            }
        } catch(e) {
            console.error('Error loading products', e);
        }

        isLoading = false;
        loader.classList.remove('active');
    }

    // Quick View Logic
    const qvModal = document.getElementById('quickview-modal');
    if(qvModal) {
        window.openQuickView = async function(id) {
            qvModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Show loading state internally
            document.getElementById('qv-title').textContent = 'Loading...';
            
            try {
                const res = await fetch(`${window.baseUrl}/shop/quick-view?id=${id}`);
                const data = await res.json();
                if(data.success) {
                    const p = data.product;
                    const priceFormatted = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(p.price);
                    
                    document.getElementById('qv-img').src = p.image_url;
                    document.getElementById('qv-title').textContent = p.name;
                    document.getElementById('qv-price').textContent = priceFormatted;
                    document.getElementById('qv-desc').textContent = p.description;
                    document.getElementById('qv-artisan').textContent = p.artisan_name || 'Verified Artisan';
                    document.getElementById('qv-artisan-bio').textContent = p.artisan_bio || '';
                    document.getElementById('qv-location').textContent = p.location || 'Bihar';
                    document.getElementById('qv-materials').textContent = p.materials || 'Traditional materials';
                    document.getElementById('qv-product-id').value = p.id;
                }
            } catch(e) {
                console.error(e);
            }
        };

        document.getElementById('qv-close').addEventListener('click', () => {
            qvModal.classList.remove('active');
            document.body.style.overflow = '';
        });

        qvModal.addEventListener('click', (e) => {
            if(e.target === qvModal) {
                qvModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    window.toggleWishlist = function(id) {
        alert('Added to your Wishlist!');
    }
});
