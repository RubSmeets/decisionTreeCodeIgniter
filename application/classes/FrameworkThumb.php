<?php

class FrameworkThumb {
	private $_dbId;
    public $status;
    public $framework;
    public $thumb_img;    

	public function __construct($tmpId, $tmpStatus, $tmpFramework, $tmpThumb_img) {
		$this->_dbId = $tmpId;
		$this->status = $tmpStatus;
		$this->framework = $tmpFramework;
		$this->thumb_img = $tmpThumb_img;
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