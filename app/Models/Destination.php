<?php

namespace App\Models;

use App\Core\Database;

class Destination {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all($category = null) {
        if ($category) {
            $sql = "SELECT * FROM destinations WHERE category = ? AND status = 'active' ORDER BY rating DESC";
            return $this->db->query($sql, [$category]);
        }
        $sql = "SELECT * FROM destinations WHERE status = 'active' ORDER BY views_count DESC";
        return $this->db->query($sql);
    }

    public function find($id) {
        $sql = "SELECT * FROM destinations WHERE id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function incrementViews($id) {
        $sql = "UPDATE destinations SET views_count = views_count + 1 WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function getAttractions($destinationId) {
        $sql = "SELECT * FROM attractions WHERE destination_id = ?";
        return $this->db->query($sql, [$destinationId]);
    }

    // Smart Autocomplete search
    public function search($query) {
        $sql = "SELECT id, name, category, location, image_url 
                FROM destinations 
                WHERE (name LIKE ? OR category LIKE ? OR location LIKE ?) AND status = 'active' 
                LIMIT 8";
        $searchTerm = "%$query%";
        return $this->db->query($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }

    // AI-powered personalized travel itinerary questionnaire match
    public function getPersonalizedRecommendations($preferences) {
        // Simple heuristic engine simulating AI classification
        // Matches categories ('Spiritual', 'Heritage', 'Nature', 'Adventure') & location tags
        $category = $preferences['category'] ?? null;
        $rating = floatval($preferences['rating'] ?? 4.0);

        if ($category) {
            $sql = "SELECT * FROM destinations WHERE category = ? AND rating >= ? AND status = 'active' ORDER BY rating DESC LIMIT 5";
            return $this->db->query($sql, [$category, $rating]);
        }
        
        $sql = "SELECT * FROM destinations WHERE rating >= ? AND status = 'active' ORDER BY views_count DESC LIMIT 5";
        return $this->db->query($sql, [$rating]);
    }

    // Saved wishlist destinations
    public function savePlace($userId, $destinationId) {
        $sql = "INSERT IGNORE INTO saved_places (user_id, destination_id) VALUES (?, ?)";
        return $this->db->execute($sql, [$userId, $destinationId]);
    }

    public function unsavePlace($userId, $destinationId) {
        $sql = "DELETE FROM saved_places WHERE user_id = ? AND destination_id = ?";
        return $this->db->execute($sql, [$userId, $destinationId]);
    }

    public function getSavedPlaces($userId) {
        $sql = "SELECT d.* FROM destinations d
                JOIN saved_places s ON d.id = s.destination_id
                WHERE s.user_id = ?";
        return $this->db->query($sql, [$userId]);
    }

    // Custom multi-day itineraries
    public function createItinerary($userId, $title, $description, $days) {
        $sql = "INSERT INTO itineraries (user_id, title, description, duration_days) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [$userId, $title, $description, count($days)]);
        $itineraryId = $this->db->lastInsertId();

        foreach ($days as $dayNumber => $activities) {
            $sqlDay = "INSERT INTO itinerary_days (itinerary_id, day_number, activities) VALUES (?, ?, ?)";
            $this->db->execute($sqlDay, [$itineraryId, $dayNumber + 1, $activities]);
        }

        return $itineraryId;
    }

    public function getItinerary($id) {
        $sql = "SELECT * FROM itineraries WHERE id = ?";
        $itinerary = $this->db->queryRow($sql, [$id]);
        
        if ($itinerary) {
            $sqlDays = "SELECT * FROM itinerary_days WHERE itinerary_id = ? ORDER BY day_number ASC";
            $itinerary['days'] = $this->db->query($sqlDays, [$id]);
        }
        
        return $itinerary;
    }

    public function getUserItineraries($userId) {
        $sql = "SELECT * FROM itineraries WHERE user_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$userId]);
    }
}
