<?php
namespace App\Core;

/**
 * Main Application Class
 * 
 * Handles the request lifecycle
 */
class App
{
    /**
     * Run the application
     */
    public function run()
    {
        try {
            // Get the current URI
            $uri = $this->getUri();
            
            // Dispatch the route
            Router::dispatch($uri);
        } catch (\Exception $e) {
            // Handle exceptions
            ErrorHandler::handleException($e);
        }
    }
    
    /**
     * Get current URI
     * 
     * @return string The current URI
     */
    private function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // If empty, set to root
        if (empty($uri)) {
            $uri = '/';
        }
        
        return $uri;
    }
}