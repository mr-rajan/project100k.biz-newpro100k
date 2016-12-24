<div class="mid">
<?php if($this->session->flashdata('errors')) { 
 echo $this->session->flashdata('errors');
 } ?>
 
	<div class="regester">
         <div class="login">
<div class="form">
			<ul>
				<li>User Name :</li>
				<li><input type="text" name="" value="Enter your Name"  onblur="if(value==''){ value='Enter your Name'}" onfocus="if(value=='Enter your Name'){value=''}" /></li>
				<li>Password :</li>
				<li><input type="text" name="" value="Enter your Password"  onblur="if(value==''){ value='Enter your Password'}" onfocus="if(value=='Enter your Password'){value=''}" /></li>
				<li><input type="submit" name="" class="sub" /></li>
			</ul>
		  </div><!--end of form-->
		  <div class="cl"></div>
</div>
	</div><!--end of regester-->		
		
</div><!--end of mid-->

<script>
$(window).load(function() {
	$("html, body").animate({ scrollTop: $('.regester').height() },1000);	
});
</script>

	
