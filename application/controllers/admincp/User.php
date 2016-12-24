<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class User extends CI_Controller{
		
		public static $downlineBreadCrumbTracking	=	array();
		public function __construct(){
			parent::__construct();
			//check if admin is logged in or not.			
			$this->load->database();
			$this->load->model('base_model');
			$this->load->model('admincp/user_model');						
		}
		
		
		//function to retreive list of all downlines
		public function downline($leveloneParentRefId){	
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 						= 	'Projects 100K';
			$data['addNewUserForUserLabel'] 	= 	'';
			$data['parentUserID']				=	'';
			$post_leveloneParentRefId			=	$this->security->xss_clean($leveloneParentRefId);			
			$userID								=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$post_leveloneParentRefId));
			$isUserExists						=	$this->base_model->is_Record_Exists('','',array('id'=>$userID));
			$dataArray							=	array();
			if(!empty($isUserExists)){
					$downlineData						=	$this->base_model->get_all_downline($userID);	
					$dataArray							=	$downlineData;
					$data['addNewUserForUserLabel'] 	= 	'Add New User (As <b>'.ucfirst($isUserExists->first_name).'</b> Downline)';
					$data['parentUserID'] 				= 	$post_leveloneParentRefId;
			}
			/************************************************************************
			@Purpose: Script to create and handle the breadcrumb in users listing
			**************************************************************************/
			$downlineBreadCrumb =  '<li><a href="'.base_url().'admincp/account"><i class="fa fa-dashboard"></i>Home</a></li>
										<li><a href="'.base_url().'admincp/user">User</a></li>';
			if(!$this->session->userdata('downlineBreadCrumb'))
			{
			    $temp	= 	array();
			    array_push($temp,array('name'=>ucfirst($isUserExists->first_name),'hashKey'=>$post_leveloneParentRefId));
				$this->session->set_userdata('downlineBreadCrumb', $temp);
				$downlineBreadCrumb	.=	'<li>'.ucfirst($isUserExists->first_name).'</a></li>';
			}
			else
			{
				$temp			=	$this->session->userdata('downlineBreadCrumb');	
				$array_column	=	array_column($temp,'hashKey');
				if(!in_array($post_leveloneParentRefId,$array_column)){				
					array_push($temp,array('name'=>ucfirst($isUserExists->first_name),'hashKey'=>$post_leveloneParentRefId));
					$this->session->set_userdata('downlineBreadCrumb',$temp);
				}
				$sessBreadCrumb	=	$this->session->userdata('downlineBreadCrumb');
				if(!empty($sessBreadCrumb)){
					$hashKey_column	=	array_column($sessBreadCrumb,'hashKey');
					$keyFound	=	array_search($post_leveloneParentRefId,$hashKey_column);					
					$newArray	=	array();
					if((count($sessBreadCrumb)-1) != $keyFound && $keyFound<(count($sessBreadCrumb)-1)){
					     	for($i=0;$i<=$keyFound;$i++){
								array_push($newArray,$sessBreadCrumb[$i]);
							}
						$sessBreadCrumb	=	$newArray;
						$this->session->set_userdata('downlineBreadCrumb',$sessBreadCrumb);
					}					
					foreach($sessBreadCrumb as $key=>$breadcrumb){						
						if(($key+1)<count($sessBreadCrumb)){ $activeClass = ' class="active"';
						$hyperLink	=	'<a href="'.base_url().'admincp/user/downline/'.$breadcrumb['hashKey'].'">'.$breadcrumb['name'].'</a>';
						}
						else{ $activeClass = '';  $hyperLink	=	$breadcrumb['name'];}
						$downlineBreadCrumb	.=	'<li'.$activeClass.'>'.$hyperLink.'</li>';
					};
					
				}			
			}
			//end
			
			$data['downlineBreadCrumb']		=	$downlineBreadCrumb;
		    $data['downlineParentUserID'] 	=	$post_leveloneParentRefId;
			$data['userLists']				=	$dataArray;
			$data['parentUserName'] 		=	ucfirst($isUserExists->first_name);
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/user/downline', $data);
		}
		
	
		//function to get lists of all users
		public function index(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 		= 'Projects 100K';		
			$data['userLists']	=	$this->user_model->get_all_users_by_join();
			$this->session->unset_userdata('downlineBreadCrumb');
			$data['downlineParentUserID'] 		= 	'';
			$data['parentUserID'] 				= 	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode(0));
			$data['addNewUserForUserLabel'] 	= 	'Add New User (As <b>Admin</b> Downline)';
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/user/index', $data);		
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
		//function to edit existing user
		public function edit($editableUserId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 		= 'Projects 100K';
			$postedValue_fname 	= array();
			if($this->session->userdata('post_registeratonData'))
			$data['postedValue'] 		= 	$this->session->userdata('post_registeratonData');
			$userID 					= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$editableUserId);			
			$data['userInfo']			=	$this->base_model->is_Record_Exists('','',array('id'=>$this->encrypt->decode($userID)));
			$data['parentUserID'] 		= 	'';
			$data['userID']				=	$editableUserId;
			$data['countries_lists']	=	$this->base_model->get_All_Records('tbl_countries',array('*'),array('publish'=>'Yes'));		
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/user/form', $data);	
		}
		
		
		//function to add new user
		public function addnew($refId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp'); //check if admin is logged-in if not redirect to login page
			// get and set any posted value of form
			if($this->session->userdata('post_registeratonData'))
			$data['postedValue'] 		= 	$this->session->userdata('post_registeratonData');			
			$data['title'] 				= 	'Projects 100K'; //title of page			
			$data['countries_lists']	=	$this->base_model->get_All_Records('tbl_countries',array('*'),array('publish'=>'Yes'));	
			$data['userID']				=	'';	
			$data['userInfo']			=	array();
			$parentUserID				=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$refId));	
			if($parentUserID>0){
			$data['refUserInfo']	   =	$this->base_model->is_Record_Exists('',array('id'),array('id'=>$parentUserID));
			if(!empty($data['refUserInfo'])){	
			    $data['parentUserID'] = $refId;			
				$this->load->view('templates/admincp/header', $data);
				$this->load->view('admincp/user/form', $data);
			}
			else{
				$this->session->set_flashdata('admincp_userlist_flash_message', '<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-info"></i> <b>Important: </b>User does not exists under whose downline you are trying to add new user.</div>');
				redirect('admincp/user');
			}
			}
			else{
				$data['parentUserID'] = $refId;
				$this->load->view('templates/admincp/header', $data);
				$this->load->view('admincp/user/form', $data);	
			}
			
		}
		
		//function to save new User
		public function save(){
		   
				$this->form_validation->set_rules('contactno', 'Contact Number', 'trim|numeric|xss_clean');
				$this->form_validation->set_rules('country', 'country', 'trim|required|xss_clean');		
				$this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
				//cross-checking unique fields 
				// First name must be unique
				$editableUserID = $refID	=	'';
				$fnameRule 	=	$emailRule	=	'';
				//checks and stroe userid value 
				if($this->input->post('hdnUserID')!=''){
					$editableUserID		=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('hdnUserID')));			
				}
				//check if first_name record already exists or not
				if($this->input->post('first_name')!=''){
					$fname_status	=	$this->base_model->is_Record_Exists('tbl_users',array('id'),array('first_name'=>$this->input->post('first_name')));
					if(!empty($fname_status)){	
						if($editableUserID!='' && $fname_status->id === $editableUserID){
								$proceedToSave = 'Yes';
						}
						else{
							 $proceedToSave = 'No';	
							 $fnameRule = '|is_unique[tbl_users.first_name]';
						}
					}
				}
				//check if email record already exists or not
				if($this->input->post('email')!=''){
					$email_status	=	$this->base_model->is_Record_Exists('tbl_users',array('id'),array('email'=>$this->input->post('email')));
					if(!empty($email_status)){	
						if($editableUserID!='' && $email_status->id === $editableUserID){
								$proceedToSave = 'Yes';
						}
						else{
							$proceedToSave = 'No';	
							$emailRule = '|is_unique[tbl_users.email]';
						}
					}
				}
				//end
				
				$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[5]|alpha_numeric_spaces'.$fnameRule.'|xss_clean');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean'.$emailRule);
		
				if ($this->form_validation->run() == FALSE)
				{
						$this->session->set_flashdata('admincp_userform_flash_message', '<div class="alert alert-danger">'.validation_errors().'</div>');
						$this->session->set_userdata('post_registeratonData',$this->input->post());				
						if($editableUserID!=''){
							$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($editableUserID));
							redirect('admincp/user/edit/'.$link);
						}
						else{						
							$refId	=	$this->input->post('parentUserID');	
							redirect('admincp/user/addnew/'.$refId);							
						}
				}
				else
				{ 
							$dbStatus = '';
							$userData = array();
							if($_FILES['profilepic']['name']!='')
							$profile_pic	=	$this->security->xss_clean($_FILES['profilepic']['name']);
							else $profile_pic = '';
							//apply the condition to set config for image
							if($profile_pic!='')
							{   
										if($editableUserID!=''){
												$userProfilePicInfo		=	$this->base_model->is_Record_Exists('tbl_users',array('profilePic'),
																										         array('id'=>$editableUserID));
												if($userProfilePicInfo->profilePic !='' && file_exists('themes/userimages/'.$userProfilePicInfo->profilePic))
												unlink('themes/userimages/'.$userProfilePicInfo->profilePic);
										}
										
										//setting up the configuration for image				
										$config['file_name'] 			= 	$_FILES['profilepic']['name'];
										$config['upload_path']          = 	'themes/userimages/';
										$config['allowed_types']        = 	'jpeg|jpg|png|gif|bmp';	
										$config['remove_spaces']		=	true;
										$config['detect_mime']			=	true;
										$config['encrypt_name']			=	true;
										$config['max_size']             =	100;
										$config['max_width']            = 	1024;
										$config['max_height']           = 	768;
										//end
										$this->load->library('upload',$config);
										$this->upload->initialize($config);
										if($this->upload->do_upload('profilepic')){
											$uploadData = $this->upload->data();
											$imageName = $uploadData['file_name'];
										}else{
											$imageName = '';
										}
										$userData['profilePic']	=	$imageName;
							}
						
				// setting variables and its value to pass to database to save records			
				$userData['first_name']				=	$this->security->xss_clean($this->input->post('first_name'));
				$userData['last_name']				=	$this->security->xss_clean($this->input->post('last_name'));
				$userData['gender']					=	$this->security->xss_clean($this->input->post('gender'));
				$userData['email']					=	$this->security->xss_clean($this->input->post('email'));
				$userData['contactno']				=	$this->security->xss_clean($this->input->post('contactno'));
				$userData['country']				=	$this->security->xss_clean($this->input->post('country'));		
				$userData['status']					=	$this->security->xss_clean($this->input->post('accountStatus'));
				
				if($editableUserID!=''){
						$previousAccountStatus	    =	$this->base_model->is_Record_Exists('tbl_users',array('status'),array('id'=>$editableUserID));						
						if($previousAccountStatus->status != $userData['status']){
								switch($userData['status']){
										case 'Active':		
															$password					=	$this->base_model->generateStrongPassword();
															$userData['password']		=	sha1($password);
															$html						= 	'Your account has been successfully activated by administrator. <br/><br/>
																							 Please use below provided password with your registered email address to 
																							  <a href="'.base_url().'index.php/home/login">login</a> your account.<br/><br/>
																							  Password: '.$password.'<br/><br/>';
																							  break;	
									
										case 'Suspend':	    $html	= 	'Your account has been suspended because of some security purpose. 
																				Please contact to administrator of the site. <br/><br/>';
																		   		break;										   	
								}
								$message = 'Dear '.ucwords($userData['first_name'].' '.$userData['last_name']).',<br/><br/>
								'.$html.'This is an auto-generated email please do not reply.<br/><br/><br/><br/>
								Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';
																
								$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
								$this->email->to($userData['email'], $userData['first_name']);
								$this->email->subject('Account status update notificaton.');				
								$this->email->message($message); 	
								$this->email->send();
						}
						$userData['updatedOn']	    =	date('Y-m-d H:i:s');							
						$dbStatus			        =	$this->base_model->updateRecord($userData,'',array('id'=>$editableUserID));
						$flashMessage			    =	'User info updated successfully.';
						$user_insertID			    =	$editableUserID;					
				}
				else{
						$userData['registeredOn']			=	date('Y-m-d H:i:s');
						$userData['activation_mail_status']	=	'no';
						$password							=	$this->base_model->generateStrongPassword();
						$userData['password']				=	sha1($password);		
						$userData['refID']					=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('parentUserID')));	
						$userData['status']					=	$this->security->xss_clean($this->input->post('accountStatus'));
						//print'<pre/>';print_r($userData);die;
						$dbStatus							=	$this->base_model->saveRecord($userData);
						$user_insertID						=	$this->db->insert_id();
						$membershipData['userID'] 			=	$user_insertID;	
						$membershipData['membershipID'] 	=	4;
						$membershipData['isActive'] 		=	'yes';
						$membershipData['startDate'] 		=	date('Y-m-d H:i:s');
						$saveMembership						=	$this->base_model->saveRecord($membershipData,'tbl_user_membershipplan');
						//preparing content for sending email to use					   
						$message = 'Dear '.ucwords($userData['first_name'].' '.$userData['last_name']).',<br/><br/>
									Your account has been created successfully by administrator.<br/><br/>
									<i>Your referral ID: <b>'.base_url().'register/ref/'.strtolower($userData['first_name']).'</b></i><br/><br/>';						
						if($userData['status']=='Suspend'){	
							$message .= '<b>However, your account has been kept as suspend for some security purpose. Please wait for update from administrator.</b><br/><br/>';
						}
						else{
							$message .= 'Please use below provided email address and password to login your account.<br/><br/>
										 Email Address: '.$userData['email'].'<br/>Password: '.$password.'<br/><br/>';
						}
							$message.=	'This is an auto-generated email please do not reply.<br/><br/><br/><br/>
											Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';	
						//end	
						//setting email headers and ending email
						$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
						$this->email->to($userData['email'], $userData['first_name']);
						$this->email->subject('Account created successfully.');
						$this->email->message($message); 					
						$this->email->send();
						$flashMessage	=	'User created successfully. An email has been sent to user.';
				}
				
				$this->session->unset_userdata('post_registeratonData');
				if($dbStatus){							
					if($this->input->post('submit')=='savenc')
					{
						$this->session->set_flashdata('admincp_userform_flash_message', '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');		
						$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($user_insertID));
						redirect('admincp/user/edit/'.$encryptedID);
					}
					elseif($this->input->post('submit')=='save')
					{
						$this->session->set_flashdata('admincp_userlist_flash_message', '<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');		
						redirect('admincp/user');
					}
				}
				else
				{						
						if($this->input->post('submit')=='savenc')
						{
							 $this->session->set_flashdata('admincp_userform_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($user_insertID));
							redirect('admincp/user/edit/'.$encryptedID);
						}
						elseif($this->input->post('submit')=='save')
						{
							 $this->session->set_flashdata('admincp_userlist_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							redirect('admincp/user');
						}
				}
			}
				//end
		}
		
		
}

?>