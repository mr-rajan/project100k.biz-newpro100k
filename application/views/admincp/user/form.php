 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <style>
	.img-preview{
		margin-top:10px;
	}
	</style>
      <h1><?php echo (!empty($userInfo) && $userID!='') ? 'Edit ': 'Add New '?>User</h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="<?php echo base_url().'admincp/user'?>">User</a></li>
        <li><?php echo (!empty($userInfo) && $userID!='') ? 'Edit ': 'Add New '?>User</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
       <!-- form start -->
            <?php   $form_attributes = array('name'=>'user_registration','class'=>'form-horizontal' );
				      echo form_open_multipart('admincp/user/save',$form_attributes);
			  ?>
        <div class="box box-info">
       		  <?php if($this->session->flashdata('admincp_userform_flash_message')) {
             	echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_userform_flash_message').'</div>';
			 } 
			 
			 ?>
            <div class="box-header with-border">
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel" 
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/user'?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
            </div>
			
            <!-- /.box-header -->
            <?php
            		$postedValue_fname	=	$postedValue_lname	=	$postedValue_gender	=	'';
					$postedValue_email	=	$postedValue_contactno	=	$postedValue_country	=	'';
					$postedValue_Status	=	'';
					if(empty($userInfo) && !empty($postedValue)){
							$postedValue_fname		=	$postedValue['first_name'];
							$postedValue_lname		=	$postedValue['last_name'];
							$postedValue_gender		=	$postedValue['gender'];
							$postedValue_email		=	$postedValue['email'];
							$postedValue_contactno	=	$postedValue['contactno'];
							$postedValue_country	=	$postedValue['country'];
							$postedValue_Status		=	$postedValue['accountStatus'];
					}
					elseif(!empty($userInfo) && empty($postedValue)){
							$postedValue_fname		=	$userInfo->first_name;
							$postedValue_lname		=	$userInfo->last_name;
							$postedValue_gender		=	$userInfo->gender;
							$postedValue_email		=	$userInfo->email;
							$postedValue_contactno	=	$userInfo->contactno;
							$postedValue_country	=	$userInfo->country;
							$postedValue_Status		=	$userInfo->status;
					}
					elseif(!empty($userInfo) && !empty($postedValue)){
							$postedValue_fname		=	$postedValue['first_name'];
							$postedValue_lname		=	$postedValue['last_name'];
							$postedValue_gender		=	$postedValue['gender'];
							$postedValue_email		=	$postedValue['email'];
							$postedValue_contactno	=	$postedValue['contactno'];
							$postedValue_country	=	$postedValue['country'];
							$postedValue_Status		=	$postedValue['accountStatus'];
					}
					
			$imagePath	=	'themes/userimages/';
			if(!empty($userInfo) && $userInfo->profilePic!='' && file_exists($imagePath.$userInfo->profilePic)):
					$image	=	img(array('src'=>base_url().'themes/userimages/'.$userInfo->profilePic,'height'=>70,'width'=>60));					
			else:
					$image	=	'';
			endif;	
					
			?>
           
              <div class="box-body">
                <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">First Name <span class="required">*</span></label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'first_name', 'id'=>'first_name', 
						                'value'=>$postedValue_fname, 
										'required'=>'required', 'class'=>'form-control', 'placeholder'=>'First Name' );
						    echo  form_input($data)
					?>
                     <span class="glyphicon glyphicon-user form-control-feedback"></span>
                     <span class="help-block"></span>
                  </div>
                  
       				
                </div>
                
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Last Name</label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'last_name', 'id'=>'last_name', 
						                'value'=>$postedValue_lname, 'class'=>'form-control', 'placeholder'=>'Last Name' );
						    echo  form_input($data)
					?>
                  </div>
                </div>
                
             	                
                 <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gender <span class="required">*</span></label>
                  <div class="col-sm-10">
                      <input type="radio" name="gender" value="M"  <?php echo ($postedValue_gender === 'M') ? 'checked' : ''?> required/> Male  
					  <input type="radio" name="gender" value="F"  <?php echo ($postedValue_gender === 'F') ? 'checked' : ''?> required/> Female
					  <input type="radio" name="gender" value="O"  <?php echo ($postedValue_gender === 'O') ? 'checked' : ''?> required/> Other
                  </div>
                </div>
                
                <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Profile Picture</label>
                  <div class="col-sm-10">
                        <input type="file" class="form-control" placeholder="Choose Picture" name="profilepic" id="profilepic">
                        <div class="img-preview"><?=$image?></div>
                        <span class="glyphicon glyphicon-picture form-control-feedback"></span>
                        <span class="help-block"></span>
                  </div>
                </div>
                
                <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email Address <span class="required">*</span></label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'email', 'id'=>'email',
										'value'=>$postedValue_email, 
										'required'=>'required', 'class'=>'form-control', 'placeholder'=>'Email Address' );
						    echo  form_input($data)
					?>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
         			<span class="help-block"></span>
                  </div>
                </div>
                
                 <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Contact Number (Optional)</label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'contactno', 'id'=>'contactno',
										'value'=>$postedValue_contactno, 
										'class'=>'form-control', 'placeholder'=>'Contact Number' );
						    echo  form_input($data)
					?>
                    <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
         			<span class="help-block"></span>
                  </div>
                </div>
                
                <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Country <span class="required">*</span></label>
                  <div class="col-sm-10">
                   <?php if(!empty($countries_lists)): ?>
					              <select name="country" class="form-control select2" style="width: 100%;" required="required" >
								  <option value="">-Select-</option>
								  <?php foreach($countries_lists as $countryData):?>
								         <option value="<?php echo $countryData->id ?>" <?php echo ($postedValue_country == $countryData->id) ? 'selected' : ''?>>
										 <?php echo $countryData->country_name?></option>
								  <?php endforeach;?>
								  </select>
					   <?php endif;?>
                       
                  </div>
                </div>   
                
                <div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Account Status <span class="required">*</span></label>
                  <div class="col-sm-10">
					              <select name="accountStatus" class="form-control select2" style="width: 100%;" required="required" >
								  <option value="" <?php  echo ($postedValue_Status == '') ? 'selected' : ''?>>-Select-</option>
								  <option value="Active" <?php echo ($postedValue_Status == 'Active') ? 'selected' : ''?>>Active</option>
                                  <option value="Suspend" <?php  echo ($postedValue_Status == 'Suspend') ? 'selected' : ''?>>Suspend</option>
                                  
								  </select>
				
                  </div>
                </div>        
              </div>
              <!-- /.box-body -->
              <div class="box-footer">                    
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel"
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/user'?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
              </div>
              <!-- /.box-footer -->
               <input type="hidden" name="hdnUserID" id="hdnUserID" value="<?php echo $userID?>"/>
               <input type="hidden" id="parentUserID" name="parentUserID" value="<?php echo $parentUserID?>"/>
          </div>
           <?php echo form_close(); ?>
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
<?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
<?php $this->load->view('templates/footer_body_close')?>  
  
  <!-- /.content-wrapper -->          