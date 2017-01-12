<?php

	require_once dirname(__DIR__) . '/vendor/Twig/Autoloader.php';

	/**
	 * Render Twig Template
	 * 
	 * 
	 * @template_variables, @template_file are initialized in each view
	 *  
	 * @template_variables has a default value of []
	 * @template_dir has a default value "../templates/"
	*/
	class Twig_Loader
	{
		
		private static $template_variables = [];
		private static $template_dir = null;
		private static $twig;
		private static $load = false;
		private static $template = null;

		public static function setDir($template_dir){
			self::$template_dir = $template_dir;
		}

		public static function setVar($template_variables){
			self::$template_variables = $template_variables;
		}

		/**
		 * Load twig and set default options
		 */
		public static function load(){

			require_once dirname(__DIR__) . '/vendor/Twig/Autoloader.php';
			Twig_autoloader::register();
			
			if (self::$template_dir == null) {
				self::$template_dir = dirname(__DIR__) . "/templates/";
			}

			self::init();

			self::$load = true;

		}

		/**
		 * @template_variables, @template_dir are optional
		 * 
		 * Return the redered template
		 */
		public static function render($template_file, $template_variables = null){

			// Trigger load if not done yet
			if(self::$load == false){
				self::load();
			}

			if(empty($template_file)){
				return;
			}

			if(!empty($template_variables)){
				self::setVar($template_variables);
			}

			self::$template = self::$twig->render($template_file, self::$template_variables);	
		
		}

		/**
		 * Print rendered template
		 */
		public static function show($template_file = null, $template_variables = null){
			if(self::$template == null){
				self::render($template_file, $template_variables);
			}

			echo self::$template;
		}

		/**
		 * Return rendered template
		 */
		public static function getTemplate($template_file = null, $template_variables = null){
			if(self::$template == null){
				self::render($template_file, $template_variables);
			}

			return self::$template;
		}

		/**
		 * Instanziate twig with current options
		 */		
		private static function init(){
			require_once dirname(__DIR__) . '/vendor/Twig/Autoloader.php';
			$loader = new Twig_Loader_Filesystem(self::$template_dir);
			self::$twig = new Twig_Environment($loader);
		}
	}

?>