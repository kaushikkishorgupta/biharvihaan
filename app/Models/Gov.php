<?php

namespace App\Models;

use App\Core\Database;

class Gov {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllSchemes($status = 'active') {
        $sql = "SELECT * FROM government_schemes WHERE status = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$status]);
    }

    public function getSchemeById($id) {
        $sql = "SELECT * FROM government_schemes WHERE id = ?";
        return $this->db->queryRow($sql, [$id]);
    }
}
