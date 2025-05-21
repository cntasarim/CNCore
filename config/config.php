<?php
/**
 * Application Configuration
 */

// Load environment variables
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0 || empty($line)) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, '"\'');
    }
}

// Helper function to get environment variables
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Debug mode
define('DEBUG_MODE', env('APP_DEBUG', false));

// Database configuration
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'mvc_database'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

// Application configuration
define('APP_NAME', env('APP_NAME', 'Secure MVC Framework'));
define('APP_URL', env('APP_URL', 'http://localhost:8000'));

// Security configuration
define('CSRF_TOKEN_NAME', env('CSRF_TOKEN_NAME', 'csrf_token'));
define('SESSION_NAME', env('SESSION_NAME', 'secure_session'));
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 1800));
define('SECURE_COOKIES', env('SECURE_COOKIES', false));
define('COOKIE_HTTPONLY', env('COOKIE_HTTPONLY', true));
define('COOKIE_SAMESITE', env('COOKIE_SAMESITE', 'Lax'));

// Cache configuration
define('CACHE_DRIVER', env('CACHE_DRIVER', 'file'));
define('CACHE_PREFIX', env('CACHE_PREFIX', 'mvc_'));
define('CACHE_LIFETIME', env('CACHE_LIFETIME', 3600));

// Redis configuration
define('REDIS_HOST', env('REDIS_HOST', '127.0.0.1'));
define('REDIS_PORT', env('REDIS_PORT', 6379));
define('REDIS_PASSWORD', env('REDIS_PASSWORD', null));
define('REDIS_DB', env('REDIS_DB', 0));

// File upload configuration
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('MAX_UPLOAD_SIZE', env('MAX_UPLOAD_SIZE', 2 * 1024 * 1024));
define('ALLOWED_EXTENSIONS', explode(',', env('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,pdf,doc,docx')));
define('ALLOWED_MIMES', explode(',', env('ALLOWED_MIMES', 'image/jpeg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document')));