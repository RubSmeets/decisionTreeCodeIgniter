<?php

include APPPATH . 'classes/FrameworkThumb.php';
include APPPATH . 'classes/FrameworkThumbPrivate.php';

class FrameworksModel extends CI_Model {
	
    //Get all the frameworks and extract relevant data for listView
    function getFrameworkListThumbData(&$errmsg) {
        $this->db->select('framework_id, status, framework, logo_img');
        $query = $this->db->get('frameworks_v2');
		
        //Open console window in folder application/logs and type: tail -100 log-file to see debug info
		//log_message('debug', print_r($query,TRUE));

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumb = new FrameworkThumb (
                    $resultData->framework_id,
                    $resultData->status,
                    $resultData->framework,
                    (PublicConstants::IMG_PATH . $resultData->logo_img)               
                );
                array_push($frameworks, $frameworkThumb);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting framework thumbs";
		return PublicConstants::FAILED;
    }
    //Get all the frameworks that are approved. format data for jQuery dataTable
    function getEditFrameworkListThumbData($userId, &$errmsg) {
        $this->db->select('framework_id, framework, logo_img, state, comparison_data_last_update');
        $this->db->where('state', PublicConstants::STATE_APPROVED);
        $this->db->where('framework_id NOT IN (SELECT f.reference FROM frameworks_v2 AS f WHERE f.modified_by='.$userId.' AND f.state='.PublicConstants::STATE_AWAIT_APPROVAL.')', NULL, FALSE);
        $query = $this->db->get('frameworks_v2');
		
        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbPrivate = new frameworkThumbPrivate (
                    $resultData->framework_id,
                    $resultData->framework,
                    (PublicConstants::IMG_PATH . $resultData->logo_img),
                    $resultData->state,
                    0,
                    $resultData->comparison_data_last_update       
                );
                array_push($frameworks, $frameworkThumbPrivate);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting private framework thumbs";
		return PublicConstants::FAILED;
    }
    //Get all the frameworks that are  NOT approved and belong to the user. format data for jQuery dataTable
    function getPrivateEditFrameworkListThumbData($userId, &$errmsg) {
        $this->db->select('framework_id, framework, logo_img, state, comparison_data_last_update');
        $this->db->where('state', PublicConstants::STATE_AWAIT_APPROVAL);
        $this->db->where('modified_by', $userId);
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbPrivate = new frameworkThumbPrivate (
                    $resultData->framework_id,
                    $resultData->framework,
                    (PublicConstants::IMG_PATH . $resultData->logo_img),
                    $resultData->state,
                    $resultData->framework_id,
                    $resultData->comparison_data_last_update               
                );
                array_push($frameworks, $frameworkThumbPrivate);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting private framework thumbs";
		return PublicConstants::FAILED;
    }
    //Get all the data from one specific framework with {name}
    function getFrameworkByName($frameworkName, &$errmsg) {
        $this->db->where('framework', $frameworkName);
        $this->db->limit(1);    //only return one framework
        $query = $this->db->get('frameworks_v2');
		
        //Open console window in folder application/logs and type: tail -100 log-file to see debug info
		//log_message('debug', print_r($query,TRUE));

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                return $resultData;
            }
		}
		$errmsg = "Framework not found";
		return PublicConstants::FAILED;
    }

    function getEditedFrameworkByName($frameworkName, $userId, &$errmsg) {
        $this->db->where('framework', $frameworkName);
        $this->db->where('modified_by', $userId);
        $this->db->where('state', PublicConstants::STATE_AWAIT_APPROVAL); 
        $this->db->limit(1);    //only return one framework
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                return $resultData;
            }
		}
		$errmsg = "Framework not found";
		return PublicConstants::FAILED;
    }

    function getPrivateFrameworkByName($name, $state, $userId, $errmsg) {
        $this->db->where('framework', $name);
        $this->db->where('state', $state);
        $this->db->where('modified_by', $userId);
        $this->db->limit(1);    //only return one framework
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                $framework = new Framework($resultData->framework_id, false); //do not validate object
                foreach($resultData as $key => $value) {
                    $framework->$key($value);
                }
                // update reference for edit according to approved
                if(!($resultData->state == PublicConstants::STATE_AWAIT_APPROVAL)) {
                    $framework->reference = $resultData->framework_id;
                }
                return $framework;
            }
		}
		$errmsg = "Framework not found";
		return PublicConstants::FAILED;
    }

    //Store a new framework in the database
    function storeFramework($frameworkObj, &$errmsg) {
        $query = $this->db->insert('frameworks_v2', $frameworkObj);
		
        if(!$query) { $errmsg = "No insertion of framework: ".$frameworkObj->framework; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }

    //Update an existing framework in the database
    function updateFramework($framework_id, $newFrameworkObj, &$errmsg) {
        $this->db->set($newFrameworkObj);
        $this->db->where('framework_id', $framework_id);
        $query = $this->db->update('frameworks_v2');
		
        if(!$query) { $errmsg = "No update of framework: ".$newFrameworkObj->framework; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }

    //Remove a framework that is being edited
    function removeFrameworkByNameAndId($frameworkName, $frameworkId, $userId, &$errmsg) {
        $this->db->where('framework', $frameworkName);
        $this->db->where('framework_id', $frameworkId);
        $this->db->where('modified_by', $userId);
        $this->db->where('state', PublicConstants::STATE_AWAIT_APPROVAL);        
        $query = $this->db->delete('frameworks_v2');

        if(!$query) { $errmsg = "No deletion of framework: ".$frameworkName; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }

}

?>