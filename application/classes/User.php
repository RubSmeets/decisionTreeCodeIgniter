<?php

class User {
	private $_dbId;
	public $socialID;
	public $username;
	public $email;
	public $accessToken;
    public $admin;
    public $visitCount;
	public $isBlocked;

	public function __construct($tmpId, $tmpSocialID, $tmpUsername, $tmpEmail, $tmpAccessToken, $tmpAdmin, $tmpVisitCount, $tmpIsBlocked) {
		$this->_dbId = $tmpId;
		$this->socialID = $tmpSocialID;
		$this->username =  $tmpUsername;
		$this->email = $tmpEmail;
		$this->accessToken = $tmpAccessToken;
        $this->admin = $tmpAdmin;
        $this->visitCount = $tmpVisitCount;
		$this->isBlocked = $tmpIsBlocked;
	}

	public function __destruct() {
		//log_message('debug', "The object " .  __CLASS__ . " was destroyed. " . $this->username);
	}

	public function __toString() {
		return "" . $this->_dbId;
	}

	public function getDbID() {
		return $this->_dbId;
	}
}

?>