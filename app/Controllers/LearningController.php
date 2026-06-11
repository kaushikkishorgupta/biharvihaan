<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Services\BlockchainService;

class LearningController extends Controller {
    private $db;
    private $blockchain;

    public function __construct() {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please log in to access the Learning Hub.');
            $this->redirect('/login');
        }
        $this->db = Database::getInstance();
        $this->blockchain = new BlockchainService();
    }

    /**
     * Lists active courses and student enrollment progress
     */
    public function index() {
        $userId = Session::get('user_id');
        
        $courses = $this->db->query("SELECT * FROM courses ORDER BY id ASC");
        
        // Find enrolled courses with progress
        $enrollments = $this->db->query("SELECT * FROM student_courses WHERE user_id = ?", [$userId]);
        $progressMap = [];
        foreach ($enrollments as $e) {
            $progressMap[$e['course_id']] = $e;
        }

        $this->render('learning', [
            'title' => 'Bihar Skill Development & Learning Hub',
            'courses' => $courses,
            'progressMap' => $progressMap,
            'view_mode' => 'catalog'
        ]);
    }

    /**
     * Details of course showing available video lessons
     */
    public function course() {
        $routeParams = $GLOBALS['router']->getParams() ?? [];
        $courseId = intval($routeParams['id'] ?? 0);

        if ($courseId <= 0) {
            Session::setFlash('error', 'Course ID is required.');
            $this->redirect('/learning');
        }

        $course = $this->db->queryRow("SELECT * FROM courses WHERE id = ?", [$courseId]);
        if (!$course) {
            Session::setFlash('error', 'Course not found.');
            $this->redirect('/learning');
        }

        $lessons = $this->db->query("SELECT * FROM lessons WHERE course_id = ? ORDER BY id ASC", [$courseId]);
        $quizzes = $this->db->query("SELECT * FROM quizzes WHERE course_id = ?", [$courseId]);

        // Auto-enroll user if not already enrolled
        $userId = Session::get('user_id');
        $enrollment = $this->db->queryRow("SELECT * FROM student_courses WHERE user_id = ? AND course_id = ?", [$userId, $courseId]);
        
        if (!$enrollment) {
            $this->db->execute("INSERT INTO student_courses (user_id, course_id, progress, completed) VALUES (?, ?, 0, 0)", [
                $userId,
                $courseId
            ]);
            $enrollment = ['progress' => 0, 'completed' => 0, 'certificate_hash' => null];
        }

        $this->render('learning', [
            'title' => htmlspecialchars($course['title']) . ' - Lessons',
            'course' => $course,
            'lessons' => $lessons,
            'quizzes' => $quizzes,
            'enrollment' => $enrollment,
            'view_mode' => 'lessons'
        ]);
    }

    /**
     * Handle quiz submissions and award Blockchain certificates on 100% correct answers
     */
    public function submitQuiz() {
        $courseId = intval($_POST['course_id'] ?? 0);
        $answers = $_POST['answers'] ?? []; // Array [quiz_id => user_option]

        if ($courseId <= 0 || empty($answers)) {
            Session::setFlash('error', 'Please answer the quiz questions before submitting.');
            $this->redirect('/learning');
        }

        // Fetch correct options from quizzes table
        $questions = $this->db->query("SELECT id, correct_option FROM quizzes WHERE course_id = ?", [$courseId]);
        
        $totalQuestions = count($questions);
        $correctAnswers = 0;

        foreach ($questions as $q) {
            $userAns = $answers[$q['id']] ?? '';
            if (strtoupper($userAns) === strtoupper($q['correct_option'])) {
                $correctAnswers++;
            }
        }

        $userId = Session::get('user_id');
        $userName = Session::get('user_name');
        
        if ($correctAnswers === $totalQuestions && $totalQuestions > 0) {
            // Student scored 100%! Generate cryptographic certificate using Blockchain Service
            $payload = [
                'student_name' => $userName,
                'user_id' => $userId,
                'course_id' => $courseId,
                'score' => '100%',
                'timestamp' => time()
            ];

            // Mine block record
            $block = $this->blockchain->mineRecord('course_cert', $payload);
            $certHash = $block['hash'];

            // Update student progress to completed & write the certificate hash
            $this->db->execute("UPDATE student_courses SET progress = 100, completed = 1, certificate_hash = ? 
                                WHERE user_id = ? AND course_id = ?", [
                                    $certHash,
                                    $userId,
                                    $courseId
                                ]);

            Session::setFlash('success', 'Congratulation! You passed the quiz with 100% score. A secure digital certificate hash has been minted on the Bihar Vihaan Blockchain Ledger!');
        } else {
            // Failed or partially correct
            $percentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;
            
            $this->db->execute("UPDATE student_courses SET progress = ? WHERE user_id = ? AND course_id = ?", [
                $percentage,
                $userId,
                $courseId
            ]);

            Session::setFlash('error', "You scored $percentage% ($correctAnswers/$totalQuestions correct). You need 100% correct answers to earn a certified Blockchain credential. Please try again!");
        }

        $this->redirect('/learning/course/' . $courseId);
    }
}
