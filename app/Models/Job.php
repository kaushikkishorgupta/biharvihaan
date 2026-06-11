<?php

namespace App\Models;

use App\Core\Database;

class Job {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getInternships($status = 'active') {
        $sql = "SELECT * FROM internships WHERE status = ? ORDER BY id DESC";
        return $this->db->query($sql, [$status]);
    }

    public function findInternship($id) {
        $sql = "SELECT * FROM internships WHERE id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function createInternship($data) {
        $sql = "INSERT INTO internships (title, department, description, duration, requirements, stipend, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'active')";
        
        $this->db->execute($sql, [
            $data['title'],
            $data['department'],
            $data['description'],
            $data['duration'],
            $data['requirements'],
            $data['stipend']
        ]);

        return $this->db->lastInsertId();
    }

    public function applyForInternship($data) {
        $sql = "INSERT INTO job_applications (internship_id, name, email, phone, resume_path, cover_letter, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        
        return $this->db->execute($sql, [
            $data['internship_id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['resume_path'],
            $data['cover_letter'] ?? null
        ]);
    }

    public function getApplications($internshipId = null) {
        if ($internshipId) {
            $sql = "SELECT ja.*, i.title as internship_title, i.department as internship_dept 
                    FROM job_applications ja
                    JOIN internships i ON ja.internship_id = i.id
                    WHERE ja.internship_id = ?
                    ORDER BY ja.id DESC";
            return $this->db->query($sql, [$internshipId]);
        }
        $sql = "SELECT ja.*, i.title as internship_title, i.department as internship_dept 
                FROM job_applications ja
                JOIN internships i ON ja.internship_id = i.id
                ORDER BY ja.id DESC";
        return $this->db->query($sql);
    }

    public function updateApplicationStatus($applicationId, $status) {
        $sql = "UPDATE job_applications SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $applicationId]);
    }
}
