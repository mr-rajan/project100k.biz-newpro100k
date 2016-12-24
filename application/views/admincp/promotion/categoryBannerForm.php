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
      <h1><?php echo (!empty($promotionFieldInfo) && $ref_hdnPromotionID!='') ? 'Edit ': 'Add New '?>Field</h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="<?php echo base_url().'admincp/promotion'?>">Promotion Listing</a></li>
        <li><?php echo (!empty($promotionFieldInfo) && $ref_hdnPromotionID!='') ? 'Edit ': 'Add New '?>Field</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
       <!-- form start -->
            <?php   $form_attributes = array('name'=>'promotionURLForm','class'=>'form-horizontal' );
				      echo form_open_multipart('admincp/promotion/savefield',$form_attributes);
			  ?>
        <div class="box box-info">
       		  <?php if($this->session->flashdata('admincp_promotionfieldform_flash_message')) {
             	echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_promotionfieldform_flash_message').'</div>';
			 } 
			 
			 ?>
            <div class="box-header with-border">
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel" 
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/promotion/edit/'.$ref_hdnPromotionID?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
            </div>
			
            <!-- /.box-header -->
            <?php
            		$postedValue_title			=	$postedValue_type		=	$postedValue_hyperlink_label	=	'';
					$postedValue_isMandatory	=	$postedValue_Sortable 	= 	$postedValue_Status	=	''; 
					$addtoTableHeading			=	$postedValue_order		=	$visibility_order;
					$postedValue_fieldOptions	=	array();
					$type_isReadOnly			=	false;
					if(!empty($promotionFieldInfo)){
							$postedValue_title			=	$promotionFieldInfo->title;
							$postedValue_type			=	$promotionFieldInfo->type;
							$postedValue_isMandatory	=	$promotionFieldInfo->isMandatory;
							$postedValue_Status			=	$promotionFieldInfo->status;
							$addtoTableHeading			=	$promotionFieldInfo->addtoTableHeading;
							$postedValue_fieldOptions	=	$fieldOptions;
							$postedValue_order			=	$promotionFieldInfo->order;
							$postedValue_Sortable 		= 	$promotionFieldInfo->sortable;
							$postedValue_hyperlink_label=	$promotionFieldInfo->hyperlink_label;
							$type_isReadOnly			=	true;
					}
			     
				if($type_isReadOnly):
			?>
           		 <input type="hidden" name="type" id="type" value="<?php echo $postedValue_type ?>">
            	<?php endif;?>
           
              <div class="box-body">
                <div class="form-group has-feedback">
                  <label for="title" class="col-sm-2 control-label">Title <span class="required">*</span></label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'title', 'id'=>'title', 
						                'value'=>$postedValue_title, 
										'required'=>'required', 'class'=>'form-control', 'placeholder'=>'Title');
						   if($type_isReadOnly)
						   $data['readonly']	=	'readonly';
						   echo  form_input($data)
					?>                    
                  </div>
                </div>  
                
                <div class="form-group has-feedback">
                  <label for="type" class="col-sm-2 control-label">Type <span class="required">*</span></label>
                  <div class="col-sm-10">
                <select name="type" id="type" class="form-control select2" style="width: 100%;" required="required" <?php echo ($type_isReadOnly)? 'disabled="disabled"':''?> >
                            <option value="" <?php   echo ($postedValue_type == '')  ? 'selected' : ''?>>-Select-</option>
                            <option value="1" <?php  echo ($postedValue_type == '1') ? 'selected' : ''?>>Textfield</option>
                            <option value="2" <?php  echo ($postedValue_type == '2') ? 'selected' : ''?>>Textarea</option>  
                            <option value="3" <?php  echo ($postedValue_type == '3') ? 'selected' : ''?>>Dropdown</option> 
                            <option value="4" <?php  echo ($postedValue_type == '4') ? 'selected' : ''?>>Checkbox</option> 
                            <option value="5" <?php  echo ($postedValue_type == '5') ? 'selected' : ''?>>Radio (Choice)</option> 
                            <option value="6" <?php  echo ($postedValue_type == '6') ? 'selected' : ''?>>File (Selectable)</option> 
                            <option value="7" <?php  echo ($postedValue_type == '7') ? 'selected' : ''?>>Hyperlink</option>                                
                        </select>                  
                  </div>
                  
                </div>                
                <?php 
                		if(!empty($postedValue_fieldOptions)):
				?>
                
    <div class="form-group has-feedback" id="defaultfieldoptions">
        <label for="inputEmail3" class="col-sm-2 control-label">&nbsp;</label>
        <div class="col-sm-10">
            <div class="controlsFirst"> 
                   <div role="formFirst" id="formFirst">
                   <?php   foreach($postedValue_fieldOptions as $key=>$optVal):?>
                    <div class="entry input-group col-xs-8">
                        <input class="form-control" name="field_options[]" value="<?php echo $optVal->optionvalue?>" type="text" placeholder="Type something"/>
                        <span class="input-group-btn">
                        <button class="btn btn-removeFirst btn-danger" type="button">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>                                   
                        </span>
                    </div>
                    <?php  endforeach;?>
                    </div>
            </div>     
        </div>
    </div>        
                 
                 <?php	endif;?> 
                 <?php  if(!empty($promotionFieldInfo) && ($postedValue_type=='3'|| $postedValue_type=='4'||$postedValue_type=='5') && empty($postedValue_fieldOptions))
				       	$fieldRequired	=	 'required="required"';
					    else
						$fieldRequired	=	 '';	
					   ?>
                 
                 <div class="form-group has-feedback" id="options" style="display:none;">
                  	<label for="inputEmail3" class="col-sm-2 control-label">&nbsp;</label>
                 	<div class="col-sm-10">
                        <div class="controls"> 
                               <div role="form" id="form">
                                <div class="entry input-group col-xs-8">
                                    <input class="form-control" name="field_options[]" type="text" placeholder="Type something" <?php echo $fieldRequired?>/>
                                    <span class="input-group-btn">
                                     <button class="btn btn-success btn-add" type="button">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                    </span>
                                </div>
                                </div>
                            <br>
                            <small>Press <span class="glyphicon glyphicon-plus gs"></span> to add another form field</small>
                        </div>     
                	</div>
                </div>
                
                <div class="form-group has-feedback">
                  <label for="isMandatory" class="col-sm-2 control-label">Is Mandatory? <span class="required">*</span></label>
                  <div class="col-sm-10">
					              <select name="isMandatory" class="form-control select2" style="width: 100%;" required="required" >
								  <option value="" 	<?php  echo ($postedValue_isMandatory == '') ? 'selected' : ''?>>-Select-</option>
								  <option value="yes" <?php echo ($postedValue_isMandatory == 'yes') ? 'selected' : ''?>>Yes</option>
                                  <option value="no" <?php  echo ($postedValue_isMandatory == 'no') ? 'selected' : ''?>>No</option>                                  
								  </select>
				
                  </div>
                </div>  
                
                 <div class="form-group has-feedback">
                  <label for="isMandatory" class="col-sm-2 control-label">Add To Table Heading? <span class="required">*</span></label>
                  <div class="col-sm-10">
					              <select name="addtoTableHeading" class="form-control select2" style="width: 100%;" required="required" >
								  <option value="" 	<?php  echo ($addtoTableHeading == '') ? 'selected' : ''?>>-Select-</option>
								  <option value="yes" <?php echo ($addtoTableHeading == 'yes') ? 'selected' : ''?>>Yes</option>
                                  <option value="no" <?php  echo ($addtoTableHeading == 'no') ? 'selected' : ''?>>No</option>                                  
								  </select>
				
                  </div>
                </div>  
                
                <div class="form-group has-feedback">
                  <label for="title" class="col-sm-2 control-label">Visibility Order</label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'order', 'id'=>'order', 
						                'value'=>$postedValue_order, 'class'=>'form-control', 'placeholder'=>'Visibility Order', 
										'onkeypress'=>'return isNumberKey(event)', 'maxlength'=>2 );
						    echo  form_input($data)
					?>                               
                  </div>
                </div>
                
                <div class="form-group has-feedback">
                  <label for="status" class="col-sm-2 control-label">Status <span class="required">*</span></label>
                  <div class="col-sm-10">
					              <select name="status" class="form-control select2" style="width: 100%;" required="required" >
                                      <option value="" <?php  echo ($postedValue_Status == '') ? 'selected' : ''?>>-Select-</option>
                                      <option value="Active" <?php echo ($postedValue_Status == 'Active') ? 'selected' : ''?>>Active</option>
                                      <option value="Suspend" <?php  echo ($postedValue_Status == 'Suspend') ? 'selected' : ''?>>Suspend</option>
								  </select>
				
                  </div>
                </div>
                
                <div class="form-group has-feedback">
                  <label for="status" class="col-sm-2 control-label">Column Sortable <span class="required">*</span></label>
                  <div class="col-sm-10">
					              <select name="sortable" class="form-control select2" style="width: 100%;" required="required" >
                                      <option value="" <?php  echo ($postedValue_Sortable == '') ? 'selected' : ''?>>-Select-</option>
                                      <option value="yes" <?php echo ($postedValue_Sortable == 'yes') ? 'selected' : ''?>>Yes</option>
                                      <option value="no" <?php  echo ($postedValue_Sortable == 'no') ? 'selected' : ''?>>No</option>
								  </select>
				
                  </div>
                </div>
                      
              </div>
              <!-- /.box-body -->
              <div class="box-footer">                    
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel"
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/promotion/edit/'.$ref_hdnPromotionID?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
              </div>
              <!-- /.box-footer -->
               <input type="hidden" name="ref_hdnPromotionID" id="ref_hdnPromotionID" value="<?php echo $ref_hdnPromotionID?>">
               <input type="hidden" name="hdnFieldID" id="hdnFieldID" value="<?php echo $hdnFieldID?>">
               <input type="hidden" name="postedValue_fieldOptions" id="postedValue_fieldOptions" value="<?php echo (!empty($postedValue_fieldOptions)) ? '1' : '0'?>">
          </div>
           <?php echo form_close(); ?>
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->         
<?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
<script type="text/javascript">
if($('#postedValue_fieldOptions').val()==1){
		$('#options').show();
}
else{
	   $('#options').hide();
}

$('select[name="type"]').bind('change',function(e){
		if($(this).val() == '3' || $(this).val() == '4' || $(this).val() == '5' ){
			$('#options').show();
			$('#form').find('input[name="field_options[]"]').attr('required',true);
		}
		else{
			 $('#form').find('input[name="field_options[]"]').removeAttr('required');			 
			 $('#options').hide();
		}
		
});

$(document).on('click', '.btn-add', function(e)
{
   e.preventDefault();

        var controlForm = $('.controls div:first'),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry:not(:last) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove', function(e)
    {
		$(this).parents('.entry:first').remove();
		e.preventDefault();
		return false;
	});
	
$(document).on('click', '.btn-removeFirst', function(e){
		$(this).parents('.entry:first').remove();
		var child_length	=	$('#formFirst div').length;
		if(child_length==0){
			$('#defaultfieldoptions').remove();
			$('#form').find('input[name="field_options[]"]').attr('required',true);
		}
		e.preventDefault();
		return false;
	});



</script>
<?php $this->load->view('templates/footer_body_close')?>   