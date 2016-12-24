<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Register extends CI_Controller{

	public function __construct(){		
	    parent::__construct();	
		$this->load->database();
        $this->load->model('base_model');
		
	}

	public function index(){	

		$data['title'] = 'Projects 100K';
		$data['countries_lists']	=	$this->base_model->get_All_Records('tbl_countries',array('*'),array('publish'=>'Yes'));	
		$postedValue_fname = array();		
		(strstr($_SERVER['REQUEST_URI'],'ref')) ?	$referrerID = $this->uri->segment(3)	:	$referrerID = '';
		($referrerID!='') ? $data['ref']	=	$referrerID	:	$data['ref']	=	'';		
		if($this->session->userdata('post_registeratonData'))
		$data['postedValue'] = $this->session->userdata('post_registeratonData');
		$this->load->view('templates/header', $data);
		$this->load->view('register/index', $data);
		$this->load->view('templates/footer', $data);
	}
	
	public function save()
	{
		$postReferrerID = '';
		$errorRedirectTo	=	'register';	
		if($this->security->xss_clean($this->input->post('ref'))!=''){
				$postReferrerID 			=	$this->security->xss_clean($this->input->post('ref'));
				$referrerInfo				=	$this->base_model->is_Record_Exists('tbl_users',array('id'),array('first_name'=>$postReferrerID));
				$userData['refID']		=	(!empty($referrerInfo)) ? $referrerInfo->id	:	$referrerInfo	=	0;
				if(!empty($referrerInfo) && $referrerInfo->id!='')$errorRedirectTo = 'register/ref/'.strtolower($postReferrerID);
		}
		
		//$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[5]|max_length[15]|xss_clean|alpha_dash');
		$this->form_validation->set_rules('contactno', 'Contact Number', 'trim|numeric|xss_clean');
		$this->form_validation->set_rules('country', 'country', 'trim|required|xss_clean');		
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
		$this->form_validation->set_rules('g-recaptcha-response', 'Security', 'trim|required|xss_clean');
		//cross-checking unique fields 
		// First name must be unique
		$fnameRule 	=	$emailRule	=	'';
		if($this->input->post('email')!=''){
			$fname_status	=	$this->base_model->is_Record_Exists('tbl_users',array('id'),array('first_name'=>$this->input->post('first_name')));
			if(!empty($fname_status))$fnameRule = '|is_unique[tbl_users.first_name]';
		}
		// Email must be unique
		if($this->input->post('email')!=''){
			$record_status	=	$this->base_model->is_Record_Exists('tbl_users',array('id'),array('email'=>$this->input->post('email')));			
			if(!empty($record_status))$emailRule = '|is_unique[tbl_users.email]';
		}
		//end
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[5]|max_length[15]|alpha_dash'.$fnameRule.'|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean'.$emailRule);

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('errors', '<div class="alert alert-danger">'.validation_errors().'</div>');
				$this->session->set_userdata('post_registeratonData',$this->input->post());				
				redirect($errorRedirectTo);							
		}
		else
		{
			$reCaptcha = $this->input->post('g-recaptcha-response');
			$secretKey = "6LcxuQoUAAAAAIR5Qe3RI_Uwj2bWHXhIVfDOGVYP";
			$ip = $_SERVER['REMOTE_ADDR'];
			$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$reCaptcha."&remoteip=".$ip);
			$responseKeys = json_decode($response,true);
			if($responseKeys['success'] == 1)
			{
				
				$userData['first_name']				=	$this->security->xss_clean($this->input->post('first_name'));
				$userData['last_name']				=	$this->security->xss_clean($this->input->post('last_name'));
				$userData['gender']					=	$this->security->xss_clean($this->input->post('gender'));
				$userData['email']					=	$this->security->xss_clean($this->input->post('email'));
				$userData['contactno']				=	($this->input->post('contactno')!='') ? $this->security->xss_clean($this->input->post('contactno')) : '';
				$userData['country']				=	$this->security->xss_clean($this->input->post('country'));				
				$userData['registeredOn']			=	date('Y-m-d H:i:s');
				$userData['activation_mail_status']	=	'yes';
				$password							=	$this->base_model->generateStrongPassword();
				$userData['password']				=	sha1($password);			
				$userData['status']					=	'Inactive';
				$userData['account_encryption_key'] =	$this->base_model->generateStrongPassword();
				$encryptedKey						=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($userData['account_encryption_key']));
				$link	=	'<a href="'.base_url().'account/activate/'.$encryptedKey.'">'.base_url().'account/activate/'.$encryptedKey.'</a>';				
				$insertStatus						=	$this->base_model->saveUser($userData);
				$user_insertID						=	$this->db->insert_id();
				$membershipData['userID'] 			=	$user_insertID;	
				$membershipData['membershipID'] 	=	4;
				$membershipData['isActive'] 		=	'yes';
				$membershipData['startDate'] 		=	date('Y-m-d H:i:s');
				$saveMembership						=	$this->base_model->saveUser($membershipData,'tbl_user_membershipplan');
				$this->session->unset_userdata('post_registeratonData');
				if($insertStatus){			
					//preparing content for sending email to use
					
					$message = 'Dear '.ucwords($userData['first_name'].' '.$userData['last_name']).',<br/><br/>
								Thank you for registering your account with us.<br/><br/>
								<i>Your referral ID: <b>'.base_url().'register/ref/'.strtolower($userData['first_name']).'</b></i><br/><br/>
								Please click or copy paste the link provided here to activate your account.<br/><br/>'.$link.
								'<br/><br/>After account activation please use below provided password with your registered email address to login your account.<br/><br/>
								Password: '.$password.'<br/><br/>This is an auto-generated email please do not reply.<br/><br/><br/><br/>
								Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';				
				
					//end	
					//setting email headers and ending email
					$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
					$this->email->to($userData['email'], $userData['first_name']);
					$this->email->subject('Account activation email link.');
					$this->email->message($message); 					
					$this->email->send();
					$this->session->set_flashdata('errors', '<div class="alert alert-success">Thank you for registering your account with us. 
												   An account activation link has been sent to your email address please check your email.</div>');
				
					redirect('register');
				}
				else{
						$this->session->set_flashdata('errors', '<div class="alert alert-danger">Oh! something went wrong, please try again!</div>');
						redirect($errorRedirectTo);
				}
				
			}
			
		}
		
	}

}





?>