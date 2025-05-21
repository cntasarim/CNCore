<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;
use App\Models\User;

/**
 * User Controller
 * 
 * Handles user authentication and registration
 */
class UserController extends Controller
{
    /**
     * Registration form
     */
    public function registerForm()
    {
        $this->view('user.register', [
            'title' => 'Register'
        ]);
    }
    
    /**
     * Process registration
     */
    public function register()
    {
        // Get form data
        $name = $this->input('name');
        $email = $this->input('email');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Validate form data
        $errors = $this->validate([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirm' => $passwordConfirm
        ], [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirm' => 'required|matches:password'
        ]);
        
        if (!empty($errors)) {
            // Validation failed, show form again with errors
            return $this->view('user.register', [
                'title' => 'Register',
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ]);
        }
        
        // Check if email already exists
        $userModel = new User();
        $existingUser = $userModel->findByEmail($email);
        
        if ($existingUser) {
            return $this->view('user.register', [
                'title' => 'Register',
                'errors' => [
                    'email' => ['Email already in use']
                ],
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ]);
        }
        
        // Hash password
        $hashedPassword = Security::hashPassword($password);
        
        // Create user
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);
        
        if (!$userId) {
            // User creation failed
            return $this->view('user.register', [
                'title' => 'Register',
                'errors' => [
                    'general' => ['Registration failed. Please try again.']
                ],
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ]);
        }
        
        // Set success message
        Session::flash('success', 'Registration successful. You can now log in.');
        
        // Redirect to login page
        $this->redirectToRoute('user.login');
    }
    
    /**
     * Login form
     */
    public function loginForm()
    {
        $this->view('user.login', [
            'title' => 'Login'
        ]);
    }
    
    /**
     * Process login
     */
    public function login()
    {
        // Get form data
        $email = $this->input('email');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validate form data
        $errors = $this->validate([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            // Validation failed, show form again with errors
            return $this->view('user.login', [
                'title' => 'Login',
                'errors' => $errors,
                'old' => [
                    'email' => $email,
                    'remember' => $remember
                ]
            ]);
        }
        
        // Find user by email
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if (!$user || !Security::verifyPassword($password, $user['password'])) {
            // Invalid credentials
            return $this->view('user.login', [
                'title' => 'Login',
                'errors' => [
                    'general' => ['Invalid email or password']
                ],
                'old' => [
                    'email' => $email,
                    'remember' => $remember
                ]
            ]);
        }
        
        // Update last login time
        $userModel->updateLastLogin($user['id']);
        
        // Set session variables
        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        
        // Set remember me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $token);
            
            // Store token in database
            $userModel->storeRememberToken($user['id'], $hashedToken);
            
            // Set cookie
            setcookie(
                'remember_token',
                $token,
                time() + 60 * 60 * 24 * 30, // 30 days
                '/',
                '',
                SECURE_COOKIES,
                true
            );
        }
        
        // Regenerate session ID
        Session::regenerate();
        
        // Redirect to intended URL or dashboard
        $intendedUrl = Session::get('intended_url', null);
        
        if ($intendedUrl) {
            Session::delete('intended_url');
            $this->redirect($intendedUrl);
        } else {
            $this->redirectToRoute('dashboard');
        }
    }
    
    /**
     * Process logout
     */
    public function logout()
    {
        // Delete remember token if exists
        if (isset($_COOKIE['remember_token'])) {
            // Remove from database
            $userModel = new User();
            $userId = Session::get('user_id');
            
            if ($userId) {
                $userModel->removeRememberToken($userId);
            }
            
            // Delete cookie
            setcookie('remember_token', '', time() - 3600, '/', '', SECURE_COOKIES, true);
        }
        
        // Destroy session
        Session::destroy();
        
        // Redirect to home page
        $this->redirectToRoute('home');
    }
}