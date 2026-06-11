<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Destination;

class TourismController extends Controller {

    public function index() {
        $destModel = new Destination();
        $category = $_GET['category'] ?? null;
        $destinations = $destModel->all($category);

        $this->render('tourism', [
            'title' => 'Tourism Hotspots - Bihar Vihaan Enterprise',
            'destinations' => $destinations,
            'selected_category' => $category
        ]);
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        $destModel = new Destination();
        $results = [];
        if (strlen($query) >= 2) {
            $results = $destModel->search($query);
        }

        $this->render('tourism', [
            'title' => 'Search Results - Bihar Vihaan Enterprise',
            'query' => $query,
            'destinations' => $results,
            'view_mode' => 'search_results'
        ]);
    }

    public function detail($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/tourism');
        }

        $destModel = new Destination();
        $destination = $destModel->find($id);

        if (!$destination) {
            die("Destination not found.");
        }

        // Increment views for analytics
        $destModel->incrementViews($id);

        $attractions = $destModel->getAttractions($id);

        // Check if user has saved this place
        $isSaved = false;
        if (Auth::check()) {
            $saved = $destModel->getSavedPlaces(Session::get('user_id'));
            foreach ($saved as $s) {
                if ($s['id'] == $id) {
                    $isSaved = true;
                    break;
                }
            }
        }

        $this->render('tourism', [
            'title' => $destination['name'] . ' - Bihar Vihaan Enterprise',
            'destination' => $destination,
            'attractions' => $attractions,
            'is_saved' => $isSaved,
            'view_mode' => 'detail'
        ]);
    }

    public function savePlace($params) {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to save places to your wishlist.');
            $this->redirect('/login');
        }

        $destId = $params['id'] ?? null;
        if ($destId) {
            $destModel = new Destination();
            $destModel->savePlace(Session::get('user_id'), $destId);
            Session::setFlash('success', 'Destination saved to your wishlist!');
        }

        $this->redirect('/tourism/' . $destId);
    }

    public function unsavePlace($params) {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $destId = $params['id'] ?? null;
        if ($destId) {
            $destModel = new Destination();
            $destModel->unsavePlace(Session::get('user_id'), $destId);
            Session::setFlash('success', 'Destination removed from your wishlist.');
        }

        $this->redirect('/tourism/' . $destId);
    }

    public function recommendations() {
        $this->render('tourism', [
            'title' => 'Personalized Tourism Intelligence - Bihar Vihaan Enterprise',
            'view_mode' => 'recommendations'
        ]);
    }

    public function handleRecommendations() {
        $category = $_POST['category'] ?? '';
        $rating = $_POST['rating'] ?? 4.0;
        
        $destModel = new Destination();
        $results = $destModel->getPersonalizedRecommendations([
            'category' => $category,
            'rating' => $rating
        ]);

        $this->render('tourism', [
            'title' => 'Your Custom Recommendations - Bihar Vihaan Enterprise',
            'recommendations' => $results,
            'survey' => [
                'category' => $category,
                'rating' => $rating
            ],
            'view_mode' => 'recommendations_results'
        ]);
    }

    public function handleCreateItinerary() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to build custom travel itineraries.');
            $this->redirect('/login');
        }

        $title = $_POST['title'] ?? 'My Trip to Bihar';
        $desc = $_POST['description'] ?? '';
        $days = $_POST['days'] ?? [];

        if (empty($days)) {
            Session::setFlash('error', 'Please add activities for at least one day.');
            $this->redirect('/tourism');
        }

        $destModel = new Destination();
        $itineraryId = $destModel->createItinerary(Session::get('user_id'), $title, $desc, $days);

        if ($itineraryId) {
            Session::setFlash('success', 'Itinerary generated successfully! You can view it in your dashboard.');
        } else {
            Session::setFlash('error', 'Could not save itinerary. Try again.');
        }

        $this->redirect('/dashboard');
    }

    // GET /tourism/ai-planner
    public function aiPlanner() {
        $this->render('tourism', [
            'title' => 'AI Travel Planner - Bihar Vihaan Enterprise',
            'view_mode' => 'ai_planner'
        ]);
    }

    // POST /tourism/ai-planner
    public function handleAiPlanner() {
        $style = $_POST['style'] ?? 'Solo';
        $budget = $_POST['budget'] ?? 'medium';
        $duration = intval($_POST['duration'] ?? 3);

        if ($duration < 1 || $duration > 7) {
            $duration = 3;
        }

        // 1. Calculate Estimated Budget
        $baseRate = ($budget === 'low') ? 1200 : (($budget === 'luxury') ? 8000 : 3500);
        $totalEstimatedCost = $baseRate * $duration;

        // 2. Query destinations suited for category mapping
        $categoryMap = [
            'Pilgrimage' => 'Spiritual',
            'Adventure'  => 'Adventure',
            'Solo'       => 'Heritage',
            'Family'     => 'Spiritual',
            'Student'    => 'Heritage'
        ];
        
        $category = $categoryMap[$style] ?? 'Spiritual';
        $destModel = new Destination();
        $destinations = $destModel->all($category);
        
        if (empty($destinations)) {
            $destinations = $destModel->all(); // Fallback to all
        }

        // 3. Assemble day-wise itineraries
        $dayPlans = [];
        for ($i = 1; $i <= $duration; $i++) {
            $dest = $destinations[($i - 1) % count($destinations)];
            
            $dayPlans[$i] = [
                'destination_name' => $dest['name'],
                'location' => $dest['location'],
                'image' => $dest['image_url'],
                'activities' => "Morning visit to the main " . htmlspecialchars($dest['name']) . " temple/ruins. Afternoon local guided walks. Evening street shopping for native Bihar handicrafts.",
                'travel_tip' => ($budget === 'low') ? "Use local shared e-rickshaws for cheap transit." : "Rent a private AC sedan from Bihar Vihaan booking portal."
            ];
        }

        $this->render('tourism', [
            'title' => 'AI Generated Trip Plan - Bihar Vihaan Enterprise',
            'style' => $style,
            'budget' => $budget,
            'duration' => $duration,
            'total_cost' => $totalEstimatedCost,
            'day_plans' => $dayPlans,
            'view_mode' => 'ai_planner_results'
        ]);
    }
}

