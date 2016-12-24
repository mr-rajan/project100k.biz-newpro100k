 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <style>
	.img-preview{
		margin-top:10px;
	}
	.entry:not(:first-of-type)
{
    margin-top: 10px;
}

.glyphicon
{
    font-size: 12px;
}
.has-feedback .form-control{
	padding-right: 23px !important;
}
	</style>
      <h1><?php echo (!empty($postedRequestValue) && $hdn_editable_formID!='') ? 'Edit ': 'Add New '?>Record</h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><?php echo $breadcrumb_string?></li>
        <li><?php echo (!empty($postedRequestValue) && $hdn_editable_formID!='') ? 'Edit ': 'Add New '?>Record</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
       <!-- form start -->
            <?php   if(!empty($fields)):  					  
					  $form_attributes = array('name'=>'adsGeneralForm','class'=>'form-horizontal' );
				      echo form_open_multipart('admincp/bannercategory/save',$form_attributes);
			  ?>
        <div class="box box-info">
       		  <?php if($this->session->flashdata('admincp_bannerurl_common_form_flash_message')) {
             	echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_bannerurl_common_form_flash_message').'</div>';
			 } 			 	
			 ?>
            <div class="box-header with-border">
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel" 
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/bannercategory/'.$ref_hdncatgID?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
            </div>
			
            <!-- /.box-header -->
              		
              <div class="box-body">
                  <?php foreach($fields as $key=>$field):?>
               			<div class="form-group has-feedback">
                  			<label for="title" class="col-sm-2 control-label"><?php echo $field['title']?> 
							<?php 
							$fieldName	=	strtolower(str_replace(array(' ','-'),array('_'),$field['title']));
							if($field['type'] == 6){
								$imageAvailable		 =	false;															
								$uploadPath			 =	'themes/other_uploaded_images/';
								if(!empty($postedRequestValue) && array_key_exists($fieldName,$postedRequestValue) 
								&& file_exists($uploadPath.$postedRequestValue[$fieldName]))
								{
									$imageAvailable	=	true;
								}
								if(!$imageAvailable){
									if($field['isMandatory'] == 'yes')
									echo '<span class="required">*</span>';
								}
							}
							else{
							echo ($field['isMandatory'] == 'yes') ? '<span class="required">*</span>':'';
							}
							($field['type'] == 4) ? $requiredChbox = 'id="checkbox-field required"' :  $requiredChbox = '';
							?>
                            </label>
                 				 <div class="col-sm-10">
									<?php   
									        
											$data = array('name'=>$fieldName, 'id'=>$fieldName);												
                                            switch($field['type']){
													case '1':
															$data['class']			=	'form-control';	
															$data['placeholder']	=	ucwords($field['title']);
															if($field['isMandatory'] == 'yes')
															$data['required']	=	'required';
															$data['value']	=	(!empty($postedRequestValue) && $postedRequestValue[$fieldName]!='') ? 
																				$postedRequestValue[$fieldName] : '';	
															echo form_input($data);
															break;	
													case '2':	
															$data['rows']	=	5;
															$data['cols']	=	3;
															$data['class']	=	'form-control';	
															$data['placeholder']	=	ucwords($field['title']);
															if($field['isMandatory'] == 'yes')
															$data['required']	=	'required';
															$data['value']	=	(!empty($postedRequestValue) && $postedRequestValue[$fieldName]!='') ? 
																				htmlspecialchars_decode($postedRequestValue[$fieldName]) : '';	
															echo form_textarea($data);
															break;
													case '3':																
															$data['class']	=	'form-control';	
															$data['placeholder']	=	ucwords($field['title']);
															if($field['isMandatory'] == 'yes')
															$data['required']	=	'required';
															$selected		=	(!empty($postedRequestValue) && $postedRequestValue[$fieldName]!='') ? 
																				$postedRequestValue[$fieldName] : '';
															echo form_dropdown($data,$field['options'],$selected);break;
													case '4':	
															foreach($field['options'] as $optKey=>$option){														
																$data['name']	=	$fieldName.'[]';
																$data['id']		=	'';
																$data['class']	=	'flat-red';
																$data['style']	=	'position: absolute; opacity: 0;';
																//if($field['isMandatory'] == 'yes')
																//$data['required']	=	'required';
																$data['value']	=	$optKey;
																$data['checked']=	(!empty($postedRequestValue) && array_key_exists($fieldName,$postedRequestValue) && 
																					in_array($optKey,explode('~',$postedRequestValue[$fieldName]))) ? 'checked' : '';
																echo form_checkbox($data).' '.ucwords($option).' ';
															}
															break;
													case '5':
															foreach($field['options'] as $optKey=>$option){														
																$data['name']	=	$fieldName;
																$data['id']		=	'';
																$data['class']	=	'flat-red';
																if($field['isMandatory'] == 'yes')
																$data['required']	=	'required';
																$data['style']	=	'position: absolute; opacity: 0;';
																$data['value']	=	$optKey;
																//echo '--'.array_search($fieldName,$postedRequestValue);
																$data['checked']=	(!empty($postedRequestValue) && array_key_exists($fieldName,$postedRequestValue) && 
																					$optKey == $postedRequestValue[$fieldName])  ? 'checked' : '';
																echo form_radio($data).' '.ucwords($option).' ';
															}
															break;
													case '6':	
															
															$data['class']	=	'form-control';	
															if(!$imageAvailable){
																if($field['isMandatory'] == 'yes')
																$data['required']	=	'required';
															}
															echo form_upload($data);															
															if($imageAvailable){
																echo'<br/><img src="'.base_url().'/'.$uploadPath.$postedRequestValue[$fieldName].'"
																				  width="70" height="100"/>';	
															}
															break;		
													default:break;
											}
                                    ?>                                    
                              </div>
                              
                		</div> 
                  <?php endforeach;?>    
              </div>
              <!-- /.box-body -->
              <div class="box-footer">                    
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel"
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/bannercategory/'.$ref_hdncatgID?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
              </div>
              <!-- /.box-footer -->
               <input type="hidden" name="ref_hdncatgID" id="ref_hdncatgID" value="<?php echo $ref_hdncatgID?>">
               <input type="hidden" name="hdn_editable_formID" id="hdn_editable_formID" value="<?php echo $hdn_editable_formID?>">
          </div>
           <?php echo form_close();
		         endif;
		   ?>
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->         
<?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
<script>
 $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

</script>

<?php $this->load->view('templates/footer_body_close')?>   