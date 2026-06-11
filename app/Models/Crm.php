<?php

namespace App\Models;

use App\Core\Database;

class Crm {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getLeads($businessId = null) {
        if ($businessId) {
            $sql = "SELECT * FROM crm_leads WHERE business_id = ? ORDER BY created_at DESC";
            return $this->db->query($sql, [$businessId]);
        }
        $sql = "SELECT * FROM crm_leads ORDER BY created_at DESC";
        return $this->db->query($sql);
    }

    public function submitLead($businessId, $name, $email, $phone, $message) {
        $sql = "INSERT INTO crm_leads (business_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?)";
        $this->db->execute($sql, [$businessId, $name, $email, $phone, $message]);
        return $this->db->lastInsertId();
    }

    public function getLeadDetails($leadId) {
        $sql = "SELECT * FROM crm_leads WHERE id = ?";
        return $this->db->queryRow($sql, [$leadId]);
    }

    public function addNote($leadId, $note, $addedBy) {
        $sql = "INSERT INTO crm_notes (lead_id, note, added_by) VALUES (?, ?, ?)";
        $this->db->execute($sql, [$leadId, $note, $addedBy]);
        return $this->db->lastInsertId();
    }
}
