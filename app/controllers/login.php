<?php
	
/**
 * Login function
 *
 * @return boolean login successful
 *
 */
function login($login_email, $login_password){

	/**
	 * Declare global scope
	 */
	global $login_data, $MODELS_DIR;

	/**
	 * Include and instatiate models
	 */
	require_once $MODELS_DIR . '/Account.php';
	$account = new Account();
	require_once $MODELS_DIR . '/Credentials.php';
	$credentials = new Credentials();

	/**
	 * Set user inputs
	 */
	$account->setEmail($login_email);
	$account->setPassword($login_password);

	/**
	 * Perform login
	 */
	$account_data = $credentials->login($account);

	/**
	 * Set defalut values of email
	 */
	$login_data = ['email' => $login_email, 'password' => $login_password];


	/**
	 * default global variable to switch and redirect if login is successful 
	 * or show error message on login_form
	 */
	$loginOk = (count($account_data) > 0);

	/**
	 * If login is successful: 
	 * - save session data
	 * - save cookie for permanent login
	 * - redirect to home page
	 */
	if ($loginOk) {

		// Set session data
		foreach ($account_data as $key => $value) {
			$_SESSION[$key] = $value;
		}

	   	// Check if a persistent login has been required
	   	/*
	   	if (isset($isPermaLogin) &&
	   		$isPermaLogin === TRUE && 
	   		!empty( $cookie_token = $credentials->setPermaLogin($_SESSION['id']))) {

	   		$days = 90;
	   		setcookie("permalog", $cookie_token, time() + (86400 * $days), "/");
	   	
	   	}
	   	*/

	}

	return $loginOk;

}

?>