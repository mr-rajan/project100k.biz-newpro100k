<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Downline extends CI_Controller{
		
		private $userSessInfo;
		public function __construct(){
			parent::__construct();
			//check if admin is logged in or not.	
			if($this->session->userdata('loggedIn_usercpInfo')=='')redirect('home/login'); //checks if user session is not creted or not
			else $this->userSessInfo	=	$this->session->userdata('loggedIn_usercpInfo');
			$this->load->model('base_model');
			$this->load->model('usercp/downline_model');	
					
		}
		//function to get lists of all users
		public function index(){
			$data['title'] 			=	'Projects 100K';
			$data['userName']		=	$this->userSessInfo['name'];	
			$data['downlineLists']	=	$this->downline_model->get_all_downline($this->userSessInfo['id']);
			$this->load->view('templates/usercp/header', $data);
			$this->load->view('usercp/downline/index', $data);
			$this->load->view('templates/usercp/footer', $data);		
		}
		
		//function to authenticate and login the user to account
		public function authenticate()
		{	
		    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');						
				if ($this->form_validation->run() == FALSE){
					$this->session->set_flashdata('admincp_login_flash_message', '<div class="alert alert-danger">'.validation_errors().'</div>');			
					redirect('admincp');
				}
				else{
						$username	=	$this->security->xss_clean($this->input->post('username'));
						$password	=	sha1($this->security->xss_clean($this->input->post('password')));
						$adminInfo	=	$this->base_model->is_Record_Exists('tbl_admin','',array('username'=>$username,'password'=>$password));						
						if(!empty($adminInfo)){							
								$loggedIn_adminInfo	=	array('id'=>$adminInfo->id,
															  'name'=>ucwords($adminInfo->username),
															  'email'=>$adminInfo->email);
								$this->session->set_userdata('loggedIn_adminInfo',$loggedIn_adminInfo);
								$this->session->set_flashdata('admincp_dashboard_flash_message', '<div class="alert alert-success">
								Welcome back, you have successfully logged-in.</div>');
								redirect('admincp/account');
						}
						else{
							$this->session->set_flashdata('admincp_login_flash_message', '<div class="alert alert-danger">The username/password you entered is invalid. </div>');
							redirect('admincp');
						}
				}
			}
			else{
				$this->session->set_flashdata('admincp_login_flash_message', '<div class="alert alert-danger">Please provide username and password.</div>');
				redirect('admincp');
			}
				
		}
		
		//function to add new user
		public function addnew(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 		= 'Projects 100K';
			$data['countries_lists']	=	$this->base_model->get_All_Records('tbl_countries',array('*'),array('publish'=>'Yes'));		
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/user/form', $data);
			$this->load->view('templates/admincp/footer', $data);	
		}
		
		
		
		
}

?>