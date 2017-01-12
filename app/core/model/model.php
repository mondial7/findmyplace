<?php

/**
 * Include database connection class
 *
 * @todo improve and remove this include, since all these classes are included by coreLoader
 *
 */
require_once dirname( __DIR__ ) . '/database/DB.php';
        
/**
 * Model class parent of all models
 */
abstract Class Model {


    /**
     * Database connection
     */
    protected $db;

    function __construct() {

        // ...

    }
 

    function __destruct() {

    	// Close database connection
    	if (isset($this->db)) {

    		$this->db->close();

    	}

    }


    /**
     * Declare a new connection to the database
     *
     * @return void
     * 
     */
    protected function connectDB(){

        // Prevent to open multiple connections
        if (!isset($this->db)) {

            $this->db = (new Db())->connect();
        
        }

    }

}