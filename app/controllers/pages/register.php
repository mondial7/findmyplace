<?php
    

    if($userLogged){
        header("Location: ../");
        exit;
    }


    $currentPage = "register/";
    $page_title = "Find My Place - Register";
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


    if (isset($_POST['submit'])){

        // Include register function
        include $CONTROLLERS_DIR . '/register.php';
        $registration_ = register($_POST['email'], 
                                  $_POST['username'], 
                                  $_POST['password'], 
                                  $_POST['password_check']);

        if ($registration_["status"]) {

            // automatic login
            include_once $CONTROLLERS_DIR . '/login.php';
            if (login($_POST['email'], $_POST['password'])) {

                // Redirect to home page
                header("Location: ../");
                // Client redirect if header fails
                echo "<script>window.location='../'</script>";
 
            }

        } else {

            $template_variables['error_message'] = $registration_['error_message'];
            $template_variables['placeholder'] = $registration_['data'];

        }

    }

    // Include header and footer controllers
    include 'page__common.php';

    // Set template name and variables
    
    $template_file = "register.twig";

    $template_variables['page_title'] = $page_title;
    $template_variables['metatags'] = $metatags;
    $template_variables['footer_scripts'] = $footer_scripts;

    // Render the template
    require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
    Twig_Loader::show($template_file, $template_variables);

?>