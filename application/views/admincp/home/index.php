<!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign In</p>

    <?php   $form_attributes = array('name'=>'login');
					echo form_open('admincp/user/authenticate',$form_attributes);
					if($this->session->flashdata('admincp_login_flash_message')) { 
					echo '<div id="sessionMessageDiv">'.$this->session->flashdata('admincp_login_flash_message').'</div>';
			} ?>
      <div class="form-group has-feedback">      
         <?php $data = array('name'=>'username','id'=>'username', 'class'=>'form-control', 
					 				'placeholder'=>'Username','required'=>'required');
						    echo  form_input($data)
						?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
          <?php $data = array('name'=>'password','id'=>'password', 'class'=>'form-control', 
					  				'placeholder'=>'Password','required'=>'required');
						    echo form_password($data)
						?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
       <!-- <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>-->
        <!-- /.col -->
        <div class="col-xs-4">         
            <?php $data = array('name'=>'submit', 'value'=>'Sign In', 'class'=>'btn btn-primary btn-block btn-flat');
					echo form_submit($data);
					echo form_close();
					?>
        </div>
        <!-- /.col -->
      </div>
    <a href="#">I forgot my password</a>
  </div>
  <!-- /.login-box-body -->