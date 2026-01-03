<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function showLogin()
    {
        if (isLoggedIn()) {
            $this->redirect($this->getDashboardRoute());
        }
        $this->view('auth.login');
    }
    
    public function showRegister()
    {
        if (isLoggedIn()) {
            $this->redirect($this->getDashboardRoute());
        }
        $this->view('auth.register');
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            flash('error', 'Email and password are required');
            $this->redirect('/login');
        }
        
        $user = $this->userModel->verifyPassword($email, $password);
        
        if ($user) {
            $this->userModel->updateLastLogin($user['id']);
            session('user', $user);
            flash('success', 'Welcome back, ' . $user['name']);
            $this->redirect($this->getDashboardRoute());
        } else {
            flash('error', 'Invalid email or password');
            $this->redirect('/login');
        }
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = sanitize($_POST['role'] ?? 'student');
        
        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'All fields are required');
            $this->redirect('/register');
        }
        
        if ($password !== $confirmPassword) {
            flash('error', 'Passwords do not match');
            $this->redirect('/register');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Invalid email address');
            $this->redirect('/register');
        }
        
        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            flash('error', 'Email already registered');
            $this->redirect('/register');
        }
        
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ];
        
        if ($role === 'instructor') {
            $data['instructor_status'] = 'pending';
        }
        
        $userId = $this->userModel->register($data);
        
        if ($userId) {
            $user = $this->userModel->find($userId);
            session('user', $user);
            flash('success', 'Registration successful! Welcome to SchoolDream+');
            $this->redirect($this->getDashboardRoute());
        } else {
            flash('error', 'Registration failed. Please try again.');
            $this->redirect('/register');
        }
    }
    
    public function logout()
    {
        session_destroy();
        flash('success', 'You have been logged out');
        $this->redirect('/');
    }
    
    private function getDashboardRoute()
    {
        $user = auth();
        switch ($user['role']) {
            case 'admin':
                return '/admin/dashboard';
            case 'instructor':
                return '/instructor/dashboard';
            default:
                return '/student/dashboard';
        }
    }
}
