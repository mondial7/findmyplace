<?php

// Declare response
$response = [];


if (!$userLogged) {

	$response['status'] = "denied";

} else {

	$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : NULL;
	
	if (is_null($key)) {

		$response['status'] = "wrong parameters";
		$response['log'] = true;

	} else {

		// Sanitaze inputs
		$key = htmlentities($_REQUEST['key'], ENT_QUOTES, 'utf-8');

		// Instanziate place classes
		require_once $MODELS_DIR . "/Place.php";
		require_once $MODELS_DIR . "/PlacesManager.php";
		$place = new Place();
		$placeManager = new PlacesManager();

		// Switch between options
		switch ($key) {

			case 'add':

				$parameters = ['address','status','ownership','about'];

				if (!areset($parameters)) {
					$response['status'] = "wrong parameters";
					break;
				}
				
				// Validate and prepare inputs
				$address_obj = $placeManager->prettifyAddress($_REQUEST['address']);
				if ($address_obj['status'] != "OK") {
					$response['status'] = "location not found";
					break;
				}
				if ($placeManager->addressExists($address_obj['address'])) {
					$response['status'] = "This place was already marked";
					break;
				}
				$place->setAccount($_SESSION['id']);
				$place->setAddress($address_obj["address"]);
				$place->setLatitude($address_obj["lat"]);
				$place->setLongitude($address_obj["lon"]);
				$place->setAbout($_REQUEST['about']);
				$place->setOwnership($_REQUEST['ownership']);
				$place->setStatus($_REQUEST['status']);

				// Save new place
				if ($place->isValid() && $placeManager->add($place)) {
					
					$response['status'] = "OK";
					// fill response with place data
					$addr = $address_obj["address"];
					$is_address = true;
					$response['data'] = $placeManager->getPlaceDetails($addr, $is_address);

				} else {
					
					$response['status'] = "Error, please try again later.";
					// here shoud notify developers with mail or a log system
				
				}

				break;
			
			case 'remove':
			
				if (!isset($_REQUEST['id'])) {
					$response['status'] = "wrong parameters";
					break;
				}

				$place->setId($_REQUEST['id']);

				if (!$placeManager->isOwner($_REQUEST['id'])) {
					$response['status'] = "you cannot remove this";
					break;
				}

				if ($place->isValid() && $placeManager->remove($place)) {

					$response['status'] = "OK";
					
				} else {

					$response['status'] = "Error, please try again later.";
					// here shoud notify developers with mail or a log system

				}

				break;

			default:
				$response['status'] = "wrong key";
				break;

		}

	}

}

echo json_encode($response);

?>