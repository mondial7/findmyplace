<?php

class Credentials extends Model {
 
    /**
     * Name of the cookie for the 'stay logged in'
     */
    private $permalogin = "permalog";

    function __construct() {
        
        parent::__construct();

        // Declare database connection
        $this->connectDB();

    } 
 
    /**
     * Evaluate login
     *
     * @param Account account to log in
     * @return array account data
     *
     */
    public function login(Account $user) {

        $result = [];
        $email = $user->getEmail();
        $password = $user->getPassword();

        $query = "SELECT id, about, avatar, username, 
                         email, firstname, lastname, role 
                  FROM " . _T_ACCOUNT . " 
                  WHERE email = ?
                  AND password = ? ";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('ss', $email, $password);
            $stmt->execute();

            $db_result = $stmt->get_result();

            // query result is ok if only one match is found (one account)
            if($db_result && $db_result->num_rows == 1){

                $result = $db_result->fetch_assoc();

            }

            $stmt->close();
        
        }

        // No account found
        // Return an empty array
        return $result;

    }

    /**
     * Register new user
     *
     * @param Account new account to register
     * @return boolean status of registration
     *
     */
    public function register(Account $user){

        $result = false;
        $email = $user->getEmail();
        $username = $user->getUsername();
        $password = $user->getPassword();

        $query = "INSERT INTO "._T_ACCOUNT." (email, username, password)
                  VALUES ( ? , ? , ? );";
        
        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('sss', $email, $username, $password);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){

                $result = true;
                
            }

            $stmt->close();
        
        }

        return $result;
    
    }


    /**
     * Check if email already exists
     */
    public function emailExists($email){

        $query = "SELECT id FROM "._T_ACCOUNT." WHERE email = ? ;";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows == 1){

                return true;                

            }

            $stmt->close();
        
        }

        return false;

    }


    /**
     * Check if username already exists
     */
    public function usernameExists($username){

        $query = "SELECT id FROM "._T_ACCOUNT." WHERE username = ? ;";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows == 1){

                return true;                

            }

            $stmt->close();
        
        }

        return false;

    }






    /* END (temporary) */

    /* ******************************************************************************************************* */







    /**
     * Autologin through cookie
     *
     * @return boolean with user logged status
     *
     */
    /*
    public function autologin(){
        
        $userLogged = false;

        // Get required_logged_id cookie
        $cookie_token = isset($_COOKIE[$this->permalogin]) ? $_COOKIE[$this->permalogin] : null;

        if( $cookie_token != null ){

            $query = "SELECT l.account_id 
                      FROM "._T_ACCOUNT_LOGGED." AS l 
                      WHERE l.cookie_token='" . $cookie_token . "';";

            if( $result = $this->db->query($query) ){
                  
                $result = $result->fetch_assoc();

                // AUTOMATIC LOG IN

                // Automatically log in the the account
                $query = "SELECT id, about, avatar, username, email, firstname, lastname, role 
                          FROM "._T_ACCOUNT." 
                          WHERE id='" . $result['account_id'] . "'";

                $result = $this->db->query($query);

                // query result is ok if only one match is found (one account)
                if( $result && $result->num_rows == 1 ){

                    $account_data = $result->fetch_assoc();

                    // Set session data
                    foreach ($account_data as $key => $value) { $_SESSION[$key] = $value; }

                    $userLogged = isset($_SESSION['username']);

                }

            }

        }

        return $userLogged;

    }
    */

    /**
     * Set Permanent Login
     */
    /*
    public function setPermaLogin($account_id) {

        $cookie_token = $this->generateCookieToken($account_id);

        if($this->issetPermaLogin($cookie_token)){

            // Cookie is already present, maybe the user deleted the cookie on the browser
            return $cookie_token;

        } else {

            $query = "INSERT INTO "._T_ACCOUNT_LOGGED." (account_id, cookie_token)
                      VALUES ('" . $account_id . "','" . $cookie_token . "');";

        }

        $result = $this->conn->query($query);

        if($this->conn->affected_rows != 1){

            // Reset cookie token
            $cookie_token = "";

        }
      
        return $cookie_token;

    }
    */

    /**
     * Check if user permalogin cookie is already in the db
     * Return if the perma login is already set or not
     */
    /*
    public function issetPermaLogin($cookie_token) {
      
        $query = "SELECT account_id
                FROM "._T_ACCOUNT_LOGGED."
                WHERE cookie_token='" . $cookie_token . "';";

        $result = $this->conn->query($query);

        if($result && $result->num_rows == 1 ){
            
            return true;

        }

        return false;

    }
    */


    /**
     * Generate a new cookie token
     * temporary the cookie is generated as the hash
     * of the account id, a default "key" and user_agent
     */
    /*
    private function generateCookieToken($account_id) {

        return md5( $account_id . "findmyplace" . $_SERVER['HTTP_USER_AGENT'] );

    }
    */


    /**
     * Reset Password
     */
    /*
    public function reset_password(){
    
        if(!$this->emailExists()){
            return false;
        }

        $new_password = $this->generateNewPassword();

        $query = "UPDATE "._T_ACCOUNT." 
                  SET password='".$new_password."'
                  WHERE email='".$this->email."';";

        $result = $this->conn->query($query);

        if($this->conn->affected_rows == 1){

            mail($this->email,
                "New Password - findmyplace",
                "We have successfully reset the password please\n\n".$idea_link,
                "From: findmyplace - Lean Startup <info@findmyplace.com>");

            return true;
        
        }

        return false;

    }   
    */

    /**
      * Generate a temporary new password
      */ 
    /*
    public function generateNewPassword(){
        return md5("findmyplace");
    }*/

}

?>