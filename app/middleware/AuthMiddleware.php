<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Router;
use App\Core\Session;

/**
 * Authentication Middleware
 * 
 * Ensures user is authenticated
 */
class AuthMiddleware implements Middleware
{
    /**
     * Handle the request
     * 
     * @param callable $next Next middleware in pipeline
     * @return void
     */
    public function handle($next)
    {
        // Check if user is authenticated
        if (!Session::has('user_id')) {
            // Store intended URL for redirect after login
            Session::set('intended_url', $_SERVER['REQUEST_URI']);
            
            // Redirect to login page
            Session::flash('error', 'Please log in to access this page');
            Router::redirectToRoute('user.login');
            exit;
        }
        
        // User is authenticated, continue to next middleware
        $next();
    }
}