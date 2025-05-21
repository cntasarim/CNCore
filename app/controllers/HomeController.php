<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;

/**
 * Home Controller
 * 
 * Handles home page and basic pages
 */
class HomeController extends Controller
{
    /**
     * Home page
     */
    public function index()
    {
        $this->view('home.index', [
            'title' => 'Home'
        ]);
    }
    
    /**
     * About page
     */
    public function about()
    {
        $this->view('home.about', [
            'title' => 'About Us'
        ]);
    }
    
    /**
     * Contact page
     */
    public function contact()
    {
        $this->view('home.contact', [
            'title' => 'Contact Us'
        ]);
    }
    
    /**
     * Submit contact form
     */
    public function submitContact()
    {
        // Validate form data
        $name = $this->input('name');
        $email = $this->input('email');
        $message = $this->input('message');
        
        $errors = $this->validate([
            'name' => $name,
            'email' => $email,
            'message' => $message
        ], [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        
        if (!empty($errors)) {
            // Validation failed, show form again with errors
            return $this->view('home.contact', [
                'title' => 'Contact Us',
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                    'message' => $message
                ]
            ]);
        }
        
        // In a real application, you would store the message in the database
        // and/or send an email
        
        // Flash success message and redirect
        $this->redirectToRoute('home');
    }
}