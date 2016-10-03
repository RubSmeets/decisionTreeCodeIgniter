<?php 
include APPPATH . 'classes/PublicConstants.php';
include APPPATH . 'classes/Framework.php';

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PrivateCon extends CI_Controller {

    /* CONSTRUCTOR ********************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->library('session');
        $status = session_status();
        if($status == PHP_SESSION_NONE) {
            //There is no active session
            session_start();
        }
        
        if ($this->session->userdata('user_loggedin') != true) {
            $address = base_url() . "publicCon/";
            redirect($address,'location');
        }       
    }

    /*
     * VIEWS (routes)
     * */
    public function index() {
        $data = array('email' => $this->session->userdata('email'));
        $this->load->view('privateview/index', $data);
    }

    public function contribute() {
        $data = array('email' => $this->session->userdata('email'), 'admin' => $this->session->userdata('admin'));
        $this->load->view('privateview/contribute', $data);
    }

    public function compare() {
        $data = array('email' => $this->session->userdata('email'));
        $this->load->view('compare', $data);
    }

    /*
     * Async calls
     * */
    public function AJ_logout() {
        $this->session->unset_userdata('userID');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('idtoken');
        $this->session->unset_userdata('admin');
        $this->session->unset_userdata('user_loggedin');

        //remove all session data
        session_destroy();

        $errmsg = base_url() . "publicCon/";
        $retval = PublicConstants::SUCCESS;
        $this->echoResponse($errmsg, $retval);
        //redirect(site_url('public_homepage'),'location');
    }

    public function AJ_addFramework() {
        $errmsg = "";
        $retval = PublicConstants::SUCCESS;
        $framework = $_POST["framework"];
        $currentEditName = $_POST["currentEditName"];

        // convert to framework class (in class do validation)
        $frameworkObj = new Framework(0, true);
        foreach ($framework as $key) {
            $frameworkObj->$key["name"]($key["value"]);
        }
        log_message('debug', print_r($frameworkObj,TRUE));
        // Check if we have a valid object
        if($frameworkObj->isInvalidFramework()) {
            $errmsg = "Validation failed";
            $retval = PublicConstants::FAILED;
            $this->echoResponse($errmsg,$retval);
            return;
        }
        // Check if user exists and is not blocked
        $this->load->library('session');
        $this->load->model('UserModel');
        // check if user exists
        $userEmail = $this->session->email;
        $userObj = $this->UserModel->getUserByEmail($userEmail, $errmsg);
        if(is_a($userObj, 'User')) {
            if($userObj->isBlocked != 0) {
                $errmsg = "Contribution failed.";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg, $retval);
                return;
            } else {
                $frameworkObj->modified_by = $userObj->getDbID();
            }
        }
        // find if edited version of framework allready exist for user and update
		$this->load->model('FrameworksModel');
        if(empty($currentEditName)) {
            $existingFramework = PublicConstants::FAILED;
        } else {
		    $existingFramework = $this->FrameworksModel->getEditedFrameworkByName($currentEditName, $userObj->getDbID(), $errmsg);
        }

		if(is_object($existingFramework)) {
			// Update exisiting framework entry with new data
            $retval = $this->FrameworksModel->updateFramework($existingFramework->framework_id, $frameworkObj, $errmsg);
            if($retval != PublicConstants::SUCCESS) {
                // Database error
                $this->echoResponse($errmsg, $retval);
                return;
            }
		} else {
            // add framework to database with correct settings (contributor, approved settings)
            $retval = $this->FrameworksModel->storeFramework($frameworkObj, $errmsg);
            if($retval != PublicConstants::SUCCESS) {
                // Database error
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }
        // provide feedback to user
        $errmsg = "Framework is stored and waiting for approval";
        $this->echoResponse($errmsg,$retval);
    }

    public function AJ_getThumbFrameworks() {
		// Load database interaction model
		$this->load->model('FrameworksModel');

        // Check if user exists and is not blocked
        $this->load->library('session');
        $this->load->model('UserModel');
        // check if user exists
        $userEmail = $this->session->email;
        $userObj = $this->UserModel->getUserByEmail($userEmail, $errmsg);
        if(is_a($userObj, 'User')) {
            if($userObj->isBlocked != 0) {
                $errmsg = "retrieving thumb data for user failed.";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }

		$frameworks = $this->FrameworksModel->getEditFrameworkListThumbData($userObj->getDbID(),$errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $frameworks
		);
		echo json_encode($data);
	}

    public function AJ_getUserThumbFrameworks() {
		// Load database interaction model
		$this->load->model('FrameworksModel');

        // Check if user exists and is not blocked
        $this->load->library('session');
        $this->load->model('UserModel');
        // check if user exists
        $userEmail = $this->session->email;
        $userObj = $this->UserModel->getUserByEmail($userEmail, $errmsg);
        if(is_a($userObj, 'User')) {
            if($userObj->isBlocked != 0) {
                $errmsg = "retrieving thumb data for user failed.";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }

		$frameworks = $this->FrameworksModel->getPrivateEditFrameworkListThumbData($userObj->getDbID(),$errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $frameworks
		);
		echo json_encode($data);
	}

    public function AJ_getFramework() {
		$errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_GET["name"]) && isset($_GET["state"])) {
        	$frameworkName = $_GET["name"];
            $frameworkState = $_GET["state"];
    	} else {
			$errmsg = "No correct framework data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

        // Check if user exists and is not blocked
        $this->load->library('session');
        $this->load->model('UserModel');
        // check if user exists
        $userEmail = $this->session->email;
        $userObj = $this->UserModel->getUserByEmail($userEmail, $errmsg);
        if(is_a($userObj, 'User')) {
            if($userObj->isBlocked != 0) {
                $errmsg = "retrieving data for user failed.";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }

		// Load database interaction model
		$this->load->model('FrameworksModel');
		$framework = $this->FrameworksModel->getPrivateFrameworkByName($frameworkName, $frameworkState, $userObj->getDbID(), $errmsg);

		if(!is_a($framework, 'Framework')) {
            $errmsg = "retrieving framework for user failed.";
            $retval = PublicConstants::FAILED;
            $this->echoResponse($errmsg, $retval);
            return;
		}

        $this->echoResponse($framework, $retval);
	}

    public function AJ_removeFrameworkEdit() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_POST["name"]) && isset($_POST["identifier"])) {
        	$frameworkName = $_POST["name"];
            $frameworkId = $_POST["identifier"];
    	} else {
			$errmsg = "No correct framework data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

        // Check if user exists and is not blocked
        $this->load->library('session');
        $this->load->model('UserModel');
        // check if user exists
        $userEmail = $this->session->email;
        $userObj = $this->UserModel->getUserByEmail($userEmail, $errmsg);
        if(is_a($userObj, 'User')) {
            if($userObj->isBlocked != 0) {
                $errmsg = "retrieving data for user failed.";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }

		// Load database interaction model
		$this->load->model('FrameworksModel');
		$retval = $this->FrameworksModel->removeFrameworkByNameAndId($frameworkName, $frameworkId, $userObj->getDbID(), $errmsg);

		if($retval != PublicConstants::SUCCESS) {
			// Database error
			$this->echoResponse($errmsg, $retval);
			return;
		}
        // provide feedback to user
        $errmsg = "Framework edit is removed";
        $this->echoResponse($errmsg,$retval);
    }

    public function AJ_getThumbUsers() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;
        // No need to check if user is blocked because we are admin
        $this->load->model('UserModel');

        // Get active users
		$users = $this->UserModel->getActiveUsersThumbs($errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"users" => $users
		);
		echo json_encode($data);
    }

    public function AJ_getThumbBlockedUsers() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;
        // No need to check if user is blocked because we are admin
        $this->load->model('UserModel');

        // Get active users
		$users = $this->UserModel->getBlockedUsersThumbs($errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"users" => $users
		);
		echo json_encode($data);
    }

    public function AJ_manageUser() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_POST["email"]) && isset($_POST['action'])) {
        	$email = $_POST["email"];
            $action = $_POST['action'];
    	} else {
			$errmsg = "No correct user data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

        // Block the user by updating entry
        $this->load->model('UserModel');
		$retval = $this->UserModel->blockUnblockUserByEmail($email, $action, $errmsg);

		if($retval != PublicConstants::SUCCESS) {
			// Database error
			$this->echoResponse($errmsg, $retval);
			return;
		}
        // provide feedback to user
        if($action == 1) {$errmsg = "User is blocked";}
        else {$errmsg = "User is unblocked";}
        $this->echoResponse($errmsg,$retval);
    }

    public function AJ_getUserContributions() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_GET["email"])) {
        	$email = $_GET["email"];
    	} else {
			$errmsg = "No correct user data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

        $this->load->model('FrameworksModel');
		$contributions = $this->FrameworksModel->getContributionsOfUser($email, $errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $contributions
		);
		echo json_encode($data);
    }

    public function AJ_getAllPendingContributions() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

        $this->load->model('FrameworksModel');
		$pendingFrameworks = $this->FrameworksModel->getAllPendingContributionThumbs($errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $pendingFrameworks
		);
		echo json_encode($data);
    }

    public function AJ_getAdminFramework() {
		$errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_GET["framework_id"])) {
        	$frameworkId = $_GET["framework_id"];
    	} else {
			$errmsg = "No correct framework data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

		// Load database interaction model
		$this->load->model('FrameworksModel');
		$frameworks = $this->FrameworksModel->getPrivateFrameworkById($frameworkId, $errmsg);

		if(!is_a($frameworks[0], 'Framework')) {
            $errmsg = "retrieving framework for admin failed.";
            $retval = PublicConstants::FAILED;
            $this->echoResponse($errmsg, $retval);
            return;
		}
        // NOTE: $frameworks[0] contains requested and $frameworks[1] if set contains refered
        $this->echoResponse($frameworks, $retval);
	}

    private function echoResponse($errmsg, $retval) {
		$data = array(
			'srvResponseCode' => $retval,
			'srvMessage' => $errmsg
		);
		echo json_encode($data);
	}

}
