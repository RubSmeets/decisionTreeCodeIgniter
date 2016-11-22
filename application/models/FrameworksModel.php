<?php

include APPPATH . 'classes/FrameworkThumb.php';
include APPPATH . 'classes/FrameworkThumbPrivate.php';
include APPPATH . 'classes/FrameworkThumbAdmin.php';
include APPPATH . 'classes/FrameworkFormat.php';
include APPPATH . 'classes/FrameworkThumbProcessed.php';

class FrameworksModel extends CI_Model {
	
    //Get all frameworks pre-formatted for the index page
    function getPreFormattedFrameworks(&$errmsg) {
        $this->db->where('state', PublicConstants::STATE_APPROVED);
        $this->db->order_by('framework', 'ASC');
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $formattedFramework = new FrameworkFormat($resultData);
                array_push($frameworks, $formattedFramework);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting framework thumbs";
		return PublicConstants::FAILED;
    }
    //Get all the frameworks and extract relevant data for listView
    function getFrameworkListThumbData(&$errmsg) {
        $this->db->select('framework_id, status, framework, logo_name');
        $this->db->where('state', PublicConstants::STATE_APPROVED);
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
                    ("../" . PublicConstants::IMG_PATH . $resultData->logo_name)               
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
        $this->db->select('framework_id, framework, logo_name, state, comparison_data_last_update, username');
        $this->db->from('frameworks_v2');
        $this->db->join('users', 'frameworks_v2.modified_by = users.id', 'left');
        $this->db->where('state', PublicConstants::STATE_APPROVED);
        $this->db->where('framework_id NOT IN (SELECT f.reference FROM frameworks_v2 AS f WHERE f.modified_by='.$userId.' AND f.state='.PublicConstants::STATE_AWAIT_APPROVAL.')', NULL, FALSE);
        $query = $this->db->get();
		
        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbPrivate = new FrameworkThumbPrivate (
                    $resultData->framework_id,
                    $resultData->framework,
                    ("../" . PublicConstants::IMG_PATH . $resultData->logo_name),
                    $resultData->state,
                    0,
                    $resultData->comparison_data_last_update       
                );
                $frameworkThumbPrivate->setUser($resultData->username);
                array_push($frameworks, $frameworkThumbPrivate);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting private framework thumbs";
		return PublicConstants::FAILED;
    }
    //Get all the frameworks that are  NOT approved and belong to the user. format data for jQuery dataTable
    function getPrivateEditFrameworkListThumbData($userId, &$errmsg) {
        $this->db->select('framework_id, framework, logo_name, state, comparison_data_last_update');
        $this->db->where('state', PublicConstants::STATE_AWAIT_APPROVAL);
        $this->db->where('modified_by', $userId);
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbPrivate = new FrameworkThumbPrivate (
                    $resultData->framework_id,
                    $resultData->framework,
                    ("../" . PublicConstants::IMG_PATH . $resultData->logo_name),
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
    //Get framework thumb data for datatable that shows a users processed contributions
    function getProcessedFrameworkListThumbData($userId, &$errmsg) {
        $this->db->select('framework_id, framework, logo_name, state, comparison_data_last_update, admin_remark');
        $this->db->where('state !=', PublicConstants::STATE_AWAIT_APPROVAL);
        $this->db->where('modified_by', $userId);
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbProcessed = new FrameworkThumbProcessed (
                    $resultData->framework_id,
                    $resultData->framework,
                    ("../" . PublicConstants::IMG_PATH . $resultData->logo_name),
                    $resultData->state,
                    $resultData->comparison_data_last_update,
                    $resultData->admin_remark               
                );
                array_push($frameworks, $frameworkThumbProcessed);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting processed framework thumbs for user";
		return PublicConstants::FAILED;
    }
    //Get framework logo by name 
    function getFrameworkLogoByName($frameworkName, $userId, $state, &$errmsg) {
        $this->db->select('framework_id, logo_name');
        $this->db->where('framework', $frameworkName);
        $this->db->where('modified_by', $userId);
        $this->db->where('state', $state);
        $this->db->limit(1);    //only return one framework
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                return $result = array(
                    'id' => $resultData->framework_id,
                    'logo_name' => $resultData->logo_name
                );
            }
		}
		$errmsg = "Framework not found";
		return PublicConstants::FAILED;
    }
    //Get framework logo by id
    function getFrameworkLogoById($frameworkid, $state, &$errmsg) {
        $this->db->select('framework_id, logo_name');
        $this->db->where('framework_id', $frameworkid);
        $this->db->where('state', $state);
        $this->db->limit(1);    //only return one framework
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                return $result = array(
                    'id' => $resultData->framework_id,
                    'logo_name' => $resultData->logo_name
                );
            }
		}
		$errmsg = "Framework not found";
		return PublicConstants::FAILED;
    }
    //Get all the data from one specific framework with {name}
    function getFrameworkByName($frameworkName, &$errmsg) {
        $this->db->where('framework', $frameworkName);
        $this->db->where('state', PublicConstants::STATE_APPROVED);
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
        if($state == PublicConstants::STATE_AWAIT_APPROVAL) {
            $this->db->where('modified_by', $userId);
        }
        $this->db->limit(1);    //only return one framework
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                $framework = new Framework($resultData->framework_id, PublicConstants::DONT_VALIDATE_FRAMEWORK); //do not validate object
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

    //Get contribution framework data from a specific user_error
    function getContributionsOfUser($email, &$errmsg) {
        $this->db->select('framework_id, framework, logo_name, state, comparison_data_last_update');
        $this->db->where("modified_by = (SELECT u.id FROM users AS u WHERE u.user_email = '" . $email ."')", NULL, FALSE);
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbPrivate = new FrameworkThumbPrivate (
                    $resultData->framework_id,
                    $resultData->framework,
                    ("../" . PublicConstants::IMG_PATH . $resultData->logo_name),
                    $resultData->state,
                    $resultData->framework_id,
                    $resultData->comparison_data_last_update               
                );
                array_push($frameworks, $frameworkThumbPrivate);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting user contributions";
		return PublicConstants::FAILED;
    }

    //Get all the frameworks that are approved. format data for jQuery dataTable
    function getAllPendingContributionThumbs(&$errmsg) {
        $this->db->select('framework_id, framework, logo_name, comparison_data_last_update, modified_by, reference, username');
        $this->db->from('frameworks_v2');
        $this->db->join('users', 'frameworks_v2.modified_by = users.id', 'left');
        $this->db->where('state', PublicConstants::STATE_AWAIT_APPROVAL);
        $query = $this->db->get();
		
        if($query->num_rows() != 0) {
            $frameworks = array();
			foreach ($query->result() as $resultData) {
                $frameworkThumbAdmin = new FrameworkThumbAdmin (
                    $resultData->framework_id,
                    $resultData->framework,
                    ("../" . PublicConstants::IMG_PATH . $resultData->logo_name),
                    $resultData->framework_id,
                    $resultData->comparison_data_last_update,
                    $resultData->username,
                    $resultData->reference,
                    $resultData->modified_by
                );
                array_push($frameworks, $frameworkThumbAdmin);
            }
            return $frameworks;
		}
		$errmsg = "Something went wrong with getting admin framework thumbs";
		return PublicConstants::FAILED;
    }

    //Get framework for approval or decline
    function getPrivateFrameworkById($frameworkId, &$errmsg) {
        $this->db->where('framework_id', $frameworkId);
        $query = $this->db->get('frameworks_v2');
        $frameworks = array();

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                $framework = new Framework($resultData->framework_id, PublicConstants::DONT_VALIDATE_FRAMEWORK); //do not validate object
                foreach($resultData as $key => $value) {
                    $framework->$key($value);
                }
                array_push($frameworks, $framework);
            }
		} else {
            $errmsg = "Framework not found";
            return PublicConstants::FAILED;
        }

        // Second query if needed to get orignal refered framework
        $this->db->where('framework_id', $framework->reference);
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                $framework = new Framework($resultData->framework_id, PublicConstants::DONT_VALIDATE_FRAMEWORK); //do not validate object
                foreach($resultData as $key => $value) {
                    $framework->$key($value);
                }
                array_push($frameworks, $framework);
                return $frameworks;
            }
		} else {
            return $frameworks;
        }
        $errmsg = "Framework not found";
        return PublicConstants::FAILED;
    }

    //Get a processed framework for resubmission (edit)
    function getProcessedFrameworkById($frameworkId, &$errmsg) {
        $this->db->where('framework_id', $frameworkId);
        $query = $this->db->get('frameworks_v2');

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                $framework = new Framework($resultData->framework_id, PublicConstants::DONT_VALIDATE_FRAMEWORK); //do not validate object
                foreach($resultData as $key => $value) {
                    $framework->$key($value);
                }
                return $framework;
            }
		} else {
            $errmsg = "Framework not found";
            return PublicConstants::FAILED;
        }
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
    //Update framework logo
    function updateFrameworkLogo($framework_id, $logoName, $logoNameCB, &$errmsg) {
        $this->db->set('logo_img', $logoName);
        $this->db->set('logo_name', $logoNameCB);
        $this->db->where('framework_id', $framework_id);
        $query = $this->db->update('frameworks_v2');
		
        if(!$query) { $errmsg = "No update of framework logo"; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }
    //Approve a contribution 
    function approveFramework($frameworkId, $frameworkObj, &$errmsg) {
        $this->db->set($frameworkObj);
        $this->db->set('state', PublicConstants::STATE_APPROVED);
        $this->db->where('framework_id', $frameworkId);
        $query = $this->db->update('frameworks_v2');

        // Set the refered framework state to outdated
        if($frameworkObj->reference != 0) {
            $this->db->set('state', PublicConstants::STATE_OUTDATED);
            $this->db->where('framework_id', $frameworkObj->reference);
            $query = $this->db->update('frameworks_v2');
        }
		
        if(!$query) { $errmsg = "No update of framework: ".$frameworkObj->framework; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }

    //Decline a contribution 
    function declineFramework($frameworkId, $admin_remark, &$errmsg) {
        $this->db->set('state', PublicConstants::STATE_DECLINED);
        $this->db->set('admin_remark', $admin_remark);
        $this->db->where('framework_id', $frameworkId);
        $query = $this->db->update('frameworks_v2');
		
        if(!$query) { $errmsg = "No update of framework: ".$frameworkId; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }

    //Remove a framework that is being edited
    function removeFrameworkByNameAndId($frameworkName, $frameworkId, $userId, &$errmsg) {
        $logoNameCurr = "";

        // Get logo_name of framework that we want to delete
        $this->db->select('f1.logo_name AS curr_logo, f2.logo_name AS ref_logo');
        $this->db->from('frameworks_v2 AS f1');
        $this->db->join('frameworks_v2 AS f2', 'f2.framework_id = f1.reference', 'inner');
        $this->db->where('f1.framework_id', $frameworkId);
        $query = $this->db->get();
        
        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                if (strcmp($resultData->curr_logo, $resultData->ref_logo) !== 0) {
                    $logoNameCurr = $resultData->curr_logo;
                }
            }
		} else {
            $errmsg = "Framework not found";
            return PublicConstants::FAILED;
        }
        
        // Delete the framework
        $this->db->where('framework', $frameworkName);
        $this->db->where('framework_id', $frameworkId);
        $this->db->where('modified_by', $userId);
        $this->db->where('state', PublicConstants::STATE_AWAIT_APPROVAL);        
        $query = $this->db->delete('frameworks_v2');

        if(!$query) { $errmsg = "No deletion of framework: ".$frameworkName; return PublicConstants::FAILED; }
		else return $logoNameCurr;
    }

}

?>