<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Models\User;
use App\Models\Destination;
use App\Models\Event;

class HomeController extends Controller {

    public function index() {
        $db = \App\Core\Database::getInstance()->getConnection();
        
        // Fetch CMS Homepage content
        $stmt = $db->query("SELECT * FROM cms_pages WHERE slug = 'home'");
        $cms = $stmt->fetch(\PDO::FETCH_ASSOC);
        $heroTitle = "Bihar Vihaan Enterprise"; // Default
        if ($cms && isset($cms['meta_data'])) {
            $meta = json_decode($cms['meta_data'], true);
            $heroTitle = $meta['hero_title'] ?? "Bihar Vihaan Enterprise";
        }

        $destModel = new \App\Models\Destination();
        $eventModel = new \App\Models\Event();
        
        $trending = $destModel->all();
        $festivals = $eventModel->getFestivals();

        $partners = $db->query("SELECT * FROM partners WHERE status = 'active' ORDER BY created_at ASC")->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('home', [
            'title' => 'Bihar Vihaan - Complete Enterprise 5.0',
            'meta_description' => 'Discover the heritage, culture, and business opportunities of Bihar.',
            'trending' => array_slice($trending, 0, 6),
            'festivals' => array_slice($festivals, 0, 3),
            'cms' => $cms,
            'heroTitle' => $heroTitle,
            'partners' => $partners
        ]);
    }

    public function login() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('home', [
            'title' => 'Sign In - Bihar Vihaan Enterprise',
            'view_mode' => 'login'
        ]);
    }

    public function handleLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Both email and password are required.');
            $this->redirect('/login');
        }

        $user = Auth::attempt($email, $password);
        if ($user) {
            // Check if 2FA enabled (simulate)
            if ($user['two_factor_enabled']) {
                Session::set('temp_2fa_user_id', $user['id']);
                $this->redirect('/2fa');
            }
            
            Session::setFlash('success', "Welcome back, " . htmlspecialchars($user['name']) . "!");
            $this->redirect('/');
        } else {
            Session::setFlash('error', 'Invalid login credentials or suspended account.');
            $this->redirect('/login');
        }
    }

    public function register() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('home', [
            'title' => 'Create Account - Bihar Vihaan Enterprise',
            'view_mode' => 'register'
        ]);
    }

    public function handleRegister() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        
        if (empty($name) || empty($email) || empty($password)) {
            Session::setFlash('error', 'Name, Email, and Password are required.');
            $this->redirect('/register');
        }

        $userModel = new User();
        $existing = $userModel->findByEmail($email);
        if ($existing) {
            Session::setFlash('error', 'This email address is already registered.');
            $this->redirect('/register');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'role_id' => 8, // User role
            'status' => 'active'
        ]);

        if ($userId) {
            Logger::log('User Registration', "New user registration for ID: $userId");
            Session::setFlash('success', 'Registration successful! You can now log in.');
            $this->redirect('/login');
        } else {
            Session::setFlash('error', 'Something went wrong. Please try again.');
            $this->redirect('/register');
        }
    }

    public function forgotPassword() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('home', [
            'title' => 'Forgot Password - Bihar Vihaan Enterprise',
            'view_mode' => 'forgot_password'
        ]);
    }

    public function handleForgotPassword() {
        if (!\App\Core\Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid security token.');
            $this->redirect('/forgot-password');
            return;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            Session::setFlash('error', 'Email is required.');
            $this->redirect('/forgot-password');
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            // Generate plaintext secure token
            $plainToken = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $plainToken);
            
            // Set expires_at (30 minutes from now)
            $expiresAt = date('Y-m-d H:i:s', time() + 1800);
            
            // Save to database
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $hashedToken, $expiresAt]);

            // Construct reset link
            $resetLink = BASE_URL . "/reset-password?token=" . $plainToken;
            
            // Log for debugging / local testing
            error_log("=== PASSWORD RESET LINK GENERATED ===\nTo: $email\nLink: $resetLink\n=====================================");
            
            // Render HTML email template
            $year = date('Y');
            $emailBody = <<<HTML
<html>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #1e293b; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #14b8a6; margin: 0; font-size: 24px;">Bihar Vihaan Enterprise</h2>
        </div>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 20px;">
        <p style="font-size: 16px; line-height: 1.6;">Hello,</p>
        <p style="font-size: 16px; line-height: 1.6;">We received a request to reset the password for your account. Click the button below to choose a new password. This link will expire in 30 minutes.</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{$resetLink}" style="background-color: #14b8a6; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block;">Reset Password</a>
        </div>
        <p style="font-size: 14px; color: #64748b; line-height: 1.6;">If you did not request a password reset, you can safely ignore this email.</p>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">
        <p style="font-size: 12px; color: #94a3b8; text-align: center; margin: 0;">&copy; {$year} Bihar Vihaan Enterprise. All rights reserved.</p>
    </div>
</body>
</html>
HTML;
            
            $headers = "MIME-Version: 1.0" . "\r\n" .
                       "Content-type: text/html; charset=UTF-8" . "\r\n" .
                       "From: hello@biharvihaan.com" . "\r\n" .
                       "X-Mailer: PHP/" . phpversion();
            
            @mail($email, "Password Reset Link - Bihar Vihaan", $emailBody, $headers);
            
            // Log audit activity
            Logger::log('User Password Reset Request', "Password reset flow triggered for email: $email");
        }
        
        Session::setFlash('success', "If this email is registered, a password reset instruction has been dispatched.");
        $this->redirect('/login');
    }

    public function resetPassword() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            Session::setFlash('error', 'Invalid password reset token.');
            $this->redirect('/login');
            return;
        }

        $hashedToken = hash('sha256', $token);
        
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ?");
        $stmt->execute([$hashedToken]);
        $reset = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$reset) {
            Session::setFlash('error', 'This password reset link is invalid or has already been used.');
            $this->redirect('/login');
            return;
        }

        if (strtotime($reset['expires_at']) < time()) {
            // Expired, delete it
            $stmt = $db->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$hashedToken]);
            Session::setFlash('error', 'This password reset link has expired. Please request a new one.');
            $this->redirect('/forgot-password');
            return;
        }

        $this->render('home', [
            'title' => 'Choose New Password - Bihar Vihaan Enterprise',
            'view_mode' => 'reset_password',
            'token' => $token
        ]);
    }

    public function handleResetPassword() {
        if (!\App\Core\Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Invalid security token.');
            $this->redirect('/login');
            return;
        }

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($token)) {
            Session::setFlash('error', 'Reset token missing.');
            $this->redirect('/login');
            return;
        }

        if (strlen($password) < 6) {
            Session::setFlash('error', 'Password must be at least 6 characters.');
            $this->redirect('/reset-password?token=' . urlencode($token));
            return;
        }

        if ($password !== $confirmPassword) {
            Session::setFlash('error', 'Passwords do not match.');
            $this->redirect('/reset-password?token=' . urlencode($token));
            return;
        }

        $hashedToken = hash('sha256', $token);
        
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ?");
        $stmt->execute([$hashedToken]);
        $reset = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$reset || strtotime($reset['expires_at']) < time()) {
            Session::setFlash('error', 'Invalid or expired password reset token.');
            $this->redirect('/forgot-password');
            return;
        }

        $email = $reset['email'];
        $userModel = new User();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        if ($userModel->updatePasswordByEmail($email, $hashedPassword)) {
            // Delete the token
            $stmt = $db->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            // Audit Log
            Logger::log('User Password Updated via Reset Link', "Password successfully updated for user email: $email");
            Session::setFlash('success', 'Your password has been successfully updated. You can now log in.');
            $this->redirect('/login');
        } else {
            Session::setFlash('error', 'Failed to update password. Please try again.');
            $this->redirect('/reset-password?token=' . urlencode($token));
        }
    }

    public function logout() {
        Auth::logout();
        Session::setFlash('success', 'You have been logged out successfully.');
        $this->redirect('/');
    }

    public function verify2FA() {
        if (!Session::has('temp_2fa_user_id')) {
            $this->redirect('/login');
        }

        $this->render('home', [
            'title' => 'Two-Factor Authentication - Bihar Vihaan Enterprise',
            'view_mode' => '2fa'
        ]);
    }

    public function handle2FA() {
        $code = $_POST['code'] ?? '';
        
        // Mock verification: any 6 digit code works for demo, or '123456'
        if ($code === '123456') {
            $userId = Session::get('temp_2fa_user_id');
            $userModel = new User();
            $user = $userModel->find($userId);
            
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role_name']);
            Session::set('user_role_id', $user['role_id']);
            Session::remove('temp_2fa_user_id');
            
            Session::setFlash('success', "Two-Factor verification successful!");
            $this->redirect('/');
        } else {
            Session::setFlash('error', 'Invalid Two-Factor Code. Please enter 123456 to bypass demo lock.');
            $this->redirect('/2fa');
        }
    }

    public function media() {
        $this->render('api_docs', [ // We reuse documentation elements or render media
            'title' => 'Media & Press Center - Bihar Vihaan',
            'view_mode' => 'media'
        ]);
    }

    public function gallery() {
        // Database integration: Fetch destinations to show their images
        $destModel = new \App\Models\Destination();
        $destinations = $destModel->all();
        
        $this->render('gallery', [
            'title' => 'Bihar Vihaan Gallery',
            'destinations' => $destinations
        ]);
    }

    public function about() {
        $this->render('about', [
            'title' => 'About Us - Bihar Vihaan Enterprise'
        ]);
    }

    public function services() {
        $this->render('services', [
            'title' => 'Our Services - Bihar Vihaan Enterprise'
        ]);
    }

    public function contact() {
        $this->render('contact', [
            'title' => 'Contact Us - Bihar Vihaan Enterprise'
        ]);
    }

    public function handleContact() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            Session::setFlash('error', 'All fields are required.');
            $this->redirect('/contact');
        }

        try {
            $db = \App\Core\Database::getInstance();
            $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
            $db->execute($sql, [$name, $email, $subject, $message]);
            Session::setFlash('success', 'Message sent successfully! We will get in touch with you shortly.');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Could not send message. Please try again.');
        }
        $this->redirect('/contact');
    }
}

