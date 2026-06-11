<?php

namespace App\Models;

use App\Core\Database;

class Learning {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllCourses($status = 'active') {
        $sql = "SELECT c.*, u.name as instructor_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.status = ?";
        return $this->db->query($sql, [$status]);
    }

    public function getCourseById($id) {
        $sql = "SELECT c.*, u.name as instructor_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function enrollUser($courseId, $userId) {
        $sql = "INSERT INTO course_enrollments (course_id, user_id) VALUES (?, ?)";
        return $this->db->execute($sql, [$courseId, $userId]);
    }
}
