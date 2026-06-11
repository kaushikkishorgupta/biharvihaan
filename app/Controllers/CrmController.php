<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Core\Logger;

class CrmController extends Controller {
    private $db;

    public function __construct() {
        // Ensure user is authenticated
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to access the Business CRM.');
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
    }

    /**
     * Display CRM Lead tracker dashboard
     */
    public function index() {
        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');

        // Fetch businesses owned by this user
        if ($userRole === 'super_admin' || $userRole === 'admin') {
            $myBusinesses = $this->db->query("SELECT id, name FROM business_listings ORDER BY name ASC");
        } else {
            $myBusinesses = $this->db->query("SELECT id, name FROM business_listings WHERE owner_id = ? ORDER BY name ASC", [$userId]);
        }

        if (empty($myBusinesses)) {
            // Render CRM dashboard with warnings that they need a business listing first
            $this->render('crm', [
                'title' => 'Business CRM Dashboard - Bihar Vihaan',
                'hasBusiness' => false,
                'leads' => [],
                'businesses' => []
            ]);
            return;
        }

        $businessIds = array_column($myBusinesses, 'id');
        $inQuery = implode(',', array_fill(0, count($businessIds), '?'));

        // Retrieve leads
        $sql = "SELECT cl.*, bl.name as business_name 
                FROM crm_leads cl 
                JOIN business_listings bl ON cl.business_id = bl.id 
                WHERE cl.business_id IN ($inQuery) 
                ORDER BY cl.created_at DESC";
        
        $leads = $this->db->query($sql, $businessIds);

        // Recalculate lead scores on the fly for display
        foreach ($leads as &$lead) {
            $lead['score'] = $this->calculateLeadScore($lead);
        }

        $this->render('crm', [
            'title' => 'Business CRM Dashboard - Bihar Vihaan',
            'hasBusiness' => true,
            'leads' => $leads,
            'businesses' => $myBusinesses
        ]);
    }

    /**
     * View lead details and follow-up history
     */
    public function detail() {
        $routeParams = $GLOBALS['router']->getParams() ?? [];
        $leadId = intval($routeParams['id'] ?? 0);

        if ($leadId <= 0) {
            Session::setFlash('error', 'Lead ID is required.');
            $this->redirect('/crm');
        }

        // Fetch lead and ensure owner owns the business
        $lead = $this->db->queryRow("SELECT cl.*, bl.name as business_name, bl.owner_id 
                                    FROM crm_leads cl 
                                    JOIN business_listings bl ON cl.business_id = bl.id 
                                    WHERE cl.id = ?", [$leadId]);

        if (!$lead) {
            Session::setFlash('error', 'Lead record not found.');
            $this->redirect('/crm');
        }

        // Security check
        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
        if ($lead['owner_id'] != $userId && $userRole !== 'super_admin' && $userRole !== 'admin') {
            Session::setFlash('error', 'Unauthorized access to lead detail.');
            $this->redirect('/crm');
        }

        // Fetch follow up notes
        $notes = $this->db->query("SELECT cn.*, u.name as author_name 
                                   FROM crm_notes cn 
                                   JOIN users u ON cn.author_id = u.id 
                                   WHERE cn.lead_id = ? 
                                   ORDER BY cn.created_at DESC", [$leadId]);

        // Generate WhatsApp template message link
        $leadPhone = preg_replace('/[^0-9+]/', '', $lead['phone']);
        // If phone does not start with +91 or 91, pre-pend it for Indian numbers
        if (strlen($leadPhone) === 10) {
            $leadPhone = '91' . $leadPhone;
        }
        $whatsappMsg = "Hello " . htmlspecialchars($lead['name']) . ", thank you for inquiring about our services at " . htmlspecialchars($lead['business_name']) . " via Bihar Vihaan. How can we assist you further?";
        $whatsappUrl = "https://wa.me/" . $leadPhone . "?text=" . urlencode($whatsappMsg);

        $lead['score'] = $this->calculateLeadScore($lead, count($notes));

        $this->render('crm', [
            'title' => 'Lead Details: ' . htmlspecialchars($lead['name']),
            'lead' => $lead,
            'notes' => $notes,
            'whatsappUrl' => $whatsappUrl,
            'view_mode' => 'detail'
        ]);
    }

    /**
     * Add a follow-up log or note to a lead
     */
    public function addNote() {
        $leadId = intval($_POST['lead_id'] ?? 0);
        $note = trim($_POST['note'] ?? '');
        $status = $_POST['status'] ?? '';
        $followUpDate = $_POST['follow_up_date'] ?? null;

        if ($leadId <= 0 || empty($note)) {
            Session::setFlash('error', 'Follow-up note content cannot be empty.');
            $this->redirect('/crm');
        }

        // Verify ownership
        $lead = $this->db->queryRow("SELECT cl.*, bl.owner_id 
                                    FROM crm_leads cl 
                                    JOIN business_listings bl ON cl.business_id = bl.id 
                                    WHERE cl.id = ?", [$leadId]);

        if (!$lead) {
            Session::setFlash('error', 'Lead record not found.');
            $this->redirect('/crm');
        }

        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
        if ($lead['owner_id'] != $userId && $userRole !== 'super_admin' && $userRole !== 'admin') {
            Session::setFlash('error', 'Unauthorized action.');
            $this->redirect('/crm');
        }

        // Insert Note
        $sql = "INSERT INTO crm_notes (lead_id, author_id, note, follow_up_date) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [
            $leadId,
            $userId,
            $note,
            !empty($followUpDate) ? $followUpDate : null
        ]);

        // Update Lead Status if changed
        if (!empty($status) && $status !== $lead['status']) {
            $this->db->execute("UPDATE crm_leads SET status = ? WHERE id = ?", [$status, $leadId]);
            Logger::log('CRM Lead Status Update', "Lead ID $leadId status updated to $status by user $userId");
        }

        Session::setFlash('success', 'Follow-up note added successfully!');
        $this->redirect('/crm/detail/' . $leadId);
    }

    /**
     * Log web visitor inquiries to the CRM leads table
     */
    public function submitLead() {
        $businessId = intval($_POST['business_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $source = $_POST['source'] ?? 'website_inquiry';

        if ($businessId <= 0 || empty($name) || empty($phone)) {
            echo json_encode(['status' => 'error', 'message' => 'Name and contact phone are required.']);
            exit;
        }

        // Save CRM Lead
        $sql = "INSERT INTO crm_leads (business_id, name, email, phone, source, status, score) 
                VALUES (?, ?, ?, ?, ?, 'new', 20)";
        
        $result = $this->db->execute($sql, [$businessId, $name, $email, $phone, $source]);
        $leadId = $this->db->lastInsertId();

        if ($result) {
            // Add initial note with client query
            if (!empty($message)) {
                $this->db->execute("INSERT INTO crm_notes (lead_id, author_id, note) VALUES (?, 1, ?)", [
                    $leadId,
                    "Client query: " . $message
                ]);
            }

            // Sync notifications for the owner
            $business = $this->db->queryRow("SELECT owner_id, name FROM business_listings WHERE id = ?", [$businessId]);
            if ($business) {
                $this->db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (?, 'crm', 'New Business Lead Received', ?)", [
                    $business['owner_id'],
                    "You have a new inquiry from $name for " . $business['name'] . ". Open CRM dashboard to review."
                ]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Your inquiry was sent. The business manager will contact you soon.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to record lead query.']);
        }
        exit;
    }

    /**
     * CRM Lead Scoring Algorithm
     */
    private function calculateLeadScore($lead, $notesCount = null) {
        $score = 20; // baseline

        // Quality parameters
        if (!empty($lead['email']) && filter_var($lead['email'], FILTER_VALIDATE_EMAIL)) {
            $score += 15;
        }
        if (!empty($lead['phone']) && strlen(preg_replace('/[^0-9]/', '', $lead['phone'])) >= 10) {
            $score += 15;
        }

        // Action parameters
        if ($notesCount === null) {
            $notesCount = $this->db->queryRow("SELECT COUNT(*) as count FROM crm_notes WHERE lead_id = ?", [$lead['id']])['count'] ?? 0;
        }
        $score += ($notesCount * 15);

        // Status adjustments
        switch ($lead['status']) {
            case 'contacted':
                $score += 10;
                break;
            case 'qualified':
                $score += 25;
                break;
            case 'won':
                $score += 40;
                break;
            case 'lost':
                $score -= 15;
                break;
        }

        // Source weight
        if ($lead['source'] === 'whatsapp') {
            $score += 10; // high intent
        }

        return min($score, 100); // capped at 100
    }
}
