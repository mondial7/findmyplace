<?php

/**
 * Place Model
 *
 */
class Place extends Model {
 
    /**
     * Place properties 
     * @var string
     */
    private $address,
            $about;
    
    /**
     * Place properties 
     * @var int
     */
    private $ownership,
            $status,
            $account,
            $id;

    /**
     * Place properties 
     * @var double
     */
    private $latitude,
            $longitude;

    /**
     * Validation
     * @var boolean
     */
    private $valid = true; 

    /**
     * Sanitize and set the Place address
     *
     * @param string
     * @return void
     *
     */
    public function setAddress($address) {

        $this->address = htmlspecialchars(strip_tags($address), ENT_QUOTES, 'utf-8');
        
        if (empty(trim($this->address))) {
            $this->valid = false;
        }

    }

    /**
     * set the Place latitude
     *
     * @param double
     * @return void
     *
     */
    public function setLatitude($latitude) {

        $this->latitude = $latitude;

    }

    /**
     * set the Place longitude
     *
     * @param double
     * @return void
     *
     */
    public function setLongitude($longitude) {

        $this->longitude = $longitude;

    }

    /**
     * Sanitize and set the Place about
     *
     * @param string
     * @return void
     *
     */
    public function setAbout($about) {

        $this->about = htmlspecialchars(strip_tags($about), ENT_QUOTES, 'utf-8');

        if (empty(trim($this->about))) {
            $this->valid = false;
        }

    }

    /**
     * Validate, sanitize and set the place preservation status
     *
     * @param int
     * @return void
     *
     */
    public function setStatus($status) {

        if (!($this->status = filter_var($status,  FILTER_VALIDATE_INT))) {

            $this->status = filter_var($status,  FILTER_SANITIZE_NUMBER_INT);
            $this->valid = false;

        }

    }

    /**
     * Validate, sanitize and set the place ownership
     *
     * @param int
     * @return void
     *
     */
    public function setOwnership($ownership) {

        if (!($this->ownership = filter_var($ownership,  FILTER_VALIDATE_INT))) {

            $this->ownership = filter_var($ownership,  FILTER_SANITIZE_NUMBER_INT);
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
     * Set place id
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
     * Get the Place id
     *
     * @return int id
     *
     */
    public function getId() {

        return $this->id;

    }

    /**
     * Get the Place address
     *
     * @return string address
     *
     */
    public function getAddress() {

        return $this->address;

    }

    /**
     * Get the Place latitude
     *
     * @return string latitude
     *
     */
    public function getLatitude() {

        return $this->latitude;

    }

    /**
     * Get the Place longitude
     *
     * @return string longitude
     *
     */
    public function getLongitude() {

        return $this->longitude;

    }
    
    /**
     * Get the Place about
     *
     * @return string about
     *
     */
    public function getAbout() {

        return $this->about;

    }
    
    /**
     * Get the Place ownership
     *
     * @return string ownership
     *
     */
    public function getOwnership() {

        return $this->ownership;

    }
    
    /**
     * Get the Place status
     *
     * @return string status
     *
     */
    public function getStatus() {

        return $this->status;

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
     * Get the Place validation result
     *
     * @return boolean valid
     *
     */
    public function isValid() {

        return $this->valid;

    }    

}

?>