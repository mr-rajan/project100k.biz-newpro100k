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
<script src="<?php echo base_url().'themes/js/'?>jquery-1.12.3.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>dist/js/app.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url().'themes/admincp/'?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url().'themes/js/'?>jquery.dataTables.min.js"></script>
<script src="<?php echo base_url().'themes/js/'?>dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.bootstrap.min.js"></script>

<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.print.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/buttons.colVis.min.js"></script>
<script src="<?php echo base_url().'themes/admincp/'?>plugins/datatables/vfs_fonts.js"></script>

<script src="<?php echo base_url().'themes/js/'?>dataTables.responsive.min.js"></script>
<script src="<?php echo base_url().'themes/js/'?>responsive.bootstrap.min.js"></script>
<script src="<?php echo base_url().'themes'?>/js/jstree.min.js"></script>
<script src="<?php echo base_url().'themes/js/custom.js'?>"></script>


<script>
$(document).ready(function(e) {
	//$('#sessionMessageDiv').delay(5000).fadeOut('slow');
	var rows_selected = [];
	var table = $('#userlists').DataTable({
      "paging": true,
	  "responsive":true,
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
	  "buttons": [
	  			 {
		  			"text":'Add New User',
					"action":function( e, dt, node, config){window.location.href=$.trim($('#baseURL').val())+'admincp/user/addnew';}
	  			  },
				  'colvis',
	  			{
					"extend":'collection',
					"text":'Export Data',
					"buttons":['copy', 'excel', 'pdf'],	
				},	    	  	
	  			{
					"extend": 'collection',
					"text": 'Action',
					"buttons":[
								{"text": 'Delete',"action": function( e, dt, node, config){
					//var no_selected	=	table.rows('.selected').data().length;
					
					if(rows_selected.toString()!=''){
								if(confirm('Do you really want to delete selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'delete', 'controller':'user', 'ids':rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}},
								{"text": 'Activate',"action": function( e, dt, node, config){
					//var no_selected	=	table.rows('.selected').length;
					
					if(rows_selected.toString()!=''){
								if(confirm('Are you sure to activate account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'active', 'controller':'user', 'ids':rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}},		
								{"text": 'Suspend',"action": function( e, dt, node, config){
					//var no_selected	=	table.rows('.selected').data().length;
					if(rows_selected.toString()!=''){
								if(confirm('Are you sure to suspend account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'suspend', 'controller':'user', 'ids':rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}}
						 	 ],
				}
	  ]
	 
    });	 	
	$('#userlists tbody').on( 'click', 'tr', function () {		 
      $(this).toggleClass('selected');
        // Get row data
	  var rowData = table.row($(this));
       // Get row ID
      //var rowId = rowData[0];
	  var rowId = $(this).attr('id').split('_');
	   
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId[1], rows_selected);
	 
      // If checkbox is checked and row ID is not in list of selected row IDs
      if($(this).hasClass('selected') && index === -1){
         rows_selected.push(rowId[1]);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!$(this).hasClass('selected') && index !== -1){
         rows_selected.splice(index, 1);
      }
    	
    });	
	
	var bannerurl_rows_selected = []; 
	var table = $('#urlbannerlists').DataTable({
      "paging": true,
	  "responsive":true,
      "lengthChange": true,      
      "searching": true,
      "ordering": true,		  
      "info": true,	
	  "autoWidth": false,
	  "processing": true,
      "serverSide": false,
	  "aoColumnDefs": [{ "bSortable": false,"aTargets": [5] } ],
	  'select': {
         'style': 'multi'
      },
      "dom": 'lf<"floatright"B>rtip',
	  "buttons": [
	  			 {
		  			"text":'Add New Category',
					"action":function( e, dt, node, config){window.location.href=$.trim($('#baseURL').val())+'admincp/bannerurl/addnew';}
	  			  },
				  'colvis',
	  			{
					"extend":'collection',
					"text":'Export Data',
					"buttons":['copy', 'excel', 'pdf'],	
				},	    	  	
	  			{
					"extend": 'collection',
					"text": 'Action',
					"buttons":[
								{"text": 'Delete',"action": function( e, dt, node, config){
					var no_selected	=	table.rows('.selected').data().length;
					if(bannerurl_rows_selected.toString()!=''){
								if(confirm('Do you really want to delete selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'delete', 'controller':'bannerurl', 'ids':bannerurl_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}},
								{"text": 'Activate',"action": function( e, dt, node, config){
					var no_selected	=	table.rows('.selected').data().length;
					if(bannerurl_rows_selected.toString()!=''){
								if(confirm('Are you sure to activate account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'active', 'controller':'bannerurl', 'ids':bannerurl_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}},		
								{"text": 'Suspend',"action": function( e, dt, node, config){
					var no_selected	=	table.rows('.selected').data().length;
					if(bannerurl_rows_selected.toString()!=''){
								if(confirm('Are you sure to suspend account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'suspend', 'controller':'bannerurl', 'ids':bannerurl_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}}
						 	 ],
				}
	  ]
	 
    });	 	
	$('#urlbannerlists tbody').on( 'click', 'tr', function () {		 
      $(this).toggleClass('selected');
        // Get row data
	  var rowData = table.row($(this));
       // Get row ID
      //var rowId = rowData[0];
	  var rowId = $(this).attr('id').split('_');
	   
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId[1], bannerurl_rows_selected);
	 
      // If checkbox is checked and row ID is not in list of selected row IDs
      if($(this).hasClass('selected') && index === -1){
         bannerurl_rows_selected.push(rowId[1]);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!$(this).hasClass('selected') && index !== -1){
         bannerurl_rows_selected.splice(index, 1);
      }
    	
    });
	
	/*Manage bannerurl fields*/
	var bannerurl_fields_rows_selected = []; 
	var table = $('#urlbannerFieldlists').DataTable({
      "paging": true,
	  "responsive":true,
      "lengthChange": true,      
      "searching": true,
      "ordering": true,		  
      "info": true,	
	  "autoWidth": false,
	  "processing": true,
      "serverSide": false,
	  "aoColumnDefs": [{ "bSortable": false,"aTargets": [5] } ],
	  'select': {
         'style': 'multi'
      },
      "dom": 'lf<"floatright"B>rtip',
	  "buttons": [
	  			 {
		  			"text":'Add New Field',
					"action":function( e, dt, node, config){window.location.href=$.trim($('#baseURL').val())+'admincp/bannerurl/managefield';}
	  			  },
				  'colvis',
	  			{
					"extend":'collection',
					"text":'Export Data',
					"buttons":['copy', 'excel', 'pdf'],	
				},	    	  	
	  			{
					"extend": 'collection',
					"text": 'Action',
					"buttons":[
								{"text": 'Delete',"action": function( e, dt, node, config){
					var no_selected	=	table.rows('.selected').data().length;
					if(bannerurl_fields_rows_selected.toString()!=''){
								if(confirm('Do you really want to delete selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'delete', 'controller':'bannerurl', 'ids':bannerurl_fields_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}},
								{"text": 'Activate',"action": function( e, dt, node, config){
					var no_selected	=	table.rows('.selected').data().length;
					if(bannerurl_fields_rows_selected.toString()!=''){
								if(confirm('Are you sure to activate account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'active', 'controller':'bannerurl', 'ids':bannerurl_fields_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}},		
								{"text": 'Suspend',"action": function( e, dt, node, config){
					var no_selected	=	table.rows('.selected').data().length;
					if(bannerurl_fields_rows_selected.toString()!=''){
								if(confirm('Are you sure to suspend account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'suspend', 'controller':'bannerurl', 'ids':bannerurl_fields_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
					}
					else{
							$('#openAlertModal').trigger('click');
					}
				}}
						 	 ],
				}
	  ]
	 
    });	
	$('#urlbannerFieldlists tbody').on( 'click', 'tr', function () {		 
      $(this).toggleClass('selected');
        // Get row data
	  var rowData = table.row($(this));
       // Get row ID
      //var rowId = rowData[0];
	  var rowId = $(this).attr('id').split('_');
	   
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId[1], bannerurl_fields_rows_selected);
	 
      // If checkbox is checked and row ID is not in list of selected row IDs
      if($(this).hasClass('selected') && index === -1){
         bannerurl_fields_rows_selected.push(rowId[1]);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!$(this).hasClass('selected') && index !== -1){
         bannerurl_fields_rows_selected.splice(index, 1);
      }
    	
    });	
	/*End for bannerurl fields*/
	    $("#myTab a").click(function(e){
			e.preventDefault();
			//$(this).tab('show');
			alert($(this).val());
    });
});

function delete_record(recordID,controller){
	
	if(confirm('Do you really want to delete record?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'delete', 'controller':controller, 'ids':recordID},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){
												if(respData!='')window.location.reload();
											}
									});									
								}
}

function viewDownline(uID)
{	
	//$('#modelBtnHdn').trigger('click');
	$.ajax({
				'type':'POST',
				'url':$.trim($('#baseURL').val())+'admincp/user/treeview',
				'data':{'userID':uID, '<?php echo $this->security->get_csrf_token_name()?>': '<?php echo $this->security->get_csrf_hash()?>'},
				beforeSend:function(){
										$('#modelBtnHdn').trigger('click');
									 },
				success:function(responseData){//alert(responseData);return false;
					$('#contentDownlineDiv').find('p:eq(0)').fadeOut('slow');
					$('#contentDownlineDiv').find('p:eq(1)').fadeIn('slow');
				}		
	});
}
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
