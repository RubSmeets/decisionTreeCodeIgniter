<?php

include APPPATH . 'classes/User.php';
include APPPATH . 'classes/UserThumb.php';

class UserModel extends CI_Model {
	
    //Get user by email address
    function getUserByEmail($email, &$errmsg) {
        $this->db->where('user_email', $email);
		$query = $this->db->get('users');
        //Open console window in folder application/logs and type: tail -100 log-file to see debug info
		//log_message('debug', print_r($query,TRUE));

        if($query->num_rows() != 0) {
			foreach ($query->result() as $resultData) {
                //log_message('debug', print_r($resultData,TRUE));
				return new User(
					$resultData->id,
					$resultData->googleID,
					$resultData->username,
					$resultData->user_email,
					$resultData->access_token,
					$resultData->admin,
					$resultData->visit_count,
					$resultData->blocked);
			}
		}
		$errmsg = "User not found";
		return PublicConstants::FAILED;
    }

	// Get user thumb data for active users
	function getActiveUsersThumbs(&$errmsg) {
		$this->db->select('id, user_email, visit_count, date,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_DECLINED . ') AS declinedCount,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_APPROVED . ') AS approvedCount,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_AWAIT_APPROVAL . ') AS awaitCount,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_OUTDATED . ') AS outDatedCount', FALSE);
		$this->db->from('users');
		$this->db->where('users.admin', 0);
		$this->db->where('users.blocked', PublicConstants::USER_NOT_BLOCKED);
		$this->db->group_by('users.id');
        $query = $this->db->get();

        if($query->num_rows() != 0) {
            $users = array();
			foreach ($query->result() as $resultData) {
                $userThumb = new UserThumb (
					$resultData->id,
                    $resultData->user_email,
					$resultData->date,
                    $resultData->visit_count,
					array(
						'awaitCount' => $resultData->awaitCount,
						'approvedCount' => $resultData->approvedCount,
						'declinedCount' => $resultData->declinedCount,
						'outDatedCount' => $resultData->outDatedCount
					)
                );
                array_push($users, $userThumb);
            }
            return $users;
		}
		$errmsg = "Something went wrong with getting active users thumbs";
		return PublicConstants::FAILED;
	}

	// Get user thumb data for blocked users
	function getBlockedUsersThumbs(&$errmsg) {
		$this->db->select('id, user_email, visit_count, date, 
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_DECLINED . ') AS declinedCount,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_APPROVED . ') AS approvedCount,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_AWAIT_APPROVAL . ') AS awaitCount,
			(SELECT COUNT(*) FROM frameworks_v2 WHERE frameworks_v2.modified_by = users.id AND frameworks_v2.state = ' . PublicConstants::STATE_OUTDATED . ') AS outDatedCount', FALSE);
		$this->db->from('users');
		$this->db->where('users.admin', 0);
		$this->db->where('users.blocked', PublicConstants::USER_BLOCKED);
		$this->db->group_by('users.id');
        $query = $this->db->get();

        if($query->num_rows() != 0) {
            $users = array();
			foreach ($query->result() as $resultData) {
                $userThumb = new UserThumb (
					$resultData->id,
                    $resultData->user_email,
					$resultData->date,
                    $resultData->visit_count,
					array(
						'awaitCount' => $resultData->awaitCount,
						'approvedCount' => $resultData->approvedCount,
						'declinedCount' => $resultData->declinedCount,
						'outDatedCount' => $resultData->outDatedCount
					)
                );
                array_push($users, $userThumb);
            }
            return $users;
		}
		$errmsg = "Something went wrong with getting active users thumbs";
		return PublicConstants::FAILED;
	}

	//Store user in database
    function createUser($userData, $accessToken, &$errmsg) {
		$data = array(
			'id' => 0,
			'googleID' => $userData->sub,
			'username' => $userData->name,
			'user_email' => $userData->email,
			'access_token' => $accessToken,
			'admin' => 0
		);
		$query = $this->db->insert('users', $data);

        if(!$query) { $errmsg = "No insertion of user: ".$userData->name; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
    }

	// Update the user (user token)
	function updateUser($userObj, &$errmsg) {
		$data = array(
			'googleID' => $userObj->socialID,
			'username' => $userObj->username,
			'user_email' => $userObj->email,
			'access_token' => $userObj->accessToken,
			'admin' => $userObj->admin,
			'visit_count' => $userObj->visitCount
		);
		$this->db->where('id', $userObj->getDbID());
		$query = $this->db->update('users', $data);

        if(!$query) { $errmsg = "No update of user data: ".$userObj->username; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
	}

	// Block an active user 
	function blockUnblockUserByEmail($email, $action, &$errmsg) {
		$this->db->set('blocked', $action);
		$this->db->where('user_email', $email);
        $query = $this->db->update('users');

        if(!$query) { $errmsg = "No deletion of user: ".$email; return PublicConstants::FAILED; }
		else return PublicConstants::SUCCESS;
	}
}

?>