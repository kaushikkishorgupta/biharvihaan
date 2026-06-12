<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminSeoController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_seo');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM seo_settings ORDER BY id DESC");
        $seoSettings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/seo', [
            'title' => 'SEO Metadata Manager | Bihar Vihaan',
            'seoSettings' => $seoSettings
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        
        $route = $_POST['route'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $keywords = $_POST['keywords'] ?? '';
        $canonical_url = $_POST['canonical_url'] ?? '';
        $og_title = $_POST['og_title'] ?? '';
        $og_description = $_POST['og_description'] ?? '';
        $twitter_card = $_POST['twitter_card'] ?? 'summary_large_image';
        $schema_markup = $_POST['schema_markup'] ?? '';
        $robots_settings = $_POST['robots_settings'] ?? 'index, follow';
        $sitemap_settings = $_POST['sitemap_settings'] ?? '';
        
        // Handle OG Image upload or URL
        $open_graph_image = '';
        if (isset($_FILES['og_image']) && $_FILES['og_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['og_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['og_image']['tmp_name'], $uploadPath)) {
                $open_graph_image = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['og_image_url'])) {
            $open_graph_image = $_POST['og_image_url'];
        }

        $stmt = $db->prepare("
            INSERT INTO seo_settings (
                route, meta_title, meta_description, open_graph_image, keywords, 
                canonical_url, og_title, og_description, twitter_card, schema_markup, 
                robots_settings, sitemap_settings
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $route, $meta_title, $meta_description, $open_graph_image, $keywords,
            $canonical_url, $og_title, $og_description, $twitter_card, $schema_markup,
            $robots_settings, $sitemap_settings
        ]);

        Session::setFlash('success', 'SEO configuration created successfully.');
        $this->redirect('/admin/seo');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $route = $_POST['route'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $keywords = $_POST['keywords'] ?? '';
        $canonical_url = $_POST['canonical_url'] ?? '';
        $og_title = $_POST['og_title'] ?? '';
        $og_description = $_POST['og_description'] ?? '';
        $twitter_card = $_POST['twitter_card'] ?? 'summary_large_image';
        $schema_markup = $_POST['schema_markup'] ?? '';
        $robots_settings = $_POST['robots_settings'] ?? 'index, follow';
        $sitemap_settings = $_POST['sitemap_settings'] ?? '';
        
        // Fetch current OG image
        $current = $db->prepare("SELECT open_graph_image FROM seo_settings WHERE id = ?");
        $current->execute([$id]);
        $open_graph_image = $current->fetchColumn() ?: '';

        if (isset($_FILES['og_image']) && $_FILES['og_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['og_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['og_image']['tmp_name'], $uploadPath)) {
                $open_graph_image = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['og_image_url'])) {
            $open_graph_image = $_POST['og_image_url'];
        }

        $stmt = $db->prepare("
            UPDATE seo_settings 
            SET route=?, meta_title=?, meta_description=?, open_graph_image=?, keywords=?, 
                canonical_url=?, og_title=?, og_description=?, twitter_card=?, schema_markup=?, 
                robots_settings=?, sitemap_settings=?
            WHERE id=?
        ");
        
        $stmt->execute([
            $route, $meta_title, $meta_description, $open_graph_image, $keywords,
            $canonical_url, $og_title, $og_description, $twitter_card, $schema_markup,
            $robots_settings, $sitemap_settings, $id
        ]);

        Session::setFlash('success', 'SEO configuration updated successfully.');
        $this->redirect('/admin/seo');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM seo_settings WHERE id=?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'SEO configuration deleted.');
        }
        $this->redirect('/admin/seo');
    }
}
