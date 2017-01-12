<?php

/**
 * Project Model
 *
 */
class Project extends Model {
 
    /**
     * Project properties 
     * @var string
     */
    private $title,
            $about;
    
    /**
     * Project properties 
     * @var int
     */
    private $account,
            $id,
            $place_id;

    /**
     * Validation 
     * @var boolean
     */
    private $valid = true; 

    /**
     * Sanitize and set the Project title
     *
     * @param string
     * @return void
     *
     */
    public function setTitle($title) {

        $this->title = htmlspecialchars(strip_tags($title), ENT_QUOTES, 'utf-8');

    }

    /**
     * Sanitize and set the Project about
     *
     * @param string
     * @return void
     *
     */
    public function setAbout($about) {

        $this->about = htmlspecialchars(strip_tags($about, "<br>"), ENT_QUOTES, 'utf-8');

        if (empty(trim($this->about))) {
            $this->valid = false;
        }

    }

    /**
     * Set the account
     *
     * @param int
     * @return void
     *
     */
    public function setAccount($account) {

        if (!($this->account = filter_var($account,  FILTER_VALIDATE_INT))) {

            $this->account = filter_var($account,  FILTER_SANITIZE_NUMBER_INT);
            $this->valid = false;

        }

    }

    /**
     * Set Project place_id
     *
     * @param int
     * @return void
     *
     */
    public function setPlaceId($place_id) {

        if (!($this->place_id = filter_var($place_id,  FILTER_VALIDATE_INT))) {

            $this->place_id = filter_var($place_id,  FILTER_SANITIZE_NUMBER_INT);
            $this->valid = false;

        }

    }

    /**
     * Set Project id
     *
     * @param int
     * @return void
     *
     */
    public function setId($id) {

        if (!($this->id = filter_var($id,  FILTER_VALIDATE_INT))) {

            $this->id = filter_var($id,  FILTER_SANITIZE_NUMBER_INT);
            $this->valid = false;

        }

    }
    
    /**
     * Get the Project id
     *
     * @return int id
     *
     */
    public function getId() {

        return $this->id;

    }

    /**
     * Get the Project place_id
     *
     * @return int place_id
     *
     */
    public function getPlaceId() {

        return $this->place_id;

    }

    /**
     * Get the Project title
     *
     * @return string title
     *
     */
    public function getTitle() {

        return $this->title;

    }
    
    /**
     * Get the Project about
     *
     * @return string about
     *
     */
    public function getAbout() {

        return $this->about;

    }
    
    /**
     * Get the account
     *
     * @return string account
     *
     */
    public function getAccount() {

        return $this->account;

    }

    /**
     * Get the Project validation result
     *
     * @return boolean valid
     *
     */
    public function isValid() {

        return $this->valid;

    }

    /**
     * Get the Project data as array
     *
     * @return array
     *
     */
    public function toArray() {

        return [ "id" => $this->id,
                 "account" => $this->account,
                 "place_id" => $this->place_id,
                 "title" => $this->title,
                 "about" => $this->about,
                 "valid" => $this->valid ];

    }    

}

?>