<?php

namespace App\Models;

use App\Core\Database;

class Artist {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getArtists($category = null, $onlyVerified = true) {
        $params = [];
        $sql = "SELECT a.*, u.name as user_name, u.email as user_email, u.phone as user_phone
                FROM artists a
                JOIN users u ON a.user_id = u.id";
        
        $conditions = [];
        if ($category) {
            $conditions[] = "a.category = ?";
            $params[] = $category;
        }
        if ($onlyVerified) {
            $conditions[] = "a.verification_status = 'verified'";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY a.id DESC";
        return $this->db->query($sql, $params);
    }

    public function findArtist($id) {
        $sql = "SELECT a.*, u.name as user_name, u.email as user_email, u.phone as user_phone
                FROM artists a
                JOIN users u ON a.user_id = u.id
                WHERE a.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function findArtistByUserId($userId) {
        $sql = "SELECT a.*, u.name as user_name, u.email as user_email, u.phone as user_phone
                FROM artists a
                JOIN users u ON a.user_id = u.id
                WHERE a.user_id = ?";
        return $this->db->queryRow($sql, [$userId]);
    }

    public function createArtistProfile($data) {
        $sql = "INSERT INTO artists (user_id, stage_name, category, bio, portfolio_url, verification_status) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        
        $this->db->execute($sql, [
            $data['user_id'],
            $data['stage_name'],
            $data['category'],
            $data['bio'],
            $data['portfolio_url'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function verifyArtist($id, $status = 'verified') {
        $sql = "UPDATE artists SET verification_status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $id]);
    }

    // Portfolio Management
    public function getPortfolios($artistId) {
        $sql = "SELECT * FROM portfolios WHERE artist_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$artistId]);
    }

    public function addPortfolio($artistId, $data) {
        $sql = "INSERT INTO portfolios (artist_id, title, description, media_type, media_url) 
                VALUES (?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            $artistId,
            $data['title'],
            $data['description'] ?? null,
            $data['media_type'], // image, video, link
            $data['media_url']
        ]);
    }

    // Collaboration Networking requests
    public function createCollaborationRequest($senderId, $receiverId, $message) {
        $sql = "INSERT INTO collaboration_requests (sender_id, receiver_id, message, status) 
                VALUES (?, ?, ?, 'pending')";
        return $this->db->execute($sql, [$senderId, $receiverId, $message]);
    }

    public function getCollaborationRequests($userId) {
        // Fetch collaboration requests received by this user (artist)
        $sql = "SELECT cr.*, u.name as sender_name, u.email as sender_email 
                FROM collaboration_requests cr
                JOIN users u ON cr.sender_id = u.id
                WHERE cr.receiver_id = ?
                ORDER BY cr.id DESC";
        return $this->db->query($sql, [$userId]);
    }

    public function updateCollaborationStatus($requestId, $status) {
        $sql = "UPDATE collaboration_requests SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $requestId]);
    }
}
