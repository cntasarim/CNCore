<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Database Class
 * 
 * Handles database connections and provides helper methods
 * Uses PDO with prepared statements to prevent SQL injection
 */
class Database
{
    private static $instance = null;
    private $connection;
    private $statement;
    
    /**
     * Private constructor to prevent direct initialization
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
                PDO::MYSQL_ATTR_FOUND_ROWS => true,
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error but don't expose details
            error_log('Database connection error: ' . $e->getMessage());
            throw new \Exception('Database connection failed');
        }
    }
    
    /**
     * Get database instance (Singleton pattern)
     * 
     * @return Database Database instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Prepare a SQL statement
     * 
     * @param string $sql SQL query
     * @return Database This instance for method chaining
     */
    public function query($sql)
    {
        $this->statement = $this->connection->prepare($sql);
        return $this;
    }
    
    /**
     * Bind values to prepared statement
     * 
     * @param array $params Array of parameter values
     * @return Database This instance for method chaining
     */
    public function bind($params)
    {
        foreach ($params as $param => $value) {
            $type = $this->getParamType($value);
            
            // If numeric key, add 1 for positional parameters
            if (is_numeric($param)) {
                $param = $param + 1;
            }
            
            $this->statement->bindValue($param, $value, $type);
        }
        
        return $this;
    }
    
    /**
     * Determine PDO parameter type
     * 
     * @param mixed $value Parameter value
     * @return int PDO parameter type
     */
    private function getParamType($value)
    {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            default:
                return PDO::PARAM_STR;
        }
    }
    
    /**
     * Execute the prepared statement
     * 
     * @return bool True on success, false on failure
     */
    public function execute()
    {
        try {
            return $this->statement->execute();
        } catch (PDOException $e) {
            error_log('Database query error: ' . $e->getMessage());
            // Don't expose the actual SQL error to users
            throw new \Exception('Database query failed');
        }
    }
    
    /**
     * Get all result rows
     * 
     * @return array Result rows
     */
    public function fetchAll()
    {
        $this->execute();
        return $this->statement->fetchAll();
    }
    
    /**
     * Get single result row
     * 
     * @return array|null Result row or null if no results
     */
    public function fetch()
    {
        $this->execute();
        return $this->statement->fetch();
    }
    
    /**
     * Get number of affected rows
     * 
     * @return int Number of affected rows
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
    
    /**
     * Get last inserted ID
     * 
     * @return string Last inserted ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Begin a transaction
     * 
     * @return bool True on success, false on failure
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit a transaction
     * 
     * @return bool True on success, false on failure
     */
    public function commit()
    {
        return $this->connection->commit();
    }
    
    /**
     * Roll back a transaction
     * 
     * @return bool True on success, false on failure
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }
}