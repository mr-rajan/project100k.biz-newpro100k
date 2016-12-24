<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Action extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			//check if admin is logged in or not.			
			$this->load->database();
			$this->load->model('base_model');
			$this->load->model('admincp/user_model');	
		}
		
		//function to process the request coming for data
		public function process()
		{
			$action		=	$this->input->post('action');
			$controller	=	$this->input->post('controller');
			$ids		=	explode(',',$this->input->post('ids'));
			switch($controller){
				case 'user' : 	$table 	= 	'tbl_users';	break;
				default		:	$table	=	'tbl_users';	break;		
			}
			$caption = '';
			$data = $failed_user_email = array(); 
			$counter = 0; $failed_counter = 0;
			
			switch($action){
				case 'delete':
									$caption	=	'deleted';
									foreach($ids as $key=>$id){
										$userInfo	=	$this->base_model->is_Record_Exists('','',array('id'=>$id));
										//$emailStatus	=	$this->sendEmailToUser($userInfo,$action);
										$temp = array('id'=>$id,'isDeleted'=>'yes');
										array_push($data,$temp);
										$counter++;
									}
									if(!empty($data) && $counter>0)
									$status	=	$this->base_model->updateRecords($data,$table,'id');
									break;
				case 'suspend':
									$caption	=	'suspended';
									foreach($ids as $key=>$id){
										$userInfo	=	$this->base_model->is_Record_Exists('','',array('id'=>$id));
										if($userInfo->status =='Active'){
											//	$emailStatus	=	$this->sendEmailToUser($userInfo,$action);
												$temp 			= 	array('id'=>$id,'status'=>'Suspend');
												array_push($data,$temp);
												$counter++;
										}
										elseif($userInfo->status =='Inactive' || $userInfo->status =='Suspend')
										{
												array_push($failed_user_email,$userInfo->email);
										}	
									}
									if(!empty($data) && $counter>0)
									$status	=	$this->base_model->updateRecords($data,$table,'id');									
									break;
									
				case 'active':
									$caption	=	'activated';
									foreach($ids as $key=>$id){
										$userInfo	=	$this->base_model->is_Record_Exists('','',array('id'=>$id));
										if($userInfo->status == 'Inactive' || $userInfo->status == 'Suspend'){
											    $password				=	$this->base_model->generateStrongPassword();
												$encrypt_password		=	sha1($password);			
												//$emailStatus			=	$this->sendEmailToUser($userInfo,$action,$password);
												$temp 					= 	array('id'=>$id, 'password'=>$encrypt_password, 'status'=>'Active');
												array_push($data,$temp);
												$counter++;
										}
										else
										{
												array_push($failed_user_email,$userInfo->email);
										}	
									}
									if(!empty($data) && $counter>0)
									$status	=	$this->base_model->updateRecords($data,$table,'id');
									break;										
									
			}
		    // handle those users who does not meet criteria
			$flashMessage = $lists = '';
		    if(!empty($failed_user_email)){
				     
					 if($counter>0)	$flashMessage = $counter.' account status are '.$caption.' successfully.';
					 elseif($counter == 0) $flashMessage = 'No any record(s) are updated. No record(s) matches request criteria';
					 							  
					 $flashMessage .= '<br/>However, there are '.count($failed_user_email).' user(s) for whom request cannot be proccessed. <br/>
					 						Below are email lists of those users. ';
					 if($action == 'suspend') 	 $flashMessage .= 'To process request account must be active.<br/><br/>' ;
					 elseif($action == 'active') $flashMessage .= 'To process request account status must be either inactive or suspended.<br/><br/>' ;
					 
					 foreach($failed_user_email as $failedUser){
						    $lists .= $failedUser.', ';
					 }
					 $flashMessage .= substr($lists,0,-2);
					  $this->session->set_flashdata('admincp_userlist_flash_message', '<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-info"></i> <b>Important: </b>'.$flashMessage.'</div>');
					echo 'noupdate';	
			}
			if($status && $flashMessage==''){
					$this->session->set_flashdata('admincp_userlist_flash_message', '<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="icon fa fa-check"></i> <b>Success:</b> User(s) '.$caption.'.</div>');
					echo 'success';
				}
			elseif(!$status && $flashMessage==''){
					 $this->session->set_flashdata('admincp_userlist_flash_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="icon fa fa-ban"></i> <b>Failure:</b> Unable to '.$action.' user(s). Please try again.</div>');
					 echo 'failed';
				}
			die;
		}
		
		//public function to create and send email to user on account status update
		public function sendEmailToUser($userInfo,$case,$password=NULL){
				
			switch($case){
			        case 'active':		$html	= 'Your account has been successfully activated by administrator. <br/><br/>
													   Please use below provided password with your registered email address to 
													   <a href="'.base_url().'index.php/home/login">login</a> your account.<br/><br/>
													   Password: '.$password.'<br/><br/>';
													   break;	
					case 'delete':	    $html	= 'Your account has been deleted because of some security purpose. Please contact to administrator of the site. <br/><br/>';
													   break;	
					
					case 'suspend':	    $html	= 'Your account has been suspended because of some security purpose. Please contact to administrator of the site. <br/><br/>';
													   break;										   	
			}
			
			if(!empty($userInfo)){
				
				
				$message = 'Dear '.ucwords($userInfo->first_name.' '.$userInfo->last_name).',<br/><br/>
							'.$html.'This is an auto-generated email please do not reply.<br/><br/><br/><br/>
							Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';
				
				$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
				$this->email->to($userInfo->email, $userInfo->first_name);
				$this->email->subject('Account status update notificaton.');				
				$this->email->message($message); 
				try{ 
						$this->email->send();
						return true;					
				}
				catch(Exception $e){
						return false;
				}		
			}
		}
		
}

?>