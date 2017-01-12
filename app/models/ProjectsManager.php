<?php

class ProjectsManager extends Model {
 

    function __construct() {
        
        parent::__construct();

        // Declare database connection
        $this->connectDB();

    }

 
    /**
     * Save new project
     *
     * @param project project to create
     * @return boolean
     *
     */
    public function add(Project $project) {

        $result = false;
        $account = $project->getAccount();
        $title = $project->getTitle();
        $about = $project->getAbout();
        $place_id = $project->getPlaceId();

        $query = "INSERT INTO "._T_PROJECT." (account_id, place_id, title, about)
                  VALUES ( ? , ? , ? , ? );";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('iiss', $account,
                                      $place_id,
                                      $title, 
                                      $about);
            $stmt->execute();
        
            if($stmt->affected_rows == 1){

                $result = $this->db->insert_id;

            }

            $stmt->close();
        
        }

        return $result;

    }

    /**
     * Delete project
     *
     * @param project project to delete
     * @return array project data
     *
     */
    public function remove(Project $project) {

        $result = false;
        $project_id = $project->getId();

        $query = "DELETE FROM "._T_PROJECT."
                  WHERE id = ? ;";
        
        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param('i', $project_id);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){

                $result = true;

            }

            $stmt->close();
        
        }

        return $result;

    }

    /**
     * Check if the logged user is the owner,
     * based on project id or place id
     *
     * @param int project id
     * @param int place id
     * @return boolean
     *
     */
    public function isOwner($place_id, $project_id) {

        $result = false;

        $query = "SELECT id FROM "._T_PROJECT."
                  WHERE id = ? AND ( place_id = ? OR account_id = ? ) ;";
        
        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param('iii', $project_id, 
                                     $place_id,
                                     $_SESSION['id']);
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
     * Get all the projects
     *
     * @return matrix with projects
     * 
     */
    public function getProjects($place_id, $limit = null){

        $result = [];
        
        if ($limit != null) {
            $query = "SELECT * FROM "._T_PROJECT." WHERE place_id = ? LIMIT ? ORDER BY id DESC ;";
        } else {
            $query = "SELECT * FROM "._T_PROJECT." WHERE place_id = ? ORDER BY id DESC ;";
        }

        if ($stmt = $this->db->prepare($query)) {

            if ($limit != null) {
                $stmt->bind_param("ii", $place_id, $limit);
            } else {
                $stmt->bind_param("i", $place_id);    
            }

            $stmt->execute();
            $db_result = $stmt->get_result();

            if($db_result && $db_result->num_rows > 0){

                while ($project = $db_result->fetch_assoc()) {
                    $project['about'] = htmlspecialchars_decode($project['about'], ENT_QUOTES);
                    $result[] = $project;
                }

            }

            $stmt->close();

        }

        return $result;

    }

}

?>