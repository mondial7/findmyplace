<?php

class PlacesManager extends Model {
 
    /**
     * Default values, need to match with html form
     */
    private $ownership_ = ["public","private","unknown"],
            $status_ = ["very bad","bad","not bad","good","very good"];


    function __construct() {
        
        parent::__construct();

        // Declare database connection
        $this->connectDB();

    }

 
    /**
     * Save new place
     *
     * @param Place place to create
     * @return boolean
     *
     */
    public function add(Place $place) {

        $result = false;
        $account = $place->getAccount();
        $address = $place->getAddress();
        $latitude = $place->getLatitude();
        $longitude = $place->getLongitude();
        $about = $place->getAbout();
        $ownership = $place->getOwnership();
        $status = $place->getStatus();

        $query = "INSERT INTO "._T_PLACE." (account_id, address, latitude, longitude, about, ownership, status)
                  VALUES ( ? , ? , ? , ? , ? , ? , ? );";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('isddsii', $account,
                                         $address,
                                         $latitude,
                                         $longitude, 
                                         $about, 
                                         $ownership, 
                                         $status);
            $stmt->execute();
        
            if($stmt->affected_rows == 1){

                $result = true;

            }

            $stmt->close();
        
        }

        return $result;

    }

    /**
     * Update place
     *
     * @param Place place to update
     * @return array place data
     *
     */
    public function edit(Place $place) {

        $result = false;
        $account = $place->getAccount();
        $address = $place->getAddress();
        $about = $place->getAbout();
        $ownership = $place->getOwnership();
        $status = $place->getStatus();
        $place_id = $place->getId();

        $query = "UPDATE "._T_PLACE." SET account_id = ? , 
                                            address = ? , 
                                            about = ? , 
                                            ownership = ?, 
                                            status = ?
                  WHERE id = ? ;";
        
        if ($stmt = $this->db->prepare($query)) {
        
            $stmt->bind_param('issiii', $account,
                                        $address, 
                                        $about, 
                                        $ownership, 
                                        $status,
                                        $place_id);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){

                $result = true;

            }

            $stmt->close();
        
        }

        return $result;

    }

    /**
     * Delete place
     *
     * @param Place place to delete
     * @return array place data
     *
     */
    public function remove(Place $place) {

        $result = false;
        $place_id = $place->getId();

        $query = "DELETE FROM "._T_PLACE."
                  WHERE id = ? ;";
        
        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param('i', $place_id);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){

                $result = true;

            }

            $stmt->close();
        
        }

        return $result;

    }

    /**
     * Check if the logged user is 
     * the owner, based on place id
     *
     * @param int place_id
     * @return boolean
     *
     */
    public function isOwner($place_id) {

        $result = false;

        $query = "SELECT id FROM "._T_PLACE."
                  WHERE id = ? AND account_id = ? ;";
        
        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param('ii', $place_id, $_SESSION['id']);
            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows > 0){

                $result = true;

            }

            $stmt->close();

        }

        return $result;

    }


    /**
     * Prettify Addresses 
     * Use google geolocating api
     *
     * @param string address
     * @return object with formatted address and coords
     *
     */
    public function prettifyAddress($address) {
        
        $result = [
                    "status" => "error",
                    "address" => $address,
                    "lat" => "",
                    "lon" => ""
                  ];

        $address = str_replace(".", "+", $address);
        $address = str_replace(",", "+", $address);
        $address = str_replace(" ", "+", $address);

        $api_key = "AIzaSyD-36J0un4Pqx-WPJMg1GBu0LHMBQD7KwM";
        $api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=";
        $url_call = $api_url . $address . "&key=" . $api_key;

        $location = json_decode(file_get_contents($url_call));

        if ($location->status == "OK") {

            $res = $location->results[0];
            $result['status'] = "OK";
            $result['address'] = $res->formatted_address;
            $result['lat'] = $res->geometry->location->lat;
            $result['lon'] = $res->geometry->location->lng;

        }

        return $result;

    }

    /**
     * Check if address already exists
     *
     * @param string address
     * @return boolean
     * 
     */
    public function addressExists($address){

        $result = false;

        $query = "SELECT address FROM "._T_PLACE."
                  WHERE address = ? ;";
        
        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param('s', $address);
            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows > 0){

                $result = true;

            }

            $stmt->close();

        }

        return $result;

    }

    /**
     * Get all the places
     *
     * @return matrix with places
     * 
     */
    public function getPlaces($limit = null){

        $result = [];

        $query = "SELECT * FROM "._T_PLACE.";";
        
        if ($limit != null) {
            $limit = $this->db->real_escape_string($limit);
            $query = "SELECT * FROM "._T_PLACE." LIMIT ? ;";
        }

        if ($stmt = $this->db->prepare($query)) {

            if ($limit != null) {
                $stmt->bind_param("i", $limit);
            }

            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows > 0){

                while ($place = $db_result->fetch_assoc()) {
                    $place['ownership'] = $this->ownership_[$place['ownership']=1];
                    $place['status'] = $this->status_[$place['status']-1];
                    $result[] = $place;
                }

            }

            $stmt->close();

        }

        return $result;

    }

    /**
     * Get place details
     * 
     * @param int place id
     * @param boolean switch to use address as key
     * @return matrix with place details
     * 
     */
    public function getPlaceDetails($key, $use_address = false){

        $result = [];

        $query = "SELECT id, address, about, ownership,
                         longitude, latitude, status, created_at
                  FROM " . _T_PLACE;

        if ($use_address) {
            $param_type = "s";
            $query .= " WHERE address = ? ;";
        } else {
            $param_type = "i";
            $query .= " WHERE id = ? ;";
        }

        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param($param_type, $key);
            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows == 1){

                $place = $db_result->fetch_assoc();
                $place['ownership'] = $this->ownership_[$place['ownership']=1];
                $place['status'] = $this->status_[$place['status']-1];
                $result = $place;

            }

            $stmt->close();

        }

        return $result;

    }

}

?>