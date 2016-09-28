<?php

class FrameworkThumbPrivate extends FrameworkThumb {
	public $internalState;  
	public $framework_id;  

	public function __construct($tmpId, $tmpFramework, $tmpThumb_img, $tmpInternalState, $tmpFramework_id) {
		parent::__construct($tmpId, "UNDEF", $tmpFramework, $tmpThumb_img); 
       
       	$this->internalState = $tmpInternalState;
		$this->framework_id = $tmpFramework_id;	// used for deletion
	}

	public function __destruct() {
		log_message('debug', "The object " .  __CLASS__ . " was destroyed. " . $this->framework);
	}

	public function __toString() {
		return "" . $this->framework;
	}

	public function getDbID() {
		return $this->_dbId;
	}
}

?>