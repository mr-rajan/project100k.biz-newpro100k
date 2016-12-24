 <!-- Main Footer -->
  <footer class="main-footer">
    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  
 
      
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<input type="hidden" id="baseURL" value="<?php echo base_url()?>"/>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url().'themes/admincp/'?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url().'themes/admincp/'?>bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url().'themes/admincp/'?>plugins/fastclick/fastclick.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url().'themes/admincp/'?>dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url().'themes/admincp/'?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url().'themes/admincp/'?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url().'themes/admincp/'?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="<?php echo base_url().'themes/admincp/'?>plugins/chartjs/Chart.min.js"></script>


<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.bootstrap.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.print.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.colVis.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/dataTables.checkboxes.min.js"></script>

<script>
$(document).ready(function(e) {

	//$('#sessionMessageDiv').delay(5000).fadeOut('slow');
	var rows_selected = [];    
	var table = $('#downlinelists').DataTable({
      "paging": true,
      "lengthChange": true,      
      "searching": true,
      "ordering": true,		  
      "info": true,	
	  "autoWidth": false,
	  "processing": true,
      "serverSide": false,
	  "aoColumnDefs": [{ "bSortable": false,"aTargets": [4,6] } ],
	  'select': {
         'style': 'multi'
      },
      "dom": 'lf<"floatright"B>rtip',
	  "buttons": [{
		  			"text":'Invite New Downline',
					"action":function( e, dt, node, config){window.location.href=$.trim($('#baseURL').val())+'usercp/downline/addnew';}
	  			  },
	  			{
					"extend":'collection',
					"text":'Export Data',
					"buttons":['copy', 'excel', 'pdf'],	
				},	    	  		  			
	   ]	 
    });
});

</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
