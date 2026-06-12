<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance()->getConnection();

// Create ai_planner_rules Table
$db->exec("
CREATE TABLE IF NOT EXISTS ai_planner_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_type VARCHAR(50) NOT NULL,
    criteria VARCHAR(255) NOT NULL,
    recommendations_json JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Clear existing data to reseed safely
$db->exec("TRUNCATE TABLE ai_planner_rules");

$rules = [
    // Travel Circuits (Style = Spiritual)
    [
        'rule_type' => 'circuit',
        'criteria' => 'spiritual',
        'recommendations_json' => json_encode([
            'name' => 'The Enlightenment Trail',
            'route' => ['Patna', 'Bodh Gaya', 'Rajgir', 'Nalanda'],
            'highlights' => ['Mahabodhi Temple', 'Vishwa Shanti Stupa', 'Nalanda University Ruins'],
            'ideal_duration' => 4
        ])
    ],
    // Travel Circuits (Style = Heritage)
    [
        'rule_type' => 'circuit',
        'criteria' => 'heritage',
        'recommendations_json' => json_encode([
            'name' => 'The Golden Heritage',
            'route' => ['Patna', 'Vaishali', 'Kesaria', 'Valmiki Tiger Reserve'],
            'highlights' => ['Bihar Museum', 'Ashokan Pillar', 'Kesaria Stupa', 'Wildlife Safari'],
            'ideal_duration' => 5
        ])
    ],
    // Travel Circuits (Style = Nature)
    [
        'rule_type' => 'circuit',
        'criteria' => 'nature',
        'recommendations_json' => json_encode([
            'name' => 'Wild Bihar Expedition',
            'route' => ['Patna', 'Valmiki Tiger Reserve', 'Kaimur', 'Rohtasgarh'],
            'highlights' => ['Jungle Safari', 'Kaimur Hills', 'Waterfalls', 'Rohtasgarh Fort'],
            'ideal_duration' => 5
        ])
    ],
    // Hidden Gems
    [
        'rule_type' => 'hidden_gem',
        'criteria' => 'all',
        'recommendations_json' => json_encode([
            'places' => [
                ['name' => 'Barabar Caves', 'description' => 'The oldest surviving rock-cut caves in India, located in Jehanabad.'],
                ['name' => 'Tutel Bhawani Waterfall', 'description' => 'A mesmerizing waterfall in Rohtas district, perfect for nature lovers.'],
                ['name' => 'Kakolat Waterfall', 'description' => 'A scenic waterfall in Nawada, great for a refreshing dip.']
            ]
        ])
    ],
    // Budget Rules
    [
        'rule_type' => 'budget_breakdown',
        'criteria' => 'premium',
        'recommendations_json' => json_encode([
            'daily_multiplier' => 8000, // INR per day
            'transport_pct' => 25,
            'accommodation_pct' => 45,
            'food_pct' => 20,
            'activities_pct' => 10,
            'suggested_stay' => '4-Star Hotels & Heritage Resorts',
            'suggested_transport' => 'Private AC Cab (Innova/SUV)'
        ])
    ],
    [
        'rule_type' => 'budget_breakdown',
        'criteria' => 'budget',
        'recommendations_json' => json_encode([
            'daily_multiplier' => 2000, // INR per day
            'transport_pct' => 30,
            'accommodation_pct' => 30,
            'food_pct' => 30,
            'activities_pct' => 10,
            'suggested_stay' => 'Hostels & Budget Guesthouses',
            'suggested_transport' => 'Local Buses & Shared Autos'
        ])
    ]
];

$stmt = $db->prepare("INSERT INTO ai_planner_rules (rule_type, criteria, recommendations_json) VALUES (?, ?, ?)");
foreach ($rules as $rule) {
    $stmt->execute([$rule['rule_type'], $rule['criteria'], $rule['recommendations_json']]);
}

echo "ai_planner_rules table created and seeded successfully.\n";
