<?php

/**
 * 
 * Constant to save the current core directory
 *
 */
define("CORE_DIRECTORY", __DIR__);

/**
 * Load application core ( Configurations, Abstract Models, etc. )
 *
 * List of file included { Classes }:
 *
 * config.php
 * controller.php
 * controller_page.php
 * model.php { Model } 
 * dump_router.php { Dump_Router }
 *
 */
Class CoreLoader {

	/**
	 * List of file to include
	 */
	private $paths = [];

	/**
	 * Loas the paths on instatiazion of the class
	 */
	function __construct() {
    	
    	$this->load();

    }

	/**
	 * 
	 * @return string[] list of paths to include
	 *
	 */
	public function getPaths(){
		
		return $this->paths;

	}

	/**
	 * Init the loading
	 *
	 * @return string[] list of paths to include
	 *
	 */

	/**
	 * Load paths array with good paths to include
	 * Find only files in a first level subfolder
	 *
	 * @param string core directory path
	 * @return int number of files to include
	 *
	 */
	private function load(){

		$autoload_dir = scandir( CORE_DIRECTORY );
		$i = 0;

		foreach ($autoload_dir as $value) {

			$path = CORE_DIRECTORY . "/" . $value;

			if ($this->is_good_to_load($path)) {

				$autoload_subdir = scandir( $path );

				foreach ($autoload_subdir as $value) {
					
					$file_path = $path . "/" . $value;
					
					if ($this->is_good_to_load($file_path, true)) {

						$this->paths[] = $file_path;
						$i++;

					}

				}

			}

		}

		return $i;

	}

	/**
	 * Check if is a good directory to be scanned and
	 * file to be loaded.
	 *
	 * @param string directory
	 * @param boolean optional switch between dir or file check
	 * @return boolean 
	 *
	 */
	private function is_good_to_load( $path, $check_file = false ){

		if ($check_file) {
			$check = file_exists($path);
		} else {
			$check = is_dir($path);
		}

		$name = basename($path);

		return ($check && $name != "." && $name != "..");

	}

}

?>