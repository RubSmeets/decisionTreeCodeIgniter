<?php

include APPPATH . 'classes/FrameworkThumb.php';
include APPPATH . 'classes/FrameworkThumbPrivate.php';

class FrameworksModel extends CI_Model {
	
    //Get all the frameworks and extract relevant data for listView
    function getFrameworkListThumbData(&$errmsg) {
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
    //Get all the frameworks that are approved and belong to the user. format data for jQuery dataTable
    function getPrivateFrameworkListThumbData($userId, &$errmsg) {
        $this->db->where('state', PublicConstants::STATE_APPROVED);
        $whereOr = "state='" . PublicConstants::STATE_AWAIT_APPROVAL . "' AND modified_by=" . $userId;
        $this->db->or_where($whereOr);
        $query = $this->db->get('frameworks_v2');
		
        //Open console window in folder application/logs and type: tail -100 log-file to see debug info
		//log_message('debug', print_r($query,TRUE));

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbPrivate = new frameworkThumbPrivate (
                    $resultData->framework_id,
                    $resultData->status,
                    $resultData->framework,
                    (PublicConstants::IMG_PATH . $resultData->logo_img),
                    $resultData->state               
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

}

?>