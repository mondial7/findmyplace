<?php

	$currentPage = "404";
	$page_title = "Find My Place - 404";
	$metatags = [
					[
						"kind" => "link",
						"type" => "text/css",
						"rel"  => "stylesheet",
						"href" => "app/assets/css/base.css"
					]
				];
	$footer_scripts = ["app/assets/js/oldwheel.js",
					   "app/assets/js/findmyplace.js"];


	// Include header and footer controllers
	include 'page__common.php';

	// Set template name and variables
	
	$template_file = "404.twig";

	$template_variables['page_title'] = $page_title;
	$template_variables['metatags'] = $metatags;
	$template_variables['footer_scripts'] = $footer_scripts;

    // Render the template
    require_once dirname( __DIR__ ) . '/Twig_Loader.php';
    Twig_Loader::show($template_file, $template_variables);

?>