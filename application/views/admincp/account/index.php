 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($this->session->flashdata('admincp_dashboard_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_dashboard_flash_message').'</div>';
			} ?>
    <section class="content-header">
    
      <h1>Dashboard</h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
      <?php $this->load->view('admincp/account/smallboxes');?>
      <?php //$this->load->view('admincp/account/analytics_report');?>
      <?php //$this->load->view('admincp/account/visitors_report');?>
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
<?php $this->load->view('templates/footer_body_close')?>