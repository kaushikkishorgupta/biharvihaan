<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use PDO;

class ContactController extends Controller {

    public function submit() {
        if (!Session::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid security token.');
            $this->redirect('/contact');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            Session::setFlash('error', 'All fields are required.');
            $this->redirect('/contact');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$name, $email, $subject, $message])) {
            
            // Send email via native mail() function
            $to = 'hello@biharvihaan.com';
            $headers = "From: " . $email . "\r\n" .
                       "Reply-To: " . $email . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();
            
            $emailBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
            
            @mail($to, $subject, $emailBody, $headers);

            Session::setFlash('success', 'Your message has been sent successfully. We will get back to you soon.');
        } else {
            Session::setFlash('error', 'Failed to send message. Please try again.');
        }

        $this->redirect('/contact');
    }
}
