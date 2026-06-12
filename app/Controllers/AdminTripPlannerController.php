<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use App\Core\RBAC;
use PDO;

class AdminTripPlannerController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_tourism');
        RBAC::requirePermission('manage_tourism');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        // Fetch planner algorithmic rules
        $stmtRules = $db->query("SELECT * FROM ai_planner_rules ORDER BY rule_type, created_at DESC");
        $rules = $stmtRules->fetchAll(PDO::FETCH_ASSOC);

        // Fetch settings key-values
        $stmtSettings = $db->query("SELECT * FROM trip_planner_settings");
        $settingsRaw = $stmtSettings->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }

        $this->renderAdmin('admin/trip_planner', [
            'title' => 'Manage AI Planner Rules & Settings | Bihar Vihaan',
            'rules' => $rules,
            'settings' => $settings
        ]);
    }

    // Handles saving general templates/rules settings
    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $prompt_template = $_POST['prompt_template'] ?? '';
        $default_budget_breakdown = $_POST['default_budget_breakdown'] ?? '';
        $seasonal_recommendations = $_POST['seasonal_recommendations'] ?? '';
        $hidden_gems = $_POST['hidden_gems'] ?? '';

        $settings = [
            'prompt_template' => $prompt_template,
            'default_budget_breakdown' => $default_budget_breakdown,
            'seasonal_recommendations' => $seasonal_recommendations,
            'hidden_gems' => $hidden_gems
        ];

        foreach ($settings as $key => $val) {
            $stmt = $db->prepare("INSERT INTO trip_planner_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute([$key, $val, $val]);
        }

        Session::setFlash('success', 'AI Planner settings saved.');
        $this->redirect('/admin/trip-planner');
    }

    // Handles adding algorithmic route mapping rules
    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $rule_type = $_POST['rule_type'] ?? '';
        $criteria = $_POST['criteria'] ?? '';
        $recommendations_json = $_POST['recommendations_json'] ?? '[]';

        if ($id) {
            $stmt = $db->prepare("UPDATE ai_planner_rules SET rule_type = ?, criteria = ?, recommendations_json = ? WHERE id = ?");
            $stmt->execute([$rule_type, $criteria, $recommendations_json, $id]);
            Session::setFlash('success', 'Algorithmic rule updated.');
        } else {
            $stmt = $db->prepare("INSERT INTO ai_planner_rules (rule_type, criteria, recommendations_json) VALUES (?, ?, ?)");
            $stmt->execute([$rule_type, $criteria, $recommendations_json]);
            Session::setFlash('success', 'Algorithmic rule created.');
        }

        $this->redirect('/admin/trip-planner');
    }

    // Handles deleting algorithmic rules
    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM ai_planner_rules WHERE id = ?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Algorithmic rule deleted.');
        }
        $this->redirect('/admin/trip-planner');
    }
}
