<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * Error Controller
 * 
 * Handles error pages
 */
class ErrorController extends Controller
{
    /**
     * 404 Not Found page
     */
    public function notFound()
    {
        http_response_code(404);
        $this->view('error.404');
    }
    
    /**
     * 403 Forbidden page
     */
    public function forbidden()
    {
        http_response_code(403);
        $this->view('error.403');
    }
    
    /**
     * 500 Server Error page
     */
    public function serverError()
    {
        http_response_code(500);
        $this->view('error.500');
    }
}