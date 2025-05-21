<?php
namespace App\Models;

use App\Core\Model;

/**
 * User Model
 * 
 * Handles user data operations
 */
class User extends Model
{
    /**
     * Table name
     */
    protected $table = 'users';
    
    /**
     * Find user by email
     * 
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function findByEmail($email)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1")
                       ->bind(['email' => $email])
                       ->fetch();
    }
    
    /**
     * Find user by remember token
     * 
     * @param string $token Remember token
     * @return array|null User data or null if not found
     */
    public function findByRememberToken($token)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE remember_token = :token LIMIT 1")
                       ->bind(['token' => $token])
                       ->fetch();
    }
    
    /**
     * Store remember token
     * 
     * @param int $userId User ID
     * @param string $token Remember token
     * @return bool True on success, false on failure
     */
    public function storeRememberToken($userId, $token)
    {
        return $this->db->query("UPDATE {$this->table} SET remember_token = :token WHERE id = :id")
                       ->bind([
                           'id' => $userId,
                           'token' => $token
                       ])
                       ->execute();
    }
    
    /**
     * Remove remember token
     * 
     * @param int $userId User ID
     * @return bool True on success, false on failure
     */
    public function removeRememberToken($userId)
    {
        return $this->db->query("UPDATE {$this->table} SET remember_token = NULL WHERE id = :id")
                       ->bind(['id' => $userId])
                       ->execute();
    }
    
    /**
     * Update last login time
     * 
     * @param int $userId User ID
     * @return bool True on success, false on failure
     */
    public function updateLastLogin($userId)
    {
        return $this->db->query("UPDATE {$this->table} SET last_login = NOW() WHERE id = :id")
                       ->bind(['id' => $userId])
                       ->execute();
    }
}