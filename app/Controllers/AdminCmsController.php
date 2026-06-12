<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminCmsController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_cms');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM cms_pages ORDER BY id DESC");
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/cms', [
            'pages' => $pages,
            'title' => 'Page CMS Manager | Bihar Vihaan'
        ]);
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['page_id'] ?? null;
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $metaData = $_POST['meta_data'] ?? '{}';

        if ($id) {
            $stmt = $db->prepare("UPDATE cms_pages SET title = ?, content = ?, meta_data = ? WHERE id = ?");
            $stmt->execute([$title, $content, $metaData, $id]);

            // Save manual version
            $this->saveVersion($id, [
                'title' => $title,
                'content' => $content,
                'meta_data' => $metaData
            ], 'page');

            Session::setFlash('success', 'Page updated successfully.');
        }

        $this->redirect('/admin/cms');
    }

    // AJAX Auto Save
    public function autosave() {
        header('Content-Type: application/json');
        $id = $_POST['page_id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No active ID for autosave.']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $metaData = $_POST['meta_data'] ?? '{}';

        $payload = [
            'title' => $title,
            'content' => $content,
            'meta_data' => $metaData
        ];

        // Save autosave draft version
        $this->saveVersion($id, $payload, 'page_autosave');

        echo json_encode(['success' => true, 'time' => date('h:i:s A')]);
        exit;
    }

    // Rollback Page Version
    public function rollback() {
        $db = Database::getInstance()->getConnection();
        $versionId = $_POST['version_id'] ?? null;

        if ($versionId) {
            $stmt = $db->prepare("SELECT * FROM content_versions WHERE id = ?");
            $stmt->execute([$versionId]);
            $ver = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($ver) {
                $data = json_decode($ver['version_data'], true);
                $pageId = $ver['content_id'];

                $stmtUpdate = $db->prepare("UPDATE cms_pages SET title = ?, content = ?, meta_data = ? WHERE id = ?");
                $stmtUpdate->execute([
                    $data['title'],
                    $data['content'],
                    $data['meta_data'],
                    $pageId
                ]);

                Session::setFlash('success', 'Rolled back page to version from ' . date('M d, g:i A', strtotime($ver['created_at'])));
            }
        }
        $this->redirect('/admin/cms');
    }

    // Fetch Page Versions
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
            WHERE content_id = ? AND (content_type = 'page' OR content_type = 'page_autosave') 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$id]);
        $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($versions);
        exit;
    }

    private function saveVersion($pageId, $data, $type = 'page') {
        $db = Database::getInstance()->getConnection();

        if ($type === 'page_autosave') {
            $userId = Session::get('user_id') ?: 1;
            $stmt = $db->prepare("SELECT id FROM content_versions WHERE content_type = 'page_autosave' AND content_id = ? AND created_by = ?");
            $stmt->execute([$pageId, $userId]);
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
            $pageId,
            json_encode($data),
            Session::get('user_id') ?: 1
        ]);
    }
}
