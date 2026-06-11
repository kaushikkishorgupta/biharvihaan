<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class GovController extends Controller {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Display announcements, alerts, and NGO projects
     */
    public function index() {
        // Fetch public announcements from settings or direct queries (simulated)
        $announcements = [
            [
                'title' => 'Bihar Eco-Tourism Scheme 2026 Guidelines Released',
                'dept' => 'Department of Tourism, Government of Bihar',
                'date' => '10 Jun 2026',
                'details' => 'New financial grants and subsidies have been approved for local homestay operators and registered tourist guides across historical circles.'
            ],
            [
                'title' => 'Monsoon Safety Alerts for River Ganga Cruises',
                'dept' => 'Bihar State Tourism Development Corporation (BSTDC)',
                'date' => '08 Jun 2026',
                'details' => 'Due to increasing water discharge volumes, cruise operators in Patna and Bhagalpur must suspend evening river safari trips temporarily.'
            ],
            [
                'title' => 'Mithila Art Global Heritage Promotion Initiative',
                'dept' => 'Upendra Maharathi Shilp Anusandhan Sansthan',
                'date' => '01 Jun 2026',
                'details' => 'Collaborations are invited from local artists and self-help groups for the upcoming International Crafts Expo in New Delhi.'
            ]
        ];

        // Fetch official institutional events (simulated)
        $events = $this->db->query("SELECT * FROM events WHERE price = 0.00 ORDER BY date ASC LIMIT 3");

        $this->render('government', [
            'title' => 'Gov & Institutional Portal - Bihar Vihaan',
            'announcements' => $announcements,
            'officialEvents' => $events
        ]);
    }
}
