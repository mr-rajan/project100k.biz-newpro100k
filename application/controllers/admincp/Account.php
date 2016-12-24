<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Account extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$this->load->database();
			$this->load->model('base_model');
		}	
		
		//public function user dashboard
		public function index(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');				
			$data['title'] = 'Projects 100K';
			$data['loggedIn_adminInfo']	=	$this->session->userdata('loggedIn_adminInfo');
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/account/index', $data);
		}
		
		//public function to logout the user
		public function logout(){
			 if($this->session->userdata('loggedIn_adminInfo')!=''){
			 	  $this->session->unset_userdata('loggedIn_adminInfo');
				  $this->session->unset_userdata('downlineBreadCrumb');
				  $this->session->unset_userdata('post_bannerUrlCommonForm_Data');
				  //$this->session->sess_destroy();
				  $this->session->set_flashdata('admincp_login_flash_message', '<div class="alert alert-success">You have successfully logged out.</div>');
				  redirect('admincp');
			 }
		}
}

?>