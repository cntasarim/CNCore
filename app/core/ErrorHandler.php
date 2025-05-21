<?php
namespace App\Core;

/**
 * Error Handler Class
 * 
 * Handles errors and exceptions consistently
 */
class ErrorHandler
{
    /**
     * Handle PHP errors
     * 
     * @param int $errno Error number
     * @param string $errstr Error message
     * @param string $errfile File where error occurred
     * @param int $errline Line where error occurred
     * @return bool True to prevent standard error handler
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        // Log the error
        error_log("Error [$errno]: $errstr in $errfile on line $errline");
        
        // Don't expose error details in production
        if (!DEBUG_MODE) {
            self::displayErrorPage(500);
            exit;
        }
        
        // Display error in development
        echo "<h1>Error</h1>";
        echo "<p><strong>Error:</strong> $errstr</p>";
        echo "<p><strong>File:</strong> $errfile on line $errline</p>";
        
        return true;
    }
    
    /**
     * Handle exceptions
     * 
     * @param \Throwable $exception The exception object
     * @return void
     */
    public static function handleException(\Throwable $exception)
    {
        // Log the exception
        error_log("Exception: " . $exception->getMessage() . " in " . 
                 $exception->getFile() . " on line " . $exception->getLine());
        
        // Don't expose exception details in production
        if (!DEBUG_MODE) {
            self::displayErrorPage(500);
            exit;
        }
        
        // Display exception in development
        echo "<h1>Exception</h1>";
        echo "<p><strong>Message:</strong> " . $exception->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $exception->getFile() . 
             " on line " . $exception->getLine() . "</p>";
        echo "<h2>Stack Trace</h2>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    }
    
    /**
     * Display error page
     * 
     * @param int $code HTTP status code
     * @return void
     */
    public static function displayErrorPage($code)
    {
        http_response_code($code);
        
        // Map error codes to routes
        $errorRoutes = [
            404 => 'error.404',
            403 => 'error.403',
            500 => 'error.500'
        ];
        
        // If the route exists, redirect to it
        if (isset($errorRoutes[$code]) && Router::routeExists($errorRoutes[$code])) {
            Router::redirectToRoute($errorRoutes[$code]);
            exit;
        }
        
        // Fallback error messages
        $errorMessages = [
            404 => 'Page Not Found',
            403 => 'Forbidden',
            500 => 'Server Error'
        ];
        
        $message = $errorMessages[$code] ?? 'Unknown Error';
        
        // Simple error page
        echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Error: $code</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; 
                           color: #333; max-width: 650px; margin: 0 auto; padding: 1rem; }
                    h1 { color: #e74c3c; }
                </style>
            </head>
            <body>
                <h1>Error $code</h1>
                <p>$message</p>
                <p><a href='/'>Return to Homepage</a></p>
            </body>
            </html>";
    }
}