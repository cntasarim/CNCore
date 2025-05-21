<?php
namespace App\Core;

/**
 * Security Class
 * 
 * Provides security functions to prevent common vulnerabilities
 */
class Security
{
    /**
     * Initialize security measures
     */
    public static function init()
    {
        // Set secure headers
        self::setSecureHeaders();
        
        // Generate CSRF token if not exists
        if (!Session::has('csrf_token')) {
            self::regenerateCsrfToken();
        }
    }
    
    /**
     * Set secure HTTP headers
     */
    private static function setSecureHeaders()
    {
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-XSS-Protection: 1; mode=block');
            header('X-Frame-Options: SAMEORIGIN');
            if (SECURE_COOKIES) {
                header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
            }
            header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'");
            header('Referrer-Policy: no-referrer-when-downgrade');
        }
    }
    
    /**
     * Generate a new CSRF token
     * 
     * @return string New CSRF token
     */
    public static function regenerateCsrfToken()
    {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }
    
    /**
     * Get current CSRF token
     * 
     * @return string Current CSRF token
     */
    public static function getCsrfToken()
    {
        return Session::get('csrf_token');
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token Token to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateCsrfToken($token)
    {
        $storedToken = Session::get('csrf_token');
        
        if (!$storedToken || !$token || !hash_equals($storedToken, $token)) {
            return false;
        }
        
        // Generate a new token after validation (one-time use)
        self::regenerateCsrfToken();
        
        return true;
    }
    
    /**
     * Generate a CSRF token field
     * 
     * @return string HTML input field with CSRF token
     */
    public static function csrfField()
    {
        $token = self::getCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
    
    /**
     * Sanitize input to prevent XSS
     * 
     * @param string $input Input to sanitize
     * @return string Sanitized input
     */
    public static function sanitizeInput($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Escape output for HTML
     * 
     * @param string $output Output to escape
     * @return string Escaped output
     */
    public static function escape($output)
    {
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Hash a password
     * 
     * @param string $password Password to hash
     * @return string Hashed password
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Verify a password against a hash
     * 
     * @param string $password Password to verify
     * @param string $hash Hash to check against
     * @return bool True if password matches hash, false otherwise
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}