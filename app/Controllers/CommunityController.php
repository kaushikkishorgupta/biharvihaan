<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Core\Logger;

class CommunityController extends Controller {
    private $db;

    public function __construct() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to participate in the travel community forums.');
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
    }

    /**
     * Lists active forums and message boards
     */
    public function index() {
        $forums = $this->db->query("SELECT f.*, 
                                   (SELECT COUNT(*) FROM forum_topics WHERE forum_id = f.id) as topics_count 
                                   FROM forums f ORDER BY f.id ASC");

        $this->render('community', [
            'title' => 'Bihar Vihaan Travel & Cultural Communities',
            'forums' => $forums,
            'view_mode' => 'forums'
        ]);
    }

    /**
     * View list of thread topics inside a single forum category
     */
    public function forum() {
        $routeParams = $GLOBALS['router']->getParams() ?? [];
        $slug = $routeParams['slug'] ?? '';

        if (empty($slug)) {
            $this->redirect('/community');
        }

        $forum = $this->db->queryRow("SELECT * FROM forums WHERE slug = ?", [$slug]);
        if (!$forum) {
            Session::setFlash('error', 'Forum category not found.');
            $this->redirect('/community');
        }

        $topics = $this->db->query("SELECT ft.*, u.name as author_name, 
                                    (SELECT COUNT(*) FROM forum_posts WHERE topic_id = ft.id) as posts_count 
                                    FROM forum_topics ft 
                                    JOIN users u ON ft.user_id = u.id 
                                    WHERE ft.forum_id = ? 
                                    ORDER BY ft.created_at DESC", [$forum['id']]);

        $this->render('community', [
            'title' => htmlspecialchars($forum['name']) . ' Board - Bihar Vihaan',
            'forum' => $forum,
            'topics' => $topics,
            'view_mode' => 'topics'
        ]);
    }

    /**
     * View a single thread topic with post comment logs
     */
    public function topic() {
        $routeParams = $GLOBALS['router']->getParams() ?? [];
        $topicId = intval($routeParams['id'] ?? 0);

        if ($topicId <= 0) {
            $this->redirect('/community');
        }

        $topic = $this->db->queryRow("SELECT ft.*, f.name as forum_name, f.slug as forum_slug 
                                      FROM forum_topics ft 
                                      JOIN forums f ON ft.forum_id = f.id 
                                      WHERE ft.id = ?", [$topicId]);
        
        if (!$topic) {
            Session::setFlash('error', 'Discussion topic not found.');
            $this->redirect('/community');
        }

        $posts = $this->db->query("SELECT fp.*, u.name as author_name, r.name as author_role 
                                   FROM forum_posts fp 
                                   JOIN users u ON fp.user_id = u.id 
                                   JOIN roles r ON u.role_id = r.id 
                                   WHERE fp.topic_id = ? 
                                   ORDER BY fp.created_at ASC", [$topicId]);

        $this->render('community', [
            'title' => htmlspecialchars($topic['title']) . ' - Discussion',
            'topic' => $topic,
            'posts' => $posts,
            'view_mode' => 'detail'
        ]);
    }

    /**
     * Start a new discussion topic
     */
    public function createTopic() {
        $forumId = intval($_POST['forum_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($forumId <= 0 || empty($title) || empty($content)) {
            Session::setFlash('error', 'Discussion title and content are required.');
            $this->redirect('/community');
        }

        $userId = Session::get('user_id');

        // Insert Topic
        $this->db->execute("INSERT INTO forum_topics (forum_id, user_id, title) VALUES (?, ?, ?)", [
            $forumId,
            $userId,
            $title
        ]);
        $topicId = $this->db->lastInsertId();

        // Insert first post comment containing body text
        $this->db->execute("INSERT INTO forum_posts (topic_id, user_id, content) VALUES (?, ?, ?)", [
            $topicId,
            $userId,
            $content
        ]);

        Logger::log('Community Topic Created', "New forum topic ID $topicId created by User ID $userId");
        Session::setFlash('success', 'Discussion topic posted successfully!');
        
        $this->redirect('/community/topic/' . $topicId);
    }

    /**
     * Add reply comment to discussion thread
     */
    public function replyTopic() {
        $topicId = intval($_POST['topic_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if ($topicId <= 0 || empty($content)) {
            Session::setFlash('error', 'Reply content cannot be empty.');
            $this->redirect('/community');
        }

        $userId = Session::get('user_id');

        $this->db->execute("INSERT INTO forum_posts (topic_id, user_id, content) VALUES (?, ?, ?)", [
            $topicId,
            $userId,
            $content
        ]);

        Session::setFlash('success', 'Reply comment posted successfully.');
        $this->redirect('/community/topic/' . $topicId);
    }
}
