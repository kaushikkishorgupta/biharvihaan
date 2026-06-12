<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminSocialController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_social');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM social_links ORDER BY platform ASC");
        $socials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/social', [
            'title' => 'Social Media Manager | Bihar Vihaan',
            'socials' => $socials
        ]);
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $urls = $_POST['urls'] ?? [];
        $enabled = $_POST['enabled'] ?? [];

        // Begin transaction
        $db->beginTransaction();
        try {
            // Retrieve all platforms
            $stmt = $db->query("SELECT platform FROM social_links");
            $platforms = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $updateStmt = $db->prepare("UPDATE social_links SET url = ?, is_enabled = ? WHERE platform = ?");
            
            foreach ($platforms as $platform) {
                $url = $urls[$platform] ?? '';
                $is_active = isset($enabled[$platform]) ? 1 : 0;
                $updateStmt->execute([$url, $is_active, $platform]);
            }
            $db->commit();
            Session::setFlash('success', 'Social media configuration saved successfully.');
        } catch (\Exception $e) {
            $db->rollBack();
            Session::setFlash('error', 'Failed to save configuration: ' . $e->getMessage());
        }

        $this->redirect('/admin/social');
    }
}
