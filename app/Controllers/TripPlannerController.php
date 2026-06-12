<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class TripPlannerController extends Controller {

    public function index() {
        $this->render('trip_planner', ['title' => 'AI Trip Planner | Bihar Vihaan Enterprise']);
    }

    public function generate() {
        // Collect Advanced Inputs
        $start_city = $_POST['start_city'] ?? 'Patna';
        $duration = (int)($_POST['duration'] ?? $_POST['days'] ?? 3);
        $group = $_POST['travel_group'] ?? $_POST['travelers'] ?? 'solo';
        $budget_level = $_POST['budget_level'] ?? $_POST['budget'] ?? 'standard';
        $travel_style = $_POST['travel_style'] ?? $_POST['interests'] ?? 'heritage';
        
        $db = Database::getInstance()->getConnection();

        // 1. Fetch Circuit Rule based on Travel Style
        $stmt = $db->prepare("SELECT recommendations_json FROM ai_planner_rules WHERE rule_type = 'circuit' AND criteria = ?");
        $stmt->execute([strtolower($travel_style)]);
        $circuitRule = $stmt->fetch(PDO::FETCH_ASSOC);

        $circuitData = $circuitRule ? json_decode($circuitRule['recommendations_json'], true) : [
            'name' => 'Custom ' . ucfirst($travel_style) . ' Circuit',
            'route' => [$start_city, 'Nalanda', 'Rajgir'],
            'highlights' => ['Local Exploration']
        ];

        // 2. Fetch Budget Rule
        $stmtBudget = $db->prepare("SELECT recommendations_json FROM ai_planner_rules WHERE rule_type = 'budget_breakdown' AND criteria = ?");
        $stmtBudget->execute([strtolower($budget_level)]);
        $budgetRule = $stmtBudget->fetch(PDO::FETCH_ASSOC);

        $budgetData = $budgetRule ? json_decode($budgetRule['recommendations_json'], true) : [
            'daily_multiplier' => 4000,
            'transport_pct' => 25,
            'accommodation_pct' => 40,
            'food_pct' => 25,
            'activities_pct' => 10,
            'suggested_stay' => '3-Star Hotels',
            'suggested_transport' => 'AC Cab / Train'
        ];

        // Adjust multiplier based on group
        $groupMultiplier = 1;
        switch($group) {
            case 'couple': $groupMultiplier = 2; break;
            case 'family': $groupMultiplier = 4; break;
            case 'friends': $groupMultiplier = 3; break;
        }

        $totalBudget = $duration * $budgetData['daily_multiplier'] * $groupMultiplier;
        
        $budgetBreakdown = [
            'transport' => $totalBudget * ($budgetData['transport_pct'] / 100),
            'accommodation' => $totalBudget * ($budgetData['accommodation_pct'] / 100),
            'food' => $totalBudget * ($budgetData['food_pct'] / 100),
            'activities' => $totalBudget * ($budgetData['activities_pct'] / 100),
            'total' => $totalBudget,
            'stay_type' => $budgetData['suggested_stay'],
            'transport_type' => $budgetData['suggested_transport']
        ];

        // 3. Fetch Hidden Gems
        $stmtGems = $db->prepare("SELECT recommendations_json FROM ai_planner_rules WHERE rule_type = 'hidden_gem'");
        $stmtGems->execute();
        $gemsRule = $stmtGems->fetch(PDO::FETCH_ASSOC);
        $hiddenGems = $gemsRule ? json_decode($gemsRule['recommendations_json'], true)['places'] : [];

        // 4. Fetch Actual Destinations from DB matching the route
        $routeCities = $circuitData['route'];
        $placeholders = str_repeat('?,', count($routeCities) - 1) . '?';
        $stmtDest = $db->prepare("SELECT * FROM destinations WHERE name IN ($placeholders) OR location IN ($placeholders)");
        $params = array_merge($routeCities, $routeCities);
        $stmtDest->execute($params);
        $dbDestinations = $stmtDest->fetchAll(PDO::FETCH_ASSOC);

        // 5. Generate Day-wise Itinerary
        $itinerary = [];
        $routeFlow = [];
        
        for ($i = 1; $i <= $duration; $i++) {
            $city = $routeCities[($i - 1) % count($routeCities)];
            $routeFlow[] = $city;

            // Find matching DB destination
            $matchingDest = null;
            foreach ($dbDestinations as $d) {
                if (stripos($d['name'], $city) !== false || stripos($d['location'], $city) !== false) {
                    $matchingDest = $d;
                    break;
                }
            }

            $itinerary[] = [
                'day' => $i,
                'city' => $city,
                'title' => "Day $i: Discovering " . $city,
                'description' => $matchingDest ? $matchingDest['description'] : "Explore the beautiful city of $city.",
                'image' => $matchingDest ? $matchingDest['image_url'] : '/public/assets/images/placeholder.jpg',
                'schedule' => [
                    'morning' => 'Visit Top Attractions: ' . ($matchingDest['name'] ?? $city . ' Highlights'),
                    'afternoon' => 'Cultural Exploration & Local Shopping',
                    'evening' => 'Relax & Authentic Dinner'
                ],
                'insider_tip' => 'Start your day early to avoid crowds and experience the serene morning ambiance.',
                'food_recommendation' => 'Try local ' . $city . ' street food specialties.',
                'travel_time' => 'Approx. 2 Hours travel today'
            ];
        }

        // De-duplicate route flow for the map
        $routeFlow = array_values(array_unique($routeFlow));

        // Prepare response
        $result = [
            'success' => true,
            'circuit_name' => $circuitData['name'],
            'route_flow' => $routeFlow,
            'itinerary' => $itinerary,
            'budget' => $budgetBreakdown,
            'hidden_gems' => $hiddenGems
        ];

        // Simulate AI generation time for frontend UX
        sleep(1); 

        echo json_encode($result);
    }
}
