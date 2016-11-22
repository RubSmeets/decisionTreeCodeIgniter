<?php

class FrameworkThumbProcessed extends FrameworkThumb {
	public $time;
    public $remark;
    public $framework_id;  

	public function __construct($tmpId, $tmpFramework, $tmpThumb_img, $tmpInternalState, $tmpComparison_data_last_update, $tmpAdminRemark) {
		parent::__construct($tmpId, $tmpInternalState, $tmpFramework, $tmpThumb_img); 
		$this->time = $tmpComparison_data_last_update;
        $this->remark = $tmpAdminRemark;
        $this->framework_id = $tmpId;	// used for editing
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
}

?>