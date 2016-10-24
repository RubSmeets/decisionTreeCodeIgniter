<?php
include APPPATH . 'classes/PublicConstants.php';
include APPPATH . 'classes/FrameworkMarkup.php';
include APPPATH . 'classes/FormatKey.php';

defined('BASEPATH') OR exit('No direct script access allowed');

class PublicCon extends CI_Controller {
	const CONST_CLIENT_ID = "814864177982-qhde0ik7hkaandtoaaa0515niinslg94.apps.googleusercontent.com";
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	/* Visiting the base url will redirect to the base view
	 * which in our case is index.php (.php can be omitted)
	 * NOTE: we can specify a different name for the view.
	 * this way there is no connection with the base_url and
	 * the view being loaded.
	 */
	public function index() {
		$this->load->view('index');
	}
	/* Other routes that are called on the index.php page
	 * are captured here and redirected to the desired view
	 */
	public function compare() {
		$this->load->view('compare');
	}

	public function AJ_getFrameworks() {
		// Load database interaction model
		$this->load->model('FrameworksModel');
		$frameworks = $this->FrameworksModel->getPreFormattedFrameworks($errmsg);

		// Custom response
		echo json_encode($frameworks);

	}

	public function AJ_login() {
		// get parameters
		$idtoken = $_POST["idtoken"];

		$errmsg = "";
		$retval = PublicConstants::SUCCESS;
		$user = null;
		$userObj = null;

		// check validity of token with google API end-point
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $idtoken,
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		if($resp) {
			$user = json_decode($resp);
			if(isset($user->error_description)) {
				$errmsg = "Token verification failed";
			}
		} else {
			$errmsg = "Verification server not responding";
		}
		// Close request to clear up some resources
		curl_close($curl);

		// If error message set then return
		if($errmsg) {
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

		// Check if the aud value is equal to our client ID
		if($user->aud === self::CONST_CLIENT_ID) {
			//Store the user information in DB if it does not exist
			$this->load->model('UserModel');
			// check if user exists
			$userObj = $this->UserModel->getUserByEmail($user->email, $errmsg);
			if(is_a($userObj, 'User')) {
				if($userObj->isBlocked != 0) {
					$errmsg = "Login failed";
					$retval = PublicConstants::FAILED;
					$this->echoResponse($errmsg, $retval);
					return;
				} else {
					// update user token and visit count
					$userObj->accessToken = $idtoken;
					$userObj->visitCount += 1;
					$retval = $this->UserModel->updateUser($userObj, $errmsg);
				}
			} else {
				// create new
				$retval = $this->UserModel->createUser($user, $idtoken, $errmsg);
				$userObj = $this->UserModel->getUserByEmail($user->email, $errmsg);
			}
		}

		if($retval != PublicConstants::SUCCESS) {
			// Database error
			$this->echoResponse($errmsg, $retval);
			return;
		}
		
		// Create session
		$userData = array(
			'userID' => $user->sub,
			'username' => $user->name,
			'email' => $user->email,
			'idtoken' => $idtoken,
			'admin' => $userObj->admin,
			'user_loggedin' => true
		);
		$this->load->library('session');
		$this->session->set_userdata($userData);
		
		// send response redirect to private index page
		$errmsg = base_url() . "PrivateCon/";
		$this->echoResponse($errmsg, $retval);
	}

	public function AJ_getThumbFrameworks() {
		// Load database interaction model
		$this->load->model('FrameworksModel');

		$frameworks = $this->FrameworksModel->getFrameworkListThumbData($errmsg);

		// Custom response for the jQuery datatables
		$data = array(
			"frameworks" => $frameworks
		);
		echo json_encode($data);
	}

	public function AJ_getFramework() {
		$errmsg = "";
		$retval = PublicConstants::SUCCESS;

		if(isset($_GET["keyword"])) {
        	$frameworkName = $_GET["keyword"];
    	} else {
			$errmsg = "No framework specified";
			$retval = PublicConstants::FAILED;
			$this->echoResponse($errmsg, $retval);
			return;
		}

		// Load database interaction model
		$this->load->model('FrameworksModel');
		$framework = $this->FrameworksModel->getFrameworkByName($frameworkName, $errmsg);

		if(is_object($framework)) {
			$markupGenerator = new FrameworkMarkup($framework);
			$resp = $markupGenerator->createFrameworkCompareMarkup();
		}

		echo json_encode($resp);
		//echo $this->echoResponse($errmsg, $retval);
	}

	/*
	 * Helper functions
	 * */
	private function echoResponse($errmsg, $retval) {
		$data = array(
			'srvResponseCode' => $retval,
			'srvMessage' => $errmsg
		);
		echo json_encode($data);
	}
}
	
