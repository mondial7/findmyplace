<?php

$response = [];

if ($userLogged && isset($_REQUEST['exists'])) {

	if (!$userLogged) {

		$response['status'] = "you are not logged";

	} else if (!isset($_REQUEST['address']) || empty($_REQUEST['address'])) {

		$response['status'] = "wrong parameters";

	} else {

		// Instanziate place classes
		require_once $MODELS_DIR . "/PlacesManager.php";

		if ((new PlacesManager())->addressExists($_REQUEST['address'])) {

			/**
			 * @todo finish this
			 */

		}

	}

} else if (isset($_REQUEST['address'])) {

	// Instanziate place classes
	require_once $MODELS_DIR . "/PlacesManager.php";
	
	$address_obj = (new PlacesManager())->prettifyAddress($_REQUEST['address']);

	if ($address_obj['status'] != "OK") {

		$response['status'] = "location not found";

	} else {

		$response['data'] = ["lat" => $address_obj['lat'],
							 "lon" => $address_obj['lon'],
							 "address" => $address_obj['address']];

		$response['status'] = "OK";

	}

} else {

	$response['status'] = "no address defined";

}


echo json_encode($response);

?>