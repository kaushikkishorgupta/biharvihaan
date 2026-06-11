/**
 * Bihar Vihaan Enterprise 5.0 - Premium Marketplace JS
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. AJAX Search & Live Suggestions
    const searchInput = document.getElementById('marketplace-search');
    const searchSuggestions = document.getElementById('search-suggestions');
    
    if (searchInput && searchSuggestions) {
        searchInput.addEventListener('input', debounce(function() {
            const query = this.value.trim();
            if (query.length > 2) {
                // In a real app, this would fetch from an API endpoint
                // For demonstration, simulating live results
                searchSuggestions.innerHTML = 
                    <a href="#" class="dropdown-item py-2 border-bottom"><i class="fa-solid fa-search text-muted me-2"></i> +query+ in Madhubani Paintings</a>
                    <a href="#" class="dropdown-item py-2 border-bottom"><i class="fa-solid fa-search text-muted me-2"></i> +query+ in Handicrafts</a>
                    <a href="#" class="dropdown-item py-2 text-primary fw-bold">View all results for "+query+"</a>
                ;
                searchSuggestions.classList.add('show');
            } else {
                searchSuggestions.classList.remove('show');
            }
        }, 300));

        // Hide suggestions on outside click
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.classList.remove('show');
            }
        });
    }

    // 2. Wishlist Toggle
    window.toggleWishlist = function(id) {
        // Toggle the button style
        const btn = event.currentTarget;
        btn.classList.toggle('active');
        
        const icon = btn.querySelector('i');
        if (btn.classList.contains('active')) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            // Show toast notification
            showToast('Added to Wishlist');
        } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            showToast('Removed from Wishlist');
        }
    };

    // 3. Quick View Modal
    const quickViewModal = document.getElementById('quickViewModal');
    let bsModal;
    
    if (quickViewModal && typeof bootstrap !== 'undefined') {
        bsModal = new bootstrap.Modal(quickViewModal);
    }

    window.openQuickView = function(id) {
        // Fetch via AJAX
        fetch(window.baseUrl + '/api/marketplace/quickview?id=' + id)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.product;
                    document.getElementById('qv-img').src = p.image_url || window.baseUrl + '/assets/images/fallback.jpg';
                    document.getElementById('qv-title').innerText = p.name;
                    document.getElementById('qv-price').innerText = '₹' + parseFloat(p.price).toLocaleString();
                    document.getElementById('qv-category').innerText = p.category;
                    document.getElementById('qv-desc').innerText = p.description || 'Authentic handcrafted piece from Bihar.';
                    document.getElementById('qv-artisan').innerText = p.artisan_name || 'Verified Craftsman';
                    document.getElementById('qv-product-id').value = p.id;
                    
                    if (bsModal) bsModal.show();
                } else {
                    alert('Product details could not be loaded.');
                }
            })
            .catch(err => console.error(err));
    };

    // Helper: Debounce
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Helper: Toast Notification
    function showToast(message) {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-dark border-0 show';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = 
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid fa-check-circle text-success me-2"></i> 
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        ;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }
});
