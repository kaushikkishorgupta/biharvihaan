<?php
use App\Core\Session;
$viewMode = $view_mode ?? 'docs';
?>

<!-- Alerts Container -->
<div class="container" style="margin-top: 100px; margin-bottom: -50px; z-index: 10; position: relative;">
    <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-success-subtle bg-success-subtle text-success small" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= Session::getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<!-- ==========================================
     1. INVOICE RECEIPT BILLING
     ========================================== -->
<?php if ($viewMode === 'invoice'): ?>
    <div class="container py-5 mt-5 max-w-2xl">
        <div class="card bg-white text-dark shadow p-4 p-md-5" id="printable-area" style="border-radius:12px; border:1px solid rgba(0,0,0,0.08);">
            
            <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-0 font-outfit">BIHAR VIHAAN ENTERPRISE</h1>
                    <span class="small text-muted fw-bold">GST Billed Transaction Receipt</span>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-dark fs-5">TAX INVOICE</div>
                    <div class="small text-muted mt-1">Date: <?= date('d M Y H:i', strtotime($payment['created_at'])) ?></div>
                </div>
            </div>

            <div class="row g-4 mb-5 small">
                <div class="col-6">
                    <div class="text-uppercase text-muted fw-bold mb-2">Billed To:</div>
                    <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($payment['user_name']) ?></div>
                    <div class="text-muted"><?= htmlspecialchars($payment['user_email']) ?></div>
                </div>
                <div class="col-6 text-end">
                    <div class="text-uppercase text-muted fw-bold mb-2">Audit Codes:</div>
                    <div>Order ID: <code class="bg-light text-dark px-2 py-1 rounded small"><?= htmlspecialchars($payment['order_id']) ?></code></div>
                    <div class="mt-1">TXN Code: <code class="bg-light text-dark px-2 py-1 rounded small"><?= htmlspecialchars($payment['transaction_id']) ?></code></div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table align-middle border-bottom small">
                    <thead>
                        <tr class="table-light text-muted fw-bold border-bottom">
                            <th class="py-2 px-3">Billed Category</th>
                            <th class="py-2 px-3">Item Details</th>
                            <th class="py-2 px-3 text-end">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 text-uppercase text-muted small"><?= htmlspecialchars($payment['reference_type']) ?></td>
                            <td class="px-3 fw-bold text-dark">
                                <?php 
                                if ($payment['reference_type'] === 'booking') {
                                    echo htmlspecialchars($reference['item_name']) . " Room Booking / Guide Reservation";
                                } elseif ($payment['reference_type'] === 'event_registration') {
                                    echo htmlspecialchars($reference['event_title']) . " (" . $reference['ticket_count'] . " passes)";
                                } else {
                                    echo "Premium Membership Package";
                                }
                                ?>
                            </td>
                            <td class="px-3 text-end fw-bold">₹<?= number_format($payment['amount'], 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end fw-bold text-muted py-2">Base Total:</td>
                            <td class="text-end fw-bold py-2">₹<?= number_format($payment['amount'], 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end fw-bold text-muted py-2">Taxes (IGST 0%):</td>
                            <td class="text-end fw-bold py-2">₹0.00</td>
                        </tr>
                        <tr class="table-group-divider border-top border-dark border-3 fs-5">
                            <td colspan="2" class="text-end fw-extrabold text-dark py-3">Total Amount Billed:</td>
                            <td class="text-end fw-extrabold text-primary py-3">₹<?= number_format($payment['amount'], 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-end small text-muted border-top pt-4">
                <div>
                    <div>Payment Gateway: <?= htmlspecialchars($payment['gateway']) ?></div>
                    <div>Status Code: <span class="text-success fw-bold">CAPTURED</span></div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-dark mb-4">BIHAR VIHAAN OFFICIAL STAMP</div>
                    <div class="border-top pt-1 text-center" style="width: 150px; display:inline-block;">Authorized Signatory</div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 d-flex justify-content-center gap-3">
            <button onclick="window.print()" class="btn btn-primary px-4 fw-bold">🖨 Print Invoice</button>
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light px-4">Back to Dashboard</a>
        </div>
    </div>

<!-- ==========================================
     2. NEWSROOM & PODCASTS
     ========================================== -->
<?php elseif ($viewMode === 'media'): ?>
    <div class="container py-5 mt-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="display-5 fw-bold text-white font-outfit">News &amp; Media Network</h1>
            <p class="text-muted">Stay updated with Bihar news category logs, startup fundings, and documentaries.</p>
        </div>

        <!-- News articles categories -->
        <h2 class="h3 text-white fw-bold mb-4 font-outfit border-left border-primary ps-3" style="border-left-width: 4px;">Top Stories</h2>
        <div class="row g-4 mb-5" data-aos="fade-up">
            <div class="col-md-6">
                <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-success mb-2 px-3">Startup &amp; Funding</span>
                        <h3 class="h5 fw-bold text-white mb-2 font-outfit">Patna Startups Hit Record Seed Capital</h3>
                        <p class="small text-muted lh-lg">Agri-tech startups, local Madhubani craft hubs, and organic food supply lines across Bihar secured positive capital commitments from national VC circles, accelerating direct exports.</p>
                    </div>
                    <span class="xsmall text-muted pt-3 border-top border-secondary-subtle">Date Published: June 11, 2026</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow h-100 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-primary mb-2 px-3">Infrastructure</span>
                        <h3 class="h5 fw-bold text-white mb-2 font-outfit">Eco-safari Cottages Open inside Valmiki Forest</h3>
                        <p class="small text-muted lh-lg">The Department of Tourism completed construction on solar-powered eco-cottages inside Valmiki Tiger reserves, encouraging nature guides hiring and online safari reservations.</p>
                    </div>
                    <span class="xsmall text-muted pt-3 border-top border-secondary-subtle">Date Published: May 24, 2026</span>
                </div>
            </div>
        </div>

        <!-- Podcast loops -->
        <h2 class="h3 text-white fw-bold mb-4 font-outfit border-left border-secondary ps-3" style="border-left-width: 4px;">Bihar Vihaan podcasts</h2>
        <div class="row g-4" data-aos="fade-up">
            <div class="col-md-6">
                <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow">
                    <h3 class="h5 fw-bold text-white mb-2 font-outfit">Relics of ancient Nalanda University</h3>
                    <p class="small text-muted mb-4">An interview with archaeological curators explaining the library records, stupas, and ancient clay seals found during Nalanda excavations.</p>
                    <div class="bg-dark p-3 rounded d-flex justify-content-between align-items-center">
                        <span class="small text-primary"><i class="fa-solid fa-headphones me-2"></i> Episode #4 Playing (Simulated)</span>
                        <button class="btn btn-primary btn-sm px-3 rounded-pill">Play</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-card border-secondary-subtle p-4 rounded-4 shadow">
                    <h3 class="h5 fw-bold text-white mb-2 font-outfit">Folk Rhythms &amp; kajri Folk Songs</h3>
                    <p class="small text-muted mb-4">A podcast with singer Sanjeev Kumar highlighting the acoustic archiving of Bhojpuri and Maithili folk music recitals for Chhath Puja.</p>
                    <div class="bg-dark p-3 rounded d-flex justify-content-between align-items-center">
                        <span class="small text-primary"><i class="fa-solid fa-headphones me-2"></i> Episode #3 Playing (Simulated)</span>
                        <button class="btn btn-primary btn-sm px-3 rounded-pill">Play</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ==========================================
<!-- ==========================================
     3. OUR CLIENTS & PARTNERS
     ========================================== -->
<?php elseif ($viewMode === 'clients'): ?>
    
    <!-- Premium Futuristic Enterprise Logo Showcase -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clients.css">
    
    <section class="clients-section mt-5" style="border-radius: 24px;">
        <!-- Animated Backgrounds -->
        <div class="client-bg-gradient"></div>
        <div class="client-particles"></div>
        
        <div class="container position-relative py-5" style="z-index: 2;">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge badge-glow text-uppercase px-3 py-2 rounded-pill mb-3 fw-bold">Trusted By</span>
                <h2 class="display-5 fw-bold text-white mb-3" style="font-family: 'Outfit', sans-serif;">Our Clients & Partners</h2>
                <p class="body-text-custom text-white-50" style="max-width: 700px; margin: 0 auto;">
                    Partnering with leading institutions, tourism organizations, educational centers, and enterprises across Bihar.
                </p>
            </div>
            
            <div class="clients-marquee-wrapper" data-aos="fade-up" data-aos-delay="100">
                <!-- Two identical marquees for seamless infinite looping -->
                <div class="clients-marquee">
                    <?php
                    $clientsList = [
                        ['name' => 'Bihar Tourism', 'tag' => 'Tourism', 'img' => 'https://ui-avatars.com/api/?name=BT&background=0B3D91&color=fff&size=200'],
                        ['name' => 'NTPC Kahalgaon', 'tag' => 'Enterprise', 'img' => 'https://ui-avatars.com/api/?name=NTPC&background=FF9933&color=fff&size=200'],
                        ['name' => 'Maurya Hotels', 'tag' => 'Hospitality', 'img' => 'https://ui-avatars.com/api/?name=MH&background=0D8ABC&color=fff&size=200'],
                        ['name' => 'Sudha Dairy', 'tag' => 'Enterprise', 'img' => 'https://ui-avatars.com/api/?name=SD&background=28a745&color=fff&size=200'],
                        ['name' => 'IIT Patna', 'tag' => 'Education', 'img' => 'https://ui-avatars.com/api/?name=IIT&background=6c757d&color=fff&size=200'],
                        ['name' => 'NIFT Patna', 'tag' => 'Education', 'img' => 'https://ui-avatars.com/api/?name=NIFT&background=17a2b8&color=fff&size=200'],
                    ];
                    foreach ($clientsList as $clientData): ?>
                    <div class="client-card">
                        <img src="<?= $clientData['img'] ?>" alt="<?= $clientData['name'] ?>" loading="lazy">
                        <div class="client-info">
                            <h5><?= $clientData['name'] ?></h5>
                            <span><?= $clientData['tag'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="clients-marquee" aria-hidden="true">
                    <?php foreach ($clientsList as $clientData): ?>
                    <div class="client-card">
                        <img src="<?= $clientData['img'] ?>" alt="<?= $clientData['name'] ?>" loading="lazy">
                        <div class="client-info">
                            <h5><?= $clientData['name'] ?></h5>
                            <span><?= $clientData['tag'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <script src="<?= BASE_URL ?>/assets/js/clients.js"></script>

     4. DEVELOPER REST API DOCUMENTATION
     ========================================== -->
<?php else: ?>
    <div class="container py-5 mt-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="display-5 fw-bold text-white font-outfit">REST API Reference</h1>
            <p class="text-muted">Developer documentation for queries, reviews, and news category listings.</p>
        </div>

        <!-- Doc 1 -->
        <div class="card bg-card border-secondary-subtle rounded-4 overflow-hidden shadow-lg p-4 mb-4" data-aos="fade-up">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-success px-3">GET</span>
                <code class="text-white font-mono"><?= BASE_URL ?>/api/destinations</code>
            </div>
            <p class="small text-muted mb-3">List all active tourism destinations. Support optional category filters.</p>
            <pre class="bg-dark text-info p-3 rounded small font-monospace mb-0">{
  "success": true,
  "count": 1,
  "data": [
    {
      "id": 1,
      "name": "Mahabodhi Temple Complex",
      "category": "Spiritual",
      "location": "Bodh Gaya, Gaya",
      "rating": "4.9",
      "views_count": 5420
    }
  ]
}</pre>
        </div>

        <!-- Doc 2 -->
        <div class="card bg-card border-secondary-subtle rounded-4 overflow-hidden shadow-lg p-4 mb-4" data-aos="fade-up">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-success px-3">GET</span>
                <code class="text-white font-mono"><?= BASE_URL ?>/api/news</code>
            </div>
            <p class="small text-muted mb-3">Fetch news categories articles (Politics, Startup, Sports, Culture).</p>
            <pre class="bg-dark text-info p-3 rounded small font-monospace mb-0">{
  "success": true,
  "count": 2,
  "data": [
    {
      "id": 1,
      "title": "Sonepur Mela Cultural Stage Expansion",
      "category": "Culture",
      "views_count": 450
    }
  ]
}</pre>
        </div>

        <!-- Doc 3 -->
        <div class="card bg-card border-secondary-subtle rounded-4 overflow-hidden shadow-lg p-4" data-aos="fade-up">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-primary px-3">POST</span>
                <code class="text-white font-mono"><?= BASE_URL ?>/api/reviews</code>
            </div>
            <p class="small text-muted mb-3">Post rating review comments. Requires active Auth session cookie.</p>
            <pre class="bg-dark text-info p-3 rounded small font-monospace mb-0">{
  "success": true,
  "message": "Review published successfully!"
}</pre>
        </div>
    </div>
<?php endif; ?>
