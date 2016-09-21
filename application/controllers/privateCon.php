<?php 
include APPPATH . 'classes/PublicConstants.php';

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
        $data = array('email' => $this->session->userdata('email'));
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

    private function echoResponse($errmsg, $retval) {
		$data = array(
			'srvResponseCode' => $retval,
			'srvMessage' => $errmsg
		);
		echo json_encode($data);
	}

}
