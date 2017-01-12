<?php

/**
 * Account Model
 *
 */
class Account extends Model {
 
    /**
     * Account properties : string
     */
    private $email,
            $username,
            $password,
            $fname,
            $lname,
            $about;
    
    /**
     * Account properties : int
     */
    private $role;

    /**
     * Validation : boolean
     */
    private $valid = true; 

    /**
     * Validate, sanitize and set the account email and validate
     *
     * @param string
     * @return void
     *
     */
    public function setEmail($email) {

        if (!($this->email = filter_var($email,  FILTER_VALIDATE_EMAIL))) {

            $this->email = filter_var($email,  FILTER_SANITIZE_EMAIL);
            $this->valid = false;

        }

    }

    /**
     * Sanitize and set the account username and sanitize
     *
     * @param string
     * @return void
     *
     */
    public function setUsername($username) {

        $this->username = htmlspecialchars(strip_tags($username), ENT_QUOTES, 'utf-8');

        if (empty(trim($this->username))) {
            $this->valid = false;
        }

    }

    /**
     * Set the account password
     * no need to sanitize or validate since we
     * are going to hash it
     *
     * @param string
     * @return void
     *
     */
    public function setPassword($password) {
      
        $this->password = md5($password);

    } 

    /**
     * Set the account name
     *
     * @param string
     * @param string
     * @return void
     *
     */
    public function setName($first, $last) {

        $this->setFisrtName($first);
        $this->setLastName($last);

    }


    /**
     * Sanitize and set the account first name and sanitize
     *
     * @param string
     * @return void
     *
     */
    public function setFirstName($first) {
          
        $this->fname = htmlspecialchars(strip_tags($first), ENT_QUOTES, 'utf-8');

    }

    /**
     * Sanitize and set the account last name
     *
     * @param string
     * @return void
     *
     */
    public function setLasttName($last) {
          
        $this->lname = htmlspecialchars(strip_tags($last), ENT_QUOTES, 'utf-8');

    }

    /**
     * Sanitize and set the account about description
     *
     * @param string
     * @return void
     *
     */
    public function setAbout($about) {

        $this->about = htmlspecialchars(strip_tags($about), ENT_QUOTES, 'utf-8');

    }

    /**
     * Validate, sanitize and set the account role
     *
     * @param int
     * @return void
     *
     */
    public function setRole($role) {

        if (!($this->role = filter_var($role,  FILTER_VALIDATE_INT))) {

            $this->role = filter_var($role,  FILTER_SANITIZE_NUMBER_INT);
            $this->valid = false;

        }

    }

    /**
     * Get the account email
     *
     * @return string email
     *
     */
    public function getEmail() {

        return $this->email;

    }

    /**
     * Get the account username
     *
     * @return string username
     *
     */
    public function getUsername() {

        return $this->username;

    }

    /**
     * Get the account password
     *
     * @return string password
     *
     */
    public function getPassword() {

        return $this->password;

    }

    /**
     * Get the account name
     *
     * @return string name
     *
     */
    public function getName() {

        return $this->fname . " " . $this->lname;

    }

    /**
     * Get the account first name
     *
     * @return string first name
     *
     */
    public function getFirstName() {

        return $this->fname;

    }

    /**
     * Get the account last name
     *
     * @return string last name
     *
     */
    public function getLastName() {

        return $this->lname;

    }

    /**
     * Get the account about
     *
     * @return string about
     *
     */
    public function getAbout() {

        return $this->about;

    }

    /**
     * Get the account role
     *
     * @return string role
     *
     */
    public function getRole() {

        return $this->role;

    }

    /**
     * Get the account validation result
     *
     * @return boolean valid
     *
     */
    public function isValid() {

        return $this->valid;

    }    

}

?>