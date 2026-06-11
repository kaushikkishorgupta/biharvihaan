<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Models\Job;

class CareerController extends Controller {

    public function index() {
        $jobModel = new Job();
        $internships = $jobModel->getInternships();

        $this->render('careers', [
            'title' => 'Internship & Career Portal - Bihar Vihaan',
            'internships' => $internships
        ]);
    }

    public function detail($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/careers');
        }

        $jobModel = new Job();
        $internship = $jobModel->findInternship($id);

        if (!$internship) {
            die("Internship position not found.");
        }

        $this->render('careers', [
            'title' => $internship['title'] . ' - Bihar Vihaan',
            'internship' => $internship,
            'view_mode' => 'detail'
        ]);
    }

    public function handleApply() {
        $internshipId = intval($_POST['internship_id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $cover = $_POST['cover_letter'] ?? '';

        if (empty($name) || empty($email) || empty($phone) || $internshipId <= 0) {
            Session::setFlash('error', 'All contact fields are required.');
            $this->redirect('/careers');
        }

        // Simulate Resume File Upload
        $resumePath = '/uploads/resumes/mock_resume_' . time() . '.pdf';
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            // In a real application, check extension and move file
            // Since this is shared hosting cPanel friendly, we will store a simulated string or create mock path
            $targetDir = dirname(__DIR__, 2) . '/assets/uploads/resumes/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = 'resume_' . time() . '_' . basename($_FILES['resume']['name']);
            move_uploaded_file($_FILES['resume']['tmp_name'], $targetDir . $fileName);
            $resumePath = '/assets/uploads/resumes/' . $fileName;
        }

        $jobModel = new Job();
        $success = $jobModel->applyForInternship([
            'internship_id' => $internshipId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'resume_path' => $resumePath,
            'cover_letter' => $cover
        ]);

        if ($success) {
            Logger::log('Job Application Submit', "Candidate $name applied for internship ID: $internshipId");
            
            // System Notification
            $db = \App\Core\Database::getInstance();
            $db->execute("INSERT INTO notifications (user_id, type, title, message) VALUES (1, 'system', ?, ?)", [
                'New Applicant Registered',
                "Candidate $name ($email) has submitted an application for role #$internshipId."
            ]);

            Session::setFlash('success', 'Your application has been submitted successfully! Recruiters will review it shortly.');
        } else {
            Session::setFlash('error', 'Failed to register application. Try again.');
        }

        $this->redirect('/careers');
    }
}
