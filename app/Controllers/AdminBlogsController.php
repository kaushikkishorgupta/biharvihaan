<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use App\Models\Blog;
use PDO;

class AdminBlogsController extends Controller {

    protected $blogModel;

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_blogs');
        $this->blogModel = new Blog();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $blogs = $this->blogModel->all();
        
        // Fetch categories for forms
        $categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/blogs', [
            'title' => 'Blog & Articles CMS | Bihar Vihaan',
            'blogs' => $blogs,
            'categories' => $categories
        ]);
    }

    public function store() {
        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        }
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $scheduled_at = !empty($_POST['scheduled_at']) ? $_POST['scheduled_at'] : null;
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $tags = $_POST['tags'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';

        // Handle Image Upload
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('blog_') . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image_url = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image_url = $_POST['image_url'];
        }

        $id = $this->blogModel->create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_url' => $image_url,
            'author_id' => Session::get('user_id') ?: 1,
            'status' => $status,
            'scheduled_at' => $scheduled_at,
            'category_id' => $category_id,
            'tags' => $tags,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description
        ]);

        // Create initial version history
        $this->saveVersion($id, [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_url' => $image_url,
            'status' => $status,
            'scheduled_at' => $scheduled_at,
            'category_id' => $category_id,
            'tags' => $tags,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description
        ]);

        Session::setFlash('success', 'Blog post created successfully.');
        $this->redirect('/admin/blogs');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        
        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        }
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $scheduled_at = !empty($_POST['scheduled_at']) ? $_POST['scheduled_at'] : null;
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $tags = $_POST['tags'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';

        // Fetch current
        $current = $this->blogModel->find($id);
        $image_url = $current['image_url'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('blog_') . '.' . $ext;
            $uploadPath = dirname(__DIR__, 2) . '/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $image_url = '/uploads/' . $filename;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image_url = $_POST['image_url'];
        }

        $payload = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_url' => $image_url,
            'status' => $status,
            'scheduled_at' => $scheduled_at,
            'category_id' => $category_id,
            'tags' => $tags,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description
        ];

        $this->blogModel->update($id, $payload);
        
        // Save new version history
        $this->saveVersion($id, $payload);

        Session::setFlash('success', 'Blog post updated successfully.');
        $this->redirect('/admin/blogs');
    }

    public function delete() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->blogModel->delete($id);
            Session::setFlash('success', 'Blog post deleted.');
        }
        $this->redirect('/admin/blogs');
    }

    // AJAX Auto Save
    public function autosave() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No active ID for autosave.']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $content = $_POST['content'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $scheduled_at = !empty($_POST['scheduled_at']) ? $_POST['scheduled_at'] : null;
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $tags = $_POST['tags'] ?? '';
        $meta_title = $_POST['meta_title'] ?? '';
        $meta_description = $_POST['meta_description'] ?? '';
        $image_url = $_POST['image_url'] ?? '';

        $payload = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_url' => $image_url,
            'status' => $status,
            'scheduled_at' => $scheduled_at,
            'category_id' => $category_id,
            'tags' => $tags,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description
        ];

        // Save into content_versions
        $this->saveVersion($id, $payload, 'blog_autosave');

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'time' => date('h:i:s A')]);
        exit;
    }

    // Version Rollback
    public function rollback() {
        $db = Database::getInstance()->getConnection();
        $versionId = $_POST['version_id'] ?? null;
        
        if ($versionId) {
            $stmt = $db->prepare("SELECT * FROM content_versions WHERE id = ?");
            $stmt->execute([$versionId]);
            $ver = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($ver) {
                $data = json_decode($ver['version_data'], true);
                $blogId = $ver['content_id'];
                
                $this->blogModel->update($blogId, $data);
                Session::setFlash('success', 'Rolled back to version from ' . date('M d, g:i A', strtotime($ver['created_at'])));
            }
        }
        $this->redirect('/admin/blogs');
    }

    // Fetch Blog Versions
    public function versions() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode([]);
            exit;
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT id, content_type, created_at, created_by 
            FROM content_versions 
            WHERE content_id = ? AND (content_type = 'blog' OR content_type = 'blog_autosave') 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$id]);
        $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($versions);
        exit;
    }

    private function saveVersion($blogId, $data, $type = 'blog') {
        $db = Database::getInstance()->getConnection();
        
        // If autosave, check if we already have one autosave for this user and blog, and overwrite it to keep DB light!
        if ($type === 'blog_autosave') {
            $userId = Session::get('user_id') ?: 1;
            $stmt = $db->prepare("SELECT id FROM content_versions WHERE content_type = 'blog_autosave' AND content_id = ? AND created_by = ?");
            $stmt->execute([$blogId, $userId]);
            $existingId = $stmt->fetchColumn();
            
            if ($existingId) {
                $up = $db->prepare("UPDATE content_versions SET version_data = ?, created_at = CURRENT_TIMESTAMP WHERE id = ?");
                $up->execute([json_encode($data), $existingId]);
                return;
            }
        }

        $stmt = $db->prepare("INSERT INTO content_versions (content_type, content_id, version_data, created_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $type,
            $blogId,
            json_encode($data),
            Session::get('user_id') ?: 1
        ]);
    }
}
