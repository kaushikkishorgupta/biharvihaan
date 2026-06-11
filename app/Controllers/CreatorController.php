<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Core\Logger;
use App\Services\AiService;

class CreatorController extends Controller {
    private $db;
    private $aiService;

    public function __construct() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to access the Creator Studio.');
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
        $this->aiService = new AiService();
    }

    /**
     * Studio Dashboard showing monetization progress, views count, verification state, and published articles
     */
    public function index() {
        $userId = Session::get('user_id');

        // Check if user is an artist/journalist
        $artist = $this->db->queryRow("SELECT * FROM artists WHERE user_id = ?", [$userId]);

        $news = [];
        $payoutSummary = [
            'total_views' => 0,
            'total_earnings' => 0.00,
            'creator_split' => 0.00,
            'platform_split' => 0.00
        ];

        if ($artist) {
            // Retrieve content authored (Enterprise news table or creator articles)
            // Simulating content author mappings (editors/journalists post to news table)
            // For demo simplicity, we retrieve general news listings and calculate simulated view commissions
            $news = $this->db->query("SELECT * FROM news ORDER BY id DESC");
            
            $totalViews = 0;
            foreach ($news as $n) {
                $totalViews += intval($n['views_count'] ?? 0);
            }

            // Payout calculation: ₹0.15 per view, split 70% to creator, 30% to platform
            $ratePerView = 0.15;
            $grossRevenue = $totalViews * $ratePerView;
            $creatorRevenue = $grossRevenue * 0.70;
            $platformRevenue = $grossRevenue * 0.30;

            $payoutSummary = [
                'total_views' => $totalViews,
                'total_earnings' => round($grossRevenue, 2),
                'creator_split' => round($creatorRevenue, 2),
                'platform_split' => round($platformRevenue, 2)
            ];
        }

        $this->render('creators', [
            'title' => 'Creator & Journalist Studio - Bihar Vihaan',
            'artist' => $artist,
            'news' => $news,
            'payoutSummary' => $payoutSummary
        ]);
    }

    /**
     * Apply for verified status badge
     */
    public function applyVerification() {
        $stageName = trim($_POST['stage_name'] ?? '');
        $category = $_POST['category'] ?? 'other';
        $bio = trim($_POST['bio'] ?? '');
        $portfolioUrl = trim($_POST['portfolio_url'] ?? '');

        if (empty($stageName) || empty($bio)) {
            Session::setFlash('error', 'Stage Name and Bio details are required.');
            $this->redirect('/creators');
        }

        $userId = Session::get('user_id');

        // Check if profile exists, else create
        $existing = $this->db->queryRow("SELECT * FROM artists WHERE user_id = ?", [$userId]);
        if ($existing) {
            $this->db->execute("UPDATE artists SET stage_name = ?, category = ?, bio = ?, portfolio_url = ?, verification_status = 'pending' WHERE id = ?", [
                $stageName,
                $category,
                $bio,
                $portfolioUrl,
                $existing['id']
            ]);
        } else {
            $this->db->execute("INSERT INTO artists (user_id, stage_name, category, bio, portfolio_url, verification_status) VALUES (?, ?, ?, ?, ?, 'pending')", [
                $userId,
                $stageName,
                $category,
                $bio,
                $portfolioUrl
            ]);
        }

        Logger::log('Creator Verification Requested', "User ID $userId submitted verification application.");
        Session::setFlash('success', 'Verification application submitted! A Bihar Vihaan content moderator will review your profile.');
        $this->redirect('/creators');
    }

    /**
     * Create news article and auto summarize it using AI Summarizer before posting!
     */
    public function publishContent() {
        $title = trim($_POST['title'] ?? '');
        $category = $_POST['category'] ?? 'Culture';
        $content = trim($_POST['content'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');

        if (empty($title) || empty($content)) {
            Session::setFlash('error', 'Content title and body text are required.');
            $this->redirect('/creators');
        }

        if (empty($imageUrl)) {
            $imageUrl = 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=600';
        }

        // Auto Summarize news using AI
        $summary = $this->aiService->summarizeNews($content);
        $fullContentWithSummary = "<!-- AI SUMMARY: $summary -->\n" . $content;

        $sql = "INSERT INTO news (title, category, content, image_url, views_count) VALUES (?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $title,
            $category,
            $fullContentWithSummary,
            $imageUrl,
            rand(10, 100) // Simulated initial views count
        ]);

        Logger::log('Creator News Published', "New article posted: $title. [AI summary attached].");
        Session::setFlash('success', 'Article published successfully! Auto-generated AI Summary has been indexed.');
        $this->redirect('/creators');
    }

    /**
     * AJAX fetch blog content using AI Writer Service
     */
    public function aiGenerateDraft() {
        $topic = $_POST['topic'] ?? '';
        if (empty($topic)) {
            echo json_encode(['success' => false, 'message' => 'Topic is required.']);
            exit;
        }

        try {
            $draft = $this->aiService->generateBlog($topic);
            echo json_encode(['success' => true, 'draft' => $draft]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
