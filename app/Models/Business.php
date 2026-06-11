<?php

namespace App\Models;

use App\Core\Database;

class Business {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getListings($category = null, $onlyVerified = false) {
        $params = [];
        $sql = "SELECT b.*, u.name as owner_name 
                FROM business_listings b
                JOIN users u ON b.owner_id = u.id";
        
        $conditions = [];
        if ($category) {
            $conditions[] = "b.category = ?";
            $params[] = $category;
        }
        if ($onlyVerified) {
            $conditions[] = "b.verification_status = 'verified'";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Display featured/premium plans first, then newest
        $sql .= " ORDER BY CASE WHEN b.plan = 'featured' THEN 1 WHEN b.plan = 'premium' THEN 2 ELSE 3 END, b.id DESC";
        
        return $this->db->query($sql, $params);
    }

    public function findListing($id) {
        $sql = "SELECT b.*, u.name as owner_name 
                FROM business_listings b
                JOIN users u ON b.owner_id = u.id
                WHERE b.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function createListing($data) {
        $sql = "INSERT INTO business_listings (owner_id, name, category, description, address, phone, email, website, whatsapp_number, plan, verification_status, logo_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
        
        $this->db->execute($sql, [
            $data['owner_id'],
            $data['name'],
            $data['category'],
            $data['description'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['website'] ?? null,
            $data['whatsapp_number'] ?? null,
            $data['plan'] ?? 'free',
            $data['logo_url'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function verifyListing($id, $status = 'verified') {
        $sql = "UPDATE business_listings SET verification_status = ? WHERE id = ?";
        return $this->db->execute($sql, [$status, $id]);
    }

    public function upgradePlan($id, $plan) {
        $sql = "UPDATE business_listings SET plan = ? WHERE id = ?";
        return $this->db->execute($sql, [$plan, $id]);
    }

    // Lead Generation system
    public function addLead($data) {
        $sql = "INSERT INTO business_leads (business_id, name, email, phone, message) 
                VALUES (?, ?, ?, ?, ?)";
        
        return $this->db->execute($sql, [
            $data['business_id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['message']
        ]);
    }

    public function getLeads($businessId) {
        $sql = "SELECT * FROM business_leads WHERE business_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$businessId]);
    }

    public function getOwnerListings($ownerId) {
        $sql = "SELECT * FROM business_listings WHERE owner_id = ? ORDER BY id DESC";
        return $this->db->query($sql, [$ownerId]);
    }

    // Ad Click Analytics logger
    public function logAdClick($adName) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $sql = "INSERT INTO advertisement_clicks (ad_name, ip_address) VALUES (?, ?)";
        return $this->db->execute($sql, [$adName, $ip]);
    }

    public function getAdStats() {
        $sql = "SELECT ad_name, COUNT(*) as click_count 
                FROM advertisement_clicks 
                GROUP BY ad_name 
                ORDER BY click_count DESC";
        return $this->db->query($sql);
    }
}
