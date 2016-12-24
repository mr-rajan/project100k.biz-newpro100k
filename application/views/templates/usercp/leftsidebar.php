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
          <p><?php echo $userName?></p>
          <!-- Status -->
          <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">        
        <!-- Optionally, you can add icons to the links -->
        <li <?php echo (strpos($_SERVER['REQUEST_URI'],'account')) ? 'class="active"' : ''?>> 
			<a href="<?php echo base_url().'usercp/account'?>"><i class="fa fa-tachometer"></i> 
			<span>Dashboard</span></a>
		</li>
		<li <?php echo (strpos($_SERVER['REQUEST_URI'],'downline')) ? 'class="active"' : ''?>>
			<a href="<?php echo base_url().'usercp/downline'?>"><i class="fa fa-link"></i> 
			<span>My Downline</span></a>
		</li>
		<li <?php echo (strpos($_SERVER['REQUEST_URI'],'invitation')) ? 'class="active"' : ''?>>
			<a href="<?php echo base_url().'usercp/invitation'?>"><i class="fa fa-paper-plane"></i> 
			<span>Invite Friend</span></a>
		</li>       
		<li class="treeview">
          <a href="#"><i class="fa fa-rss"></i><span>Promotion</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url().'admincp/promotion/add'?>">Add New</a></li>
            <li><a href="<?php echo base_url().'admincp/promotion/list'?>">List All</a></li>
          </ul>
        </li>
         <li><a href="<?php echo base_url().'admincp/campaign'?>"><i class="fa fa-link"></i> <span>Program Management</span></a></li> 
        <li><a href="<?php echo base_url().'admincp/promotion'?>"><i class="fa fa-link"></i> <span>Promotion Management</span></a></li> 
        <li><a href="<?php echo base_url().'admincp/campaign'?>"><i class="fa fa-link"></i> <span>Campaign Management</span></a></li>        
        <!--<li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Promotion</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url().'admincp/promotion/add'?>">Add New</a></li>
            <li><a href="<?php echo base_url().'admincp/promotion/list'?>">List All</a></li>
          </ul>
        </li>-->
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>