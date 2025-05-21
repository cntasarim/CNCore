<?php
namespace App\Core;

/**
 * Class Autoloader
 * 
 * Simple PSR-4 compatible autoloader
 */
class Autoloader
{
    /**
     * Register the autoloader
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $prefix = 'App\\';
            $baseDir = BASE_PATH . '/app/';
            
            // Check if the class uses the namespace prefix
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }
            
            // Get the relative class name
            $relativeClass = substr($class, $len);
            
            // Replace namespace separators with directory separators
            // and append .php
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            
            // If the file exists, require it
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}

// Register the autoloader
Autoloader::register();