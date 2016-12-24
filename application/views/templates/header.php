<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Project 100K</title>
<link rel="stylesheet" href="<?php echo base_url()?>themes/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url()?>themes/css/socialmedia.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url()?>themes/css/flexslider.css" type="text/css" />
<script src="<?php echo base_url()?>themes/js/jquery-1.4.2.min.js"></script>
<script src="<?php echo base_url()?>themes/js/jquery.flexslider.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
		$(window).load(function() {
			$('.flexslider').flexslider();
		});
	</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div class="main">
<div class="header">
		<div class="logo"><h1><?=$title?></h1></div><!--end of logo-->
		<div class="header-right">
		    <?php if(empty($loggedIn_userInfo)):?>
			<p>Join our growing community of users. <a href="<?php echo base_url().'index.php/register'?>">Sign Up</a> or 
			<a href="<?php echo base_url().'index.php/home/login'?>">Log In</a></p>
			<ul>
				<li><a href="javascript:void(0);"><img src="<?php echo base_url()?>themes/images/facebook-icon.png" /></a></li>
				<li><a href="javascript:void(0);"><img src="<?php echo base_url()?>themes/images/linkedin-icon.png" /></a></li>
				<li><a href="javascript:void(0);"><img src="<?php echo base_url()?>themes/images/twitter-icon.png" /></a></li>
				<li><a href="javascript:void(0);"><img src="<?php echo base_url()?>themes/images/youtube-icon.png" /></a></li>
			</ul>
			<?php endif;?>
		</div><!--end of header-right-->
		<div class="cl"></div>
		<div class="menu">
			<ul>
				<li><a href="<?php echo site_url('home')?>">Home</a></li>
				<li><a href="<?php echo site_url('members')?>">Members</a></li>
				<li><a href="<?php echo site_url('aboutus')?>">About Us</a></li>
				<li><a href="<?php echo site_url('contactus')?>">Contact Us</a></li>				
				<?php if(!empty($loggedIn_userInfo)):?>
				<li class="nav-login"><a href="<?php echo base_url().'account/logout'?>">Logout</a></li>
				<?php else:?>
                <li><a href="<?php echo site_url('register')?>">Register</a></li>
				<li class="nav-login"><a href="<?php echo base_url().'home/login'?>">Log In</a></li>
				<?php endif;?>
			</ul>
		</div><!--enmd of menu-->
		<div class="banner">
			<div class="flexslider">
				<ul class="slides">
				<li><img src="<?php echo base_url()?>themes/images/banner-1.png" /></li>
				<li><img src="<?php echo base_url()?>themes/images/banner.jpg" /></li>
				</ul>
			</div>
		</div><!--end of banner-->
	</div><!--end of header-->