<?php

class FrameworkFormat {
	private $_dbId;
    private $_keyFormatter;
        # framework props
    private $_comparison_data_last_update;
    public $framework;
    public $formattedFramework;
    public $framework_current_version;
    public $twitter;
    public $twitterName;
    public $stackoverflow;
    public $stackoverflowName;
    private $_free;
    private $_opensource;
    public $repo;
    public $repoName;
        # resources
    public $url;
        #technology
    public $technology; // is displayed e.g. (runtime + nativejavascript)
        #output product
    public $output;
        #supported platforms
    public $platforms;
        #programming languages
    public $languages;
        #additional features
    public $others;
        #supported device features
    public $hardware;
        #licenses
    public $licenses;

    public $status;
    private $logo_img;
    public $logo_name;

	public function __construct($data) {
		$this->_dbId = $data->framework_id;
        $this->_keyFormatter = new FormatKey();

        $this->_comparison_data_last_update = "";
        $this->framework = $data->framework;
        $this->formattedFramework = $this->formatString($data->framework);
        $this->framework_current_version = $data->framework_current_version;

        $this->twitter = $this->formatEmpty($data->twitter);
        if(!empty($this->twitter)) { $this->twitterName = strtolower(substr($this->twitter, strpos($this->twitter, "twitter.com/")+12)); }
        $this->stackoverflow = $this->formatEmpty($data->stackoverflow);
        if(!empty($this->stackoverflow)) { 
            $tmpStr = substr($this->stackoverflow, strpos($this->stackoverflow, "tagged/")+7);
            $this->stackoverflowName = explode(".",$tmpStr, 2)[0]; //get name without the ".io"
        }
        $this->_free = $this->formatRadioCheckboxToText($data->free, "free");
        $this->_opensource = $this->formatRadioCheckboxToText($data->opensource, "opensource");
        $this->repo = $this->formatEmpty($data->repo);
        if(!empty($this->repo)) { 
            $tmpStr = substr($this->repo, strpos($this->repo, "github.com/")+11);
            $this->repoName = strtolower(explode("/",$tmpStr, 2)[0]); 
        }

        $tmpStr = $this->formatEmpty($data->license);
        if(!empty($tmpStr)) { $this->licenses = $this->formatLicense(explode("|", $tmpStr)); }
        else { $this->licenses = ""; }

        $tmpStr = $this->formatEmpty($data->url);
        $this->url = explode("|", $tmpStr)[0];  // only return first url (multiple entries are seperated by "|")

        $temp = array (
            "webtonative" => $data->webtonative,
            "nativejavascript" => $data->nativejavascript,
            "runtime" => $data->runtime,
            "javascript_tool" => $data->javascript_tool,
            "sourcecode" => $data->sourcecode,
            "appfactory" => $data->appfactory
        );
        $this->technology = $this->formatTechnology($temp);

        $temp = array (
            "hybridapp" => $data->hybridapp,
            "nativeapp" => $data->nativeapp,
            "mobilewebsite" => $data->mobilewebsite,
            "webapp" => $data->webapp 
        );
        $this->output = $this->formatFeatures($temp);

         $temp = array (
            "android" => $data->android,
            "ios" => $data->ios,
            "blackberry" => $data->blackberry,
            "windowsphone" => $data->windowsphone,
            "wup" => $data->wup,
            "androidtv" => $data->androidtv,
            "appletv" => $data->appletv,
            "watchos" => $data->watchos,
            "bada" => $data->bada,
            "firefoxos" => $data->firefoxos,
            "kindle" => $data->kindle,
            "webos" => $data->webos,
            "osx" => $data->osx,
            "windows" => $data->windows,
            "windowsmobile" => $data->windowsmobile,
            "symbian" => $data->symbian,
            "tizen" => $data->tizen,
            "maemo" => $data->maemo,
            "meego" => $data->meego
        );
        $this->platforms = $this->formatFeatures($temp);

        $temp = array (
            "html" => $data->html,
            "csharp" => $data->csharp,
            "css" => $data->css,
            "basic" => $data->basic,
            "cplusplus" => $data->cplusplus,
            "java" => $data->java,
            "javame" => $data->javame,
            "js" => $data->js,
            "jsx" => $data->jsx,
            "lua" => $data->lua,
            "objc" => $data->objc,
            "swift" => $data->swift,
            "php" => $data->php,
            "python" => $data->python,
            "objpascal" => $data->objpascal,
            "ruby" => $data->ruby,
            "actionscript" => $data->actionscript,
            "uno" => $data->uno,
            "MXML" => $data->MXML,
            "visualeditor" => $data->visualeditor,
            "xml" => $data->xml,
            "qml" => $data->qml,
            "UXMarkup" => $data->UXMarkup
        );
        $this->languages = $this->formatFeatures($temp);

        $temp = array (
            "ads" => $data->ads,
            "cd" => $data->cd,
            "encryption" => $data->encryption,
            "sdk" => $data->sdk,
            "widgets" => $data->widgets,
            "animations" => $data->animations
        );
        $this->others = $this->formatFeatures($temp);
        
        $temp = array (
            "accelerometer" => $data->accelerometer,
            "device" => $data->device,
            "file" => $data->file,
            "bluetooth" => $data->bluetooth,
            "camera" => $data->camera,
            "capture" => $data->capture,
            "geolocation" => $data->geolocation,
            "gestures_multitouch" => $data->gestures_multitouch,
            "compass" => $data->compass,
            "connection" => $data->connection,
            "contacts" => $data->contacts,
            "messages_telephone" => $data->messages_telephone,
            "nativeevents" => $data->nativeevents,
            "nfc" => $data->nfc,
            "notification" => $data->notification,
            "accessibility" => $data->accessibility,
            "storage" => $data->storage,
            "vibration" => $data->vibration
        );
        $this->hardware = $this->formatFeatures($temp, "feature-hardware");

        

        $this->status = $this->formatStatus($data->status);
        $this->logo_img = "notfound.png";
        $this->logo_name = "../" . PublicConstants::IMG_PATH . $data->logo_name;
	}

	public function __destruct() {
		//log_message('debug', "The object " .  __CLASS__ . " was destroyed. " . $this->framework);
	}

	public function __toString() {
		return "" . $this->framework;
	}

    private function formatEmpty($data) {
        if($data === "false" or $data === "UNDEF") {
            return "";
        } else {
            return $data;
        }
    }

    private function formatRadioCheckboxToText($data, $str) {
        $text = "";
        switch ($data) {
            case "UNDEF":
                $text = "";
                break;
            case "true":
                $text = array($str, $this->_keyFormatter->formatKey($str));
                break;
            case "false":
                $text = "";
                break;
            default:
                $text = array(($data . " " . $str), $this->_keyFormatter->formatKey($str));
                break;
        }
        return $text;
    }

    private function formatTechnology($technology) {
        $class = "";
        $text = "";

        foreach ($technology as $key => $value) {
            if(!empty($this->formatEmpty($value))) {
                $class .= $key . " ";
                $text .= $this->_keyFormatter->formatKey($key) . " + ";
            }
        }

        $text = rtrim($text, " + ");

        if(empty($class)) { return "<span class=\"info-label\">Undefined</span>"; }
        else { return "<div class=\"info-label-wrap\"><span class=\"info-label " . $class . "\">" . $text . "</span></div>"; }
    }

    private function formatStatus($status) {
        return "<div class=\"info-label-wrap\"><span class=\"info-label " . strtolower($status) . "\">" . $status . "</span></div>";
    }

    private function formatFeatures($data, $class = "feature") {
        $text = "";
        

        foreach ($data as $key => $value) {
            $feature = $this->formatRadioCheckboxToText($value, $key);
            if(!empty($feature)) {
                $text .= "<span class=\"" . $class . " " . $feature[0] . "\" data-toggle=\"tooltip\" data-delay=\"350\" title=\"" . $feature[1] . " is " . $this->_keyFormatter->formatKey("_" . $value) . "\">" . $feature[1] . "</span>";
            }
        }
        return $text;
    }

    private function formatLicense($licensesArray) {
        $text = "";
        $class = "";

        // add free and open source
        if(!empty($this->_free)) {
            $text .= "<span class=\"feature " . $this->_free[0] . "\" data-toggle=\"tooltip\" data-delay=\"350\" title=\"The tool is " . $this->_free[0] . "\">" . $this->_free[1] . "</span>";
        }
        if(!empty($this->_opensource)) {
            $text .= "<span class=\"feature " . $this->_opensource[0] . "\" data-toggle=\"tooltip\" data-delay=\"350\" title=\"The tool is " . $this->_opensource[0] . "\">" . $this->_opensource[1] . "</span>";
        }

        foreach($licensesArray as $value) {
            if(!empty($value)) {
                if(strpos($value, "_") === false) {
                    $class = "other";
                } else {
                    $class = strtolower(explode("_", $value, 2)[0]);
                    $value = $this->_keyFormatter->formatKey("_" . $class);
                }

                if(strlen($value) > 13) { //for overflow animation
                    $text .= "<div class=\"feature-wrap\" data-toggle=\"tooltip\" data-delay=\"350\" title=\"" . $value . "\"><span class=\"feature-license " . $class . "\">" . $value . "</span></div>";
                } else {
                    $text .= "<span class=\"feature-license " . $class . "\" data-toggle=\"tooltip\" data-delay=\"350\" title=\"" . $value . "\">" . $value . "</span>";
                }
            }
        }
        return $text;
    }

    private function formatString($str) {
        $temp = preg_replace('/[^0-9a-zA-Z]+/', '', $str);
        return strtolower($temp);
    }

}

?>