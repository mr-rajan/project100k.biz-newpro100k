 <?php $this->load->view('templates/usercp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($this->session->flashdata('usercp_dashboard_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('usercp_dashboard_flash_message').'</div>';
			} ?>
    <section class="content-header">
      <h1>Dashboard</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          Share Below Link To Invite Your Downline
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body"><?php echo base_url().'register/ref/'.strtolower($userName)?></div>
   
      </div>
      <!-- Your Page Content Here -->
      <?php $this->load->view('admincp/account/smallboxes');?>
      <?php //$this->load->view('admincp/account/analytics_report');?>
      <?php //$this->load->view('admincp/account/visitors_report');?>
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->