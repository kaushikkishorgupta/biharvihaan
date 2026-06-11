<?php

namespace App\Models;

use App\Core\Database;

class Community {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getTopics() {
        $sql = "SELECT t.*, u.name as author_name, (SELECT COUNT(*) FROM forum_replies r WHERE r.topic_id = t.id) as reply_count FROM forum_topics t JOIN users u ON t.author_id = u.id ORDER BY t.created_at DESC";
        return $this->db->query($sql);
    }

    public function getTopicBySlug($slug) {
        $sql = "SELECT t.*, u.name as author_name FROM forum_topics t JOIN users u ON t.author_id = u.id WHERE t.slug = ?";
        return $this->db->queryRow($sql, [$slug]);
    }

    public function getTopicById($id) {
        $sql = "SELECT t.*, u.name as author_name FROM forum_topics t JOIN users u ON t.author_id = u.id WHERE t.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function createTopic($title, $slug, $content, $authorId) {
        $sql = "INSERT INTO forum_topics (title, slug, content, author_id) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [$title, $slug, $content, $authorId]);
        return $this->db->lastInsertId();
    }

    public function addReply($topicId, $content, $authorId) {
        $sql = "INSERT INTO forum_replies (topic_id, content, author_id) VALUES (?, ?, ?)";
        $this->db->execute($sql, [$topicId, $content, $authorId]);
        return $this->db->lastInsertId();
    }
}
