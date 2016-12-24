<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Bannerurl extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			//check if admin is logged in or not.	
			$this->load->dbforge();			
			$this->load->model('base_model');					
		}
		
		//function to get lists of all users
		public function index(){
			
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 		= 'Projects 100K';		
			$data['bannerCategoryLists']	=	$this->base_model->get_All_Records('tbl_bannerurl_mastercategory');		
			if(!empty($data['bannerCategoryLists'])){
					foreach($data['bannerCategoryLists'] as $key=>$catg){	
						$catg->active_records	=	0;
						$catg->inactive_records	=	0;	
										
						if($this->db->table_exists('dynamic_tbl_'.$catg->uri_string)){
							//count active records
							$activeAds	=	$this->base_model->count_records('dynamic_tbl_'.$catg->uri_string, array('ads_status'=>'Active'));
							$catg->active_records	=	$activeAds;	
							//count inactive records
							$inactiveAds	=	$this->base_model->count_records('dynamic_tbl_'.$catg->uri_string, array('ads_status'=>'Suspend'));
							$catg->inactive_records	=	$inactiveAds;	
						}
					}
			}
		//	print_r($data);die;
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannerurl/index', $data);		
		}
	    
		public function category($catg)
		{
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['bannerCategoryTypeName']			=	'';
			$data['bannerurlCategorywiseLists']		=	$data['categorywiseFieldsInfo']		=	array();
			$encryptedTitle							=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($catg));
			$data['title'] 							= 	'Projects 100K';						
			$categoryInfo							=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('id','title_hashkey','title'),
														array('title'=>$catg));		
			if(!empty($categoryInfo) && $encryptedTitle === $categoryInfo->title_hashkey){
				$data['bannerurlCategorywiseLists']		=	$this->base_model->get_All_Records('tbl_bannerurl_categorywise','',array('bannercategoryid'=>$categoryInfo->id));
				$data['bannerCategoryTypeName']			=	$categoryInfo->title;
				$data['categorywiseFieldsInfo']			=	$this->base_model->is_Record_Exists('tbl_bannerurl_masterfields','',array('bannerurlCategoryId'=>$categoryInfo->id));
			}			
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannerurl/categoryBannersList', $data);
			$this->load->view('templates/admincp/footer', $data);
		}
		
		
		//function to edit existing user
		public function edit($catgId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 		= 'Projects 100K';
			$postedValue_fname 	= array();
			if($this->session->userdata('post_bannerUrlData'))
			$data['postedValue'] 		= 	$this->session->userdata('post_bannerUrlData');
			$bannerUrlID				= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$catgId);			
			$data['bannerUrlInfo']		=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory','',array('id'=>$this->encrypt->decode($bannerUrlID)));	
			$data['fieldInfo']			=	$this->base_model->get_All_Records('tbl_bannerurl_masterfields','',
											array('bannerurlCategoryId'=>$this->encrypt->decode($bannerUrlID)),'order','asc');				
			$data['catg_uri_string']	=	$data['bannerUrlInfo']->uri_string;
			$data['long_uri_string'] 	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode('tbl_bannerurl_masterfields'));
			$data['catgTitle']			=	stripslashes(ucwords($data['bannerUrlInfo']->title));
			$data['hdnBannerUrlID']		=	$catgId;
			$this->session->unset_userdata('post_bannerUrlFieldData');
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannerurl/form', $data);
		}
		
		
		//function to add new user
		public function addnew(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 			= 'Projects 100K';
			$postedValue_fname 		= array();
			if($this->session->userdata('post_bannerUrlData'))
			$data['postedValue'] 		= 	$this->session->userdata('post_bannerUrlData');
			$data['hdnBannerUrlID']		=	'';	
			$data['bannerUrlInfo']		=	array();
			$data['catg_uri_string']	=	$data['long_uri_string'] 	=	'';
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannerurl/form', $data);
		}
		
		//function to save new User
		public function save(){
		   
				$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');		
				//cross-checking unique fields 
				// Title must be unique
				$editableBannerUrlID = '';
				$titleRule =	'';
				//checks and stroe userid value 
				if($this->input->post('hdnBannerUrlID')!=''){
					$editableBannerUrlID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('hdnBannerUrlID')));			
				}
				//check if title record already exists or not
				if($this->input->post('title')!=''){
					$bannerUrlRecord	=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('id'),array('title'=>$this->input->post('title')));
					if(!empty($bannerUrlRecord)){	
						if($editableBannerUrlID!='' && $bannerUrlRecord->id === $editableBannerUrlID){
								$proceedToSave = 'Yes';
						}
						else{
							 $proceedToSave = 'No';	
							 $titleRule = '|is_unique[tbl_bannerurl_mastercategory.title]';
						}
					}
				}
				$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[5]|alpha_numeric_spaces'.$titleRule.'|xss_clean');
		
				if ($this->form_validation->run() == FALSE)
				{
						$this->session->set_flashdata('admincp_bannerurlform_flash_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
						
						$this->session->set_userdata('post_bannerUrlData',$this->input->post());				
						if($editableBannerUrlID!=''){
							$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($editableBannerUrlID));
							redirect('admincp/bannerurl/edit/'.$link);
						}
						else
						redirect('admincp/bannerurl/addnew');							
				}
				else
				{ 
							$dbStatus = '';
							$bannerUrlData = array();					
						
				// setting variables and its value to pass to database to save records			
				$bannerUrlData['title']					=	$this->security->xss_clean($this->input->post('title'));
				$uri_string								=	strtolower(str_replace(array(' ','-'),array('_'),$bannerUrlData['title']));
				$bannerUrlData['uri_string']			=	$uri_string;
				$bannerUrlData['status']				=	$this->security->xss_clean($this->input->post('status'));	
					
				if($editableBannerUrlID!=''){
						$bannerUrlData['updatedOn']	    =	date('Y-m-d H:i:s');
						$previous_uriString				=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('uri_string'),	
															array('id'=>$editableBannerUrlID));
						$dbStatus			        	=	$this->base_model->updateRecord($bannerUrlData,'tbl_bannerurl_mastercategory',array('id'=>$editableBannerUrlID));
						$flashMessage			    	=	'Category info updated successfully.';
						$bannerurl_insertID			    =	$editableBannerUrlID;		
						$this->dbforge->rename_table('dynamic_tbl_'.$previous_uriString->uri_string, 'dynamic_tbl_'.$uri_string); // rename table			
				}
				else{
						$bannerUrlData['createdOn']	=	date('Y-m-d H:i:s');													
						$dbStatus					=	$this->base_model->saveRecord($bannerUrlData,'tbl_bannerurl_mastercategory');
						$bannerurl_insertID			=	$this->db->insert_id();								
						$flashMessage				=	'Category created successfully.';
				}
				
				
				$this->session->unset_userdata('post_bannerUrlData');
				if($dbStatus){					
						
					
					if($this->input->post('submit')=='savenc')
					{
						$this->session->set_flashdata('admincp_bannerurlform_flash_message', '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($bannerurl_insertID));
						redirect('admincp/bannerurl/edit/'.$encryptedID);
					}
					elseif($this->input->post('submit')=='save')
					{
						$this->session->set_flashdata('admincp_urlbanner_flash_message', '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						redirect('admincp/bannerurl');
					}
				}
				else
				{	 
						if($this->input->post('submit')=='savenc')
						{
							$this->session->set_flashdata('admincp_bannerurlform_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($bannerurl_insertID));
							redirect('admincp/bannerurl/edit/'.$encryptedID);
						}
						elseif($this->input->post('submit')=='save')
						{
							$this->session->set_flashdata('admincp_urlbanner_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							redirect('admincp/bannerurl');
						}
				}
			}
				//end
		}
		
		
		//function to add new field
		public function addnewfield($catgId=NULL){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp'); //checks if admin is logged-in or not if not redirect on login page
			
			if($catgId=='' || $catgId==NULL){
									$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-info alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-info"></i> <b>Important: </b>You need to add category first.</div>');
					redirect('admincp/bannerurl/addnew');	

			}
			else
			{
				$bannerUrlID				= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$catgId);
				$data['bannerUrlInfo']		=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory','',array('id'=>$this->encrypt->decode($bannerUrlID)));	
				$data['hdnBannerUrlID']		=	'';
				$data['hdnFieldID']			=	'';
				if(!empty($data['bannerUrlInfo'])){
					$data['ref_hdnBannerUrlID']		=	$catgId;					
					$data['title'] 					= 	'Projects 100K';
					$postedValue_fname 				= 	array();
					if($this->session->userdata('post_bannerUrlFieldData'))
					$data['postedValue'] 			= 	$this->session->userdata('post_bannerUrlFieldData');
					$data['bannerUrlFieldInfo']		=	array();
					$last_order	=	$this->base_model->is_Record_Exists('tbl_bannerurl_masterfields',array('order'),
																	array('bannerurlCategoryId'=>$data['bannerUrlInfo']->id),
																	'order','desc','0','1'
																	);
					if(!empty($last_order))
					$data['visibility_order']	=	$last_order->order+1;
					else
					$data['visibility_order']	=	1;	
					$this->load->view('templates/admincp/header', $data);
					$this->load->view('admincp/bannerurl/categoryBannerForm', $data);
			}
				else{
					$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-info alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-info"></i> <b>Important: </b>Url &amp; Banner category does not exists for which you are trying to add new fields.</div>');
					redirect('admincp/bannerurl/edit/'.$catgId);	
				}
			}
		}
		
		//function to edit new field
		public function editfield($fieldId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$bannerUrl_fieldID			= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$fieldId);			
			$data['bannerUrlFieldInfo'] =	$this->base_model->is_Record_Exists('tbl_bannerurl_masterfields','',array('id'=>$this->encrypt->decode($bannerUrl_fieldID)));
			$data['fieldOptions']		=	array();	
			if(!empty($data['bannerUrlFieldInfo'])){
					$data['ref_hdnBannerUrlID']		=	str_replace(array('+', '/', '='), array('-', '_', '~'),
														$this->encrypt->encode($data['bannerUrlFieldInfo']->bannerurlCategoryId));					
					$data['title'] 					= 	'Projects 100K';	
					$data['hdnFieldID']				=	str_replace(array('+', '/', '='), array('-', '_', '~'),
														$this->encrypt->encode($data['bannerUrlFieldInfo']->id));
					if($data['bannerUrlFieldInfo']->type == 3 || $data['bannerUrlFieldInfo']->type == 4 || $data['bannerUrlFieldInfo']->type == 5)	
					{
						$data['fieldOptions']	=	$this->base_model->get_All_Records('tbl_bannerurl_field_options','',array('masterfieldsID'=>$data['bannerUrlFieldInfo']->id));
					}	
					$data['visibility_order']	=	'';	
					//print'<pre/>'				;print_r($data);die;
					$this->load->view('templates/admincp/header', $data);
					$this->load->view('admincp/bannerurl/categoryBannerForm', $data);
			}
			else{
				$data['bannerUrlFieldInfo']		=	array();
				$data['hdnFieldID']				=	'';				
				$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-info"></i> <b>Important: </b>Field does not exists.</div>');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		
		//function to save /edit bannerurl field
		public function savefield(){
		        if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp'); // redirecting to login page if session expired or not  logged in 
				
				$this->load->dbforge();		// loading library to create dynamic table or fields
				// Title must be unique
				$editable_hdnFieldID = '';
				$titleRule =	'';
				//checks and stroe userid value 
				if($this->input->post('hdnFieldID')!=''){
					$editable_hdnFieldID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('hdnFieldID')));			
				}
				if($this->input->post('ref_hdnBannerUrlID')!=''){
					$ref_hdnBannerUrlID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('ref_hdnBannerUrlID')));			
				}
				//check if title record already exists or not
				if($this->input->post('title')!=''){
					
					$bannerUrlFieldRecord	=	$this->base_model->is_Record_Exists('tbl_bannerurl_masterfields',array('id','type'),
												array('title'=>ucwords($this->security->xss_clean($this->input->post('title'))), 
													  'bannerurlCategoryId'=>$ref_hdnBannerUrlID));
					//echo '--'.$this->db->last_query();die;
					if(!empty($bannerUrlFieldRecord)){	
						if($editable_hdnFieldID!='' && $bannerUrlFieldRecord->id === $editable_hdnFieldID){
								$proceedToSave = 'Yes';
						}
						else{
							 $proceedToSave = 'No';	
							 $titleRule = '|is_unique[tbl_bannerurl_masterfields.title]';
						}
					}
				}
				$this->form_validation->set_rules('title', 'Title', 'trim|required|alpha_numeric_spaces'.$titleRule.'|xss_clean');
				$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
				$this->form_validation->set_rules('isMandatory', 'Is Mandatory', 'trim|required|xss_clean');
				$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
				$this->form_validation->set_rules('addtoTableHeading', 'Add to table heading', 'trim|required|xss_clean');
				$this->form_validation->set_rules('sortable', 'Sortable', 'trim|required|xss_clean');
				if ($this->form_validation->run() == FALSE)
				{	
						
						$this->session->set_userdata('post_bannerUrlFieldData',$this->input->post());				
						if($editable_hdnFieldID!=''){
							$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
							$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($editable_hdnFieldID));
							redirect('admincp/bannerurl/editfield/'.$link);
						}
						else{
							$this->session->set_flashdata('admincp_bannerurlfieldform_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
							redirect('admincp/bannerurl/addnewfield/'.$this->security->xss_clean($this->input->post('ref_hdnBannerUrlID')));		
						}
				}
				else
				{
							$dbStatus = '';
							$bannerUrlFieldData = array();					
				
				// setting variables and its value to pass to database to save records			
				$bannerUrlFieldData['title']					=	$this->security->xss_clean($this->input->post('title'));
				$bannerUrlFieldData_type						=	$this->security->xss_clean($this->input->post('type'));
				$bannerUrlFieldData['bannerurlCategoryId']		=	$ref_hdnBannerUrlID;
				$bannerUrlFieldData['isMandatory']				=	$this->security->xss_clean($this->input->post('isMandatory'));
				$bannerUrlFieldData['order']					=	$this->security->xss_clean($this->input->post('order'));
				$bannerUrlFieldData['status']					=	$this->security->xss_clean($this->input->post('status'));
				$bannerUrlFieldData['sortable']					=	$this->security->xss_clean($this->input->post('sortable'));
				$bannerUrlFieldData['addtoTableHeading']		=	$this->security->xss_clean($this->input->post('addtoTableHeading'));	
		      // print'<pre/>';print_r($bannerUrlFieldData);die;
				if($editable_hdnFieldID!=''){						
						$bannerUrlFieldData['updatedOn']  =	date('Y-m-d H:i:s');							
						$dbStatus			        	 =	$this->base_model->updateRecord($bannerUrlFieldData,'tbl_bannerurl_masterfields',array('id'=>$editable_hdnFieldID));
						
						$optionValues	=	$this->security->xss_clean($this->input->post('field_options'));
						if(!empty($optionValues)){
							  $deleteOption	=	$this->base_model->deleteRecord('tbl_bannerurl_field_options',array('masterfieldsID'=>$editable_hdnFieldID));
							  foreach($optionValues as $key=>$optValue){	
							 	$optionData	=	array();									 
										 if($optValue!=''){
												 $optionData['masterfieldsID']	=	$editable_hdnFieldID;
												 $optionData['optionvalue']		=	$optValue;
												 $optionData['precedence']		=	$key+1;
												 $optionDbStatus				=	$this->base_model->saveRecord($optionData,'tbl_bannerurl_field_options');
										 }
							 }
						}
						
						$flashMessage			    	=	'Field info updated successfully.';
						$field_insertID			        =	$editable_hdnFieldID;					
				}
				else{
						/****************************************
						@Purpose: To save category fields in table
						*****************************************/
						$bannerUrlFieldData['type']			=	$bannerUrlFieldData_type;
						$bannerUrlFieldData['createdOn']	=	date('Y-m-d H:i:s');													
						$dbStatus							=	$this->base_model->saveRecord($bannerUrlFieldData,'tbl_bannerurl_masterfields');
						$field_insertID						=	$this->db->insert_id();	
						// end
						
						/**********************************************
						@Purpose: To check if table exists or not.
						If not will create a new table or add new field.
						***********************************************/
						$new_ColumnName		=	strtolower(str_replace(array(' ','-'),array('_'),$bannerUrlFieldData['title']));	
						$categoryInfo		=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('title'),array('id'=>$ref_hdnBannerUrlID));								  						$new_TableName		=	strtolower(str_replace(array(' ','-'),array('_'),$categoryInfo->title));
						if ($this->db->table_exists('dynamic_tbl_'.$new_TableName))
						{ 
							 $tableFields 	= array(
							 							$new_ColumnName  =>	array(
																						'type'	=>	'TEXT'
																				  )
												   );
							$this->dbforge->add_field($tableFields);
							$this->dbforge->add_column('dynamic_tbl_'.$new_TableName,$tableFields);
						}
						else{
							$tableFields 	= array(
														'id' 			     =>    array(
																							    'type' => 'INT',
																							    'constraint' => 11,
																							    'auto_increment' => TRUE
																					     ),
														'bannerurlCategoryId' =>	array(
																								 'type' => 'INT',
																								 'constraint' => 11,
																						  ),
														'ads_status' 		      =>	array(
																								 'type' => 'ENUM',
																								 'constraint' => array('Active','Inactive','Suspend'),
																								 'default'=>'Active'
																						  ),
														'createdOn' 		  =>	array(
																								 'type' => 'DATETIME',
																						  ),
														'updatedOn' 		  =>	array(
																								 'type' => 'DATETIME',
																						  ),
														$new_ColumnName 	  =>	array(
																								 'type'	=>	'TEXT'
																				         )
											        );
							
							
							$this->dbforge->add_field($tableFields);						
							$this->dbforge->add_key('id', TRUE);	// gives PRIMARY KEY 						
							$this->dbforge->add_key('bannerurlCategoryId');
							// gives KEY 
							$this->dbforge->create_table('dynamic_tbl_'.$new_TableName,TRUE);
							//end						
						}
						
						if($bannerUrlFieldData_type == 3 || $bannerUrlFieldData_type == 4 || $bannerUrlFieldData_type == 5)						
						{
							 $optionValues	=	$this->security->xss_clean($this->input->post('field_options'));
							 foreach($optionValues as $key=>$optValue){	
							 			$optionData	=	array();									 
										 if($optValue!=''){
												 $optionData['masterfieldsID']	=	$field_insertID;
												 $optionData['optionvalue']		=	$optValue;
												 $optionData['precedence']		=	$key+1;
												 $optionDbStatus				=	$this->base_model->saveRecord($optionData,'tbl_bannerurl_field_options');
										 }
							 }
						}	
					
						$flashMessage	=	'Field created successfully.';
				}				
				$this->session->unset_userdata('post_bannerUrlFieldData');
				if($dbStatus){
					if($this->input->post('submit')=='savenc')
					{
						$this->session->set_flashdata('admincp_bannerurlfieldform_flash_message', '<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($field_insertID));
						redirect('admincp/bannerurl/editfield/'.$encryptedID);
					}
					elseif($this->input->post('submit')=='save')
					{
						$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						redirect('admincp/bannerurl/edit/'.$this->security->xss_clean($this->input->post('ref_hdnBannerUrlID')));
					}
				}
				else
				{						 
						if($this->input->post('submit')=='savenc')
						{
							$this->session->set_flashdata('admincp_bannerurlfieldform_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($field_insertID));
							redirect('admincp/bannerurl/editfield/'.$encryptedID);
						}
						elseif($this->input->post('submit')=='save')
						{
							$this->session->set_flashdata('admincp_bannerurlfieldlist_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							redirect('admincp/bannerurl/addnewfield/'.$this->security->xss_clean($this->input->post('ref_hdnBannerUrlID')));
						}
				}
			}
				//end
		}
		
		
}

?>