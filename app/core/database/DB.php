<?php

// Inlcude db constants (connection credentials, table names)
require_once $MODELS_DIR . '/DB_Config.php';

/**
 * 
 * Connect to database
 *
 * Configurations are placed outsite the core,
 * in the models directory (see the require above)
 *
 */
class DB {
    
    /**
     * Database connection object
     */
    private $db;
 
    /**
     * Connecting to database 
     * 
     * @return obj database connection
     *
     */
    public function connect() {
         
        // Create a new connection to mysql database
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
         
        // return database handler
        return $this->db;
    }

}
 
?>