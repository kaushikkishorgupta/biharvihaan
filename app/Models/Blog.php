<?php

namespace App\Models;

use App\Core\Database;

class Blog {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find($id) {
        $sql = "SELECT b.*, u.name as author_name 
                FROM blogs b 
                LEFT JOIN users u ON b.author_id = u.id 
                WHERE b.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function findBySlug($slug) {
        $sql = "SELECT b.*, u.name as author_name 
                FROM blogs b 
                LEFT JOIN users u ON b.author_id = u.id 
                WHERE b.slug = ?";
        return $this->db->queryRow($sql, [$slug]);
    }

    public function all() {
        $sql = "SELECT b.*, u.name as author_name 
                FROM blogs b 
                LEFT JOIN users u ON b.author_id = u.id 
                ORDER BY b.id DESC";
        return $this->db->query($sql);
    }

    public function create($data) {
        $sql = "INSERT INTO blogs (title, slug, content, image_url, author_id, status, scheduled_at, category_id, tags, meta_title, meta_description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image_url'] ?? '',
            $data['author_id'],
            $data['status'] ?? 'draft',
            $data['scheduled_at'] ?? null,
            $data['category_id'] ?? null,
            $data['tags'] ?? '',
            $data['meta_title'] ?? '',
            $data['meta_description'] ?? ''
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE blogs 
                SET title = ?, slug = ?, content = ?, image_url = ?, status = ?, scheduled_at = ?, category_id = ?, tags = ?, meta_title = ?, meta_description = ? 
                WHERE id = ?";
        return $this->db->execute($sql, [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image_url'],
            $data['status'],
            $data['scheduled_at'],
            $data['category_id'],
            $data['tags'],
            $data['meta_title'],
            $data['meta_description'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM blogs WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}
