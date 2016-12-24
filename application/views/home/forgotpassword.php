<div class="mid">

 <div id="breadcrumbdiv">
					<ul class="breadcrumb">
						<li><a href="<?php echo base_url();?>">Home</a></li>
						<li><a href="javascript:void(0);">Forgot Password</a></li>
					</ul>
				</div>
	<div class="regester" style="margin-left:200px !important;">
	
					<?php if($this->session->flashdata('forgotpassword_flash_message')) { 
					echo '<div id="sessionMessageDiv">'.$this->session->flashdata('forgotpassword_flash_message').'</div>';
					} ?>
					
         <div class="left">
                 <?php   $form_attributes = array('name'=>'forgotpassword');
				          echo form_open('home/generateresetpasswordlink',$form_attributes)?>
                <ul> 
                    <li class="liclass">
                        <strong class="strongclass">Email Address*</strong>
                       <?php $data = array('name'=>'email','id'=>'email','placeholder'=>'Email Address','required'=>'required');
						    echo  form_input($data)
						?>
                    </li>              
                    <li class="liclass">  
							<strong class="strongclass">Security*</strong>
                        <div class="recaptcha_css2"><div class="g-recaptcha" data-sitekey="6LcxuQoUAAAAAGuAImYGyNUgQ_gqMcML8UulCKcu"></div></div>
                      
                    </li>
                    <li>
                    <p style="text-align:right;padding: 0 10px 0; width:490px;">					
					<?php $data = array('name'=>'submit','class'=>'sub','style'=>'margin:15px 0 0 0;');
					echo form_submit($data);
					echo form_close();
					?>
					</p>
					<div class="cl"></div>
                    </li>
                <li>
                    <span style="float:right; padding:10px;">New User? | <a href="<?php echo site_url('register')?>">Sign Up</a></span>
                    <span style="float:right; padding:10px;">Already Have Account? | <a href="<?php echo site_url('home/login')?>">Login</a></span>
                </li>
                </ul>
          </div>
	</div><!--end of regester-->		
		
</div><!--end of mid-->

<script>
$(window).load(function() {
	$("html, body").animate({ scrollTop: $('.regester').height() },1000);	
});

</script>

	
