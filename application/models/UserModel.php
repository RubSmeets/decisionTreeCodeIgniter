<?php

include APPPATH . 'classes/User.php';

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
					$resultData->visit_count);
			}
		}
		$errmsg = "User not found";
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
}

?>