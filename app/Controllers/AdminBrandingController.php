<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminBrandingController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_branding');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $branding = $db->query("SELECT * FROM branding_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/branding', [
            'title' => 'Branding Manager | Bihar Vihaan',
            'branding' => $branding
        ]);
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $site_name = $_POST['site_name'] ?? 'Bihar Vihaan';
        $primary_color = $_POST['primary_color'] ?? '#0B3D91';
        $accent_color = $_POST['accent_color'] ?? '#FF9933';
        $success_color = $_POST['success_color'] ?? '#10B981';
        $background_color = $_POST['background_color'] ?? '#F8F4F0';
        $dark_mode_bg = $_POST['dark_mode_bg'] ?? '#0F172A';
        $font_family = $_POST['font_family'] ?? 'Inter';
        
        // Fetch current row to keep existing URLs if not uploaded
        $current = $db->query("SELECT * FROM branding_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $logo_url = $current['logo_url'] ?? '';
        $favicon_url = $current['favicon_url'] ?? '';
        $footer_logo_url = $current['footer_logo_url'] ?? '';
        $hero_logo_url = $current['hero_logo_url'] ?? '';

        // Helper function for files
        $uploadFile = function($fieldName, $currentVal) {
            if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                $uploadDir = dirname(__DIR__, 2) . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $uploadPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadPath)) {
                    return '/uploads/' . $filename;
                }
            }
            return $currentVal;
        };

        $logo_url = $uploadFile('logo', $logo_url);
        $favicon_url = $uploadFile('favicon', $favicon_url);
        $footer_logo_url = $uploadFile('footer_logo', $footer_logo_url);
        $hero_logo_url = $uploadFile('hero_logo', $hero_logo_url);

        // Update or Insert
        if ($current) {
            $stmt = $db->prepare("
                UPDATE branding_settings 
                SET site_name=?, logo_url=?, favicon_url=?, footer_logo_url=?, hero_logo_url=?, primary_color=?, accent_color=?, success_color=?, background_color=?, dark_mode_bg=?, font_family=?
                WHERE id=?
            ");
            $stmt->execute([
                $site_name, $logo_url, $favicon_url, $footer_logo_url, $hero_logo_url, 
                $primary_color, $accent_color, $success_color, $background_color, $dark_mode_bg, $font_family,
                $current['id']
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO branding_settings (site_name, logo_url, favicon_url, footer_logo_url, hero_logo_url, primary_color, accent_color, success_color, background_color, dark_mode_bg, font_family)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $site_name, $logo_url, $favicon_url, $footer_logo_url, $hero_logo_url,
                $primary_color, $accent_color, $success_color, $background_color, $dark_mode_bg, $font_family
            ]);
        }

        Session::setFlash('success', 'Branding settings updated successfully.');
        $this->redirect('/admin/branding');
    }
}
