<?php

class UserThumb {
	private $_dbId;
    public $email;
    public $lastActive;
    public $visitCount;    

	public function __construct($tmpId, $tmpEmail, $tmpLastActive, $tmpVisitCount) {
		$this->_dbId = $tmpId;
		$this->email = $tmpEmail;
		$this->lastActive = $tmpLastActive;
		$this->visitCount = $tmpVisitCount;
	}

	public function __destruct() {
		//log_message('debug', "The object " .  __CLASS__ . " was destroyed. " . $this->email);
	}

	public function __toString() {
		return "" . $this->email;
	}

	public function getDbID() {
		return $this->_dbId;
	}
}

?>