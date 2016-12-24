<div class="mid">
<div id="infoMessage">
<?php echo validation_errors(); ?>

<?php //echo $this->session->flashdata('errors');?></div>
	<div class="regester">
          <div class="left">
                 <?php   $form_attributes = array('name'=>'user_registration');
				          echo form_open('register/save',$form_attributes)?>
                <ul>
                      <li>
                        <strong>Username*</strong>
                        <?php $data = array('name'=>'username','id'=>'username','value'=>set_value(''));
						     echo form_input($data)
						?>
                    </li>
					<li>
                        <strong>First Name*</strong>
                        <?php $data = array('name'=>'first_name','id'=>'first_name','value'=>set_value(''));
						     echo form_input($data)
						?>
                    </li>
                    <li>
                        <strong>Last Name*</strong>
                       <?php $data = array('name'=>'last_name','id'=>'last_name','value'=>set_value(''));
						     echo form_input($data)
						?>
                    </li>
                    <li>
                        <strong>Email Address*</strong>
                       <?php $data = array('name'=>'email','id'=>'email','value'=>set_value(''));
						    echo  form_input($data)
						?>
                    </li>
                     <li>
                        <strong>Contact Number (Optional)</strong>
                       <?php $data = array('name'=>'contactno','id'=>'contactno','value'=>set_value(''));
						    echo form_input($data)
						?>
                    </li>
                    <li>
                        <strong>Country*</strong>
                       <?php form_dropdown('country') ?>
                    </li>
                    <li>
                        <div class="recaptcha_css"><div class="g-recaptcha" data-sitekey="6LcxuQoUAAAAAGuAImYGyNUgQ_gqMcML8UulCKcu"></div></div>
                      
                    </li>
                    <li>
                    <p style="text-align:right;padding: 0 25px 0;">
					<?php $data = array('name'=>'submit','class'=>'sub','style'=>'margin:15px 0 0 0;');
					echo form_submit($data);
					echo form_close();
					?>
					</p>
					<div class="cl"></div>
                    </li>
                </ul>
          </div>
          <div class="right">
          <div id="orSeparator">
				<div id="socialSeparatorTop"></div>
				<div id="or">or</div>
				<div id="socialSeparatorBottom"></div>
			</div>

			<div id="socialLogin">
				<div id="socialHead">
				Sign Up with a Social Network
				</div>
				<button type="button" id="fblogin">
					f&nbsp;&nbsp;&nbsp;<em id="fbname"></em> 
					<span id="fbtext">Sign Up with Facebook</span>
				</button>
	
				<button type="button" id="gpluslogin">
					g+<em id="gpname"></em> 
					<span id="gptext">Sign Up with Google</span>
				</button>
	
				<button type="button" id="twtrlogin">
					t&nbsp;&nbsp;&nbsp;<em id="twtrname"></em> 
					<span id="twtrtext">Sign Up with Twitter</span>
				</button>				
			</div>
          </div>
	</div><!--end of regester-->		
		
</div><!--end of mid-->

<script>
$(window).load(function() {
	$("html, body").animate({ scrollTop: $('.regester').height() },1000);	
});
$(document).ready(function(e) {   
	//$('.g-recaptcha').find('div:first-child').removeAttr('style');
});
</script>

	
