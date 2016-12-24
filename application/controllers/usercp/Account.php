<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Account extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			if($this->session->userdata('loggedIn_usercpInfo')=='')redirect('home/login'); //checks if user session is not creted or not
			$this->load->database();
			$this->load->model('base_model');
		}	
		
		//public function user dashboard
		public function index(){
			$data['title'] 					= 	'Projects 100K';
			$data['loggedIn_usercpInfo']	=	$this->session->userdata('loggedIn_usercpInfo');
			$data['userName']				=	$this->session->userdata('loggedIn_usercpInfo')['name'];
			$this->load->view('templates/usercp/header', $data);
			$this->load->view('usercp/account/index', $data);
			$this->load->view('templates/usercp/footer', $data);
			
		}
		
		//public function to logout the user
		public function logout(){
			 if($this->session->userdata('loggedIn_usercpInfo')!=''){
			 	  $this->session->unset_userdata('loggedIn_usercpInfo');
				  //$this->session->sess_destroy();
				  $this->session->set_flashdata('login_flash_message', '<div class="alert alert-success">You have successfully logged out.</div>');
				  redirect('home/login');
			 }
		}
}

?>