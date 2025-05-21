<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Security;
use App\Core\Session;

/**
 * CSRF Protection Middleware
 */
class CsrfMiddleware implements Middleware
{
    /**
     * Handle the request
     * 
     * @param callable $next Next middleware in pipeline
     * @return void
     */
    public function handle($next)
    {
        // Skip CSRF check for GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $next();
            return;
        }
        
        // Get token from POST data or header
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        
        // Validate token
        if (!$token || !Security::validateCsrfToken($token)) {
            Session::flash('error', 'Invalid CSRF token');
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
            exit;
        }
        
        $next();
    }
}