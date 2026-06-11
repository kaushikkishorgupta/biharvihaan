<?php

namespace App\Models;

use App\Core\Database;

class User {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find($id) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function findByEmail($email) {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.email = ?";
        return $this->db->queryRow($sql, [$email]);
    }

    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, phone, role_id, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['name'],
            $data['email'],
            $data['password'],
            $data['phone'] ?? null,
            $data['role_id'] ?? 8, // defaults to standard 'user'
            $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        return $this->db->execute($sql, [$data['name'], $data['email'], $data['phone'], $id]);
    }

    public function all() {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                ORDER BY u.id DESC";
        return $this->db->query($sql);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $id]);
    }

    public function updateRole($id, $roleId) {
        $sql = "UPDATE users SET role_id = ? WHERE id = ?";
        return $this->db->execute($sql, [$roleId, $id]);
    }

    public function update2FA($id, $secret, $enabled) {
        $sql = "UPDATE users SET two_factor_secret = ?, two_factor_enabled = ? WHERE id = ?";
        return $this->db->execute($sql, [$secret, $enabled, $id]);
    }

    public function updatePasswordByEmail($email, $hashedPassword) {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        return $this->db->execute($sql, [$hashedPassword, $email]);
    }
}
