<?php

    // Set menu items

    $menu = [];
    $submenu = [];

    if ($userLogged) {


        $menu = [

            [
                "label" => "Home",
                "link" => ""
            ],
            [
                "label" => "Map",
                "link" => "places/"
            ], 
            [
                "label" => "About",
                "link" => "about/"
            ], /*
            [
                "label" => "Contact",
                "link" => "contact/"
            ],
            [
                "label" => "Search",
                "link" => "#",
                "link_class" => "search_trigger_button"
            ],
            [
                "label" => "Settings",
                "link" => "settings/"
            ], */
            [
                "label" => "Logout",
                "link" => "logout/?s=" . $_SERVER['REQUEST_URI']
            ]

        ];


        /*
        $submenu = [
            
            [
                "label" => "Settings",
                "link" => "settings/"
            ],
            [
                "label" => "Logout",
                "link" => "logout/"
            ]

        ];
        */

    } else {

        $menu = [

            [
                "label" => "Home",
                "link" => ""
            ],
            [
                "label" => "Map",
                "link" => "places/"
            ],
            [
                "label" => "About",
                "link" => "about/"
            ], /*
            [
                "label" => "Contact",
                "link" => "contact/"
            ], 
            [
                "label" => "Search",
                "link" => "#",
                "link_class" => "search_trigger_button"
            ], */
            [
                "label" => "Login",
                "link" => "login/?s=" . $_SERVER['REQUEST_URI']
            ],
            [
                "label" => "Register",
                "link" => "register/"
            ]

        ];

        /*
        $submenu = [
            
            [
                "label" => "Login",
                "link" => "login/"
            ],
            [
                "label" => "Register",
                "link" => "register/"
            ]

        ];
        */

    }

    // Set the active menu item
    // To be improved (e.g. do check on the links)
    if (isset($currentPage)) {
        for ($i=0, $l=count($menu); $i < $l; $i++) { 
            $page = explode("/", strtolower($menu[$i]['link']))[0];
            if ($currentPage != "") {
                $page = $page . "/";
            }
            if ($page == $currentPage) {
                $menu[$i]["active_class"] = "menu_link--active";
            }
        }
    }


    // Add variables to template
    $template_variables['menu'] = $menu;
    $template_variables['submenu'] = $submenu;

    
    if ( $_SERVER['HTTP_HOST'] === "localhost" ) {
        $template_variables['website_url'] = "http://localhost/find-my-place/";
    } else {
        $template_variables['website_url'] = "https://thatsmy.name/findmyplace/";
    }

?>