<?php
namespace App\Core;

/**
 * Base Model Class
 * 
 * All models should extend this class
 */
abstract class Model
{
    /**
     * Database instance
     */
    protected $db;
    
    /**
     * Table name
     */
    protected $table;
    
    /**
     * Primary key
     */
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find a record by primary key
     * 
     * @param mixed $id Primary key value
     * @return array|null Record data or null if not found
     */
    public function find($id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1")
                      ->bind(['id' => $id])
                      ->fetch();
    }
    
    /**
     * Get all records
     * 
     * @return array Records
     */
    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table}")
                      ->fetchAll();
    }
    
    /**
     * Find records by criteria
     * 
     * @param array $conditions Where conditions as field => value pairs
     * @param string $orderBy Order by clause
     * @param int $limit Result limit
     * @param int $offset Result offset
     * @return array Records
     */
    public function findBy($conditions = [], $orderBy = '', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        // Add where clause if conditions exist
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            
            foreach ($conditions as $field => $value) {
                $whereClauses[] = "{$field} = :{$field}";
                $params[$field] = $value;
            }
            
            $sql .= implode(' AND ', $whereClauses);
        }
        
        // Add order by
        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // Add limit and offset
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
            
            if ($offset !== null) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        return $this->db->query($sql)
                      ->bind($params)
                      ->fetchAll();
    }
    
    /**
     * Create a new record
     * 
     * @param array $data Record data as field => value pairs
     * @return int|bool Last insert ID or false on failure
     */
    public function create($data)
    {
        // Extract fields and values
        $fields = array_keys($data);
        $placeholders = array_map(function ($field) {
            return ":{$field}";
        }, $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $result = $this->db->query($sql)
                        ->bind($data)
                        ->execute();
        
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Update a record
     * 
     * @param mixed $id Primary key value
     * @param array $data Record data as field => value pairs
     * @return bool True on success, false on failure
     */
    public function update($id, $data)
    {
        // Extract fields and placeholders
        $setClauses = array_map(function ($field) {
            return "{$field} = :{$field}";
        }, array_keys($data));
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . 
               " WHERE {$this->primaryKey} = :id";
        
        // Add ID to data
        $data['id'] = $id;
        
        return $this->db->query($sql)
                      ->bind($data)
                      ->execute();
    }
    
    /**
     * Delete a record
     * 
     * @param mixed $id Primary key value
     * @return bool True on success, false on failure
     */
    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id")
                      ->bind(['id' => $id])
                      ->execute();
    }
    
    /**
     * Execute a custom query
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @param bool $fetchAll Whether to fetch all results
     * @return mixed Query results
     */
    public function query($sql, $params = [], $fetchAll = true)
    {
        $query = $this->db->query($sql)->bind($params);
        
        return $fetchAll ? $query->fetchAll() : $query->fetch();
    }
}