<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class User extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->load->model('base_model');			
		}
		//function to authenticate and login the user to account
		public function authenticate()
		{
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
				$this->form_validation->set_rules('g-recaptcha-response', 'Security', 'trim|required|xss_clean');		
				if ($this->form_validation->run() == FALSE){
					$this->session->set_flashdata('login_flash_message', '<div class="alert alert-danger">'.validation_errors().'</div>');			
					redirect('home/login');
				}
				else{
						$email		=	$this->security->xss_clean($this->input->post('email'));
						$password	=	sha1($this->input->post('password'));
						$userInfo	=	$this->base_model->is_Record_Exists('','',array('email'=>$email,'password'=>$password,'isDeleted'=>'no'));	
						//print'<pre/>';print_r($userInfo);die;
						if(!empty($userInfo)){							
								if($userInfo->status=='Active'){
										$loggedIn_userInfo	=	array('id'=>$userInfo->id,
																	  'name'=>ucwords($userInfo->first_name),
																	  'email'=>$userInfo->email);
										$this->session->set_userdata('loggedIn_usercpInfo',$loggedIn_userInfo);
										$this->session->set_flashdata('usercp_dashboard_flash_message', '<div class="alert alert-success">Welcome back, you have successfully logged-in.</div>');
										redirect('usercp/account');
								}
								elseif($userInfo->status=='Inactive'){
									$this->session->set_flashdata('login_flash_message', '<div class="alert alert-warning">Dear '.ucwords($userInfo->first_name.' '.$userInfo->last_name).', <br/>Your account is not activated. Click to <a href="'.base_url().'home/regeneratelink">here</a> to activate your account.</div>');
									redirect('home/login');							
								}
								elseif($userInfo->status=='Suspend'){
									$this->session->set_flashdata('login_flash_message', '<div class="alert alert-warning">Dear '.ucwords($userInfo->first_name.' '.$userInfo->last_name).', <br/>Your account is suspended. Please contact to administrator of site to activate your account.</div>');
									redirect('home/login');		
								}
						}
						else{
							$this->session->set_flashdata('login_flash_message', '<div class="alert alert-danger">The email/password you entered is invalid. </div>');
							redirect('home/login');
						}
				}
		}
						
}

?>