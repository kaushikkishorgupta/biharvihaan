<?php

namespace App\Models;

use App\Core\Database;

class GalleryModel {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getImages($category = 'All', $limit = 20, $offset = 0) {
        if ($category && $category !== 'All') {
            $sql = "SELECT * FROM gallery_images WHERE status = 'active' AND category = ? ORDER BY id DESC LIMIT ? OFFSET ?";
            return $this->db->query($sql, [$category, $limit, $offset]);
        }
        $sql = "SELECT * FROM gallery_images WHERE status = 'active' ORDER BY id DESC LIMIT ? OFFSET ?";
        return $this->db->query($sql, [$limit, $offset]);
    }

    public function incrementViews($id) {
        $sql = "UPDATE gallery_images SET views = views + 1 WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM gallery_images WHERE status = 'active' AND category IS NOT NULL";
        $results = $this->db->query($sql);
        return array_column($results, 'category');
    }

    public function addImage($data) {
        $sql = "INSERT INTO gallery_images (title, slug, description, location, category, image, photographer, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [
            $data['title'],
            $data['slug'],
            $data['description'],
            $data['location'],
            $data['category'],
            $data['image'],
            $data['photographer'],
            $data['status'] ?? 'pending'
        ]);
    }

    public function getTrending() {
        $sql = "SELECT * FROM gallery_images WHERE status = 'active' ORDER BY views DESC LIMIT 6";
        return $this->db->query($sql);
    }
}
