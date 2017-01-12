<?php

	session_start();

	$userLogged = isset($_SESSION['username']);	
   	
   	/*
	// Check if the user required to stay logged in
	if(!$userLogged){

		// Load credentials class and
		// try autologin through cookies
		require_once $MODELS_DIR . '/Credentials.php';
		$userLogged = (new Credentials())->autologin();

	}
	*/

	$template_variables['sess'] = $_SESSION;
    $template_variables['userLogged'] = $userLogged;

?>