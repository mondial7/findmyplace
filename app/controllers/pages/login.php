<?php
    
    
    if($userLogged){
        header("Location: ../");
        exit;
    }


    $currentPage = "login/";
    $page_title = "Find My Place - Login";
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

    $login_data = ["email"=>"", "password"=>""];


    if (isset($_POST['login'])){

        /**
          * Target page to load after successful login
          * @todo add check for whitelist targets
          */
        if (isset($_GET['s']) &&
            ($target = filter_var($_GET['s'], FILTER_SANITIZE_URL))) {
            // nothing here
        } else {
            $target = "../";
        }

        // Include login function
        require_once $CONTROLLERS_DIR . '/login.php';

        $isPermaLogin = isset($_POST['permalogin']) && $_POST['permalogin'] === "y";

        $loginOk = login($_POST['email'], $_POST['password']);
        
        if ($loginOk) {

            header("Location: " . $target);
            // Client redirect if header fails
            echo "<script>window.location='" . $target . "'</script>";
        
        }

        $resetOk = true;

    } else if (isset($_POST['reset_password'])){

        $login_email = $_POST['email'];

        include $CONTROLLERS_DIR . '/reset_password.php';

        $loginOk = true;

    } else {

        // initialize variable to prevent to show the error message
        $loginOk = true;
        $resetOk = true;
        
    }

    // Include header and footer controllers
    include 'page__common.php';

    // Set template name and variables
    
    $template_file = "login.twig";

    $template_variables['page_title'] = $page_title;
    $template_variables['metatags'] = $metatags;
    $template_variables['footer_scripts'] = $footer_scripts;

    $template_variables['reset_password'] = isset($_GET['reset']);
    $template_variables['reset_password_success'] = isset($_GET['reset_done']);
    $template_variables['resetOk'] = $resetOk;
    $template_variables['loginOk'] = $loginOk;
    $template_variables['login_data'] = $login_data;


    // Render the template
    require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
    Twig_Loader::show($template_file, $template_variables);

?>