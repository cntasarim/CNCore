<?php
namespace App\Core;

/**
 * Session Class
 * 
 * Handles session management securely
 */
class Session
{
    /**
     * Start a secure session
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Set secure session parameters
            ini_set('session.use_strict_mode', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_httponly', COOKIE_HTTPONLY ? 1 : 0);
            ini_set('session.cookie_secure', SECURE_COOKIES ? 1 : 0);
            ini_set('session.cookie_samesite', COOKIE_SAMESITE);
            ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
            ini_set('session.cookie_lifetime', SESSION_LIFETIME);
            
            // Set session name
            session_name(SESSION_NAME);
            
            // Start session
            session_start();
            
            // Regenerate session ID at random intervals
            if (!isset($_SESSION['_last_regeneration']) || 
                $_SESSION['_last_regeneration'] < time() - 300) {
                self::regenerate();
            }
        }
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate()
    {
        // Regenerate session ID
        session_regenerate_id(true);
        
        // Set last regeneration time
        $_SESSION['_last_regeneration'] = time();
    }
    
    /**
     * Set a session value
     * 
     * @param string $key Session key
     * @param mixed $value Value to store
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get a session value
     * 
     * @param string $key Session key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed Session value or default
     */
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    /**
     * Check if session key exists
     * 
     * @param string $key Session key
     * @return bool True if key exists, false otherwise
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Delete a session value
     * 
     * @param string $key Session key
     */
    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Set a flash message
     * 
     * @param string $key Flash key
     * @param mixed $value Flash value
     */
    public static function flash($key, $value)
    {
        // Store flash message
        $_SESSION['_flash'][$key] = $value;
    }
    
    /**
     * Get a flash message
     * 
     * @param string $key Flash key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed Flash value or default
     */
    public static function getFlash($key, $default = null)
    {
        // Get flash message
        $value = isset($_SESSION['_flash'][$key]) ? $_SESSION['_flash'][$key] : $default;
        
        // Remove flash message
        if (isset($_SESSION['_flash'][$key])) {
            unset($_SESSION['_flash'][$key]);
        }
        
        return $value;
    }
    
    /**
     * Check if flash key exists
     * 
     * @param string $key Flash key
     * @return bool True if key exists, false otherwise
     */
    public static function hasFlash($key)
    {
        return isset($_SESSION['_flash'][$key]);
    }
    
    /**
     * Destroy the session
     */
    public static function destroy()
    {
        // Clear session array
        $_SESSION = [];
        
        // Delete session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        // Destroy session
        session_destroy();
    }
}