<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Home extends CI_Controller{
	
	public function __construct(){
	    parent::__construct();	
		$this->load->database();
        $this->load->model('base_model');
	}
	public function index(){		
		$data['title'] = 'Projects 100K';			
		$this->load->view('templates/header', $data);
		$this->load->view('home/index', $data);
		$this->load->view('templates/footer', $data);
	}
	
	//function to re-generate the account activation link
	public function regeneratelink(){			
		$data['title'] = 'Projects 100K';
		$this->load->view('templates/header', $data);
		$this->load->view('home/regeneratelink', $data);
		$this->load->view('templates/footer', $data);
	}
	
	//function to show the login page
	public function login(){			
		$data['title'] = 'Projects 100K';
		$this->load->view('templates/header', $data);
		$this->load->view('home/login', $data);
		$this->load->view('templates/footer', $data);
	}
	
	//function to save the new reset password
	public function savenewpassword(){
		
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('conf_password', 'Re-Type Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('g-recaptcha-response', 'Security', 'trim|required|xss_clean');			
		if ($this->form_validation->run() == FALSE){						
			$this->session->set_flashdata('resetpassword_flash_message', '<div class="alert alert-danger">'.validation_errors().'</div>');			
			redirect('home/resetpassword/'.$this->security->xss_clean($this->input->post('param')));
		}
		else{
			
			$reCaptcha = $this->input->post('g-recaptcha-response');
			$secretKey = "6LcxuQoUAAAAAIR5Qe3RI_Uwj2bWHXhIVfDOGVYP";
			$ip = $_SERVER['REMOTE_ADDR'];
			$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$reCaptcha."&remoteip=".$ip);
			$responseKeys = json_decode($response,true);
			if($responseKeys['success'] == 1)
			{	
			    $password		=	$this->security->xss_clean($this->input->post('password'));
				$conf_password	=	$this->security->xss_clean($this->input->post('conf_password'));
				$pattern 		= 	'/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/';
				if(!preg_match($pattern,$password)){					
					$this->session->set_flashdata('resetpassword_flash_message', '<div class="alert alert-danger">Password should contain at least one capital character, one small character, one number, one special charater ($@$!%*?&) and must be of minimum 8 characters of length.</div>');
					redirect('home/resetpassword/'.$this->security->xss_clean($this->input->post('param')));
				
				}
				elseif($password !== $conf_password){
										
					$this->session->set_flashdata('resetpassword_flash_message', '<div class="alert alert-danger">Re-Type password must match with password.</div>');
					redirect('home/resetpassword/'.$this->security->xss_clean($this->input->post('param')));
				}
				else{
						if($this->security->xss_clean($this->input->post('user'))!=''){
						$userID			=	$this->encrypt->decode($this->security->xss_clean($this->input->post('user')));
						$record_Info	=	$this->base_model->is_Record_Exists('tbl_users',array('*'),array('id'=>$userID));						
						if(!empty($record_Info) && $record_Info->id!='')
						{
							if($record_Info->status === 'Active'){
							$userData['password']			=	sha1($this->security->xss_clean($this->input->post('password')));
							$userData['passwordreset_key']	=	'';
							$updateStatus					=	$this->base_model->updateUser($userData,'tbl_users',
																array('id'=>$record_Info->id,'email'=>$record_Info->email));						
						//create message
						$message = 'Dear '.ucwords($record_Info->first_name.' '.$record_Info->last_name).',<br/><br/>
									Your account password has been reset successfully. 
									Please use below password to login your account:<br/><br/>
									Password:	'.$this->security->xss_clean($this->input->post('password')).
									'<br/><br/> This is an auto-generated email please do not reply.<br/><br/><br/><br/>
									Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';
						
						$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
						$this->email->to($record_Info->email, $record_Info->first_name);
						$this->email->subject('Account password has been updated successfully.');				
						$this->email->message($message); 
						try{
							$this->email->send();	
						}
						catch(Exception $e){
							echo $e->getMessage();
						}
							$this->session->set_flashdata('login_flash_message', '<div class="alert alert-success">Your account password has been reset successfully. Login to your account here.</div>');
								redirect('home/login');	
							}
							else{
									$this->session->set_flashdata('forgotpassword_flash_message', '<div class="alert alert-warning">Dear '.ucwords($record_Info->first_name.' '.$record_Info->last_name).', <br/>Your account is not activated. Click to <a href="'.base_url().'index.php/home/regeneratelink">here</a> to activate your account.</div>');
									redirect('home/forgotpassword');
							}
						}
						else{
							$this->session->set_flashdata('forgotpassword_flash_message', '<div class="alert alert-danger">Ohh! something went wrong, your record does not exists or has been deleted. Please contact to site administrator.</div>');	
							redirect('home/forgotpassword');
						}
						
				}
					else{
					$this->session->set_flashdata('forgotpassword_flash_message', '<div class="alert alert-danger">This link has already been used and expired. To request a new link to reset your password type your email address.</div>');
					redirect('home/forgotpassword');
				}
				}
				
			}
			
		}
		
	}
	
	//function to handle forgot password
	public function forgotpassword(){
		$data['title'] = 'Projects 100K';
		$this->load->view('templates/header', $data);
		$this->load->view('home/forgotpassword', $data);
		$this->load->view('templates/footer', $data);
	}	
	
	//function to handle forgot password
	public function resetpassword($param=NULL){
		$data['title'] = 'Projects 100K';
		$data['user']	=	'';
		if($this->security->xss_clean(trim($param))!=''){
					$userInfo = $this->base_model->is_Record_Exists('tbl_users',array('*'), array('passwordreset_key'=>$param));							
					if(!empty($userInfo) && $userInfo->id!='' && $userInfo->status === 'Active')
					$data['user']	=	$this->encrypt->encode($userInfo->id);
					$data['param']	=	$param;
			 }
		$this->load->view('templates/header', $data);
		$this->load->view('home/resetpassword', $data);
		$this->load->view('templates/footer', $data);
	}
	
	//function to generate link to reset password
	public function generateresetpasswordlink(){
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('g-recaptcha-response', 'Security', 'trim|required|xss_clean');		
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('forgotpassword_flash_message', '<div class="alert alert-danger">'.validation_errors().'</div>');			
			redirect('home/forgotpassword');
		}
		else{
			
			$reCaptcha = $this->input->post('g-recaptcha-response');
			$secretKey = "6LcxuQoUAAAAAIR5Qe3RI_Uwj2bWHXhIVfDOGVYP";
			$ip = $_SERVER['REMOTE_ADDR'];
			$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$reCaptcha."&remoteip=".$ip);
			$responseKeys = json_decode($response,true);
			if($responseKeys['success'] == 1)
			{				
				$userData['email']					=	$this->security->xss_clean($this->input->post('email'));
				$record_Info						=	$this->base_model->is_Record_Exists('tbl_users',
														array('id,first_name,last_name','status'),
														array('email'=>$userData['email']));
				if(!empty($record_Info) && $record_Info->id!=''){
				    if($record_Info->status === 'Active'){				
				
			$userData['passwordreset_key'] =	str_replace('/','',$this->encrypt->encode($this->base_model->generateStrongPassword()));
			$link	=	'<a href="'.base_url().'index.php/home/resetpassword/'.$userData['passwordreset_key'].'">'.base_url().'index.php/home/resetpassword/'.$userData['passwordreset_key'].'</a>';
						//create message
						$message = 'Dear '.ucwords($record_Info->first_name.' '.$record_Info->last_name).',<br/><br/>
									You have requested a link to reset your account passowrd.<br/><br/>
									Please click the link provided here to reset your account passowrd.<br/><br/>'.$link.
									'<br/><br/> This is an auto-generated email please do not reply.<br/><br/><br/><br/>
									Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';
						
						$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
						$this->email->to($userData['email'], $record_Info->first_name);
						$this->email->subject('Account password reset link.');				
						$this->email->message($message); 
						try{
							$this->email->send();	
						}
						catch(Exception $e){
							echo $e->getMessage();
						}				
						$updateStatus	=	$this->base_model->updateUser($userData,'tbl_users',
											array('id'=>$record_Info->id,'email'=>$userData['email']));
						if($updateStatus){
										$this->session->set_flashdata('forgotpassword_flash_message', 
										'<div class="alert alert-success">You will shortly receive an email to reset your account password. Please check your email.</div>');
						}else{
						$this->session->set_flashdata('forgotpassword_flash_message', 
											'<div class="alert alert-danger">Ohh! something went wrong, please try again!</div>');
						}
						redirect('home/forgotpassword');
					}
					else{
						$this->session->set_flashdata('forgotpassword_flash_message', '<div class="alert alert-warning">Dear '.ucwords($record_Info->first_name.' '.$record_Info->last_name).', <br/>Your account is not activated. Click to <a href="'.base_url().'index.php/home/regeneratelink">here</a> to activate your account.</div>');
					redirect('home/forgotpassword');
					}							
				}
				else{
					$this->session->set_flashdata('forgotpassword_flash_message', '<div class="alert alert-danger">The email address you entered is not registered with us. Please try again! </div>');
					redirect('home/forgotpassword');
				}				
				
			}
			
		}
	}	
		
	//function to create new link
	public function createnewlink(){
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('g-recaptcha-response', 'Security', 'trim|required|xss_clean');		
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('regenerate_account_link_flash_message', '<div class="alert alert-danger">'.validation_errors().'</div>');			
			redirect('home/regeneratelink');
		}
		else{
			
			$reCaptcha = $this->input->post('g-recaptcha-response');
			$secretKey = "6LcxuQoUAAAAAIR5Qe3RI_Uwj2bWHXhIVfDOGVYP";
			$ip = $_SERVER['REMOTE_ADDR'];
			$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$reCaptcha."&remoteip=".$ip);
			$responseKeys = json_decode($response,true);
			
			if($responseKeys['success'] == 1)
			{				
				$userData['email']					=	$this->security->xss_clean($this->input->post('email'));
				$record_Info						=	$this->base_model->is_Record_Exists('tbl_users',array('id,first_name,last_name','status'),
														array('email'=>$userData['email']));
				if(!empty($record_Info) && $record_Info->id!=''){
				    if($record_Info->status === 'Inactive'){				
				
				$userData['account_encryption_key'] =	str_replace('/','',$this->encrypt->encode($this->base_model->generateStrongPassword()));
				$link	=	'<a href="'.base_url().'index.php/account/activate/'.$userData['account_encryption_key'].'">'.base_url().'index.php/account/activate/'.$userData['account_encryption_key'].'</a>';
						//create message
						$message = 'Dear '.ucwords($record_Info->first_name.' '.$record_Info->last_name).',<br/><br/>
									You have requested a new account activation link.<br/><br/>
									Please click or copy paste the link provided here to activate your account.<br/><br/>'.$link.
									'<br/><br/> This is an auto-generated email please do not reply.<br/><br/><br/><br/>
									Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';
						
						$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
						$this->email->to($userData['email'], $record_Info->first_name);
						$this->email->subject('Re-generated account activation link.');				
						$this->email->message($message); 
						try{
							$this->email->send();	
						}
						catch(Exception $e){
							echo $e->getMessage();
						}				
						$updateStatus	=	$this->base_model->updateUser($userData,'tbl_users',array('id'=>$record_Info->id,'email'=>$userData['email']));
						if($updateStatus){
					$this->session->set_flashdata('regenerate_account_link_flash_message', '<div class="alert alert-success">Thank you for your interest with us. Re-generated account activation link has been sent to your email address please check your email.</div>');
					redirect('home/regeneratelink');
				}
						else{
						$this->session->set_flashdata('regenerate_account_link_flash_message', 
						'<div class="alert alert-danger">Ohh! something went wrong, please try again!</div>');
				}
						redirect('home/regeneratelink');
					}
					else{
						$this->session->set_flashdata('regenerate_account_link_flash_message', '<div class="alert alert-warning">Dear '.ucwords($record_Info->first_name.' '.$record_Info->last_name).', <br/>Your account is already activated. Click to <a href="'.base_url().'index.php/home/login">here</a> to login to your account.</div>');
					redirect('home/regeneratelink');
					}							
				}
				else{
					$this->session->set_flashdata('regenerate_account_link_flash_message', '<div class="alert alert-danger">The email address you entered is not registered with us. Please try again! </div>');
					redirect('home/regeneratelink');
				}				
				
			}
		}
	}
}


?>