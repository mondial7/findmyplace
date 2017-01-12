<?php

	$currentPage = "places/";
	$page_title = "Find My Place - Map";
	$metatags = [
					[
						"kind" => "link",
						"type" => "text/css",
						"rel"  => "stylesheet",
						"href" => "app/assets/css/places.css"
					]
				];
	$footer_scripts = ["app/assets/js/oldwheel.js",
					   "app/assets/js/findmyplace.js"];
	
	$head_extra_script = [["link" => "app/assets/vendor/bower_components/webcomponentsjs/webcomponents-lite.min.js",
	                       "async" => false]];
	$import_extra_html = ["app/assets/vendor/bower_components/google-map/google-map.html"];

	include $CONTROLLERS_DIR . '/map_config.php';

	/**
	 * Load places
	 */
	require_once $MODELS_DIR . "/PlacesManager.php";
	$placesManager = new PlacesManager();
	$template_variables['places'] = $placesManager->getPlaces();



	// Include header and footer controllers
	include 'page__common.php';

	// Set template name and variables
	
	$template_file = "places.twig";

	$template_variables['page_title'] = $page_title;
	$template_variables['metatags'] = $metatags;
	$template_variables['footer_scripts'] = $footer_scripts;

	$template_variables['head_extra_script'] = $head_extra_script;
	$template_variables['import_extra_html'] = $import_extra_html;


    // Render the template
    require_once dirname( __DIR__ ) . '/Twig_Loader.php';
    Twig_Loader::show($template_file, $template_variables);

?>