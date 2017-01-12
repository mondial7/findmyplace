<?php

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	// Delete Permalogin
	/*
	if( isset($_COOKIE['permalog']) ) {
		
		// Delete account record from db
		require_once $APP_DIR . '/models/database/DB_Connect.php';
		$db = new Db_Connect();
		$db->connect()->query("DELETE FROM "._T_ACCOUNT_LOGGED." WHERE account_id='".$_SESSION['id']."';");	

		// Delete cookie
		setcookie('permalog', '', time() - 42000, "/");
	
	} */

	session_unset();

	// Delete session cookie
	if ( ini_get("session.use_cookies") ) {

	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	
	}

	session_destroy();

    /**
      * Target page to load after successful login
      * @todo add check for whitelist targets
      */
    if (isset($_GET['s']) &&
        ($target = filter_var($_GET['s'], FILTER_SANITIZE_URL))) {
        // nothing here
    } else {
        $target = "../";
    }

	header("Location: " . $target);

?>