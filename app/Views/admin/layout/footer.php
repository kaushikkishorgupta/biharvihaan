            <!-- Admin Footer -->
            <footer class="admin-footer mt-5 pt-4 pb-4 border-top" style="border-color: var(--cmd-surface-border) !important;">
                <div class="container-fluid px-0">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
                            <span class="text-secondary small">&copy; <?= date('Y') ?> Bihar Vihaan Enterprise CMS. All Rights Reserved.</span>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <span class="text-secondary small d-none d-lg-inline me-3 text-uppercase fw-bold" style="letter-spacing: 1px;">Quick Links:</span>
                            <ul class="list-inline mb-0 admin-footer-links d-inline-block">
                                <li class="list-inline-item me-3"><a href="<?= BASE_URL ?>/admin/dashboard" class="text-decoration-none text-secondary small">Dashboard</a></li>
                                <li class="list-inline-item me-3"><a href="<?= BASE_URL ?>/admin/tourism" class="text-decoration-none text-secondary small">Tourism Manager</a></li>
                                <li class="list-inline-item me-3"><a href="<?= BASE_URL ?>/admin/gallery" class="text-decoration-none text-secondary small">Gallery Manager</a></li>
                                <li class="list-inline-item me-3"><a href="<?= BASE_URL ?>/admin/marketplace" class="text-decoration-none text-secondary small">Marketplace</a></li>
                                <li class="list-inline-item me-3"><a href="<?= BASE_URL ?>/admin/settings" class="text-decoration-none text-secondary small">Settings</a></li>
                                <li class="list-inline-item">
                                    <a href="<?= BASE_URL ?>/" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-bold small home-page-link" style="color: var(--cmd-text);">
                                        <i class="bi bi-house-door-fill"></i> Home Page
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <style>
                .admin-footer-links a {
                    transition: color 0.3s ease;
                }
                .admin-footer-links a:hover, .home-page-link:hover {
                    color: #FF6B00 !important;
                }
            </style>
        </div> <!-- End admin-main -->
    </div> <!-- End content -->
</div> <!-- End wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        if ($('.table').length && !$('.table').hasClass('dataTable') && !$('.table').hasClass('table-borderless')) {
            $('.table').DataTable({
                "language": {
                    "search": "Filter:"
                },
                "pageLength": 10,
                "ordering": true
            });
        }

        // Theme Toggle Logic
        const themeToggle = $('#theme-toggle');
        const themeIcon = themeToggle.find('i');
        
        function updateThemeIcon(theme) {
            if (theme === 'light') {
                themeIcon.removeClass('fa-moon').addClass('fa-sun');
            } else {
                themeIcon.removeClass('fa-sun').addClass('fa-moon');
            }
        }
        
        // Initial icon update
        updateThemeIcon(document.documentElement.getAttribute('data-bs-theme'));
        
        themeToggle.on('click', function() {
            let currentTheme = document.documentElement.getAttribute('data-bs-theme');
            let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('admin_theme', newTheme);
            updateThemeIcon(newTheme);
            
            // Dispatch custom event so active Chart.js can update colors if needed
            window.dispatchEvent(new Event('themeChanged'));
        });

        // Global Search AJAX
        const searchInput = $('#globalSearch');
        const searchResults = $('#search-results');

        searchInput.on('input', function() {
            let query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: '<?= BASE_URL ?>/admin/search',
                    data: { q: query },
                    success: function(data) {
                        searchResults.empty().show();
                        if (data.length > 0) {
                            data.forEach(item => {
                                searchResults.append(`
                                    <a href="<?= BASE_URL ?>${item.url}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="fw-bold">${item.title}</div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary small text-uppercase" style="font-size:0.65rem;">${item.type}</span>
                                        </div>
                                    </a>
                                `);
                            });
                        } else {
                            searchResults.append('<div class="p-3 text-secondary text-center">No results found.</div>');
                        }
                    }
                });
            } else {
                searchResults.hide();
            }
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('.search-wrapper').length) {
                searchResults.hide();
            }
        });

        // Notification Center AJAX Polling
        function pollNotifications() {
            $.ajax({
                url: '<?= BASE_URL ?>/admin/notifications/unread',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const badge = $('#unread-count-badge');
                    if (response && response.count > 0) {
                        badge.removeClass('d-none');
                    } else {
                        badge.addClass('d-none');
                    }
                },
                error: function() {
                    // Fail silently
                }
            });
        }
        
        // Poll immediately and then every 20 seconds
        pollNotifications();
        setInterval(pollNotifications, 20000);

        // Chart.js - Dashboard Overview Renders
        const isDark = () => document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const getGridColor = () => isDark() ? 'rgba(255,255,255,0.05)' : 'rgba(11,61,145,0.08)';
        const getTextColor = () => isDark() ? '#94a3b8' : '#64748b';

        // 1. Visitor Analytics Chart
        if (document.getElementById('visitorChart')) {
            const ctx = document.getElementById('visitorChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Visitors',
                        data: [1420, 2100, 1850, 2900, 3100, 4800, 4200],
                        borderColor: '#0B3D91',
                        backgroundColor: 'rgba(11, 61, 145, 0.05)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: getGridColor() }, ticks: { color: getTextColor() } },
                        x: { grid: { display: false }, ticks: { color: getTextColor() } }
                    }
                }
            });

            window.addEventListener('themeChanged', () => {
                chart.options.scales.y.grid.color = getGridColor();
                chart.options.scales.y.ticks.color = getTextColor();
                chart.options.scales.x.ticks.color = getTextColor();
                chart.update();
            });
        }

        // 2. Tourism Growth Chart
        if (document.getElementById('growthChart')) {
            const ctx = document.getElementById('growthChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Domestic Tourists (k)',
                        data: [120, 150, 190, 220, 280, 350],
                        backgroundColor: '#FF9933',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: getGridColor() }, ticks: { color: getTextColor() } },
                        x: { grid: { display: false }, ticks: { color: getTextColor() } }
                    }
                }
            });
        }

        // 3. AI Planner Usage Chart
        if (document.getElementById('plannerChart')) {
            const ctx = document.getElementById('plannerChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Heritage', 'Eco Tourism', 'Religious', 'Adventure'],
                    datasets: [{
                        data: [45, 20, 25, 10],
                        backgroundColor: ['#0B3D91', '#10B981', '#FF9933', '#8B5CF6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: getTextColor() } }
                    }
                }
            });
        }

        // 4. Event Registrations Chart
        if (document.getElementById('eventsChart')) {
            const ctx = document.getElementById('eventsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sonepur Mela', 'Buddha Jayanti', 'Rajgir Mahotsav', 'Chhath Puja Special'],
                    datasets: [{
                        label: 'Registrations',
                        data: [1540, 850, 1200, 3200],
                        backgroundColor: '#10B981',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: getGridColor() }, ticks: { color: getTextColor() } },
                        x: { grid: { display: false }, ticks: { color: getTextColor() } }
                    }
                }
            });
        }

        // 5. Marketplace Revenue Chart
        if (document.getElementById('marketplaceChart')) {
            const ctx = document.getElementById('marketplaceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4'],
                    datasets: [{
                        label: 'Revenue (INR)',
                        data: [45000, 78000, 92000, 120000],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: getGridColor() }, ticks: { color: getTextColor() } },
                        x: { grid: { display: false }, ticks: { color: getTextColor() } }
                    }
                }
            });
        }
    });
</script>

</body>
</html>
