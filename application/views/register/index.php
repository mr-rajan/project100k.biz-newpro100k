<div class="mid">
<?php if($this->session->flashdata('errors')) { 
 echo $this->session->flashdata('errors');
 } ?>
 
	<div class="regester">
          <div class="left">
                 <?php   
				 $form_attributes = array('name'=>'user_registration');
				 echo form_open('register/save',$form_attributes); //creating form 
            		
					//initalizing the variables
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
                          
                <ul>                     
					<li>
                        <strong>First Name <span class="required">*</span></strong>
                        <?php $data = array('name'=>'first_name','id'=>'first_name','value'=>$postedValue_fname,'required'=>'required');
						     echo form_input($data)
						?>
                    </li>
                    <li>
                        <strong>Last Name</strong>
                       <?php $data = array('name'=>'last_name','id'=>'last_name','value'=>$postedValue_lname);
						     echo form_input($data)
						?>
                    </li>
					<li>
                        <strong>Gender <span class="required">*</span></strong>
                      <input type="radio" name="gender" value="M" required <?php echo ($postedValue_gender=='M') ? 'checked' : ''?>/> Male  
					  <input type="radio" name="gender" value="F" required <?php echo ($postedValue_gender=='F') ? 'checked' : ''?>/> Female
					  <input type="radio" name="gender" value="O" required <?php echo ($postedValue_gender=='O') ? 'checked' : ''?>/> Other
                    </li>
                    <li>
                        <strong>Email Address <span class="required">*</span></strong>
                       <?php $data = array('name'=>'email','id'=>'email','value'=>$postedValue_email,'required'=>'required');
						    echo  form_input($data)
						?>
                    </li>
                     <li>
                        <strong>Contact Number</strong>
                       <?php $data = array('name'=>'contactno', 'type'=>'number', 'id'=>'contactno','value'=>$postedValue_contactno);
						    echo form_input($data)
						?>
                    </li>
                    <li>
                        <strong>Country <span class="required">*</span></strong>
                       <?php if(!empty($countries_lists)): ?>
					              <select name="country" required  style="width:305px !important;">
								  <option value="">-Select-</option>
								  <?php foreach($countries_lists as $countryData):?>
								         <option value="<?php echo $countryData->id ?>" <?php echo ($postedValue_country==$countryData->id) ? 'selected' : ''?>>
										 <?php echo $countryData->country_name?></option>
								  <?php endforeach;?>
								  </select>
					   <?php endif;?>
                    </li>
                    <li>  <strong>Security <span class="required">*</span></strong>
                        <div class="recaptcha_css"><div class="g-recaptcha" data-sitekey="6LcxuQoUAAAAAGuAImYGyNUgQ_gqMcML8UulCKcu"></div></div>
                      
                    </li>
                    <li>
                    <p style="text-align:right;padding: 0 40px 0;">
                    <input type="hidden" name="ref" value="<?php echo $ref?>"/>
					<button class="btn btn-info pull-right" type="submit" name="submit" value="save" style="margin:15px 0 0 0;">Submit</button>
					 <?php echo form_close();?>
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
</script>

	
