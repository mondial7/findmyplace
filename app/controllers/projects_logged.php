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

		// Instanziate project classes
		require_once $MODELS_DIR . "/Project.php";
		require_once $MODELS_DIR . "/ProjectsManager.php";
		$project = new Project();
		$projectManager = new ProjectsManager();

		// Switch between options
		switch ($key) {

			case 'add':

				$parameters = ['about','place_id'];

				if (!areset($parameters) || !isset($_REQUEST['title'])) {
					$response['status'] = "wrong parameters";
					break;
				}
				
				// Validate and prepare inputs
				$project->setAccount($_SESSION['id']);
				$project->setTitle($_REQUEST['title']);
				$project->setAbout($_REQUEST['about']);
				$project->setPlaceId($_REQUEST['place_id']);
				
				// Save project
				$p_id = $projectManager->add($project);

				// Save new project
				if ($project->isValid() && $p_id != false) {
					
					$project->setId($p_id);

					$response['status'] = "OK";
					$response['data'] = $project->toArray();

				} else {
					
					$response['status'] = "Error, please try again later.";
					// here shoud notify developers with mail or a log system
				
				}

				break;
			
			case 'remove':
			
				$parameters = ['id','place_id'];

				if (!areset($parameters)) {
					$response['status'] = "wrong parameters";
					break;
				}

				$project->setId($_REQUEST['id']);
				$project->setPlaceId($_REQUEST['place_id']);

				if (!$projectManager->isOwner($project->getPlaceId(), $project->getId())) {
					$response['status'] = "you cannot remove this";
					break;
				}

				if ($projectManager->remove($project)) {

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