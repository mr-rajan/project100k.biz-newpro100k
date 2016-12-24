<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Account extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->load->model('base_model');
		}
		//function to authenticate and activate the user account
		public function activate(){				
			 $param	=	$this->uri->segment(3);			 
			 if($param!=''){		
						$parsedKey		=	str_replace(array('-', '_', '~'), array('+', '/', '='),$param);
						$decryptedKey	=	$this->encrypt->decode($parsedKey);						
						$userInfo 		= 	$this->base_model->is_Record_Exists('tbl_users',array('id','first_name','last_name','status'), 
																				array('account_encryption_key'=>$decryptedKey));							
						if(!empty($userInfo) && $userInfo->id!=''){
						     if($userInfo->status === 'Inactive'){
									 $userData['status'] = 'Active';
									 $updateStatus = $this->base_model->updateUser($userData,'tbl_users',array('id'=>$userInfo->id,'account_encryption_key'=>$decryptedKey));
									 if($updateStatus){
											$this->session->set_flashdata('login_flash_message', '<div class="alert alert-success">Congratulations!! 
											Your account is activated please login to your account.</div>');			
											redirect('home/login');
									 }
									 else{
											$this->session->set_flashdata('regenerate_account_link_flash_message', 
											'<div class="alert alert-danger">Ohh! something went wrong. Please try again!</div>');			
											redirect('home/login');
									 }
							 }
							 else{	
											$this->session->set_flashdata('login_flash_message', 
											'<div class="alert alert-warning">Dear '.ucwords($userInfo->first_name.' '.$userInfo->last_name).'<br/>
											Your account is already activated. Please login to your account.</div>');			
											redirect('home/login');
							     }
						}
						else{
						$this->session->set_flashdata('regenerate_account_link_flash_message', '<div class="alert alert-danger">Ohh! this link seems to be damaged. To re-generate account activation link please provide below informations.</div>');			
							redirect('home/regeneratelink');
						}
			 }
		}
		
		//public function user dashboard
		public function dashboard(){
			if($this->session->userdata('loggedIn_userInfo')!=''){					
					$data['title'] = 'Projects 100K';
					$data['loggedIn_userInfo']	=	$this->session->userdata('loggedIn_userInfo');
					$this->load->view('templates/header', $data);
					$this->load->view('account/dashboard', $data);
					$this->load->view('templates/footer', $data);
			}
			else{
					redirect('home/login');
			}
			
		}
		
		//public function to logout the user
		public function logout(){
			 if($this->session->userdata('loggedIn_userInfo')!=''){
			 	  $this->session->unset_userdata('loggedIn_userInfo');
				  //$this->session->sess_destroy();
				  $this->session->set_flashdata('home_flash_message', '<div class="alert alert-success">You have successfully logged out.</div>');
				  redirect('home');
			 }
		}
}

?>