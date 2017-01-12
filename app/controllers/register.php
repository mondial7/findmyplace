<?php

/**
 * Register function
 *
 * @return array registration status
 * ["status"=>,"error_message"=>,"data"=>[]]
 *
 */
function register($email, $username, $password, $passwordCheck){

	/**
	 * Declare global scope
	 */
	global $MODELS_DIR;

	/**
	 * Default return array
	 */
	$registration_data = ["email" => $email, "username" => $username];
	$registration_ = ["status"=>false, "error_message"=>"", "data"=>$registration_data];
	
	/**
	 * Include and instatiate models
	 */
	require_once $MODELS_DIR . '/Account.php';
	$account = new Account();
	require_once $MODELS_DIR . '/Credentials.php';
	$credentials = new Credentials();

	/**
	 * Check if passwords match
	 */
	if ($password !== $passwordCheck) {
		
		$registration_["error_message"] = "Passwords do not match.";
		return $registration_;

	}

	/**
	 * Check if email already exists
	 */
	if ($credentials->emailExists($email)) {
		
		$registration_["error_message"] = "Email already exists.";
		return $registration_;

	}

	/**
	 * Check if email already exists
	 */
	if ($credentials->usernameExists($username)) {
		
		$registration_["error_message"] = "Username already exists.";
		return $registration_;

	}

	/**
	 * Set account data
	 */
	$account->setEmail($email);
	$account->setUsername($username);
	$account->setPassword($password);


	/**
	 * Validate inputs
	 */
	if ($account->isValid()) {

		// Execute query and evaluate result
		if ($credentials->register($account)) {
		    
		    // Send "Welcome email"
		    // ...

			$registration_["status"] = true;

		} else {

			// DB answered with error status
			$registration_["error_message"] = "We had some problem creating your account, try again and if the problem persist, please contact us at info@example.com";

		}

	} else {

		$registration_["error_message"] = "Inputs are not valid.";

	}

	return $registration_;

}

?>