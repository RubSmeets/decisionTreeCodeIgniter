<?php 
include APPPATH . 'classes/PublicConstants.php';
include APPPATH . 'classes/Framework.php';

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The private controller contains the routes and AJAX request entry points of the private pages. 
 * Only logged in user can make calls to this controller. If a loggout or non-registered user tries
 * to use these API functions, a page redirect is returned.
 */
class PrivateCon extends CI_Controller {

    /* CONSTRUCTOR ********************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->library('session');
        $status = session_status();
        // Check if there is an active (user) session
        if($status == PHP_SESSION_NONE) {
            //There is no active session
            session_start();
        }
        
        // Check if the user is logged in and redirect if not true
        if ($this->session->userdata('user_loggedin') != true) {
            $address = base_url() . "PublicCon/";
            redirect($address,'location');
        }       
    }

    /*
     * VIEWS (routes)
     * */
    public function index() {
        $data = array('email' => $this->session->userdata('email'));
        $this->load->view('index', $data);
    }

    public function searchtool() {
        $data = array('email' => $this->session->userdata('email'));
        $this->load->view('searchTool', $data);
    }

    public function contribute() {
        $data = array('email' => $this->session->userdata('email'), 'admin' => $this->session->userdata('admin'));
        $this->load->view('privateview/contribute', $data);
    }

    public function compare() {
        $data = array('email' => $this->session->userdata('email'));
        $this->load->view('compare', $data);
    }

	/*****************************************************
	 * AJAX request entry points 
	 *****************************************************/
    
    /**
     * Logout a user. This is done in code-igniter by destroying the session.
     * NOTE: to make sure the session data is removed, we unset every data member explicitly.
     *@return redirect to public page
     */
    public function AJ_logout() {
        $this->session->unset_userdata('userID');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('idtoken');
        $this->session->unset_userdata('admin');
        $this->session->unset_userdata('user_loggedin');

        //remove all session data
        session_destroy();

        $errmsg = base_url() . "PublicCon/";
        $retval = PublicConstants::SUCCESS;
        //$retval = PublicConstants::FAILED; for testing purposes SHOULD BE REMOVED 
        $this->echoResponse($errmsg, $retval);
    }

    /**
     * Request that checks if a particular framework name allready exists in the database. this 
     * is necassary because the framework name should be unique. 
     * 
     * @param name The name we would like check 
     * @return True or False or ERROR if something went wrong
     */
    public function AJ_frameworkExists() {
        $errmsg = "";
        $retval = PublicConstants::SUCCESS;
        
        if(isset($_GET["name"])) {
        	$frameworkName = $_GET["name"];
    	} else {
			$errmsg = "No correct name specified";
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
                $errmsg = "failed.";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }
        // find if edited version of framework allready exist for user and update
		$this->load->model('FrameworksModel');
        $existingFramework = $this->FrameworksModel->getFrameworkByName($frameworkName, $errmsg);

        // Perform double check to see if the name exists.
        if(is_object($existingFramework)) {
            $orig = strtolower($existingFramework->framework);
            $test = strtolower($frameworkName);
            if($orig === $test) { $unique = "false"; }
            else { $unique = "true"; }
        } else {
            $unique = "true";
        }
        $errmsg = array( "msg" => $unique );
        $this->echoResponse($errmsg,$retval);
    }

    /**
     * Request that validates framework data and creates a new framework entry inside the database after succesful
     * validation.
     * 
     * @param all the framework fields (supplied as JSON)
     * @return MESSAGE if success or ERROR if something went wrong
     */
    public function AJ_addFramework() {
        $errmsg = "";
        $retval = PublicConstants::SUCCESS;
        //Check if the REQUERED post parameters are set
        if(isset($_POST["framework"]) && isset($_POST["currentEditName"])) {
        	$framework = $_POST["framework"];
            $currentEditName = $_POST["currentEditName"];
    	} else {
			$errmsg = "No correct framework data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

        // convert to framework class (in class do validation)
        $frameworkObj = new Framework(0, PublicConstants::VALIDATE_FRAMEWORK);
        foreach ($framework as $key) {
            $frameworkObj->$key["name"]($key["value"]);
        }
        
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
            $frameworkObj->logo_name = $existingFramework->logo_name;
            $retval = $this->FrameworksModel->updateFramework($existingFramework->framework_id, $frameworkObj, $errmsg);
            if($retval != PublicConstants::SUCCESS) {
                // Database error
                $this->echoResponse($errmsg, $retval);
                return;
            }
		} else {
            // check if the logo is set and if there is a reference available
            if($frameworkObj->reference !== 0) {
                if($frameworkObj->logo_name === "notfound.png") {
                    // Get logo_name of referenced framework 
                    $retval = $this->FrameworksModel->getFrameworkLogoById($frameworkObj->reference, PublicConstants::STATE_APPROVED, $errmsg);
                    if(is_array($retval)) $frameworkObj->logo_name = $retval["logo_name"];
                }
            }
            // add framework to database with correct settings (contributor, approved settings)
            $retval = $this->FrameworksModel->storeFramework($frameworkObj, $errmsg);
            if($retval != PublicConstants::SUCCESS) {
                // Database error
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }
        // provide feedback to user
        $errmsg = array(
            "msg" => "Framework is stored and waiting for approval",
            "framework" => $frameworkObj->framework
        );
        $this->echoResponse($errmsg,$retval);
    }

    /**
     * Retrieves a subset of framework data that is used by the edit framework jQuery datatable. 
     * The data returned is specific to each user. Since a user can only make one edit entry of
     * each approved framework. Subsequent edits to the same framework (before an edit APPROVAL)
     * can be done by modifying their existing edit submission.
     * 
     * @return The user filtered APPROVED frameworks thumbnail data
     */
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

        // Retrieve thumbnail data of approved frameworks filtered for a particular user
		$frameworks = $this->FrameworksModel->getEditFrameworkListThumbData($userObj->getDbID(),$errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $frameworks
		);
		echo json_encode($data);
	}

    /**
     * Retrieves a subset of framework data that is used by the pending framework jQuery datatable. 
     * The data returned is specific to each user and returns all the frameworks that the user has 
     * made modifications to.
     * 
     * @return The user pending framework data
     */
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

        // Retrieve thumbnail data of pending contributions of a particular user
		$frameworks = $this->FrameworksModel->getPrivateEditFrameworkListThumbData($userObj->getDbID(),$errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $frameworks
		);
		echo json_encode($data);
	}

    /**
     * Retrieve all the information of one framework specified by the framework name and
     * the current state of the contribution (APPROVED, DECLINED, OUTDATED, PENDING)
     * 
     * @param name Name of framework 
     * @param state of the framework 
     * @return success -> msg: framework data, serverCode: success | error -> msg: error msg, serverCode: failed
     */
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

        // Check if database return valid framework object or a status "error" code
		if(!is_a($framework, 'Framework')) {
            $errmsg = "retrieving framework for user failed.";
            $retval = PublicConstants::FAILED;
            $this->echoResponse($errmsg, $retval);
            return;
		}
        // Echo respone in default message format
        $this->echoResponse($framework, $retval);
	}

    /**
     * Retrieves all the data of a framework that has been processed by an admin. This
     * AJAX request is called by selecting a processed contribution in the processed frameworks jQuery dataTable.
     * 
     * @param id Database ID of framework 
     * @return success -> msg: framework data, serverCode: success | error -> msg: error msg, serverCode: failed
     */
    public function AJ_getProcessedFramework() {
		$errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_GET["id"])) {
        	$frameworkId = $_GET["id"];
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
		$framework = $this->FrameworksModel->getProcessedFrameworkById($frameworkId, $errmsg);

		if(!is_a($framework, 'Framework')) {
            $errmsg = "retrieving framework for user failed.";
            $retval = PublicConstants::FAILED;
            $this->echoResponse($errmsg, $retval);
            return;
		}

        $this->echoResponse($framework, $retval);
	}

    /**
     * Allows the user to delete a contribution that is still pending (not processed by an admin). The
     * framework entry is REMOVED from the database and no backups are kept. If a logo was added, the
     * logo will be deleted as well!
     *
     *@param name name of a framework 
     *@param identifier database ID of framework 
     *@return success -> msg: success message, serverCode: success | error -> msg: error msg, serverCode: failed
     */
    public function AJ_removeFrameworkEdit() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

        // Check if request parameters are set
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
		$retval = $this->FrameworksModel->removeFrameworkByNameAndId($frameworkName, $frameworkId, $userObj->getDbID(), $errmsg); //returns logo name
		if($retval == PublicConstants::FAILED) {
			// Database error
			$this->echoResponse($errmsg, $retval);
			return;
		}
        // Delete logo if found
        if(!empty($retval)) {
            $this->deleteLogo($retval);
        }

        // provide feedback to user
        $retval = PublicConstants::SUCCESS;
        $errmsg = "Framework edit is removed";
        $this->echoResponse($errmsg,$retval);
    }

    /**
     * Retrieve all active user data that is shown in a jQuery dataTable as a row entry.
     * 
     *@return thumb data containing user active user information
     */
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

    /**
     * Retrieve all blocked users that are shown in a jQuery dataTable as a row entry.
     * 
     *@return thumb data containing all the blocked users information
     */
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

    /**
     * Method for blocking or unBlocking a specific user.
     *
     *@param email the user's email
     *@param action block or unblock
     *@return success -> msg: success message, serverCode: success | error -> msg: error msg, serverCode: failed
     */
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

    /**
     * Retrieve a list of frameworks (thumb data) that represents all the contributions a specific user has 
     * made. The list is shown in a jQuery DataTable
     *
     *@param email the user's email
     *@return success -> msg: framework data | error -> msg: error msg, serverCode: failed
     */
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

    /**
     * Retrieve a list of frameworks (thumb data) that contains all the pending contributions.  
     * This means all the contributions that are waiting on approval. The list is shown in a 
     * jQuery DataTable
     *
     *@return success -> msg: framework data | error -> msg: error msg, serverCode: failed
     */
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

    /**
     * Retrieve a list of frameworks (thumb data) that contains all the processed contributions.  
     * This means all the contributions from a user that have been approved. The list is shown in a 
     * jQuery DataTable
     *
     *@return success -> msg: framework data | error -> msg: error msg, serverCode: failed
     */
    public function AJ_getAllProcessedContributions() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

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

        // Load database interaction model
		$this->load->model('FrameworksModel');
		$frameworks = $this->FrameworksModel->getProcessedFrameworkListThumbData($userObj->getDbID(),$errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $frameworks
		);
		echo json_encode($data);
    }

    /**
     * Method for getting a pending framework that is subjected to the approval process. 
     * 
     *@param framework_id the database ID of the framework 
     *@return success -> msg: framework and refered framework as reference to compare against, serverCode: success | error -> msg: error msg, serverCode: failed
     */
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

    /**
     * Used by admins to approve or decline a contribution of a framework. 
     * The request parameters contain all the information of the framework, including changes made by
     * an admin. Therefore the data has to be verified before submitting it to the database.
     * 
     *@param toolId framework database ID
     *@param framework all the framework data
     *@param action APPROVE or DECLINE
     *@return success -> msg: success message, serverCode: success | error -> msg: error msg, serverCode: failed
     */
    public function AJ_submitContribution() {
        $errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(!empty($_POST["toolId"])) {  // should use empty to check here for some reason
        	$frameworkId = $_POST["toolId"];
            $framework = $_POST["framework"];
            $action = $_POST["action"];
    	} else {
			$errmsg = "No correct framework data specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

        // convert to framework class (in class do validation)
        $frameworkObj = new Framework($frameworkId, PublicConstants::VALIDATE_FRAMEWORK);
        foreach ($framework as $key) {
            $frameworkObj->$key["name"]($key["value"]);
        }

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
            if(!$userObj->admin) {
                $errmsg = "User is no admin";
                $retval = PublicConstants::FAILED;
                $this->echoResponse($errmsg,$retval);
                return;
            }
        }
        
		$this->load->model('FrameworksModel');
        // Get exisiting framework logo entry
        $result = $this->FrameworksModel->getFrameworkLogoById($frameworkId, PublicConstants::STATE_AWAIT_APPROVAL, $errmsg);
        // Update exisiting framework entry with new data and approve
        $frameworkObj->logo_name = $result["logo_name"];

		if($action == PublicConstants::APPROVE_TOOL) {
            // Approve exisiting contribution
            $retval = $this->FrameworksModel->approveFramework($frameworkId, $frameworkObj, $errmsg);
            if($retval != PublicConstants::SUCCESS) {
                // Database error
                $this->echoResponse($errmsg, $retval);
                return;
            }
		} else {
            // Decline existing contribution
            $retval = $this->FrameworksModel->declineFramework($frameworkId, $frameworkObj->admin_remark, $errmsg);
            if($retval != PublicConstants::SUCCESS) {
                // Database error
                $this->echoResponse($errmsg, $retval);
                return;
            }
        }
        // provide feedback to user
        $errmsg = "Framework review submission completed succesfull";
        $this->echoResponse($errmsg,$retval);
    }

    /**
     * Method for uploading an image to the database. The image is first verified to meet the specified criteria (size, format)
     * After verification the image is renamed and moved to the img/logos/ folder. The name contains the database id and a 
     * time stamp of submission.
     * NOTE: the time stamp is done to create a cache buster. This forces the browser to download the new logo.
     *
     *@param logo a png logo image of a framework
     */
    public function AJ_uploadLogo() {
        $errmsg = "";
		$retval = PublicConstants::FAILED;
        if(isset($_FILES["logo"]["type"])) {
            $validextensions = array("png");
            $temporary = explode(".", $_FILES["logo"]["name"]);
            $file_extension = end($temporary);
            if (($_FILES["logo"]["type"] == "image/png")
            && ($_FILES["logo"]["size"] < 100000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
                if ($_FILES["logo"]["error"] > 0) {
                    $errmsg = "Return Code: " . $_FILES["logo"]["error"] . "<br/><br/>";
                } else {
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
                    // Determine new name of logo [framework_id.png]
                    $retval = $this->FrameworksModel->getFrameworkLogoByName($_POST["framework"], $userObj->getDbID(), PublicConstants::STATE_AWAIT_APPROVAL, $errmsg);
                    if(!is_array($retval)) {
                        // Database error
                        $this->echoResponse($errmsg, $retval);
                        return;
                    }
                    // delete old logo
                    //$this->deleteLogo($retval["logo_name"]);

                    $logoName = $retval["id"] . ".png";
                    $logoNameCB = $retval["id"] . "_" . filemtime($_FILES['logo']['tmp_name']) . ".png"; // cache busted logo name
                    // Update framework logo 
                    $retval = $this->FrameworksModel->updateFrameworkLogo($retval["id"], $logoName, $logoNameCB, $errmsg);
                    if($retval == PublicConstants::FAILED) {
                        // Database error
                        $this->echoResponse($errmsg, $retval);
                        return;
                    }

                    $sourcePath = $_FILES['logo']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = PublicConstants::IMG_PATH . $logoNameCB; // Target path where file is to be stored (cache busted name)
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                        
                    $errmsg = "Logo upload succesfull";
                    $retval = PublicConstants::SUCCESS;
                }
            } else {
                $errmsg = "Invalid file Size or Type";
            }
        } else {
           $errmsg = "no file specified";
        }
        $this->echoResponse($errmsg,$retval);
    }

    /**
     * Same method as the "AJ_uploadLogo" only this one is used by admins only to overwrite the user contributed logo.
     *
     *@param logo a png logo image of a framework
     */
    public function AJ_adminUploadLogo() {
        $errmsg = "";
		$retval = PublicConstants::FAILED;
        if(isset($_FILES["logo"]["type"])) {
            $validextensions = array("png");
            $temporary = explode(".", $_FILES["logo"]["name"]);
            $file_extension = end($temporary);
            if (($_FILES["logo"]["type"] == "image/png")
            && ($_FILES["logo"]["size"] < 100000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
                if ($_FILES["logo"]["error"] > 0) {
                    $errmsg = "Return Code: " . $_FILES["logo"]["error"] . "<br/><br/>";
                } else {
                    // Check if user is admin
                    $this->load->library('session');
                    $this->load->model('UserModel');

                    $userEmail = $this->session->email;
                    $userObj = $this->UserModel->getUserByEmail($userEmail, $errmsg);
                    if(is_a($userObj, 'User')) {
                        if(!$userObj->admin) {
                            $errmsg = "User is no admin";
                            $retval = PublicConstants::FAILED;
                            $this->echoResponse($errmsg,$retval);
                            return;
                        }
                    }

                    // Load database interaction model
		            $this->load->model('FrameworksModel');
                    // Determine new name of logo [framework_id.png]
                    $retval = $this->FrameworksModel->getFrameworkLogoById($_POST["framework_id"], PublicConstants::STATE_AWAIT_APPROVAL, $errmsg);
                    if(!is_array($retval)) {
                        // Database error
                        $this->echoResponse($errmsg, $retval);
                        return;
                    }
                    // delete old logo
                    //$this->deleteLogo($retval["logo_name"]);

                    $logoName = $retval["id"] . ".png";
                    $logoNameCB = $retval["id"] . "_" . filemtime($_FILES['logo']['tmp_name']) . ".png"; // cache busted logo name
                    // Update framework logo in database
                    $retval = $this->FrameworksModel->updateFrameworkLogo($retval["id"], $logoName, $logoNameCB, $errmsg);
                    if($retval == PublicConstants::FAILED) {
                        // Database error
                        $this->echoResponse($errmsg, $retval);
                        return;
                    }

                    $sourcePath = $_FILES['logo']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = PublicConstants::IMG_PATH . $logoNameCB; // Target path where file is to be stored (cache busted name)
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                        
                    $errmsg = "Logo upload succesfull";
                    $retval = PublicConstants::SUCCESS;
                }
            } else {
                $errmsg = "Invalid file Size or Type";
            }
        } else {
           $errmsg = "no file specified";
        }
        $this->echoResponse($errmsg,$retval);
    }

    /**
     * Echos a standard response to webpage. The response contains a message and serverCode.
     */
    private function echoResponse($errmsg, $retval) {
		$data = array(
			'srvResponseCode' => $retval,
			'srvMessage' => $errmsg
		);
		echo json_encode($data);
	}

    /**
     * Format a string to exclude special charaters and convert to lower case
     * NOTE: currently not used
     */
    private function formatString($str) {
        $temp = preg_replace('/[^0-9a-zA-Z]+/', '', $str);
        return strtolower($temp);
    }

    /**
     * Function for deleting a logo (image) in the img/logos/ folder
     */
    private function deleteLogo($logoName) {
        // Check if logoName equals default logo (if not delete logo)
        if (strpos($logoName, PublicConstants::DEFAULT_LOGO_NAME) === false) {
            if (file_exists(PublicConstants::IMG_PATH . $logoName)) {
                unlink(PublicConstants::IMG_PATH . $logoName);
            }
        }
    }

}
