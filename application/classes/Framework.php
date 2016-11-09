<?php

class Framework {
	private $_dbId;
    private $_action; // internal variable to differentiate between framework from db or from post
    private $_inValidProperty;
        # framework props
    private $_comparison_data_last_update;
    public $framework;
    public $framework_current_version;
    public $announced;
    public $market;
    public $twitter;
    public $stackoverflow;
    public $appshowcase;
    public $clouddev;
    public $license;
    public $learning_curve;
    public $allows_prototyping;
    public $perf_overhead;
    public $integrate_with_existing_app;
    public $iteration_speed;
    public $remoteupdate;
    public $free;
    public $opensource;
    public $repo;
    public $trial;
    public $games;
    public $multi_screen;
        # Support 
    public $onsite_supp;
    public $hired_help;
    public $phone_supp;
    public $time_delayed_supp;
    public $community_supp;
        # Development
    public $publ_assist;
    public $livesync;
    public $code_sharing;
        # resources
    public $documentation_url;
    public $book;
    public $video_url;
    public $tutorial_url;
    public $url;
        #technology
    public $webtonative;
    public $nativejavascript;
    public $runtime;
    public $javascript_tool;
    public $sourcecode;
    public $appfactory;
        #output product
    public $mobilewebsite;
    public $webapp;
    public $nativeapp;
    public $hybridapp;
        #supported platforms
    public $android;
    public $ios;
    public $blackberry;
    public $windowsphone;
    public $wup;
    public $androidtv;
    public $appletv;
    public $watchos;
    public $bada;
    public $firefoxos;
    public $kindle;
    public $webos;
    public $osx;
    public $windows;
    public $windowsmobile;
    public $symbian;
    public $tizen;
    public $maemo;
    public $meego;
        #programming languages
    public $html;
    public $csharp;
    public $css;
    public $basic;
    public $cplusplus;
    public $java;
    public $javame;
    public $js;
    public $jsx;
    public $lua;
    public $objc;
    public $swift;
    public $php;
    public $python;
    public $objpascal;
    public $ruby;
    public $actionscript;
    public $uno;
    public $MXML;
    public $visualeditor;
    public $xml;
    public $qml;
    public $UXMarkup;
        #additional features
    public $ads;
    public $cd;
    public $encryption;
    public $sdk;
    public $widgets;
    public $animations;
        #supported device features
    public $accelerometer;
    public $device;
    public $file;
    public $bluetooth;
    public $camera;
    public $capture;
    public $geolocation;
    public $gestures_multitouch;
    public $compass;
    public $connection;
    public $contacts;
    public $messages_telephone;
    public $nativeevents;
    public $nfc;
    public $notification;
    public $accessibility;
    public $status;
    public $storage;
    public $vibration;
    private $logo_img;
    public $logo_name;
    private $state;
    public $modified_by;
    public $reference; // foreign key to the modified original framework entry (0 if it is the first)

	public function __construct($tmpId, $tmpAction) {
		$this->_dbId = $tmpId;
        $this->_action = $tmpAction;
        $this->_inValidProperty = false;
        $this->_comparison_data_last_update = "";
        $this->framework = "UNDEF";
        $this->framework_current_version = "UNDEF";
        $this->announced = "UNDEF";
        $this->market = "UNDEF";
        $this->twitter = "UNDEF";
        $this->stackoverflow = "UNDEF";
        $this->appshowcase = "UNDEF";
        $this->clouddev = "UNDEF";
        $this->license = "UNDEF";
        $this->learning_curve = "soon";
        $this->allows_prototyping = "UNDEF";
        $this->perf_overhead = "soon";
        $this->integrate_with_existing_app = "UNDEF";
        $this->iteration_speed = "soon";
        $this->remoteupdate = "UNDEF";
        $this->free = "UNDEF";
        $this->opensource = "UNDEF";
        $this->repo = "UNDEF";
        $this->trial = "UNDEF";
        $this->games = "UNDEF";
        $this->multi_screen = "UNDEF";
        $this->onsite_supp = "UNDEF";
        $this->hired_help = "UNDEF";
        $this->phone_supp = "UNDEF";
        $this->time_delayed_supp = "UNDEF";
        $this->community_supp = "UNDEF";
        $this->publ_assist = "UNDEF";
        $this->livesync = "UNDEF";
        $this->code_sharing = "UNDEF";
        $this->documentation_url = "UNDEF";
        $this->book = "UNDEF";
        $this->video_url = "UNDEF";
        $this->tutorial_url = "UNDEF";
        $this->url = "UNDEF";
        $this->webtonative = "UNDEF";
        $this->nativejavascript = "UNDEF";
        $this->runtime = "UNDEF";
        $this->javascript_tool = "UNDEF";
        $this->sourcecode = "UNDEF";
        $this->appfactory = "UNDEF";
        $this->mobilewebsite = "UNDEF";
        $this->webapp = "UNDEF";
        $this->nativeapp = "UNDEF";
        $this->hybridapp = "UNDEF";
        $this->android = "UNDEF";
        $this->ios = "UNDEF";
        $this->blackberry = "UNDEF";
        $this->windowsphone = "UNDEF";
        $this->wup = "UNDEF";
        $this->androidtv = "UNDEF";
        $this->appletv = "UNDEF";
        $this->watchos = "UNDEF";
        $this->bada = "UNDEF";
        $this->firefoxos = "UNDEF";
        $this->kindle = "UNDEF";
        $this->webos = "UNDEF";
        $this->osx = "UNDEF";
        $this->windows = "UNDEF";
        $this->windowsmobile = "UNDEF";
        $this->symbian = "UNDEF";
        $this->tizen = "UNDEF";
        $this->maemo = "UNDEF";
        $this->meego = "UNDEF";
        $this->html = "UNDEF";
        $this->csharp = "UNDEF";
        $this->css = "UNDEF";
        $this->basic = "UNDEF";
        $this->cplusplus = "UNDEF";
        $this->java = "UNDEF";
        $this->javame = "UNDEF";
        $this->js = "UNDEF";
        $this->jsx = "UNDEF";
        $this->lua = "UNDEF";
        $this->objc = "UNDEF";
        $this->swift = "UNDEF";
        $this->php = "UNDEF";
        $this->python = "UNDEF";
        $this->objpascal = "UNDEF";
        $this->ruby = "UNDEF";
        $this->actionscript = "UNDEF";
        $this->uno = "UNDEF";
        $this->MXML = "UNDEF";
        $this->visualeditor = "UNDEF";
        $this->xml = "UNDEF";
        $this->qml = "UNDEF";
        $this->UXMarkup = "UNDEF";
        $this->ads = "UNDEF";
        $this->cd = "UNDEF";
        $this->encryption = "UNDEF";
        $this->sdk = "UNDEF";
        $this->widgets = "UNDEF";
        $this->animations = "UNDEF";
        $this->accelerometer = "UNDEF";
        $this->device = "UNDEF";
        $this->file = "UNDEF";
        $this->bluetooth = "UNDEF";
        $this->camera = "UNDEF";
        $this->capture = "UNDEF";
        $this->geolocation = "UNDEF";
        $this->gestures_multitouch = "UNDEF";
        $this->compass = "UNDEF";
        $this->connection = "UNDEF";
        $this->contacts = "UNDEF";
        $this->messages_telephone = "UNDEF";
        $this->nativeevents = "UNDEF";
        $this->nfc = "UNDEF";
        $this->notification = "UNDEF";
        $this->accessibility = "UNDEF";
        $this->status = "UNDEF";
        $this->storage = "UNDEF";
        $this->vibration = "UNDEF";
        $this->logo_img = "notfound.png";
        $this->logo_name = "notfound.png";
        $this->state = 0;
        $this->modified_by = 0;
        $this->reference = 0;
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

    public function framework($data) {
        $this->framework = $data;
    }
    public function framework_current_version($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->framework_current_version = $this->isEmpty($data);
        } else {
            $this->framework_current_version = $this->formatEmpty($data);
        }
    }
    public function announced($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->announced = $this->isEmpty($data);
        } else {
            $this->announced = $this->formatEmpty($data);
        }
    }
    public function market($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->market = $this->validateUrl($data);
        } else {
            $this->market = $this->formatEmpty($data);
        }
    }
    public function twitter($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->twitter = $this->validateUrl($data);
        } else {
            $this->twitter = $this->formatEmpty($data);
        }
    }
    public function stackoverflow($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->stackoverflow = $this->validateUrl($data);
        } else {
            $this->stackoverflow = $this->formatEmpty($data);
        }
    }
    public function appshowcase($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->appshowcase = $this->validateUrl($data);
        } else {
            $this->appshowcase = $this->formatEmpty($data);
        }
    }
    public function clouddev($data) {
        $this->clouddev = $data;
    }
    public function license($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            if($this->license === "UNDEF") {
                if(!empty($data)) { $this->license = $this->validateText($data); }
            } else {
                if(!empty($data)) { $this->license .= '|' . $this->validateText($data); }
            }
        } else {
            $this->license = $this->formatEmpty($data);
        }
    }
    public function learning_curve($data) {
        $this->learning_curve = $data;
    }
    public function allows_prototyping($data) {
        $this->allows_prototyping = $data;
    }
    public function perf_overhead($data) {
        $this->perf_overhead = $data;
    }
    public function integrate_with_existing_app($data) {
        $this->integrate_with_existing_app = $data;
    }
    public function iteration_speed($data) {
        $this->iteration_speed = $data;
    }
    public function remoteupdate($data) {
        $this->remoteupdate = $data;
    }
    public function free($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->free = $data;
        } else {
            $this->free = $this->formatRadioEmpty($data);
        }
    }
    public function opensource($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->opensource = $data;
        } else {
            $this->opensource = $this->formatRadioEmpty($data);
        }
    }
    public function repo($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->repo = $this->validateUrl($data);
        } else {
            $this->repo = $this->formatEmpty($data);
        }
    }
    public function trial($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->trial = $data;
        } else {
            $this->trial = $this->formatRadioEmpty($data);
        }
    }
    public function games($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->games = $data;
        } else {
            $this->games = $this->formatRadioEmpty($data);
        }
    }
    public function multi_screen($data) {
        $this->multi_screen = $data;
    }
    public function onsite_supp($data) {
        $this->onsite_supp = $data;
    }
    public function hired_help($data) {
        $this->hired_help = $data;
    }
    public function phone_supp($data) {
        $this->phone_supp = $data;
    }
    public function time_delayed_supp($data) {
        $this->time_delayed_supp = $data;
    }
    public function community_supp($data) {
        $this->community_supp = $data;
    }
    public function publ_assist($data) {
        $this->publ_assist = $data;
    }
    public function livesync($data) {
        $this->livesync = $data;
    }
    public function code_sharing($data) {
        $this->code_sharing = $data;
    }
    public function documentation_url($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->documentation_url = $this->validateUrl($data);
        } else {
            $this->documentation_url = $this->formatEmpty($data);
        }
    }
    public function book($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->book = $this->validateUrl($data);
        } else {
            $this->book = $this->formatEmpty($data);
        }
    }
    public function video_url($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->video_url = $this->validateUrl($data);
        } else {
            $this->video_url = $this->formatEmpty($data);
        }
    }
    public function tutorial_url($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->tutorial_url = $this->validateUrl($data);
        } else {
            $this->tutorial_url = $this->formatEmpty($data);
        }
    }
    public function url($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->url = $this->validateUrl($data);
        } else {
            $this->url = $this->formatEmpty($data);
        }
    }
    public function webtonative($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->webtonative = $data;
        } else {
            $this->webtonative = $this->formatRadioEmpty($data);
        }
    }
    public function nativejavascript($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->nativejavascript = $data;
        } else {
            $this->nativejavascript = $this->formatRadioEmpty($data);
        }
    }
    public function runtime($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->runtime = $data;
        } else {
            $this->runtime = $this->formatRadioEmpty($data);
        }
    }
    public function javascript_tool($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->javascript_tool = $data;
        } else {
            $this->javascript_tool = $this->formatRadioEmpty($data);
        }
    }
    public function sourcecode($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->sourcecode = $data;
        } else {
            $this->sourcecode = $this->formatRadioEmpty($data);
        }
    }
    public function appfactory($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->appfactory = $data;
        } else {
            $this->appfactory = $this->formatRadioEmpty($data);
        }
    }
    public function mobilewebsite($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->mobilewebsite = $data;
        } else {
            $this->mobilewebsite = $this->formatRadioEmpty($data);
        }
    }
    public function webapp($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->webapp = $data;
        } else {
            $this->webapp = $this->formatRadioEmpty($data);
        }
    }
    public function nativeapp($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->nativeapp = $data;
        } else {
            $this->nativeapp = $this->formatRadioEmpty($data);
        }
    }
    public function hybridapp($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->hybridapp = $data;
        } else {
            $this->hybridapp = $this->formatRadioEmpty($data);
        }
    }
    public function android($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->android = $data;
        } else {
            $this->android = $this->formatRadioEmpty($data);
        }
    }
    public function ios($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->ios = $data;
        } else {
            $this->ios = $this->formatRadioEmpty($data);
        }
    }
    public function blackberry($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->blackberry = $data;
        } else {
            $this->blackberry = $this->formatRadioEmpty($data);
        }
    }
    public function windowsphone($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->windowsphone = $data;
        } else {
            $this->windowsphone = $this->formatRadioEmpty($data);
        }
    }
    public function wup($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->wup = $data;
        } else {
            $this->wup = $this->formatRadioEmpty($data);
        }
    }
    public function androidtv($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->androidtv = $data;
        } else {
            $this->androidtv = $this->formatRadioEmpty($data);
        }
    }
    public function appletv($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->appletv = $data;
        } else {
            $this->appletv = $this->formatRadioEmpty($data);
        }
    }
    public function watchos($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->watchos = $data;
        } else {
            $this->watchos = $this->formatRadioEmpty($data);
        }
    }
    public function bada($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->bada = $data;
        } else {
            $this->bada = $this->formatRadioEmpty($data);
        }
    }
    public function firefoxos($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->firefoxos = $data;
        } else {
            $this->firefoxos = $this->formatRadioEmpty($data);
        }
    }
    public function kindle($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->kindle = $data;
        } else {
            $this->kindle = $this->formatRadioEmpty($data);
        }
    }
    public function webos($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->webos = $data;
        } else {
            $this->webos = $this->formatRadioEmpty($data);
        }
    }
    public function osx($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->osx = $data;
        } else {
            $this->osx = $this->formatRadioEmpty($data);
        }
    }
    public function windows($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->windows = $data;
        } else {
            $this->windows = $this->formatRadioEmpty($data);
        }
    }
    public function windowsmobile($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->windowsmobile = $data;
        } else {
            $this->windowsmobile = $this->formatRadioEmpty($data);
        }
    }
    public function symbian($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->symbian = $data;
        } else {
            $this->symbian = $this->formatRadioEmpty($data);
        }
    }
    public function tizen($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->tizen = $data;
        } else {
            $this->tizen = $this->formatRadioEmpty($data);
        }
    }
    public function maemo($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->maemo = $data;
        } else {
            $this->maemo = $this->formatRadioEmpty($data);
        }
    }
    public function meego($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->meego = $data;
        } else {
            $this->meego = $this->formatRadioEmpty($data);
        }
    }
    public function html($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->html = $data;
        } else {
            $this->html = $this->formatRadioEmpty($data);
        }
    }
    public function csharp($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->csharp = $data;
        } else {
            $this->csharp = $this->formatRadioEmpty($data);
        }
    }
    public function css($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->css = $data;
        } else {
            $this->css = $this->formatRadioEmpty($data);
        }
    }
    public function basic($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->basic = $data;
        } else {
            $this->basic = $this->formatRadioEmpty($data);
        }
    }
    public function cplusplus($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->cplusplus = $data;
        } else {
            $this->cplusplus = $this->formatRadioEmpty($data);
        }
    }
    public function java($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->java = $data;
        } else {
            $this->java = $this->formatRadioEmpty($data);
        }
    }
    public function javame($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->javame = $data;
        } else {
            $this->javame = $this->formatRadioEmpty($data);
        }
    }
    public function js($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->js = $data;
        } else {
            $this->js = $this->formatRadioEmpty($data);
        }
    }
    public function jsx($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->jsx = $data;
        } else {
            $this->jsx = $this->formatRadioEmpty($data);
        }
    }
    public function lua($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->lua = $data;
        } else {
            $this->lua = $this->formatRadioEmpty($data);
        }
    }
    public function objc($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->objc = $data;
        } else {
            $this->objc = $this->formatRadioEmpty($data);
        }
    }
    public function swift($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->swift = $data;
        } else {
            $this->swift = $this->formatRadioEmpty($data);
        }
    }
    public function php($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->php = $data;
        } else {
            $this->php = $this->formatRadioEmpty($data);
        }
    }
    public function python($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->python = $data;
        } else {
            $this->python = $this->formatRadioEmpty($data);
        }
    }
    public function objpascal($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->objpascal = $data;
        } else {
            $this->objpascal = $this->formatRadioEmpty($data);
        }
    }
    public function ruby($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->ruby = $data;
        } else {
            $this->ruby = $this->formatRadioEmpty($data);
        }
    }
    public function actionscript($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->actionscript = $data;
        } else {
            $this->actionscript = $this->formatRadioEmpty($data);
        }
    }
    public function uno($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->uno = $data;
        } else {
            $this->uno = $this->formatRadioEmpty($data);
        }
    }
    public function MXML($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->MXML = $data;
        } else {
            $this->MXML = $this->formatRadioEmpty($data);
        }
    }
    public function visualeditor($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->visualeditor = $data;
        } else {
            $this->visualeditor = $this->formatRadioEmpty($data);
        }
    }
    public function xml($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->xml = $data;
        } else {
            $this->xml = $this->formatRadioEmpty($data);
        }
    }
    public function qml($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->qml = $data;
        } else {
            $this->qml = $this->formatRadioEmpty($data);
        }
    }
    public function UXMarkup($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->UXMarkup = $data;
        } else {
            $this->UXMarkup = $this->formatRadioEmpty($data);
        }
    }
    public function ads($data) {
        $this->ads = $data;
    }
    public function cd($data) {
        $this->cd = $data;
    }
    public function encryption($data) {
        $this->encryption = $data;
    }
    public function sdk($data) {
        $this->sdk = $data;
    }
    public function widgets($data) {
        $this->widgets = $data;
    }
    public function animations($data) {
        $this->animations = $data;
    }
    public function accelerometer($data) {
        $this->accelerometer = $data;
    }
    public function device($data) {
        $this->device = $data;
    }
    public function file($data) {
        $this->file = $data;
    }
    public function bluetooth($data) {
        $this->bluetooth = $data;
    }
    public function camera($data) {
        $this->camera = $data;
    }
    public function capture($data) {
        $this->capture = $data;
    }
    public function geolocation($data) {
        $this->geolocation = $data;
    }
    public function gestures_multitouch($data) {
        $this->gestures_multitouch = $data;
    }
    public function compass($data) {
        $this->compass = $data;
    }
    public function connection($data) {
        $this->connection = $data;
    }
    public function contacts($data) {
        $this->contacts = $data;
    }
    public function messages_telephone($data) {
        $this->messages_telephone = $data;
    }
    public function nativeevents($data) {
        $this->nativeevents = $data;
    }
    public function nfc($data) {
        $this->nfc = $data;
    }
    public function notification($data) {
        $this->notification = $data;
    }
    public function accessibility($data) {
        $this->accessibility = $data;
    }
    public function status($data) {
        $this->status = $data;
    }
    public function storage($data) {
        $this->storage = $data;
    }
    public function vibration($data) {
        $this->vibration = $data;
    }
    public function logo_img($data) {
        $this->logo_img = $data;
    }
    public function logo_name($data) {
        $this->logo_name = $data;
    }
    public function modified_by($data) {
        if($this->_action === PublicConstants::VALIDATE_FRAMEWORK) {
            $this->modified_by = $data;
        }
    }
    public function reference($data) {
        $this->reference = $data;
    }

    /* Dummy setters for private props */
    public function framework_id($data) {}
    public function comparison_data_last_update($data) {}
    public function state($data) {}
    /* ------------------------------- */

    public function setApproved($value) {
        $this->state = $value;
    }

    public function getApproved() {
        return $this->state;
    }

    public function isInvalidFramework() {
        return $this->_inValidProperty;
    }

    private function validateUrl($urlToBeValidated) {
        if(empty($urlToBeValidated)) {
            return "UNDEF";
        } elseif($this->_action === PublicConstants::DONT_VALIDATE_FRAMEWORK) {
            return $urlToBeValidated;   // just return object;
        } else {
            if (filter_var($urlToBeValidated, FILTER_VALIDATE_URL)) { 
                return $urlToBeValidated;
            } else {
                $this->_inValidProperty = true;
                return "INVALID URL";
            }
        }
    }

    private function validateText($textToBeValidated) {
        if(empty($textToBeValidated)) {
            return "";
        } elseif($this->_action === PublicConstants::DONT_VALIDATE_FRAMEWORK) {
            return $textToBeValidated;   // just return object;
        } else {
            $result = "";
            if(strpos($textToBeValidated, ",") !== false) { 
                $temp = explode(",", $textToBeValidated);
                foreach ($temp as $value) {
                    $result .= $this->applyTextFilter($value) . "|";
                }
                // strip last "|" from string
                $result = rtrim($result, "|");
            } elseif(strpos($textToBeValidated, "_") !== false) {
                $result = $this->applyTextFilter($textToBeValidated);
            } else {
                $result = $this->applyTextFilter($textToBeValidated) . "|";
            }
        }
        return $result;
    }

    private function applyTextFilter($data) {
        if (filter_var($data, FILTER_VALIDATE_REGEXP, array("options" => array("regexp"=>"/[a-z|A-Z|0-9|\-|\.|\(|\)| ]{1,50}/")))) { 
            return trim($data);
        } else {
            $this->_inValidProperty = true;
            return false;
        }
    }

    private function formatEmpty($data) {
        if($data === "false" or $data === "UNDEF") {
            return "";
        } else {
            return $data;
        }
    }

    private function formatRadioEmpty($data) {
        if($data === "UNDEF") {
            return "false";
        } else {
            return $data;
        }
    }

    private function isEmpty($var) {
        if(empty($var)) {
            return "UNDEF";
        } else {
            return $var;
        }
    }
}

?>