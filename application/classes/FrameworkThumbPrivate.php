<?php

class FrameworkThumbPrivate extends FrameworkThumb {
	public $internalState;    

	public function __construct($tmpId, $tmpStatus, $tmpFramework, $tmpThumb_img, $tmpInternalState) {
		parent::__construct($tmpId, $tmpStatus, $tmpFramework, $tmpThumb_img); 
       
       $this->internalState = $tmpInternalState;
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