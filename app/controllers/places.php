<?php

$response = ["type" => "json"];

// Instanziate place classes
require_once $MODELS_DIR . "/PlacesManager.php";
$placeManager = new PlacesManager();

if (isset($_REQUEST['id'])) {

	$response['data'] = $placeManager->getPlaceDetails($_REQUEST['id']);	

} else {

	if (isset($_REQUEST['limit'])) {
	
		$places = $placeManager->getPlaces($_REQUEST['limit']);
	
	} else {
	
		$places = $placeManager->getPlaces();
	
	}

	if (isset($_REQUEST['html'])) {

		$template_variables['places'] = $places;
		$template_file = "places__card.twig";

		// Render the template
		require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
		Twig_Loader::setDir($VIEWS_DIR . "/places/");
		exit(Twig_Loader::getTemplate($template_file, $template_variables));

	} else if (isset($_REQUEST['html_map'])) {

		include $CONTROLLERS_DIR . '/map_config.php';
		$template_variables['places'] = $places;
		$template_variables['is_async'] = true;
		$template_file = "places__map.twig";

		// Render the template
		require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
		Twig_Loader::setDir($VIEWS_DIR . "/places/");
		exit(Twig_Loader::getTemplate($template_file, $template_variables));

	} else {

		$response['data'] = $places;

	}

}

// Print result
echo json_encode($response);

?>