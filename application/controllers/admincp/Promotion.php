<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Promotion extends CI_Controller{
		
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
			$data['promotionCategoryLists']	=	$this->base_model->get_All_Records('tbl_promotion_mastercategory');		
			if(!empty($data['promotionCategoryLists'])){
					foreach($data['promotionCategoryLists'] as $key=>$catg){	
						$catg->active_records	=	0;
						$catg->inactive_records	=	0;	
										
						if($this->db->table_exists('dynamic_promo_tbl_'.$catg->uri_string)){
							//count active records
							$activeAds	=	$this->base_model->count_records('dynamic_promo_tbl_'.$catg->uri_string, array('ads_status'=>'Active'));
							$catg->active_records	=	$activeAds;	
							//count inactive records
							$inactiveAds	=	$this->base_model->count_records('dynamic_promo_tbl_'.$catg->uri_string, array('ads_status'=>'Suspend'));
							$catg->inactive_records	=	$inactiveAds;	
						}
					}
			}
		//	print_r($data);die;
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/promotion/index', $data);		
		}
	    
		public function category($catg)
		{
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['promotionCategoryTypeName']			=	'';
			$data['promotionCategorywiseLists']		=	$data['categorywiseFieldsInfo']		=	array();
			$encryptedTitle							=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($catg));
			$data['title'] 							= 	'Projects 100K';						
			$categoryInfo							=	$this->base_model->is_Record_Exists('tbl_promotion_mastercategory',array('id','title_hashkey','title'),
														array('title'=>$catg));		
			if(!empty($categoryInfo) && $encryptedTitle === $categoryInfo->title_hashkey){
				$data['promotionCategorywiseLists']		=	$this->base_model->get_All_Records('tbl_promotion_categorywise','',array('promotioncategoryid'=>$categoryInfo->id));
				$data['promotionCategoryTypeName']			=	$categoryInfo->title;
				$data['categorywiseFieldsInfo']			=	$this->base_model->is_Record_Exists('tbl_promotion_masterfields','',array('promotionCategoryId'=>$categoryInfo->id));
			}			
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/promotion/categoryBannersList', $data);
			$this->load->view('templates/admincp/footer', $data);
		}
		
		
		//function to edit existing user
		public function edit($catgId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 		= 'Projects 100K';
			$postedValue_fname 	= array();
			if($this->session->userdata('post_promotionData'))
			$data['postedValue'] 		= 	$this->session->userdata('post_promotionData');
			$promotionID				= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$catgId);			
			$data['promotionInfo']		=	$this->base_model->is_Record_Exists('tbl_promotion_mastercategory','',array('id'=>$this->encrypt->decode($promotionID)));	
			$data['fieldInfo']			=	$this->base_model->get_All_Records('tbl_promotion_masterfields','',
											array('promotionCategoryId'=>$this->encrypt->decode($promotionID)),'order','asc');				
			$data['catg_uri_string']	=	$data['promotionInfo']->uri_string;
			$data['catgTitle']			=	stripslashes(ucwords($data['promotionInfo']->title));
			$data['hdnPromotionID']		=	$catgId;
			$this->session->unset_userdata('post_promotionFieldData');
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/promotion/form', $data);
		}
		
		
		//function to add new user
		public function addnew(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 			= 'Projects 100K';
			$postedValue_fname 		= array();
			if($this->session->userdata('post_promotionData'))
			$data['postedValue'] 		= 	$this->session->userdata('post_promotionData');
			$data['hdnPromotionID']		=	'';	
			$data['promotionInfo']		=	array();
			$data['catg_uri_string']	=	'';
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/promotion/form', $data);
		}
		
		//function to save new User
		public function save(){
		   
				$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');		
				//cross-checking unique fields 
				// Title must be unique
				$editablePromotionID = '';
				$titleRule =	'';
				//checks and stroe userid value 
				if($this->input->post('hdnPromotionID')!=''){
					$editablePromotionID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('hdnPromotionID')));			
				}
				//check if title record already exists or not
				if($this->input->post('title')!=''){
					$bannerUrlRecord	=	$this->base_model->is_Record_Exists('tbl_promotion_mastercategory',array('id'),array('title'=>$this->input->post('title')));
					if(!empty($bannerUrlRecord)){	
						if($editablePromotionID!='' && $bannerUrlRecord->id === $editablePromotionID){
								$proceedToSave = 'Yes';
						}
						else{
							 $proceedToSave = 'No';	
							 $titleRule = '|is_unique[tbl_promotion_mastercategory.title]';
						}
					}
				}
				$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[5]|alpha_numeric_spaces'.$titleRule.'|xss_clean');
		
				if ($this->form_validation->run() == FALSE)
				{
						$this->session->set_flashdata('admincp_promotionform_flash_message', '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
						
						$this->session->set_userdata('post_promotionData',$this->input->post());				
						if($editablePromotionID!=''){
							$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($editablePromotionID));
							redirect('admincp/promotion/edit/'.$link);
						}
						else
						redirect('admincp/promotion/addnew');							
				}
				else
				{ 
							$dbStatus = '';
							$promotionData = array();					
						
				// setting variables and its value to pass to database to save records			
				$promotionData['title']					=	$this->security->xss_clean($this->input->post('title'));
				$uri_string								=	strtolower(str_replace(array(' ','-'),array('_'),$promotionData['title']));
				$promotionData['uri_string']			=	$uri_string;
				$promotionData['status']				=	$this->security->xss_clean($this->input->post('status'));	
					
				if($editablePromotionID!=''){
						$promotionData['updatedOn']	    =	date('Y-m-d H:i:s');
						$previous_uriString				=	$this->base_model->is_Record_Exists('tbl_promotion_mastercategory',array('uri_string'),	
															array('id'=>$editablePromotionID));
						$dbStatus			        	=	$this->base_model->updateRecord($promotionData,'tbl_promotion_mastercategory',array('id'=>$editablePromotionID));
						$flashMessage			    	=	'Category info updated successfully.';
						$promotionl_insertID			    =	$editablePromotionID;		
						$this->dbforge->rename_table('dynamic_promo_tbl_'.$previous_uriString->uri_string, 'dynamic_promo_tbl_'.$uri_string); // rename table			
				}
				else{
						$promotionData['createdOn']	=	date('Y-m-d H:i:s');													
						$dbStatus					=	$this->base_model->saveRecord($promotionData,'tbl_promotion_mastercategory');
						$promotionl_insertID			=	$this->db->insert_id();								
						$flashMessage				=	'Category created successfully.';
				}
				
				
				$this->session->unset_userdata('post_promotionData');
				if($dbStatus){					
						
					
					if($this->input->post('submit')=='savenc')
					{
						$this->session->set_flashdata('admincp_promotionform_flash_message', '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($promotionl_insertID));
						redirect('admincp/promotion/edit/'.$encryptedID);
					}
					elseif($this->input->post('submit')=='save')
					{
						$this->session->set_flashdata('admincp_promotion_flash_message', '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						redirect('admincp/promotion');
					}
				}
				else
				{	 
						if($this->input->post('submit')=='savenc')
						{
							$this->session->set_flashdata('admincp_promotionform_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($promotionl_insertID));
							redirect('admincp/promotion/edit/'.$encryptedID);
						}
						elseif($this->input->post('submit')=='save')
						{
							$this->session->set_flashdata('admincp_promotion_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							redirect('admincp/promotion');
						}
				}
			}
				//end
		}
		
		
		//function to add new field
		public function addnewfield($catgId=NULL){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp'); //checks if admin is logged-in or not if not redirect on login page
			
			if($catgId=='' || $catgId==NULL){
									$this->session->set_flashdata('admincp_promotionfieldlist_flash_message', '<div class="alert alert-info alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-info"></i> <b>Important: </b>You need to add category first.</div>');
					redirect('admincp/promotion/addnew');	

			}
			else
			{
				$promotionID				= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$catgId);
				$data['promotionInfo']		=	$this->base_model->is_Record_Exists('tbl_promotion_mastercategory','',array('id'=>$this->encrypt->decode($promotionID)));	
				$data['hdnPromotionID']		=	'';
				$data['hdnFieldID']			=	'';
				if(!empty($data['promotionInfo'])){
					$data['ref_hdnPromotionID']		=	$catgId;					
					$data['title'] 					= 	'Projects 100K';
					$postedValue_fname 				= 	array();
					if($this->session->userdata('post_promotionFieldData'))
					$data['postedValue'] 			= 	$this->session->userdata('post_promotionFieldData');
					$data['promotionFieldInfo']		=	array();
					$last_order	=	$this->base_model->is_Record_Exists('tbl_promotion_masterfields',array('order'),
																	array('promotionCategoryId'=>$data['promotionInfo']->id),
																	'order','desc','0','1'
																	);
					if(!empty($last_order))
					$data['visibility_order']	=	$last_order->order+1;
					else
					$data['visibility_order']	=	1;	
					$this->load->view('templates/admincp/header', $data);
					$this->load->view('admincp/promotion/categoryBannerForm', $data);
			}
				else{
					$this->session->set_flashdata('admincp_promotionfieldlist_flash_message', '<div class="alert alert-info alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-info"></i> <b>Important: </b>Url &amp; Banner category does not exists for which you are trying to add new fields.</div>');
					redirect('admincp/promotion/edit/'.$catgId);	
				}
			}
		}
		
		//function to edit new field
		public function editfield($fieldId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$bannerUrl_fieldID			= 	str_replace(array('-', '_', '~'), array('+', '/', '='),$fieldId);			
			$data['promotionFieldInfo'] =	$this->base_model->is_Record_Exists('tbl_promotion_masterfields','',array('id'=>$this->encrypt->decode($bannerUrl_fieldID)));
			$data['fieldOptions']		=	array();	
			if(!empty($data['promotionFieldInfo'])){
					$data['ref_hdnPromotionID']		=	str_replace(array('+', '/', '='), array('-', '_', '~'),
														$this->encrypt->encode($data['promotionFieldInfo']->promotionCategoryId));					
					$data['title'] 					= 	'Projects 100K';	
					$data['hdnFieldID']				=	str_replace(array('+', '/', '='), array('-', '_', '~'),
														$this->encrypt->encode($data['promotionFieldInfo']->id));
					if($data['promotionFieldInfo']->type == 3 || $data['promotionFieldInfo']->type == 4 || $data['promotionFieldInfo']->type == 5)	
					{
						$data['fieldOptions']	=	$this->base_model->get_All_Records('tbl_promotion_field_options','',array('masterfieldsID'=>$data['promotionFieldInfo']->id));
					}	
					$data['visibility_order']	=	'';	
					//print'<pre/>'				;print_r($data);die;
					$this->load->view('templates/admincp/header', $data);
					$this->load->view('admincp/promotion/categoryBannerForm', $data);
			}
			else{
				$data['promotionFieldInfo']		=	array();
				$data['hdnFieldID']				=	'';				
				$this->session->set_flashdata('admincp_promotionfieldlist_flash_message', '<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-info"></i> <b>Important: </b>Field does not exists.</div>');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		
		//function to save /edit promotion field
		public function savefield(){
		        if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp'); // redirecting to login page if session expired or not  logged in 
			//	print'<pre/>';print_r($this->input->post());die;
				$this->load->dbforge();		// loading library to create dynamic table or fields
				// Title must be unique
				$editable_hdnFieldID = '';
				$titleRule =	'';
				//checks and stroe userid value 
				if($this->input->post('hdnFieldID')!=''){
					$editable_hdnFieldID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('hdnFieldID')));			
				}
				if($this->input->post('ref_hdnPromotionID')!=''){
					$ref_hdnPromotionID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('ref_hdnPromotionID')));			
				}
				//check if title record already exists or not
				if($this->input->post('title')!=''){					
					$promotionFieldRecord	=	$this->base_model->is_Record_Exists('tbl_promotion_masterfields',array('id','type'),
												array('title'=>ucwords($this->security->xss_clean($this->input->post('title'))), 
													  'promotionCategoryId'=>$ref_hdnPromotionID));
					//echo '--'.$this->db->last_query();die;
					if(!empty($promotionFieldRecord)){	
						if($editable_hdnFieldID!='' && $promotionFieldRecord->id === $editable_hdnFieldID){
								$proceedToSave = 'Yes';
						}
						else{
							 $proceedToSave = 'No';	
							 $titleRule = '|is_unique[tbl_promotion_masterfields.title]';
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
						
						$this->session->set_userdata('post_promotionFieldData',$this->input->post());				
						if($editable_hdnFieldID!=''){
							$this->session->set_flashdata('admincp_promotionfieldlist_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
							$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($editable_hdnFieldID));
							redirect('admincp/promotion/editfield/'.$link);
						}
						else{
							$this->session->set_flashdata('admincp_promotionfieldform_flash_message', '<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
							redirect('admincp/promotion/addnewfield/'.$this->security->xss_clean($this->input->post('ref_hdnPromotionID')));		
						}
				}
				else
				{
							$dbStatus = '';
							$promotionFieldData = array();					
				
				// setting variables and its value to pass to database to save records			
				$promotionFieldData['title']					=	$this->security->xss_clean($this->input->post('title'));
				$promotionFieldData_type						=	$this->security->xss_clean($this->input->post('type'));
				$promotionFieldData['promotionCategoryId']		=	$ref_hdnPromotionID;
				$promotionFieldData['isMandatory']				=	$this->security->xss_clean($this->input->post('isMandatory'));
				$promotionFieldData['order']					=	$this->security->xss_clean($this->input->post('order'));
				$promotionFieldData['status']					=	$this->security->xss_clean($this->input->post('status'));
				$promotionFieldData['sortable']					=	$this->security->xss_clean($this->input->post('sortable'));
				$promotionFieldData['addtoTableHeading']		=	$this->security->xss_clean($this->input->post('addtoTableHeading'));	
		      // print'<pre/>';print_r($promotionFieldData);die;
				if($editable_hdnFieldID!=''){						
						$promotionFieldData['updatedOn']  =	date('Y-m-d H:i:s');							
						$dbStatus			        	 =	$this->base_model->updateRecord($promotionFieldData,'tbl_promotion_masterfields',array('id'=>$editable_hdnFieldID));
						
						$optionValues	=	$this->security->xss_clean($this->input->post('field_options'));
						if(!empty($optionValues)){
							  $deleteOption	=	$this->base_model->deleteRecord('tbl_promotion_field_options',array('masterfieldsID'=>$editable_hdnFieldID));
							  foreach($optionValues as $key=>$optValue){	
							 	$optionData	=	array();									 
										 if($optValue!=''){
												 $optionData['masterfieldsID']	=	$editable_hdnFieldID;
												 $optionData['optionvalue']		=	$optValue;
												 $optionData['precedence']		=	$key+1;
												 $optionDbStatus				=	$this->base_model->saveRecord($optionData,'tbl_promotion_field_options');
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
						$promotionFieldData['type']			=	$promotionFieldData_type;
						$promotionFieldData['createdOn']	=	date('Y-m-d H:i:s');													
						$dbStatus							=	$this->base_model->saveRecord($promotionFieldData,'tbl_promotion_masterfields');
						$field_insertID						=	$this->db->insert_id();	
						// end
						
						/**********************************************
						@Purpose: To check if table exists or not.
						If not will create a new table or add new field.
						***********************************************/
						$new_ColumnName		=	strtolower(str_replace(array(' ','-'),array('_'),$promotionFieldData['title']));	
						$categoryInfo		=	$this->base_model->is_Record_Exists('tbl_promotion_mastercategory',array('title'),array('id'=>$ref_hdnPromotionID));								  						$new_TableName		=	strtolower(str_replace(array(' ','-'),array('_'),$categoryInfo->title));
						if ($this->db->table_exists('dynamic_promo_tbl_'.$new_TableName))
						{ 
							 $tableFields 	= array(
							 							$new_ColumnName  =>	array(
																						'type'	=>	'TEXT'
																				  )
												   );
							$this->dbforge->add_field($tableFields);
							if($promotionFieldData_type=='7'){
								$new_ColumnName		=	'hyperlink_label_'.$field_insertID;
								$tableFields 	= array(
							 							$new_ColumnName  =>	array(
																						'type'	=>	'TEXT'
																				  )
												   );	
								$this->dbforge->add_field($tableFields);
							}
							$this->dbforge->add_field($tableFields);
							$this->dbforge->add_column('dynamic_promo_tbl_'.$new_TableName,$tableFields);
						}
						else{
							$tableFields 	= array(
														'id' 			     =>    array(
																							    'type' => 'INT',
																							    'constraint' => 11,
																							    'auto_increment' => TRUE
																					     ),
														'promotionCategoryId' =>	array(
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
							$this->dbforge->add_key('promotionCategoryId');
							// gives KEY 
							$this->dbforge->create_table('dynamic_promo_tbl_'.$new_TableName,TRUE);
							//end						
						}
						
						if($promotionFieldData_type == 3 || $promotionFieldData_type == 4 || $promotionFieldData_type == 5)						
						{
							 $optionValues	=	$this->security->xss_clean($this->input->post('field_options'));
							 foreach($optionValues as $key=>$optValue){	
							 			$optionData	=	array();									 
										 if($optValue!=''){
												 $optionData['masterfieldsID']	=	$field_insertID;
												 $optionData['optionvalue']		=	$optValue;
												 $optionData['precedence']		=	$key+1;
												 $optionDbStatus				=	$this->base_model->saveRecord($optionData,'tbl_promotion_field_options');
										 }
							 }
						}	
					
						$flashMessage	=	'Field created successfully.';
				}				
				$this->session->unset_userdata('post_promotionFieldData');
				if($dbStatus){
					if($this->input->post('submit')=='savenc')
					{
						$this->session->set_flashdata('admincp_promotionfieldform_flash_message', '<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($field_insertID));
						redirect('admincp/promotion/editfield/'.$encryptedID);
					}
					elseif($this->input->post('submit')=='save')
					{
						$this->session->set_flashdata('admincp_promotionfieldlist_flash_message', '<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<i class="icon fa fa-check"></i> <b>Success:</b> '.$flashMessage.'</div>');	
						redirect('admincp/promotion/edit/'.$this->security->xss_clean($this->input->post('ref_hdnPromotionID')));
					}
				}
				else
				{						 
						if($this->input->post('submit')=='savenc')
						{
							$this->session->set_flashdata('admincp_promotionfieldform_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($field_insertID));
							redirect('admincp/promotion/editfield/'.$encryptedID);
						}
						elseif($this->input->post('submit')=='save')
						{
							$this->session->set_flashdata('admincp_promotionfieldlist_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
							redirect('admincp/promotion/addnewfield/'.$this->security->xss_clean($this->input->post('ref_hdnPromotionID')));
						}
				}
			}
				//end
		}
		
		
}

?>