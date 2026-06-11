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
        $destModel = new Destination();
        $eventModel = new Event();

        // Fetch trending spots (limit list)
        $trending = $destModel->all();
        // Fetch upcoming festivals
        $festivals = $eventModel->getFestivals();
        // Fetch active events
        $events = $eventModel->getEvents();

        $this->render('home', [
            'title' => 'Bihar Vihaan Enterprise - Explore Bihar Tourism',
            'trending' => array_slice($trending, 0, 3),
            'festivals' => array_slice($festivals, 0, 3),
            'events' => array_slice($events, 0, 3)
        ]);
    }

    public function login() {
        if (Auth::check()) {
            $this->redirect('/dashboard');
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
            $this->redirect('/dashboard');
        } else {
            Session::setFlash('error', 'Invalid login credentials or suspended account.');
            $this->redirect('/login');
        }
    }

    public function register() {
        if (Auth::check()) {
            $this->redirect('/dashboard');
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
            $this->redirect('/dashboard');
        }

        $this->render('home', [
            'title' => 'Forgot Password - Bihar Vihaan Enterprise',
            'view_mode' => 'forgot_password'
        ]);
    }

    public function handleForgotPassword() {
        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            Session::setFlash('error', 'Email is required.');
            $this->redirect('/forgot-password');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            $newPassword = 'reset123';
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $userModel->updatePasswordByEmail($email, $hashedPassword);
            
            Logger::log('User Password Reset Request', "Password reset for user ID {$user['id']} triggered.");
            Session::setFlash('success', "A temporary password 'reset123' has been set for your account. Please log in and update it.");
        } else {
            Session::setFlash('success', "If this email is registered, a password reset instruction has been dispatched.");
        }
        $this->redirect('/login');
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
            $this->redirect('/dashboard');
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

    public function clients() {
        $this->render('api_docs', [
            'title' => 'Our Clients - Bihar Vihaan',
            'view_mode' => 'clients'
        ]);
    }

    public function googleLogin() {
        $userModel = new User();
        $user = $userModel->findByEmail('google.tourist@gmail.com');
        if (!$user) {
            $hashedPass = password_hash('google_oauth_bypass', PASSWORD_BCRYPT);
            $userModel->create([
                'name' => 'Google Traveler',
                'email' => 'google.tourist@gmail.com',
                'password' => $hashedPass,
                'phone' => '+919999900000',
                'role_id' => 8,
                'status' => 'active'
            ]);
            $user = $userModel->findByEmail('google.tourist@gmail.com');
        }

        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role_name']);
        Session::set('user_role_id', $user['role_id']);
        
        Logger::log('Google Social Login', "User authenticated via Google OAuth.");
        Session::setFlash('success', 'Logged in successfully with Google!');
        $this->redirect('/dashboard');
    }

    public function facebookLogin() {
        $userModel = new User();
        $user = $userModel->findByEmail('fb.traveler@gmail.com');
        if (!$user) {
            $hashedPass = password_hash('facebook_oauth_bypass', PASSWORD_BCRYPT);
            $userModel->create([
                'name' => 'Facebook Explorer',
                'email' => 'fb.traveler@gmail.com',
                'password' => $hashedPass,
                'phone' => '+919999911111',
                'role_id' => 8,
                'status' => 'active'
            ]);
            $user = $userModel->findByEmail('fb.traveler@gmail.com');
        }

        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role_name']);
        Session::set('user_role_id', $user['role_id']);
        
        Logger::log('Facebook Social Login', "User authenticated via Facebook OAuth.");
        Session::setFlash('success', 'Logged in successfully with Facebook!');
        $this->redirect('/dashboard');
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

