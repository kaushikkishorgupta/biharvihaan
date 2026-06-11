<?php

namespace App\Models;

use App\Core\Database;

class Creator {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function applyForVerification($userId, $bio, $portfolioUrl) {
        $sql = "INSERT INTO creators (user_id, bio, portfolio_url, verification_status) VALUES (?, ?, ?, 'pending')";
        $this->db->execute($sql, [$userId, $bio, $portfolioUrl]);
        return $this->db->lastInsertId();
    }

    public function getCreatorByUserId($userId) {
        $sql = "SELECT * FROM creators WHERE user_id = ?";
        return $this->db->queryRow($sql, [$userId]);
    }

    public function saveDraft($creatorId, $title, $content, $status = 'draft') {
        $sql = "INSERT INTO content_drafts (creator_id, title, content, status) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [$creatorId, $title, $content, $status]);
        return $this->db->lastInsertId();
    }
}
