<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Security;
use App\Models\User;

/**
 * Dashboard Controller
 * 
 * Handles dashboard and user profile
 */
class DashboardController extends Controller
{
    /**
     * Dashboard page
     */
    public function index()
    {
        $this->view('dashboard.index', [
            'title' => 'Dashboard',
            'userName' => Session::get('user_name')
        ]);
    }
    
    /**
     * User profile page
     */
    public function profile()
    {
        $userId = Session::get('user_id');
        $userModel = new User();
        $user = $userModel->find($userId);
        
        $this->view('dashboard.profile', [
            'title' => 'Profile',
            'user' => $user
        ]);
    }
    
    /**
     * Update user profile
     */
    public function updateProfile()
    {
        $userId = Session::get('user_id');
        $name = $this->input('name');
        $email = $this->input('email');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        $errors = $this->validate([
            'name' => $name,
            'email' => $email
        ], [
            'name' => 'required',
            'email' => 'required|email'
        ]);
        
        // Get user
        $userModel = new User();
        $user = $userModel->find($userId);
        
        // Check if email already exists for another user
        if ($email !== $user['email']) {
            $existingUser = $userModel->findByEmail($email);
            
            if ($existingUser && $existingUser['id'] !== $userId) {
                $errors['email'][] = 'Email already in use';
            }
        }
        
        // Validate password if provided
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            // Current password is required
            if (empty($currentPassword)) {
                $errors['current_password'][] = 'Current password is required';
            } elseif (!Security::verifyPassword($currentPassword, $user['password'])) {
                $errors['current_password'][] = 'Current password is incorrect';
            }
            
            // New password validation
            if (empty($newPassword)) {
                $errors['new_password'][] = 'New password is required';
            } elseif (strlen($newPassword) < 8) {
                $errors['new_password'][] = 'Password must be at least 8 characters';
            }
            
            // Confirm password validation
            if (empty($confirmPassword)) {
                $errors['confirm_password'][] = 'Please confirm your password';
            } elseif ($newPassword !== $confirmPassword) {
                $errors['confirm_password'][] = 'Passwords do not match';
            }
        }
        
        // If there are errors, show the form again
        if (!empty($errors)) {
            return $this->view('dashboard.profile', [
                'title' => 'Profile',
                'user' => $user,
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ]);
        }
        
        // Update user data
        $data = [
            'name' => $name,
            'email' => $email
        ];
        
        // Update password if provided
        if (!empty($newPassword)) {
            $data['password'] = Security::hashPassword($newPassword);
        }
        
        // Save changes
        $userModel->update($userId, $data);
        
        // Update session data
        Session::set('user_name', $name);
        Session::set('user_email', $email);
        
        // Set success message
        Session::flash('success', 'Profile updated successfully');
        
        // Redirect back to profile
        $this->redirectToRoute('profile');
    }
}