<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use App\Models\User;
use PDO;

class AdminUsersController extends Controller {

    protected $userModel;

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_users');
        $this->userModel = new User();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $users = $this->userModel->all();
        $roles = $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->renderAdmin('admin/users', [
            'title' => 'User & Role Management | Bihar Vihaan',
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $role_id = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : 8; // User
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || empty($email) || empty($password)) {
            Session::setFlash('error', 'Name, email, and password are required.');
            $this->redirect('/admin/users');
            return;
        }

        // Check if user exists
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            Session::setFlash('error', 'A user with that email already exists.');
            $this->redirect('/admin/users');
            return;
        }

        $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'phone' => $phone,
            'role_id' => $role_id,
            'status' => $status
        ]);

        Session::setFlash('success', 'User account created successfully.');
        $this->redirect('/admin/users');
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $role_id = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : 8;
        $status = $_POST['status'] ?? 'active';

        // Basic check
        $this->userModel->update($id, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ]);
        
        $this->userModel->updateRole($id, $role_id);
        $this->userModel->updateStatus($id, $status);

        // Optional password reset
        if (!empty($_POST['password'])) {
            $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $this->userModel->updatePasswordByEmail($email, $hashed);
        }

        Session::setFlash('success', 'User details updated.');
        $this->redirect('/admin/users');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        
        // Prevent deleting oneself
        if ($id == Session::get('user_id')) {
            Session::setFlash('error', 'You cannot delete your own account while logged in.');
            $this->redirect('/admin/users');
            return;
        }

        if ($id) {
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'User account removed.');
        }
        $this->redirect('/admin/users');
    }
}
