 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url().'themes/admincp/'?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">        
        <!-- Optionally, you can add icons to the links -->
        <li <?php echo (strpos($_SERVER['REQUEST_URI'],'account')) ? 'class="active"' : ''?>>
        <a href="<?php echo base_url().'admincp/account'?>"><i class="fa fa-tachometer" aria-hidden="true"></i><span>Dashboard</span></a></li>
        <li <?php echo (strpos($_SERVER['REQUEST_URI'],'user')) ? 'class="active"' : ''?>>
        <a href="<?php echo base_url().'admincp/user'?>"><i class="fa fa-users" aria-hidden="true"></i><span>Users Management</span></a></li>            
        <li <?php echo (strpos($_SERVER['REQUEST_URI'],'program')) ? 'class="active"' : ''?>>        
        <li class="treeview <?php echo (strpos($_SERVER['REQUEST_URI'],'bannerurl')) ? 'active' : ''?>">
          <a href="#">
            <i class="fa fa-link"></i> <span>URL &amp; Banners</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu <?php echo (strpos($_SERVER['REQUEST_URI'],'bannerurl')) ? 'menu-open' : ''?>" 
		  <?php echo (strpos($_SERVER['REQUEST_URI'],'bannerurl') || strpos($_SERVER['REQUEST_URI'],'bannercategory')) ? 'style="display:block;"' : 'style:"display:none;"'?>>
            <li <?php echo (strpos($_SERVER['REQUEST_URI'],'bannerurl')) ? 'class="active"': ''?> >
            <a href="<?php echo base_url().'admincp/bannerurl'?>"><i class="fa fa-circle-o"></i>Cateogory Lists</a></li>
            <?php get_banner_categories_lists();?>
          
          </ul>
        </li>
        
        <li><a href="<?php echo base_url().'admincp/campaign'?>"><i class="fa fa-paper-plane" aria-hidden="true"></i><span>Program Management</span></a></li> 
        <li <?php echo (strpos($_SERVER['REQUEST_URI'],'promotion')) ? 'class="active"' : ''?>>
        <a href="<?php echo base_url().'admincp/promotion'?>"><i class="fa fa-rss" aria-hidden="true"></i><span>Promotion Management</span></a></li> 
        <li <?php echo (strpos($_SERVER['REQUEST_URI'],'campaign')) ? 'class="active"' : ''?>>
        <a href="<?php echo base_url().'admincp/campaign'?>"><i class="fa fa-link"></i> <span>Campaign Management</span></a></li>      
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <?php
      if(!strpos($_SERVER['REQUEST_URI'],'user'))$this->session->unset_userdata('downlineBreadCrumbTracking');
	?>
    <!-- /.sidebar -->
  </aside>