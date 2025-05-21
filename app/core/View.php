<?php
namespace App\Core;

/**
 * View Class
 */
class View
{
    /**
     * Render a view
     * 
     * @param string $view View file to render
     * @param array $data Data to pass to the view
     * @return void
     */
    public static function render($view, $data = [])
    {
        // Extract data
        extract($data);
        
        // Helper functions
        $e = function ($value) {
            return Security::escape($value);
        };
        
        $csrf = function () {
            return Security::csrfField();
        };
        
        // Get view path
        $viewPath = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View '{$view}' not found");
        }
        
        ob_start();
        include $viewPath;
        echo ob_get_clean();
    }
}