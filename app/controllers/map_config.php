<?php

	
	$map_styles = '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"},{"gamma":"1.00"}]},{"featureType":"administrative.locality","elementType":"labels","stylers":[{"color":"#ba5858"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"color":"#e57878"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"visibility":"simplified"},{"lightness":"65"},{"saturation":"-100"},{"hue":"#ff0000"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"simplified"},{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway.controlled_access","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#eeeeee"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#ba5858"},{"saturation":"-100"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#ba5858"},{"visibility":"simplified"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"hue":"#ff0036"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#ba5858"}]}]';


	/**
	 * Set current location base on ip
	 * uses http://ipinfo.io
	 * see: http://stackoverflow.com/a/17864552/5124589
	 */
	if(in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])){
    	$ip = "";
	} else {
		$ip = $_SERVER['REMOTE_ADDR'] . "/";
	}
	$location_details = json_decode(file_get_contents("http://ipinfo.io/" . $ip . "geo"));
	if (!is_null($location_details->loc)) {

		$loc = explode(",", $location_details->loc);
		$latitude = $loc[0];
		$longitude = $loc[1];

	} else {

		// Default location -> bolzano
		$latitude = "46.49651354744985";
		$longitude = "11.35669744072267";

	}



	$MAP = ["latitude" => $latitude, "longitude" => $longitude,
          "zoom" => "14", "api" => "AIzaSyDqlyUahsoMkv2R_TIqOvJYv0jGhQ-7aV8",
          "style" => $map_styles];

	$template_variables['map'] = $MAP;

?>