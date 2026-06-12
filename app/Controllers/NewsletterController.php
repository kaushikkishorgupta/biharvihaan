<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use PDO;

class NewsletterController extends Controller {

    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

            if (!$email) {
                Session::setFlash('error', 'Please enter a valid email address.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
                return;
            }

            $db = Database::getInstance()->getConnection();
            
            // Check if already subscribed
            $stmtCheck = $db->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
            $stmtCheck->execute([$email]);
            if ($stmtCheck->fetch()) {
                Session::setFlash('success', 'You are already subscribed to our newsletter.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
                return;
            }

            $stmt = $db->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            if ($stmt->execute([$email])) {
                Session::setFlash('success', 'Thank you for subscribing to the Bihar Vihaan Newsletter!');
            } else {
                Session::setFlash('error', 'Subscription failed. Please try again later.');
            }
        }
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function unsubscribe() {
        $email = filter_var($_GET['email'] ?? '', FILTER_VALIDATE_EMAIL);
        
        if ($email) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM newsletter_subscribers WHERE email = ?");
            $stmt->execute([$email]);
            Session::setFlash('success', 'You have been successfully unsubscribed.');
        }

        $this->redirect('/');
    }

    public function broadcast() {
        // Admin broadcast logic could be added here
        $this->redirect('/admin/dashboard');
    }
}
