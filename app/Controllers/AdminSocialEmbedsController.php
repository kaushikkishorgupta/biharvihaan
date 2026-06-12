<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminSocialEmbedsController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_social');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM social_feeds ORDER BY id DESC");
        $feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/social_embeds', [
            'title' => 'YouTube & Instagram Feed Embeds | Bihar Vihaan',
            'feeds' => $feeds
        ]);
    }

    public function store() {
        $db = Database::getInstance()->getConnection();
        $platform = $_POST['platform'] ?? 'youtube';
        $url = $_POST['url'] ?? '';
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? 'General';
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        $videoId = $this->extractVideoId($platform, $url);

        if (empty($videoId)) {
            Session::setFlash('error', 'Could not parse video ID from URL.');
            $this->redirect('/admin/social-feeds');
            return;
        }

        $stmt = $db->prepare("INSERT INTO social_feeds (platform, video_id, title, category, is_featured) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$platform, $videoId, $title, $category, $is_featured]);

        Session::setFlash('success', 'Social feed item embedded successfully.');
        $this->redirect('/admin/social-feeds');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        $platform = $_POST['platform'] ?? 'youtube';
        $url = $_POST['url'] ?? '';
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? 'General';
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        $videoId = $this->extractVideoId($platform, $url);

        if (empty($videoId)) {
            Session::setFlash('error', 'Could not parse video ID from URL.');
            $this->redirect('/admin/social-feeds');
            return;
        }

        $stmt = $db->prepare("UPDATE social_feeds SET platform = ?, video_id = ?, title = ?, category = ?, is_featured = ? WHERE id = ?");
        $stmt->execute([$platform, $videoId, $title, $category, $is_featured, $id]);

        Session::setFlash('success', 'Social feed item updated successfully.');
        $this->redirect('/admin/social-feeds');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM social_feeds WHERE id = ?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Social feed item deleted.');
        }
        $this->redirect('/admin/social-feeds');
    }

    private function extractVideoId($platform, $url) {
        if ($platform === 'youtube') {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|win(?:dows)?/|user/[^/]+/)?|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
                return $match[1];
            }
            return trim($url); // Fallback to raw ID
        } else {
            // Instagram
            if (preg_match('/(?:instagram\.com\/(?:p|reel)\/)([a-zA-Z0-9_-]+)/i', $url, $match)) {
                return $match[1];
            }
            return trim($url); // Fallback to raw ID
        }
    }
}
