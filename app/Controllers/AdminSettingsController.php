<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use PDO;

class AdminSettingsController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_settings');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $stmtSEO = $db->query("SELECT * FROM seo_settings ORDER BY id DESC");
        $seoSettings = $stmtSEO->fetchAll(PDO::FETCH_ASSOC);

        $stmtGlobal = $db->query("SELECT * FROM settings");
        $globalSettingsRaw = $stmtGlobal->fetchAll(PDO::FETCH_ASSOC);
        
        $globalSettings = [];
        foreach($globalSettingsRaw as $s) {
            $globalSettings[$s['setting_key']] = $s['setting_value'];
        }

        $this->renderAdmin('admin/settings', [
            'seoSettings' => $seoSettings,
            'globalSettings' => $globalSettings
        ]);
    }

    // SEO Manager CRUD
    public function store_seo() {
        $db = Database::getInstance()->getConnection();
        $route = $_POST['route'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $open_graph_image = $_POST['open_graph_image'] ?? '';

        $stmt = $db->prepare("INSERT INTO seo_settings (route, meta_title, meta_description, open_graph_image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$route, $meta_title, $meta_description, $open_graph_image]);
        
        $this->redirect('/admin/settings?success=SEO created');
    }

    public function update_seo() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        $route = $_POST['route'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $open_graph_image = $_POST['open_graph_image'] ?? '';

        $stmt = $db->prepare("UPDATE seo_settings SET route=?, meta_title=?, meta_description=?, open_graph_image=? WHERE id=?");
        $stmt->execute([$route, $meta_title, $meta_description, $open_graph_image, $id]);
        
        $this->redirect('/admin/settings?success=SEO updated');
    }

    public function delete_seo() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM seo_settings WHERE id=?");
            $stmt->execute([$id]);
        }
        $this->redirect('/admin/settings?success=SEO deleted');
    }

    // Global Settings Update
    public function update_global() {
        $db = Database::getInstance()->getConnection();
        
        $settings = [
            'site_name' => $_POST['site_name'] ?? '',
            'site_logo' => $_POST['site_logo'] ?? '',
            'site_favicon' => $_POST['site_favicon'] ?? '',
            'contact_phone' => $_POST['contact_phone'] ?? '',
            'contact_email' => $_POST['contact_email'] ?? '',
            'contact_address' => $_POST['contact_address'] ?? '',
            'social_facebook' => $_POST['social_facebook'] ?? '',
            'social_twitter' => $_POST['social_twitter'] ?? '',
            'social_instagram' => $_POST['social_instagram'] ?? ''
        ];

        foreach ($settings as $key => $value) {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value=?");
            $stmt->execute([$key, $value, $value]);
        }
        
        $this->redirect('/admin/settings?success=Settings updated');
    }
}
