<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Bannercategory extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			//check if admin is logged in or not.	
			$this->load->dbforge();			
			$this->load->model('base_model');	
			$this->load->model('admincp/bannerurl_model');				
		}
		
		//function to get lists of all users
		public function index($catgId){	
		
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 							= 	'Projects 100K';	
			if($this->session->userdata('post_bannerUrlCommonForm_Data'))
			$this->session->unset_userdata('post_bannerUrlCommonForm_Data');	
			$data['request_catgId']				    =	$catgId;
			$decryptedCatgId						=	$this->encrypt->decode(str_replace(array('-', '_', '~'),array('+', '/', '='),$catgId));
			$categoryRecordInfo						=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('id','title'),array('id'=>$decryptedCatgId));
			$data['decryptedCatgId']				=	$decryptedCatgId;
			$data['unSortableColumnKeys']				=	'';
			$data['ads_lists']						=	array();
			if(!empty($categoryRecordInfo)){
				$data['categoryName']				=	ucwords($categoryRecordInfo->title);
				$data['tblName']	=	$tblName    =	strtolower(str_replace(array(' ','-'),array('_'),$data['categoryName']));
				$data['bannerCategoryTableHeaders']	=	$this->bannerurl_model->filter_bannerurl_rowhead($decryptedCatgId);
				if(!empty($data['bannerCategoryTableHeaders'])){
					$sortableArray	=	array();
					foreach($data['bannerCategoryTableHeaders'] as $key=>$value){
						 if($value['sortable'] == 'no')array_push($sortableArray,($key+1));						
					}
					$sortableArray[]				=	count($data['bannerCategoryTableHeaders'])+2;
					$data['unSortableColumnKeys']   =	implode(',',$sortableArray);
					if($this->db->table_exists('dynamic_tbl_'.$tblName))
					$data['ads_lists']				=	$this->base_model->get_All_Records('dynamic_tbl_'.$tblName,'','','id','asc');					
					
				}
				
				$this->load->view('templates/admincp/header', $data);
				$this->load->view('admincp/bannercategory/index', $data);	
			}
			else{			
				redirect($_SERVER['HTTP_REFERER']);								
			}
		}
	    
		public function category($catgId)
		{
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['bannerCategoryTypeName']			    =	'';
			$data['bannerurlCategorywiseLists']		    =	$data['categorywiseFieldsInfo']	 =	array();
			$encryptedTitle							    =	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($catgId));
			$data['title'] 							    = 	'Projects 100K';						
			$categoryInfo							    =	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',
															array('id','title_hashkey','title'), array('title'=>$catgId));		
			if(!empty($categoryInfo) && $encryptedTitle === $categoryInfo->title_hashkey){
				$data['bannerurlCategorywiseLists']		=	$this->base_model->get_All_Records('tbl_bannerurl_categorywise','',array('bannercategoryid'=>$categoryInfo->id));
				$data['bannerCategoryTypeName']			=	$categoryInfo->title;
				$data['categorywiseFieldsInfo']			=	$this->base_model->is_Record_Exists('tbl_bannerurl_masterfields','',array('bannerurlCategoryId'=>$categoryInfo->id));
			}			
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannerurl/categoryBannersList', $data);
			$this->load->view('templates/admincp/footer', $data);
		}
		
		//function to add new user
		public function addnew($catgId){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 			= 'Projects 100K';
			$postedValue_fname 		= array();
			if($this->session->userdata('post_bannerUrlCommonForm_Data'))
			$data['postedRequestValue'] = 	$this->session->userdata('post_bannerUrlCommonForm_Data');
			$data['hdn_editable_formID']=	'';	
			$data['bannerUrlInfo']		=	array();
			$data['ref_hdncatgID']		=	$catgId;
			$decryptedCatgId			=	$this->encrypt->decode(str_replace(array('-', '_', '~'),array('+', '/', '='),$catgId));
			$categoryInfo				=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('title'),array('id'=>$decryptedCatgId));
			$data['breadcrumb_string']	=	'<a href="'.base_url().'admincp/bannercategory/'.$catgId.'">'.$categoryInfo->title.' Listings</a>';
			
			$fields						=	$this->bannerurl_model->get_bannerurl_fieldsByCatgId($decryptedCatgId);
			if(!empty($fields)) {
				foreach($fields as $key=>$field){
					if($field['type']	== 3 || $field['type']	== 4 || $field['type']	== 5){
							$options			=	$this->bannerurl_model->field_options($field['id']);							
							$filteredOptions	=	array_column($options,'optionvalue', 'id');	
							$fields[$key]['options']	=	$filteredOptions;			
					}
				}
				
			}
			$data['fields']	=	$fields;
			//print'<pre/>';print_r($data);die;
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannercategory/form', $data);
		}
		
		//function to edit the ads
		public function edit($catgSection,$editableAdsId){
			
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');
			$data['title'] 				= 	'Projects 100K';
			$postedValue_fname 			= 	array();			
			$data['hdn_editable_formID']=	$editableAdsId;	
			
			$data['adsInfo']			=	array();
			$catgTitle					=	strtolower(str_replace(array(' ','-'),array('_'),$this->security->xss_clean($catgSection)));
			$categoryRecordInfo			=	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('id','title'),array('uri_string'=>$catgTitle));			
			$data['ref_hdncatgID']		=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($categoryRecordInfo->id));
			$data['breadcrumb_string']	=	'<a href="'.base_url().'admincp/bannercategory/'.$data['ref_hdncatgID'].'">'.$categoryRecordInfo->title.' Listings</a>';
			$decryptedEditable_formID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'),array('+', '/', '='),$editableAdsId));
			$data['postedRequestValue']	=	(array)$this->base_model->is_Record_Exists('dynamic_tbl_'.$catgSection,'',array('id'=>$decryptedEditable_formID));	
			$fields						=	$this->bannerurl_model->get_bannerurl_fieldsByCatgId($categoryRecordInfo->id);
			//if($this->session->userdata('post_bannerUrlCommonForm_Data'))
			//$data['postedRequestValue'] = 	$this->session->userdata('post_bannerUrlCommonForm_Data');
			if(!empty($fields)) {
				foreach($fields as $key=>$field){
					if($field['type']	== 3 || $field['type']	== 4 || $field['type']	== 5){
							$options			=	$this->bannerurl_model->field_options($field['id']);							
							$filteredOptions	=	array_column($options,'optionvalue', 'id');	
							$fields[$key]['options']	=	$filteredOptions;			
					}
					
				}
			}
			$data['fields']	=	$fields;
			//print'<pre/>';print_r($data);	die;
			$this->load->view('templates/admincp/header', $data);
			$this->load->view('admincp/bannercategory/form', $data);
		}
		
		//function to save the values
		public function save(){
			if($this->session->userdata('loggedIn_adminInfo')=='')redirect('admincp');   			
			
			$titleRule =	'';$editable_formID = '';
			//checks and stroe userid value 
			if($this->input->post('hdn_editable_formID')!='')
				$editable_formID	=	$this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->input->post('hdn_editable_formID')));			
			if($this->input->post('ref_hdncatgID')!='')			
				$decryptedCatgId    =	$this->encrypt->decode(str_replace(array('-', '_', '~'),array('+', '/', '='),$this->input->post('ref_hdncatgID')));
			
			$categoryInfo	 =	$this->base_model->is_Record_Exists('tbl_bannerurl_mastercategory', array('*'), array('id'=>$decryptedCatgId));
			$hdCatgTitle	 =	strtolower(str_replace(array(' ','-'),array('_'),$categoryInfo->title));
			if(!empty($categoryInfo)){				
				$fields					=	$this->bannerurl_model->get_bannerurl_fieldsByCatgId($categoryInfo->id,array('isMandatory'=>'yes'));			
				$mandatoryFields_array	=	array_column($fields,'title');
				$imageFieldName			=	'';
				foreach($mandatoryFields_array as $key=>$mandatoryField){	
					$fieldName	=	strtolower(str_replace(array(' ','-'),array('_'),$mandatoryField));				
					if($fields[$key]['type']	==	6){
							$file_error_flag 	 =  true;
							$imageFieldName		 =	$fieldName;
							if($editable_formID!=''){
								$uploadPath			 =	'themes/other_uploaded_images/';
								$editable_RecordInfo =	$this->base_model->is_Record_Exists('dynamic_tbl_'.$hdCatgTitle, array($imageFieldName), array('id'=>$editable_formID));
								if($editable_RecordInfo->$imageFieldName != '' && file_exists($uploadPath.$editable_RecordInfo->$imageFieldName))
								$file_error_flag = false;
							}
							if ($editable_formID!='')
							{
								if($file_error_flag){		
									if($_FILES[$fieldName]['name']=='')							
									$this->form_validation->set_rules($fieldName, $mandatoryField, 'trim|required|xss_clean');
								}
							}
							else{
								if(empty($_FILES[$fieldName]['name']))
								$this->form_validation->set_rules($fieldName, $mandatoryField, 'trim|required|xss_clean');	
							}
					}
					elseif($fields[$key]['type']	==	4){
						$this->form_validation->set_rules($fieldName.'[]', $mandatoryField, 'trim|required|xss_clean');	
					}
					else{
						$this->form_validation->set_rules($fieldName, $mandatoryField, 'trim|required|xss_clean');
					}
				}				
				if ($this->form_validation->run() == FALSE)
				{
					$this->session->set_flashdata('admincp_bannerurl_common_form_flash_message', '<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.validation_errors().'</div>');
						
						$this->session->set_userdata('post_bannerUrlCommonForm_Data',$this->input->post());				
						if($editable_formID!='')
							redirect('admincp/bannercategory/edit/'.$hdCatgTitle.'/'.$this->input->post('hdn_editable_formID'));
						else	
							redirect('admincp/bannercategory/addnew/'.$this->input->post('ref_hdncatgID'));
				}
				else{
						//print'<pre/>';print_r($this->input->post());
						
							if($_FILES[$imageFieldName]['name']!='')
							$fileName	=	$this->security->xss_clean($_FILES[$imageFieldName]['name']);
							else $fileName 	= 	'';
							$userData	=	array();
							//apply the condition to set config for image
							if($fileName!='')
							{   
										if($editable_formID!=''){												
												if($editable_RecordInfo->$imageFieldName != '' && file_exists($uploadPath.$editable_RecordInfo->$imageFieldName))
												unlink('themes/other_uploaded_images/'.$editable_RecordInfo->$imageFieldName);
										}
										
										//setting up the configuration for image
										$config['file_name'] 			= 	$_FILES[$imageFieldName]['name'];
										$config['upload_path']          = 	'themes/other_uploaded_images/';
										$config['allowed_types']        = 	'jpeg|jpg|png|gif|bmp';	
										$config['remove_spaces']		=	true;
										$config['detect_mime']			=	true;
										$config['encrypt_name']			=	true;	
										$config['max_size']             = 	0;
										$config['max_width']            = 	0;
										$config['max_height']           = 	0;																	
										//end
										$this->load->library('upload',$config);
										$this->upload->initialize($config);
										if($this->upload->do_upload($imageFieldName)){
											$uploadData = $this->upload->data();
											$fileName = $uploadData['file_name'];
										}else{ 
										    $uploadError	=	$this->upload->display_errors();
											$fileName = '';
										}
										$userData[$imageFieldName]	=	$fileName;
							}
							
						    // Getting lists of field created in admin
							$fields					=	$this->bannerurl_model->get_bannerurl_fieldsByCatgId($categoryInfo->id);	
							// initializing values to be saved in database
							foreach($fields as $key=>$field){
								$fieldName	=	strtolower(str_replace(array(' ','-'),array('_'),$field['title']));	
																
									if($fieldName != $imageFieldName){
										if($field['type'] == 4){										
											if(is_array($this->input->post($fieldName)))
												$userData[$fieldName]	=	implode('~',$this->security->xss_clean($this->input->post($fieldName)));
											else
												$userData[$fieldName]	=	$this->security->xss_clean($this->input->post($fieldName));
										}
										else{
												$userData[$fieldName]	=	htmlentities($this->security->xss_clean($this->input->post($fieldName)));
										}
									}
							}
							
							$this->session->unset_userdata('post_bannerUrlCommonForm_Data');
							if($editable_formID == ''){
									//passing value to model to save the values
									$userData['createdOn']	=	date('Y-m-d H:i:s');
									$userData['bannerurlCategoryId']	=	$decryptedCatgId;
									$dataStatus	=	$this->base_model->saveRecord($userData,'dynamic_tbl_'.$hdCatgTitle);
									$field_insertID		=	$this->db->insert_id();
									$flash_message		=	'<b>Success:</b> '.ucfirst(stripslashes($categoryInfo->title)).' ad added successfully.';
									//end
							}
							else{
									//passing value to model to save the values
									$userData['updatedOn']	=	date('Y-m-d H:i:s');
									$dataStatus	=	$this->base_model->updateRecord($userData,'dynamic_tbl_'.$hdCatgTitle,array('id'=>$editable_formID));
									$field_insertID		=	$editable_formID;
									$flash_message		=	'<b>Success:</b> '.ucfirst(stripslashes($categoryInfo->title)).' ad info updated successfully.';
									//end
							}
								
							//end
							
							if($dataStatus){
									if($this->input->post('submit')=='savenc')
									{
										$this->session->set_flashdata('admincp_bannerurl_common_form_flash_message', '<div class="alert alert-success alert-dismissible">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
													<i class="icon fa fa-check"></i> '.$flash_message.'</div>');	
										$encryptedID	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($field_insertID));
										redirect('admincp/bannercategory/edit/'.$hdCatgTitle.'/'.$encryptedID);
									}
									elseif($this->input->post('submit')=='save')
									{
										$this->session->set_flashdata('admincp_bannercategory_indexpage_flash_message', '<div class="alert alert-success alert-dismissible">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												<i class="icon fa fa-check"></i> '.$flash_message.'</div>');	
										redirect('admincp/bannercategory/'.$this->input->post('ref_hdncatgID'));
									}
							}
							else{
								
									if($this->input->post('submit')=='savenc')
									{
										$this->session->set_flashdata('admincp_bannerurl_common_form_flash_message', '<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');										
										redirect('admincp/bannercategory/addnew/'.$this->input->post('ref_hdncatgID'));
									}
									elseif($this->input->post('submit')=='save')
									{
										$this->session->set_flashdata('admincp_bannerurl_common_form_flash_message', '<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<i class="icon fa fa-ban"></i> <b>Failure:</b> Oh! something went wrong. Please try again.</div>');
										redirect('admincp/bannercategory/'.$this->input->post('ref_hdncatgID'));
									}	
							}
							
						
						
				}
			}
			
		}
		
}

?>