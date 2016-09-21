<?php

include APPPATH . 'classes/FrameworkThumb.php';

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

}

?>