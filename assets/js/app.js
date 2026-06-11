/**
 * Bihar Vihaan Enterprise - Core Frontend JS
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. Theme Switcher System
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    // Retrieve saved theme or default to light
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const activeTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = activeTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeIcon) return;
        if (theme === 'dark') {
            themeIcon.className = 'fa-solid fa-sun text-warning';
        } else {
            themeIcon.className = 'fa-solid fa-moon text-light';
        }
    }

    // 2. Global Image Fallback & Lazy Loading System
    document.addEventListener('error', (e) => {
        if (e.target.tagName === 'IMG') {
            e.target.onerror = null; // Prevent infinite loop if fallback also fails
            e.target.src = `${window.baseUrl}/assets/images/fallback.jpg`;
        }
    }, true);

    // Apply lazy loading dynamically to all images if not already set
    document.querySelectorAll('img').forEach(img => {
        if (!img.getAttribute('loading')) {
            img.setAttribute('loading', 'lazy');
        }
    });

    // 3. Header scroll effect
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // 4. Smart Autocomplete Search & Submissions
    const searchInput = document.getElementById('search-query');
    const searchDropdown = document.getElementById('search-dropdown');
    const searchWrapper = document.querySelector('.search-wrapper');

    if (searchInput && searchDropdown) {
        let debounceTimer;

        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                searchDropdown.classList.remove('active');
                searchDropdown.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                // Fetch matches from REST API (Corrected Promise chaining)
                fetch(`${window.baseUrl}/api/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(response => {
                        if (response.success && response.data.length > 0) {
                            searchDropdown.innerHTML = '';
                            response.data.forEach(item => {
                                const el = document.createElement('div');
                                el.className = 'search-match-item';
                                el.innerHTML = `
                                    <img src="${item.image_url}" class="search-match-img" alt="${item.name}" onerror="this.onerror=null; this.src='${window.baseUrl}/assets/images/fallback.jpg';">
                                    <div class="search-match-details">
                                        <div class="search-match-title" style="color: var(--text-white);">${item.name}</div>
                                        <div class="search-match-location" style="color: var(--text-muted);">${item.category} • ${item.location}</div>
                                    </div>
                                `;
                                el.addEventListener('click', () => {
                                    window.location.href = `${window.baseUrl}/tourism/${item.id}`;
                                });
                                searchDropdown.appendChild(el);
                            });
                            searchDropdown.classList.add('active');
                        } else {
                            searchDropdown.classList.remove('active');
                        }
                    })
                    .catch(err => console.error('Search API error:', err));
            }, 300);
        });

        // Redirect to search results page when pressing enter or clicking search button
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    window.location.href = `${window.baseUrl}/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        const searchBtn = searchWrapper ? searchWrapper.querySelector('button') : null;
        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    window.location.href = `${window.baseUrl}/search?q=${encodeURIComponent(query)}`;
                }
            });
        }

        // Hide search dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.remove('active');
            }
        });
    }

    // 5. Dashboard Tab Switching
    const tabLinks = document.querySelectorAll('.dashboard-menu-link[data-tab]');
    const tabContents = document.querySelectorAll('.dashboard-tab-content');

    if (tabLinks.length > 0 && tabContents.length > 0) {
        tabLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetTab = link.getAttribute('data-tab');

                // Toggle active menu link
                tabLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // Toggle active panel
                tabContents.forEach(content => {
                    if (content.id === targetTab) {
                        content.classList.add('active');
                    } else {
                        content.classList.remove('active');
                    }
                });
            });
        });
    }

    // 6. Dynamic Itinerary Day Adder
    const addDayBtn = document.getElementById('add-day-btn');
    const itineraryDaysContainer = document.getElementById('itinerary-days-container');

    if (addDayBtn && itineraryDaysContainer) {
        let dayCount = 1;
        addDayBtn.addEventListener('click', () => {
            dayCount++;
            const dayBox = document.createElement('div');
            dayBox.className = 'day-box';
            dayBox.innerHTML = `
                <div class="day-header">
                    <span>Day ${dayCount} Activities</span>
                    <button type="button" class="btn btn-outline btn-sm remove-day-btn" style="color: var(--status-danger);">Remove</button>
                </div>
                <textarea name="days[]" placeholder="List destinations, transport timings, lunch breaks, or sights to visit today..." rows="3" class="razorpay-field" style="width:100%; border:1px solid var(--border-color); background:rgba(255,255,255,0.02); color:white; padding:10px; border-radius:8px;" required></textarea>
            `;

            // Add remove handler
            dayBox.querySelector('.remove-day-btn').addEventListener('click', () => {
                dayBox.remove();
                reindexDays();
            });

            itineraryDaysContainer.appendChild(dayBox);
        });

        function reindexDays() {
            const boxes = itineraryDaysContainer.querySelectorAll('.day-box');
            dayCount = 0;
            boxes.forEach(box => {
                dayCount++;
                box.querySelector('.day-header span').textContent = `Day ${dayCount} Activities`;
            });
        }
    }
});
