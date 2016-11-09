<?php

class FrameworkMarkup {
	private $frameworkData;  
    private $keyFormatter;

	public function __construct($tmpframeworkData) {
		$this->frameworkData = $tmpframeworkData;
        $this->keyFormatter = new FormatKey();
	}

	public function __destruct() {
		//log_message('debug', "The object " .  __CLASS__ . " was destroyed. ");
	}

	public function __toString() {
		return "" . $this->frameworkData;
	}

	public function createFrameworkCompareMarkup () {
        $header = $this->createCompareHeader();
		$devSpec = $this->createDevelopmentSpecification();
		$hardFeat = $this->createHardwareFeatures();
		$suppFeat = $this->createSupportFeatures();
        $resources = $this->createResources();
        $toolSpec = $this->createToolSpec();

        $data = array(
			"framework" => $this->frameworkData->framework,
			"header" => $header,
			"dev_specification" => $devSpec,
			"hardware_features" => $hardFeat,
			"support_features" => $suppFeat,
            "resources" => $resources,
            "tool_specification" => $toolSpec
		);
        return $data;
    }

	private function createCompareHeader() {
        $url = $this->frameworkData->url;
        $img = $this->frameworkData->logo_name;
        $frameworkName = $this->frameworkData->framework;
        $lastUpdate = $this->frameworkData->comparison_data_last_update;
        $linkMarkSt = "";
		$linkMarkEnd = "";
		$imgPath = "../" . PublicConstants::IMG_PATH;
		$pos = strpos($url, "|");
		if($pos !== false) $urls = explode("|", $url);
		elseif($url === "UNDEF" or $url === "false") $urls = array("#");
		else $urls = array($url);
		if ($urls[0] !== "#") {
			$linkMarkSt = "<a href=\"" . $urls[0] . "\" target=\"_blank\">";
			$linkMarkEnd = "</a>";
		}
		$header = <<<HEADER
			<div class="framework-header">
				<table class="caption">
					<colgroup>
						<col style="width:40px" />
						<col style="width:140px" />
					</colgroup>
					<tr>
						<td colspan="2">
							$linkMarkSt<img src="$imgPath$img" alt="">$linkMarkEnd
						</td>
					</tr>
					<tr>
						<td width="40">
							<span class="glyphicon glyphicon-remove-circle"></span>
						</td>
						<td align="left" height="58">
							<h4 class="thumb-caption">$frameworkName</h4>
						</td>
					</tr>	
					<tr>
						<td colspan="2" class="data-status">Info updated: $lastUpdate</td>
					</tr>
				</table>
			</div>
HEADER;
		return preg_replace('/[\t\r\n]+/S', "", $header); // remove all tabs and newlines
	}

	private function createToolSpec() {
		$headerOrder = [
			"toolTecCon" => "",
			"toolAnnCon" => "",
			"toolVerCon" => "",
			"toolPlaCon" => "",
			"toolLanCon" => "",
			"toolProCon" => "",
			"toolLicCon" => "",
			"toolSrcCon" => "",
			"toolCostCon" => ""
		];
        $toolSpecMarkup = "";
        /* Add technologie */
        $technologyOrder = ["webtonative","nativejavascript","runtime","javascript_tool","sourcecode","appfactory"];
		$toolSpecMarkup = "<div class=\"feature-item\">";
        foreach ($technologyOrder as $item) {
			if ($this->frameworkData->$item === "true") $toolSpecMarkup .= "<span>" . $this->keyFormatter->formatKey($item) . "</span>, ";
		}
		$toolSpecMarkup = rtrim($toolSpecMarkup, ", ");
        $headerOrder["toolTecCon"] = $toolSpecMarkup;
        /* Add announced */
        if($this->frameworkData->announced !== "UNDEF" && $this->frameworkData->announced !== "false") $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->frameworkData->announced . "</span></div>";
        else $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->announced) . "</span></div>";
        $headerOrder["toolAnnCon"] = $toolSpecMarkup;
        /* Add version */
        if($this->frameworkData->framework_current_version !== "UNDEF"  && $this->frameworkData->framework_current_version !== "false") $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->frameworkData->framework_current_version . "</span></div>";
        else $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->framework_current_version) . "</span></div>";
        $headerOrder["toolVerCon"] = $toolSpecMarkup;
        /* Add platforms */
        $toolSpecMarkup = "<div class=\"feature-item\">";
        $platforms = ["android","ios","blackberry","windowsphone","wup","androidtv","appletv","watchos","bada","firefoxos","kindle","webos","osx","windows","windowsmobile","symbian","tizen","maemo","meego"];
        foreach ($platforms as $item) {
            if ($this->frameworkData->$item !== "UNDEF" && $this->frameworkData->$item !== "false") $toolSpecMarkup .= "<span>" . $this->keyFormatter->formatKey($item) . "</span>, ";
        }
        $toolSpecMarkup = rtrim($toolSpecMarkup, ", ");
        $headerOrder["toolPlaCon"] = $toolSpecMarkup;
        /* Supported languages */
        $toolSpecMarkup = "<div class=\"feature-item\">";
        $languages = ["html","csharp","css","basic","cplusplus","java","javame","js","jsx","lua","objc","swift","php","python","objpascal","ruby","actionscript","MXML","visualeditor","xml","qml"];
        foreach ($languages as $item) {
            if ($this->frameworkData->$item !== "UNDEF" && $this->frameworkData->$item !== "false") $toolSpecMarkup .= "<span>" . $this->keyFormatter->formatKey($item) . "</span>, ";
        }
        $toolSpecMarkup = rtrim($toolSpecMarkup, ", ");
        $headerOrder["toolLanCon"] = $toolSpecMarkup;
        /* Generated output */
        $outputType = ["mobilewebsite","webapp","nativeapp","hybridapp"];
        $toolSpecMarkup = "<ul class=\"feature-item\">";
        foreach ($outputType as $item) {
            if ($this->frameworkData->$item !== "UNDEF" && $this->frameworkData->$item !== "false") $toolSpecMarkup .= "<li>" . $this->keyFormatter->formatKey($item) . "</li>";
        }
        $toolSpecMarkup .= "</ul>";
        $headerOrder["toolProCon"] = $toolSpecMarkup;
        /* License */
        if($this->frameworkData->license !== "UNDEF") {
            if(strpos($this->frameworkData->license, "|")) {
                $toolSpecMarkup = "<ul class=\"feature-item\">";
                $nestedValues = explode("|", $this->frameworkData->license);
                foreach($nestedValues as $value) if(!empty($value)) $toolSpecMarkup .= "<li>" . $value . "</li>";
                $toolSpecMarkup .= "</ul>";
            } else {
                $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->frameworkData->license . "</span></div>";
            }
        } else { 
            $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->license) . "</span></div>";
        }
        $headerOrder["toolLicCon"] = $toolSpecMarkup;
        /* Open-source */
        $toolSpecMarkup = "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->opensource) . "</span></div>";
        $headerOrder["toolSrcCon"] = $toolSpecMarkup;
        /* Cost */
        $toolSpecMarkup = "<div class=\"feature-item\">";
        $cost = ["free","trial"];
        foreach ($cost as $item) {
            if ($this->frameworkData->$item === "true") $toolSpecMarkup .= "<span>" . $this->keyFormatter->formatKey($item) . "</span>, ";
        }
        $toolSpecMarkup = rtrim($toolSpecMarkup, ", ");
        $headerOrder["toolCostCon"] = $toolSpecMarkup;

        return $headerOrder;
    }

	private function createDevelopmentSpecification() {
		$devMarkup = "";
		$developmentSpec = ["games","clouddev","allows_prototyping","multi_screen","livesync","publ_assist"];
		foreach ($developmentSpec as $value) {
			if($this->frameworkData->$value === "via") {
				$devMarkup .= '<div class="feature-item"><span><i class="fa fa-users"></i></span></div>';
			} else {
				$devMarkup .= "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->$value) . "</span></div>";
			}
		}
		return $devMarkup;
	}
	private function createHardwareFeatures() {
		$hardwareFeatMarkup = "";
		$hardwareFeatures = ["accelerometer","device","file","bluetooth","camera","capture","compass","connection","contacts","geolocation","gestures_multitouch","nativeevents","nfc","storage","messages_telephone","vibration"];
		foreach ($hardwareFeatures as $value) {
			$hardwareFeatMarkup .= "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->$value) . "</span></div>";
		}
		return $hardwareFeatMarkup;
	}
	private function createSupportFeatures() {
		$suppFeatMarkup = "";
		$supportFeatures = ["onsite_supp","hired_help","phone_supp","time_delayed_supp","community_supp"];
		foreach ($supportFeatures as $value) {
			$suppFeatMarkup .= "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->$value) . "</span></div>";
		}
		return $suppFeatMarkup;
	}
    private function createResources() {
        $resourcesMarkup = "";
        $resources = ["url","documentation_url","tutorial_url","video_url","book","appshowcase","market","repo"];
        foreach ($resources as $item) {
			if($this->frameworkData->$item === "UNDEF") {
                $resourcesMarkup .= "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey($this->frameworkData->$item) . "</span></div>";
            } else if ($this->frameworkData->$item === "false") {
                $resourcesMarkup .= "<div class=\"feature-item\"><span>" . $this->keyFormatter->formatKey("UNDEF") . "</span></div>";
            } else {
                if($item === "book") {
                    $pos = strpos($this->frameworkData->$item, "http");
		            if($pos !== false) $resourcesMarkup .= "<div class=\"feature-item\"><a href=\"" . $this->frameworkData->$item . "\" target=\"_blank\">" . $this->keyFormatter->formatKey($item) . "</a></div>";
                    else $resourcesMarkup .= "<div class=\"feature-item\"><span>" . $this->frameworkData->$item . "</span></div>";
                } elseif (strpos($this->frameworkData->$item, "|")) {
                    $resourcesMarkup .= "<div class=\"feature-item\">";
                    $nestedValues = explode("|", $this->frameworkData->$item);
                    $resourcesMarkup .= "<a href=\"" . $nestedValues[0] . "\" target=\"_blank\">" . $this->keyFormatter->formatKey($item) . "(1)</a>, ";
                    for ($i = 0; $i < count($nestedValues); $i++) {
                        if($i != 0) $resourcesMarkup .= "<a href=\"" . $nestedValues[0] . "\" target=\"_blank\">(" . ($i+1) . ")</a>, ";
                    } 
                    $resourcesMarkup = rtrim($resourcesMarkup, ", ");
                    $resourcesMarkup .= "</div>";
                } else {
                    $resourcesMarkup .= "<div class=\"feature-item\"><a href=\"" . $this->frameworkData->$item . "\" target=\"_blank\">" . $this->keyFormatter->formatKey($item) . "</a></div>";
                }
            }
		}
        return $resourcesMarkup;
    }
}

?>