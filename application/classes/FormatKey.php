<?php

class FormatKey {
    private $FORMAT_KEY = [
        "true" =>  "<i class=\"glyphicon glyphicon-ok check\"></i>",
        "false" =>  "<i class=\"glyphicon glyphicon-remove uncheck\"></i>",
        "UNDEF" =>  "<i class=\"glyphicon glyphicon-minus\"></i>",
        "EMPTY" =>  "<i class=\"glyphicon glyphicon-minus\"></i>",
        "none" =>  "<i class=\"glyphicon glyphicon-minus\"></i>",
        "via" =>  "<i class=\"fa fa-plug\"></i>",
        "partially" =>  "Partially",
        "soon" =>  "Soon",

        "_soon" => "soon supported",
        "_via" => "supported via plugin or by third party",
        "_partially" => "only partially supported",
        "_true" => "fully supported",

        "active" => "Active",
        "discontinued" => "Discontinued",

        "_free" =>  "Proprietary free license",
        "_indie" => "Proprietary indie license",
        "_commercial" =>  "Proprietary commercial license",
        "_enterprise" =>  "Proprietary enterprise license",
        "trial" =>  "Trial version",

        "nativejavascript" =>  "Native JS",
        "webtonative" =>  "Web-to-native wrapper",
        "javascript_tool" =>  "JS framework/toolkit",
        "sourcecode" =>  "Code translator",
        "runtime" =>  "Runtime",
        "appfactory" =>  "App Factory",

        "hybridapp" => "Hybrid App",
        "nativeapp" => "Native App",
        "mobilewebsite" => "Mobile website",
        "webapp" => "Web App",

        "ios" => "iOS",
        "android" => "Android",
        "wup" => "Windows10",
        "windowsphone" => "WindowsPhone",
        "windowsmobile" => "WindowsMobile",
        "watchos" =>  "Watch OS",
        "tizen" =>  "Tizen",
        "firefoxos" =>  "Firefox OS",
        "blackberry" =>  "Blackberry",
        "appletv" =>  "Apple TV",
        "androidtv" =>  "Android TV",
        "bada" => "Bada",
        "osx" => "OSX",
        "windows" => "Windows",
        "symbian" => "Symbian",
        "webos" => "WebOS",
        "meego" => "Meego",
        "maemo" => "Maemo",
        "kindle" => "Kindle",

        "php" => "PHP",
        "basic" =>  "Basic",
        "java" => "Java",
        "ruby" => "Ruby",
        "actionscript" => "ActionScript",
        "csharp" => "C#",
        "lua" => "LUA",
        "html" => "HTML",
        "css" => "CSS",
        "js" => "JavaScript",
        "cplusplus" => "C++",
        "xml" =>  "XML",
        "visualeditor" => "Visual Editor",
        "qml" => "QML",
        "MXML" => "MXML",
        "python" => "Python",
        "swift" => "Swift",
        "objc" => "Objective C",
        "javame" => "Java ME",
        "jsx" => "JSX",
        "uno" => "Uno",
        "UXMarkup" => "UXMarkup",

        "accelerometer" => "Accelerometer",
        "camera" => "Camera",
        "capture" => "Capture",
        "compass" => "Compass",
        "connection" => "Connection",
        "contacts" => "Contacts",
        "device" => "Device",
        "nativeevents" => "Native events",
        "file" => "File",
        "geolocation" => "Geolocation",
        "notification" => "Notification",
        "storage" => "Storage",
        "gestures_multitouch" => "Multitouch gestures",
        "messages_telephone" => "Telephone messages",
        "bluetooth" => "Bluetooth",
        "nfc" => "NFC",
        "vibration" => "Vibration",

        "cd" => "Corporate Design",
        "widgets" => "Widgets",
        "accessibility" => "Accessibility",
        "animations" => "Animations",

        "sdk" => "SDK",
        "encryption" => "Encryption",
        "ads" => "Ads",

        "free" => "Free",
        "opensource" => "Open Source",
        "commercial" => "Commercial lic.",
        "enterprise" => "Enterprise Lic.",

        "url" =>  "Homepage",
        "documentation_url" =>  "Official Docs",
        "tutorial_url" =>  "Tutorial",
        "video_url" =>  "Video Introduction",
        "book" =>  "Recommended Book",
        "appshowcase" =>  "App Gallery",
        "market" =>  "Market",
        "repo" =>  "Repository",
        "license" =>  "License"
    ]; 

	public function __construct() {
	}

	public function __destruct() {
		//log_message('debug', "The object " .  __CLASS__ . " was destroyed. ");
	}

    public function formatKey($key) {
        return $this->FORMAT_KEY[$key];
    }
}

?>