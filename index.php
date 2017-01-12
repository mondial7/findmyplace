<?php
 
    /**
      * Prevent showing errors and warnings
      * (uncomment when developing)
      */
    //error_reporting(0);


    /**
     * Load application core
     */
    require __DIR__ . '/app/core/core_loader.php';
    $core_scripts = (new CoreLoader())->getPaths();
    foreach ($core_scripts as $path) {
        include $path;
    }
    

    /**
     * Handle user sessions
     */
    require __DIR__ . '/app/controllers/session.php';


    /**
     *
     * Declare routes
     *
     */
    Dump_Router::route('/',[
        'controller' => "landing"
    ]);
    Dump_Router::manyRoute(['places', 'projects', 
                            'about', 'contact',
                            'logout', 'login', 'register']);


    /**
     * Places controller is accessible
     * from both '/map/' and '/places/'
     */
    Dump_Router::route('map',[
        'controller' => "places"
    ]);


    /**
     *
     * Declare routes where router will not apply
     *
     */
    Dump_Router::noRoute('app');
    Dump_Router::noRoute('public');
    

    /**
     *
     * Trigger the router and evaluate the uri path
     *
     */
    require Dump_Router::loadController($_SERVER['REQUEST_URI'], 
                                        "./app/controllers/pages/");

?>