<?php

class FrameworkThumbAdmin extends FrameworkThumb {  
	public $framework_id;  
	public $time;
	public $contributor;
    public $type;

	public function __construct($tmpId, $tmpFramework, $tmpThumb_img, $tmpFramework_id, $tmpComparison_data_last_update, $tmpContributor, $tmpType) {
		parent::__construct($tmpId, "UNDEF", $tmpFramework, $tmpThumb_img); 
       
		$this->framework_id = $tmpFramework_id;	// used for deletion
		$this->time = $tmpComparison_data_last_update;
		$this->contributor = $tmpContributor;
        if($tmpType == 0) { // reference = 0 if it is a new framework
            $this->type = "New";
        } else {
            $this->type = "Mod";
        }
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