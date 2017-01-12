<?php


/**
 * Init response result
 */
$response = ["type" => "json", "status" => "OK"];

if (isset($_REQUEST['place_id'])) {

	/**
	  * Instanziate project classes
	  */
	require_once $MODELS_DIR . "/ProjectsManager.php";
	$projectsManager = new ProjectsManager();

	/**
	 * Get projects
	 */
	if (isset($_REQUEST['limit'])) {

		$projects = $projectsManager->getProjects($_REQUEST['place_id'], $_REQUEST['limit']);

	} else {

		$projects = $projectsManager->getProjects($_REQUEST['place_id']);

	}

	/**
	 * Handle response type, defualt is json
	 */
	if (isset($_REQUEST['html'])) {

		$template_variables['projects'] = $projects;
		$template_file = "places__projects.twig";

		// Render the template and exit printing out the html
		require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
		Twig_Loader::setDir($VIEWS_DIR . "/places/");
		exit(Twig_Loader::getTemplate($template_file, $template_variables));

	} else {
		
		$response['data'] = $projects;

	}

} else {

	$response['status'] = "bad request";

}

/**
 * Print result
 */
echo json_encode($response);

?>