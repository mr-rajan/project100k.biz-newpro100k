 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
      <h1>Add New User</h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="<?php echo base_url().'index.php/admincp/user'?>">User</a></li>
        <li>Add New</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
       <!-- form start -->
            <?php   $form_attributes = array('name'=>'user_registration','class'=>'form-horizontal');
				      echo form_open('admincp/user/save',$form_attributes);
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
					if(!empty($postedValue)){
							$postedValue_fname		=	$postedValue['first_name'];
							$postedValue_lname		=	$postedValue['last_name'];
							$postedValue_gender		=	$postedValue['gender'];
							$postedValue_email		=	$postedValue['email'];
							$postedValue_contactno	=	$postedValue['contactno'];
							$postedValue_country	=	$postedValue['country'];
					}
			?>
           
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">First Name <span class="required">*</span></label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'first_name', 'id'=>'first_name', 
						                'value'=>$postedValue_fname, 
										'required'=>'required', 'class'=>'form-control', 'placeholder'=>'First Name' );
						    echo  form_input($data)
					?>
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
                
             	                
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gender <span class="required">*</span></label>
                  <div class="col-sm-10">
                      <input type="radio" name="gender" value="M"  <?php echo ($postedValue_gender === 'M') ? 'checked' : ''?> required/> Male  
					  <input type="radio" name="gender" value="F"  <?php echo ($postedValue_gender === 'F') ? 'checked' : ''?> required/> Female
					  <input type="radio" name="gender" value="O"  <?php echo ($postedValue_gender === 'O') ? 'checked' : ''?> required/> Other
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email Address <span class="required">*</span></label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'email', 'id'=>'email',
										'value'=>$postedValue_email, 
										'required'=>'required', 'class'=>'form-control', 'placeholder'=>'Email Address' );
						    echo  form_input($data)
					?>
                  </div>
                </div>
                
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Contact Number (Optional)</label>
                  <div class="col-sm-10">
                    <?php $data = array('name'=>'contactno', 'id'=>'contactno',
										'value'=>$postedValue_contactno, 
										'class'=>'form-control', 'placeholder'=>'Contact Number' );
						    echo  form_input($data)
					?>
                  </div>
                </div>
                
                 <div class="form-group">
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
           
          </div>
           <?php echo form_close(); ?>
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->          