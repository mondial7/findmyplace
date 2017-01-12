<?php	

/**
 * Dump Router class
 * Evaluate uri and load the right controller with a switch based on path segments
 *
 */
class Dump_Router {
	
	/**
	 * Controllers dir
	 */
	private static $controllers_dir = "./app/controllers/";

	/**
	 * Default controller file extension
	 */
	private static $controllers_ext = ".php";

	/**
	 * Set here a deafult controller to load in case of errors
	 */
	private static $default_controller = "404.php";
	private static $default_error_message = "Ops, nothing is here!";

	/**
	 * Declare an empty array "routes" to store all the routes paths
	 */
	private static $routes = [];

	/**
	 * Declare an empty array "no routes" to store paths that require normal behaviour
	 */
	private static $no_routes = [];

	/**
	 * Set default controller
	 */
	public static function setDefaultController($value){
		self::$default_controller = $value;
	}

	/**
	 * Set default controller
	 */
	public static function setControllersExtension($value){
		self::$controllers_ext = $value;
	}

	/**
	 * Set paths that require normal behaviour
	 */
	public static function noRoute($path_segment){
		self::$no_routes[] = $path_segment;
	}

	/**
	 * Add new route to the collection
	 */
	public static function route($path_segment, $route_data = null) {
		// When route_data is not specified, 
	    // use the same path_segment as controller name
	    if ($route_data === null) {
	    	$route_data = ['controller' => $path_segment];
	    } else if (!isset($route_data['controller'])){
	    	$route_data['controller'] = $path_segment;	
	    }
		// Store the new route
		self::$routes[$path_segment] = $route_data;
	}

	/**
	 * Set multiple simple route
	 */
	public static function manyRoute($routes_arr) {
		foreach ($routes_arr as $route) {
			self::route($route);
		}
	}

	/**
	 * Evaluate URI and include the right controller
	 *
	 * @return void
	 *
	 */
	public static function render($uri, $controllers_dir = null) {
		
		// Require the controller
		require_once self::loadController($uri, $controllers_dir);

	}

	/**
	 * Load the controller
	 *
	 * @return string controller
	 *
	 */
	public static function loadController($uri, $controllers_dir = null) {

		// Set the controllers dir
		if ($controllers_dir === null) {
			$controllers_dir = self::$controllers_dir;
		}

		// @string uri to @array with 'path' and 'parameters'
		$uri = self::parseUriPath($uri);
		// @array of path segments
		$path = self::parseUriSegments($uri['path']);

		$controller_name = null;
		$file_not_found__controller = $controllers_dir.self::$default_controller;

		// Check if normal behaviour is mandatory
		foreach (self::$no_routes as $std_path) {
			if ($path[0] == $std_path){

				$target =  implode("/", $path);

				if ( file_exists($target) && !is_dir($target)) {
					return $target;
				} else {
					return $file_not_found__controller;
				}

				exit;

			}
		}

		// Check for path/route match
		foreach (self::$routes as $route_segment => $route_data) {

			if ($path[0] == $route_segment) {
				
				$controller_name = $route_data['controller'];
				// Set parameters in the $_GET array
				self::setGetParameters($uri['parameters']);
				// Set extra get parameters --> handle pretty url like '/category/product/'
				if (isset($route_data['pretty_parameters']) && 
					$route_data['pretty_parameters'] !== null) {
					self::setExtraGetParameters($route_data['pretty_parameters'], $path);
				}
				// Exit the loop
				break;

			}

		}

		$controller = $controllers_dir.$controller_name.self::$controllers_ext;

		// Default controller if none has been match
		// or if controller file not exists
		if (empty($controller_name) || !file_exists($controller)) {
			$controller = $file_not_found__controller;
		}

		return $controller;

	}

	/**
	 * Parse URI
	 * return array with path and optional get parameters
	 */
	private static function parseUriPath($uri) {

		// Get the base of the uri
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		// Remove base from uri string
		$uri = substr($uri, strlen($basepath));
		// Save optional get parameters
		if (strstr($uri, '?')){
			$uri_paramters = substr($uri, strpos($uri, '?')+1);
			// Remove parameters from uri
			$uri = substr($uri, 0, strpos($uri, '?'));
		} else {
			$uri_paramters = "";
		}
		// Clean the uri path (usefull for parseUriSegments)
		$uri_path = '/' . trim($uri, '/');

		return ['path' => $uri_path, 'parameters' => $uri_paramters];

	}

	/**
	 * Return an array with uri segments
	 */
	private static function parseUriSegments($uri) {
		
		$segments = explode('/', $uri);
		$clened_segments = [];

		// Clean up empty segments
		foreach ($segments as $segment) {
			if (trim($segment) != "") {
				$clened_segments[] = $segment;
			}
		}

		// Normalize path segment for home/landing page (http://yourwebsite.com/)
		if (count($clened_segments) === 0) {
			$clened_segments[0] = "/";
		}

		return $clened_segments;

	}

	/**
	 * Parse get parameters string and store in the $_GET array
	 */
	private static function setGetParameters($parameters_str){
		
		$parameters_arr = explode("&", $parameters_str);

		foreach ($parameters_arr as $parameter_str) {
			
			$parameter_arr = explode("=", $parameter_str);

			$_GET[$parameter_arr[0]] = isset($parameter_arr[1]) ? $parameter_arr[1] : "";

		}

	}

	/**
	 * Handle Pretty urls
	 * Parse segments of the uri path after the first
	 * and convert them in get parameters as define in the route
	 */
	private static function setExtraGetParameters($parameters_arr, $path_arr){

		// Remove first element from array
		array_shift($path_arr);

		// Count numbers of parameters
		$parameters_count = count($parameters_arr);
		$path_count = count($path_arr);

		// Loop and set $_GET parameters
		for($i = 0; $i < $parameters_count; $i++) {
			$_GET[$parameters_arr[$i]] = ($i<$path_count && !empty($path_arr[$i])) ? $path_arr[$i] : "";
		}

	}

}

?>