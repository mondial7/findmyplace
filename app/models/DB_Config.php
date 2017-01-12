<?php
 
/**
 * Database config variables
 */
define("DB_HOST", "localhost");
define("DB_USER", "cappuccino");
define("DB_PASSWORD", "startup7");
define("DB_DATABASE", "findmyplace");

/**
 * Database table prefix
 */
define("DB_TABLE_PREFIX", "findmyplace__");

/**
 * Database table name variables
 */
define("_T_ACCOUNT", DB_TABLE_PREFIX . "account"); //+
define("_T_ACCOUNT_LOGGED", DB_TABLE_PREFIX . "account_logged");
define("_T_PLACE", DB_TABLE_PREFIX . "places");
define("_T_PROJECT", DB_TABLE_PREFIX . "projects");

?>