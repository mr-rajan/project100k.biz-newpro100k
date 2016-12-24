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
			//print'<pre/>';print_r($this->input->post());die;
			$action		=	$this->input->post('action');
			$controller	=	$this->input->post('controller');
			$ids		=	explode(',',$this->input->post('ids'));
			$admincp_flash_message_case = $table_columnName	=	'';
			$masterfield_tableName	=	$mastercategory_tableName	=	'';
			
			switch($controller){
				case 'user' 		: 	$table 	= 	'tbl_users';	
										$admincp_flash_message_case = 'admincp_userlist_flash_message';
										break;
				case 'bannerurl' 	: 	$table 	= 	'tbl_bannerurl_mastercategory';	
										$admincp_flash_message_case = 'admincp_urlbanner_flash_message';
										$masterfield_tableName		= 'tbl_bannerurl_masterfields';	
										$mastercategory_tableName	= 'tbl_bannerurl_mastercategory';
										$dynamicTablePrefix			= 'dynamic_tbl_';
										$table_columnName			= 'bannerurlCategoryId';	
										break;
				case 'bannerurlfield': 	$table 	= 	'tbl_bannerurl_masterfields';	
										$admincp_flash_message_case = 'admincp_bannerurlfieldlist_flash_message';
										$masterfield_tableName		= 'tbl_bannerurl_masterfields';	
										$dynamicTablePrefix			= 'dynamic_tbl_';	
										$table_columnName			= 'bannerurlCategoryId';									
										break;
				case 'bannercategory': 	$table 	= 	'dynamic_tbl_'.$this->security->xss_clean($this->input->post('tbl'));	
										$admincp_flash_message_case = 'admincp_bannercategory_indexpage_flash_message';
										$dynamicTablePrefix			= 'dynamic_tbl_';
										break;	
				case 'promotion': 		$table 	= 	'tbl_promotion_mastercategory';	
										$admincp_flash_message_case = 'admincp_promotion_flash_message';
										$masterfield_tableName		= 'tbl_promotion_masterfields';	
										$mastercategory_tableName	= 'tbl_promotion_mastercategory';
										$dynamicTablePrefix			= 'dynamic_promo_tbl_';
										$table_columnName			= 'promotionCategoryId';
										break;	
				case 'promotionfield': 	$table 	= 	'tbl_promotion_masterfields';	
										$admincp_flash_message_case = 'admincp_promotionfieldlist_flash_message';
										$masterfield_tableName		= 'tbl_promotion_masterfields';	
										$dynamicTablePrefix			= 'dynamic_promo_tbl_';
										$table_columnName			= 'promotionCategoryId';
										break;					
				case 'promotioncategory': 	$table 	= 	'dynamic_promo_tbl_'.$this->security->xss_clean($this->input->post('tbl'));	
										$admincp_flash_message_case = 'admincp_promotioncategory_indexpage_flash_message';
										$dynamicTablePrefix			= 'dynamic_promo_tbl_';
										break;	
				default				:	$table	=	'tbl_users';	
										$admincp_flash_message_case = 'admincp_userlist_flash_message';
										break;		
			}
			$caption = '';
			$recordIds = $failed_recordInfo = array(); 
			$counter = 0; $failed_counter = 0;
			$controllerToExclude	= array('bannerurl','bannerurlfield','bannercategory','promotion','promotionfield','promotioncategory');
			($controller	==	'bannercategory' || $controller	==	'promotioncategory') ? $fieldStatus	=	'ads_status' : $fieldStatus	=	'status';			
			$dynamicTableName	=	'';
			switch($action){
				case 'delete':
									$caption	=	'deleted';
									foreach($ids as $key=>$id){
										$db_RecordInfo	=	$this->base_model->is_Record_Exists($table,'',array('id'=>$id));
										if(!empty($db_RecordInfo)){
												if(!in_array($controller,$controllerToExclude))
												$emailStatus	=	$this->sendEmailToUser($db_RecordInfo,$action);
												array_push($recordIds,$db_RecordInfo->id);
												$counter++;
										}
									}
									if(!empty($recordIds) && $counter>0){										
										$this->load->dbforge();
										if($controller	==	'bannerurlfield' || $controller	==	'promotionfield'){
											$catg_uri_string =	$this->security->xss_clean($this->input->post('catg_uri_string'));
											$dynamicTableName	=	$dynamicTablePrefix.$catg_uri_string;
											if($this->db->table_exists($dynamicTablePrefix.$catg_uri_string)){	
												foreach($ids as $key=>$column){
												$column_Info	=	$this->base_model->is_Record_Exists($masterfield_tableName,array('id','title','type'),array('id'=>$column));
												$columnTitle	=	strtolower(str_replace(array(' ','-'),array('_'),$column_Info->title));
													if($this->db->field_exists($columnTitle,$dynamicTablePrefix.$catg_uri_string))
														$this->dbforge->drop_column($dynamicTablePrefix.$catg_uri_string, $columnTitle);
													if($column_Info->type=='7' && $this->db->field_exists('hyperlink_label_'.$column_Info->id,$dynamicTablePrefix.$catg_uri_string))
														$this->dbforge->drop_column($dynamicTablePrefix.$catg_uri_string, 'hyperlink_label_'.$column_Info->id);
												}
												$catgId			 =	$this->security->xss_clean($this->input->post('catgId'));
												$decryptedCatgId =	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$catgId));
												
											}
										}
										elseif($controller	==	'bannerurl' || $controller	==	'promotion'){
											 foreach($ids as $key=>$catgIds){
												$catgInfo		=	$this->base_model->is_Record_Exists($mastercategory_tableName,'',array('id'=>$catgIds));
												//Drop dynamically created category related table
												if($this->db->table_exists($dynamicTablePrefix.$catgInfo->uri_string)){
													$this->dbforge->drop_table($dynamicTablePrefix.$catgInfo->uri_string);
													//delete all fields of the category
													$column_Info	=	$this->base_model->deleteRecord($masterfield_tableName,array($table_columnName	=>	$catgIds));
												}
												
											} 	
										}
										//Delete records from general table
										$status	=	$this->base_model->deleteRecordWithIn($recordIds,$table);
										//end
										if($controller	==	'bannerurlfield' || $controller	==	'promotionfield'){
												$catgId			 =	$this->security->xss_clean($this->input->post('catgId'));
												$decryptedCatgId =	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$catgId));
												$countFields	 =	$this->base_model->count_records($table,array($table_columnName	=>	$decryptedCatgId));												
												if($countFields==0){								
													$this->dbforge->drop_table($dynamicTableName,true);
												}
										}
									}
									//echo '--'.$this->db->last_query();die;break;
									break;
				case 'suspend':
									$caption		=	'suspended';								
									if($controller	==	'bannercategory'){
										foreach($ids as $key=>$id){
														$db_RecordInfo	=	$this->base_model->is_Record_Exists($table,'',array('id'=>$id));
														if(!empty($db_RecordInfo) && $db_RecordInfo->ads_status =='Active'){
																$temp 			= 	array('id'=>$id, 'ads_status'=>'Suspend');
																array_push($recordIds,$temp);
																$counter++;
														}
											}
									}
									else{
										foreach($ids as $key=>$id){
										$db_RecordInfo	=	$this->base_model->is_Record_Exists($table,'',array('id'=>$id));
										
										if(!empty($db_RecordInfo)){											
														if($db_RecordInfo->status =='Active'){
															    if(!in_array($controller,$controllerToExclude))
																$emailStatus	=	$this->sendEmailToUser($db_RecordInfo,$action);
																$temp 			= 	array('id'=>$id, 'status'=>'Suspend');
																array_push($recordIds,$temp);
																$counter++;
														}
														elseif($db_RecordInfo->status =='Inactive' || $db_RecordInfo->status =='Suspend')
														{
																if(!in_array($controller,$controllerToExclude)){
																	$variableValue =	$db_RecordInfo->email;
																}
																else{
																		$variableValue =	$db_RecordInfo->title;
																}
																array_push($failed_recordInfo,$variableValue);
														}	
											
										}
										}
									}
									
									if(!empty($recordIds) && $counter>0)
									$status	=	$this->base_model->updateRecords($recordIds,$table,'id');																
									break;
									
				case 'active':
									$caption	=	'activated';
									if($controller	==	'bannercategory'){
										foreach($ids as $key=>$id){
												$db_RecordInfo	=	$this->base_model->is_Record_Exists($table,'',array('id'=>$id));
												if(!empty($db_RecordInfo) && ($db_RecordInfo->ads_status == 'Inactive' || $db_RecordInfo->ads_status == 'Suspend')){														
															$temp 	= 	array('id'=>$id, 'ads_status'=>'Active');
															array_push($recordIds,$temp);
															$counter++;
													}
										}	
									}
									else{
										foreach($ids as $key=>$id){
										$db_RecordInfo	=	$this->base_model->is_Record_Exists($table,'',array('id'=>$id));
												if(!empty($db_RecordInfo)){
												
												if($db_RecordInfo->status == 'Inactive' || $db_RecordInfo->status == 'Suspend'){
														if(!in_array($controller,$controllerToExclude)){
															$password				=	$this->base_model->generateStrongPassword();
															$encrypt_password		=	sha1($password);			
															$emailStatus			=	$this->sendEmailToUser($db_RecordInfo,$action,$password);
															$temp 					= 	array('id'=>$id, 'password'=>$encrypt_password, 'status'=>'Active');
														}
														else $temp 					= 	array('id'=>$id, 'status'=>'Active');
														array_push($recordIds,$temp);
														$counter++;
												}
														else
														{
																if(!in_array($controller,$controllerToExclude)){
																	$variableValue =	$db_RecordInfo->email;
																}
																else{
																		$variableValue =	$db_RecordInfo->title;
																}
																array_push($failed_recordInfo,$variableValue);
														}	
													
										}
										
									}
									}
									
									if(!empty($recordIds) && $counter>0)
									$status	=	$this->base_model->updateRecords($recordIds,$table,'id');
									break;										
									
			}
			
		    // handle those users who does not meet criteria
			$flashMessage = $lists = '';
		    if(!empty($failed_recordInfo)){
				     
					 if($counter>0){
					 	$flashMessage = $counter.' record(s) status are '.$caption.' successfully.';
					 }
					 elseif($counter == 0) $flashMessage = 'No any record(s) are updated. No record(s) matches request criteria';
					 							  
					 $flashMessage .= '<br/>Below is/are '.count($failed_recordInfo).' records(s) for which request cannot be proccessed. <br/>';
					 if($action == 'suspend') 	 $flashMessage .= 'To process request status must be active.<br/><br/>' ;
					 elseif($action == 'active') $flashMessage .= 'To process request status must be either inactive or suspended.<br/><br/>' ;
					 
					 foreach($failed_recordInfo as $failedRecord){
						    $lists .= $failedRecord.', ';
					 }
					 $flashMessage .= substr($lists,0,-2);
					  $this->session->set_flashdata($admincp_flash_message_case, '<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-info"></i> <b>Important: </b>'.$flashMessage.'</div>');
					echo 'noupdate';	
			}
			if($status && $flashMessage==''){
					$this->session->set_flashdata($admincp_flash_message_case, '<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="icon fa fa-check"></i> <b>Success:</b> Record(s) '.$caption.'.</div>');
					echo 'success';
				}
			elseif(!$status && $flashMessage==''){
					 $this->session->set_flashdata($admincp_flash_message_case, '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="icon fa fa-ban"></i> <b>Failure:</b> Unable to '.$action.' record(s). Please try again.</div>');
					 echo 'failed';
				}
			die;
		}
		
		//public function to create and send email to user on account status update
		public function sendEmailToUser($db_RecordInfo,$case,$password=NULL){
				
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
			
			if(!empty($db_RecordInfo)){
				
				
				$message = 'Dear '.ucwords($db_RecordInfo->first_name.' '.$userInfo->last_name).',<br/><br/>
							'.$html.'This is an auto-generated email please do not reply.<br/><br/><br/><br/>
							Thanks.<br/><br/>Regards,<br/><br/>Administrator<br/>Project100K';
				
				$this->email->from('precjot1@pro.webprohostz.net', 'Project100K');
				$this->email->to($db_RecordInfo->email, $db_RecordInfo->first_name);
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
		
		//function to update the values
		public function updatetblfield(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp'); // redirect to login page in not logged in or session gets expired
			// intializing variables to get update
			$element1			=	$this->security->xss_clean($this->input->post('elment1'));
			$element2			=	$this->security->xss_clean($this->input->post('elment2'));
			$opt				=	$this->security->xss_clean($this->input->post('opt'));
			$long_uri_string	=	$this->security->xss_clean($this->input->post('long_uri_string'));
			//end
			
			if($element1!='' && $element2!='' && $long_uri_string!='' && $opt!=''):
				
				$userdata	=	array();
				$tblName	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$long_uri_string));
					switch($element1)
					{
						case 'valueTD':			$userdata['isMandatory']		=	$opt;
												break;
						case 'tableheading':	$userdata['addtoTableHeading']	=	$opt;
												break;
						case 'sortable':		$userdata['sortable']			=	$opt;
												break;
						default:
												break;
					}
					/*switch($tblName){
						case 'tbl_bannerurl_masterfields'	:	$pk_col	=	'bannerurlCategoryId';break;
						case 'tbl_promotion_masterfields'	:	$pk_col	=	'promotionCategoryId';break;
						default:break;
					}*/
					$updateStatus	=	$this->base_model->updateRecord($userdata,$tblName,array('id'=>$element2));
					
					if($updateStatus){
						$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-check"></i> <b>Success:</b> Info updated!</div>');	
						echo 'success';
					}
					else{
						$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
						echo 'failed';
					}
			
			endif;
			
			die;
		}
		
}

?>