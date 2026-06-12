<?php require 'layout/header.php'; ?>

<!-- Chart.js and GSAP for AI Planner -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* Premium AI Aesthetic Styles */
    :root {
        --ai-primary: #3b82f6;
        --ai-secondary: #8b5cf6;
        --ai-dark: #0f172a;
        --ai-surface: rgba(30, 41, 59, 0.7);
    }
    
    body {
        background-color: var(--bg-main); /* Keeping site theme compatibility */
    }

    .ai-hero {
        position: relative;
        padding: 100px 0 60px;
        background: linear-gradient(135deg, var(--ai-dark) 0%, #1e1b4b 100%);
        color: white;
        overflow: hidden;
    }
    
    .ai-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%);
        animation: pulse 4s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.5); opacity: 1; }
    }

    .ai-badge {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #e2e8f0;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        padding: 40px;
        position: relative;
        z-index: 10;
        margin-top: -80px;
    }

    .form-floating > label {
        color: #64748b;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 16px;
        background-color: #f8fafc;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: var(--ai-primary);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .btn-ai {
        background: linear-gradient(135deg, var(--ai-primary) 0%, var(--ai-secondary) 100%);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        color: white;
    }

    /* AI Loading Screen */
    #ai-loading-screen {
        display: none;
        text-align: center;
        padding: 60px 0;
    }
    
    .ai-step {
        font-size: 1.2rem;
        color: #64748b;
        margin: 15px 0;
        opacity: 0.3;
        transform: translateY(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .ai-step.active {
        color: var(--ai-primary);
        opacity: 1;
        font-weight: bold;
    }

    /* Itinerary Timeline */
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 40px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -45px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--ai-primary);
        border: 3px solid white;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }

    .day-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .day-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    /* Social Proof */
    .stat-counter {
        text-align: center;
        padding: 30px;
    }
    .stat-counter h3 {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .route-node {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--ai-dark);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        margin: 5px;
        font-weight: 500;
        position: relative;
    }
    
    .route-arrow {
        color: var(--ai-primary);
        margin: 0 10px;
        font-weight: bold;
    }
</style>

<!-- Hero Section -->
<section class="ai-hero">
    <div class="container text-center position-relative z-index-1">
        <div class="ai-badge mb-4 mx-auto">
            <i class="fa-solid fa-robot"></i> Powered by Advanced AI
        </div>
        <h1 class="display-3 fw-bold mb-3" style="letter-spacing: -1px;">Your Personal Bihar Travel Assistant</h1>
        <p class="lead mb-5" style="opacity: 0.9; max-width: 700px; margin: 0 auto;">
            Generate a personalized day-by-day Bihar journey based on your interests, travel style, and budget in seconds.
        </p>
    </div>
</section>

<div class="container pb-5">
    <!-- Smart Form -->
    <div class="glass-panel" id="planner-form-container">
        <form id="aiPlannerForm">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" id="start_city" name="start_city" required>
                            <option value="Patna" selected>Patna</option>
                            <option value="Gaya">Gaya (Bodh Gaya)</option>
                            <option value="Darbhanga">Darbhanga</option>
                            <option value="Bhagalpur">Bhagalpur</option>
                        </select>
                        <label for="start_city">Starting City</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" id="duration" name="duration" required>
                            <option value="2">2 Days (Weekend Trip)</option>
                            <option value="3" selected>3 Days (Short Break)</option>
                            <option value="5">5 Days (Explore Mode)</option>
                            <option value="7">7 Days (Full Experience)</option>
                        </select>
                        <label for="duration">Duration</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" id="travel_group" name="travel_group" required>
                            <option value="solo">Solo Explorer</option>
                            <option value="couple">Couple / Romance</option>
                            <option value="family" selected>Family with Kids</option>
                            <option value="friends">Group of Friends</option>
                        </select>
                        <label for="travel_group">Who's traveling?</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="budget_level" name="budget_level" required>
                            <option value="budget">Budget (Hostels, Local Transport)</option>
                            <option value="standard" selected>Standard (3-Star, Cabs)</option>
                            <option value="premium">Premium (4-Star, Private SUV)</option>
                            <option value="luxury">Luxury (Heritage Hotels, Premium)</option>
                        </select>
                        <label for="budget_level">Budget Level</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="travel_style" name="travel_style" required>
                            <option value="heritage">Heritage & History</option>
                            <option value="spiritual">Spiritual & Pilgrimage</option>
                            <option value="nature">Nature & Wildlife</option>
                            <option value="culture">Art & Culture</option>
                        </select>
                        <label for="travel_style">Primary Interest</label>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-ai w-100 w-md-auto px-5" id="generateBtn">
                    <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate My Journey
                </button>
            </div>
        </form>
    </div>

    <!-- AI Loading Screen -->
    <div id="ai-loading-screen" class="glass-panel mt-4">
        <div class="spinner-border text-primary mb-4" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="ai-step" id="step1"><i class="fa-solid fa-brain"></i> Understanding Preferences...</div>
        <div class="ai-step" id="step2"><i class="fa-solid fa-map-location-dot"></i> Discovering Destinations...</div>
        <div class="ai-step" id="step3"><i class="fa-solid fa-bed"></i> Finding Accommodations...</div>
        <div class="ai-step" id="step4"><i class="fa-solid fa-route"></i> Optimizing Routes...</div>
        <div class="ai-step" id="step5"><i class="fa-solid fa-wand-magic-sparkles"></i> Creating Personalized Journey...</div>
    </div>

    <!-- Results Container (Hidden initially) -->
    <div id="itinerary-results" style="display: none; margin-top: -40px; position: relative; z-index: 10;">
        
        <!-- Premium Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-5 bg-white p-4 rounded-4 border shadow-sm">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill">AI Generated</span>
                <h2 class="fw-bold mb-0" id="circuitName">Your Personalized Journey</h2>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark rounded-pill px-4" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Print</button>
                <button class="btn btn-primary rounded-pill px-4" id="downloadPdfBtn"><i class="fa-solid fa-file-pdf me-2"></i>Download PDF</button>
            </div>
        </div>

        <div class="row g-4" id="pdf-content">
            <!-- Left: Timeline -->
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                    <h4 class="fw-bold mb-4"><i class="fa-solid fa-route text-primary me-2"></i> Route Map</h4>
                    <div id="routeMap" class="p-3 bg-light rounded text-center">
                        <!-- Route nodes injected here -->
                    </div>
                </div>

                <div class="timeline" id="timelineContainer">
                    <!-- Days injected here via JS -->
                </div>
            </div>

            <!-- Right: Intelligence Sidebar -->
            <div class="col-lg-4">
                <!-- Budget Card -->
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4 sticky-top" style="top: 100px;">
                    <h4 class="fw-bold mb-4"><i class="fa-solid fa-wallet text-success me-2"></i> Budget Intelligence</h4>
                    <div style="height: 250px; position: relative;">
                        <canvas id="budgetChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-top text-center">
                        <p class="text-muted mb-1">Estimated Total Cost</p>
                        <h2 class="fw-bold text-dark mb-0">₹<span id="totalCost">0</span></h2>
                        <small class="text-muted" id="budgetTypeDetails">Includes 3-Star Hotels & Cabs</small>
                    </div>
                </div>

                <!-- Hidden Gems -->
                <div class="bg-white p-4 rounded-4 border shadow-sm">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-gem text-warning me-2"></i> AI Hidden Gems</h5>
                    <div id="hiddenGemsContainer">
                        <!-- Gems injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Social Proof -->
<section class="bg-white py-5 border-top">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 stat-counter">
                <h3 class="counter" data-target="10500">0</h3>
                <p class="text-muted fw-semibold mt-2">AI Trips Generated</p>
            </div>
            <div class="col-md-3 col-6 stat-counter">
                <h3 class="counter" data-target="200">0</h3><span class="fs-3 fw-bold text-primary">+</span>
                <p class="text-muted fw-semibold mt-2">Tourist Attractions</p>
            </div>
            <div class="col-md-3 col-6 stat-counter">
                <h3 class="counter" data-target="38">0</h3>
                <p class="text-muted fw-semibold mt-2">Districts Covered</p>
            </div>
            <div class="col-md-3 col-6 stat-counter">
                <h3>98%</h3>
                <p class="text-muted fw-semibold mt-2">Traveler Satisfaction</p>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    let budgetChartInstance = null;

    $('#aiPlannerForm').on('submit', function(e) {
        e.preventDefault();
        
        // UI Transition
        $('#planner-form-container').slideUp(400);
        $('#itinerary-results').hide();
        $('#ai-loading-screen').fadeIn(400);

        // GSAP Timeline for Loading
        const tl = gsap.timeline();
        tl.to('#step1', {opacity: 1, y: 0, duration: 0.5, className: 'ai-step active'})
          .to('#step1', {opacity: 0.5, className: 'ai-step', delay: 0.5})
          .to('#step2', {opacity: 1, y: 0, duration: 0.5, className: 'ai-step active'})
          .to('#step2', {opacity: 0.5, className: 'ai-step', delay: 0.5})
          .to('#step3', {opacity: 1, y: 0, duration: 0.5, className: 'ai-step active'})
          .to('#step3', {opacity: 0.5, className: 'ai-step', delay: 0.5})
          .to('#step4', {opacity: 1, y: 0, duration: 0.5, className: 'ai-step active'})
          .to('#step4', {opacity: 0.5, className: 'ai-step', delay: 0.5})
          .to('#step5', {opacity: 1, y: 0, duration: 0.5, className: 'ai-step active'});

        // AJAX Request
        $.ajax({
            url: '<?= BASE_URL ?>/trip-planner/generate',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    setTimeout(() => {
                        renderItinerary(response);
                        $('#ai-loading-screen').hide();
                        $('#itinerary-results').fadeIn(600);
                        
                        // Scroll to results
                        $('html, body').animate({
                            scrollTop: $("#itinerary-results").offset().top - 100
                        }, 500);
                    }, 4000); // Wait for GSAP animation to finish
                }
            }
        });
    });

    function renderItinerary(data) {
        $('#circuitName').text(data.circuit_name);
        
        // Render Route Map
        let routeHtml = '';
        data.route_flow.forEach((city, index) => {
            routeHtml += `<span class="route-node">${city}</span>`;
            if (index < data.route_flow.length - 1) {
                routeHtml += `<span class="route-arrow">→</span>`;
            }
        });
        $('#routeMap').html(routeHtml);

        // Render Timeline
        let timelineHtml = '';
        data.itinerary.forEach(day => {
            timelineHtml += `
                <div class="timeline-item">
                    <div class="day-card">
                        <img src="${day.image}" class="day-img" alt="${day.city}">
                        <div class="p-4">
                            <span class="text-primary fw-bold mb-2 d-block text-uppercase">Day ${day.day} • ${day.city}</span>
                            <h4 class="fw-bold mb-3">${day.title}</h4>
                            <p class="text-muted">${day.description}</p>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="fa-solid fa-sun text-warning me-2"></i> <strong>Morning:</strong> ${day.schedule.morning}</li>
                                    <li class="mb-2"><i class="fa-solid fa-cloud-sun text-info me-2"></i> <strong>Afternoon:</strong> ${day.schedule.afternoon}</li>
                                    <li><i class="fa-solid fa-moon text-dark me-2"></i> <strong>Evening:</strong> ${day.schedule.evening}</li>
                                </ul>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                                <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3"><i class="fa-solid fa-lightbulb me-1"></i> ${day.insider_tip}</span>
                                <span class="badge bg-success bg-opacity-10 text-success py-2 px-3"><i class="fa-solid fa-utensils me-1"></i> ${day.food_recommendation}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#timelineContainer').html(timelineHtml);

        // Render Hidden Gems
        let gemsHtml = '';
        if(data.hidden_gems.length > 0) {
            data.hidden_gems.forEach(gem => {
                gemsHtml += `
                    <div class="mb-3 border-bottom pb-3 last-border-0">
                        <h6 class="fw-bold text-dark mb-1">${gem.name}</h6>
                        <small class="text-muted">${gem.description}</small>
                    </div>
                `;
            });
        }
        $('#hiddenGemsContainer').html(gemsHtml);

        // Render Budget Chart
        $('#totalCost').text(Math.round(data.budget.total).toLocaleString('en-IN'));
        $('#budgetTypeDetails').text(`Includes ${data.budget.stay_type} & ${data.budget.transport_type}`);

        if (budgetChartInstance) {
            budgetChartInstance.destroy();
        }

        const ctx = document.getElementById('budgetChart').getContext('2d');
        budgetChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Transport', 'Accommodation', 'Food', 'Activities'],
                datasets: [{
                    data: [
                        data.budget.transport,
                        data.budget.accommodation,
                        data.budget.food,
                        data.budget.activities
                    ],
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });
    }

    // Download PDF
    $('#downloadPdfBtn').click(function() {
        const element = document.getElementById('pdf-content');
        const opt = {
            margin:       0.5,
            filename:     'Bihar_Vihaan_Itinerary.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });

    // Animate Counters (Social Proof)
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            };
            updateCount();
        });
    }

    // Trigger counter animation on scroll
    let animationTriggered = false;
    $(window).scroll(function() {
        if(!animationTriggered) {
            let hT = $('.stat-counter').offset().top,
                hH = $('.stat-counter').outerHeight(),
                wH = $(window).height(),
                wS = $(this).scrollTop();
            if (wS > (hT+hH-wH)){
                animateCounters();
                animationTriggered = true;
            }
        }
    });
});
</script>

<?php require 'layout/footer.php'; ?>
