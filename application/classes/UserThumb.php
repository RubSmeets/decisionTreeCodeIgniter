<?php

class UserThumb {
	private $_dbId;
    public $email;
    public $lastActive;
    public $visitCount;
	public $contributionCount; 

	public function __construct($tmpId, $tmpEmail, $tmpLastActive, $tmpVisitCount, $tmpContributionCount) {
		$this->_dbId = $tmpId;
		$this->email = $tmpEmail;
		$this->lastActive = $tmpLastActive;
		$this->visitCount = $tmpVisitCount;
		$this->contributionCount = $tmpContributionCount;
		// total contributions used for filtering
		$this->contributionCount["total"] = $this->contributionCount["awaitCount"] + $this->contributionCount["approvedCount"] + $this->contributionCount["outDatedCount"];
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