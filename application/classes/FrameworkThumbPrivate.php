<?php

class FrameworkThumbPrivate extends FrameworkThumb {
	public $internalState;  
	public $framework_id;  
	public $time;
	public $contributor;

	public function __construct($tmpId, $tmpFramework, $tmpThumb_img, $tmpInternalState, $tmpFramework_id, $tmpComparison_data_last_update) {
		parent::__construct($tmpId, "UNDEF", $tmpFramework, $tmpThumb_img); 
       
       	$this->internalState = $tmpInternalState;
		$this->framework_id = $tmpFramework_id;	// used for deletion
		$this->time = $tmpComparison_data_last_update;
		$this->contributor = "";
	}

	public function __destruct() {
		//log_message('debug', "The object " .  __CLASS__ . " was destroyed. " . $this->framework);
	}

	public function __toString() {
		return "" . $this->framework;
	}

	public function getDbID() {
		return $this->_dbId;
	}

	public function setUser($name) {
		$temp = explode(" ", $name); // only first name
		$this->contributor = $temp[0];
	}
}

?>