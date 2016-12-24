 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($this->session->flashdata('admincp_userlist_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_userlist_flash_message').'</div>';
				
			} ?>
 
      <section class="content-header">
    
      <h1><?php echo $parentUserName?> Downline</h1>
      
      <ol class="breadcrumb"><?php echo $downlineBreadCrumb?></ol>
    </section>
<style>
.action_btn{ padding:5px;}
#overlay {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 99999;
    background-color:#CCC;
    filter: alpha(opacity=75);
    -moz-opacity: 0.75;
    opacity: 0.75;
    display: none;
}
#overlay h2 {
    position: fixed;
    margin-left: 30%;
	font-size:20px;
    top: 40%;
}

</style>
    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
      
     <div class="box">
     
       <!-- Button trigger modal -->
<button data-toggle = "modal" data-target = "#myModal" id="openAlertModal" style="display:none;">Alert</button>
<div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" aria-labelledby = "myModalLabel" aria-hidden = "true">

<div class = "modal-dialog">
<div class = "modal-content"><div class = "modal-body">Please select record(s).</div><div class = "modal-footer">
<button type = "button" class = "btn btn-default" data-dismiss = "modal">Close</button></div></div></div>

</div>
<div id="overlay">	
    <h2>Processing .. Please wait</h2>
</div>  

            <!-- /.box-header -->
            <div class="box-body">
              <table id="userlists"  class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name</th>
                  <th>Gender</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Country</th>
                  <th></th>
                </tr> 
                </thead>
                <?php 
						if(!empty($userLists)):?>
                <tbody>       
				<?php		
							foreach($userLists as $key=>$user):				    
									if($user->gender=='M') $gender = '<i class="fa fa-male" aria-hidden="true"></i> Male';
									elseif($user->gender=='F') $gender = '<i class="fa fa-female" aria-hidden="true"></i> Female';
									else $gender = 'Other';	
					$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($user->id));
					if($user->status == 'Active') $status	=	'<a href="javascript:void(0);" onclick="update_status('.$user->id.', suspend)" title="Active" alt="Active">
																		 <i class="fa fa-check-circle" aria-hidden="true"></i><a>';
					elseif($user->status == 'Inactive') $status	=	'<a href="javascript:void(0);" onclick="update_status('.$user->id.', active)" 
																			title="Inactive" alt="Inactive">
																		 <i class="fa fa-minus-circle" aria-hidden="true"></i><a>';
					elseif($user->status == 'Suspend') $status	=	'<a href="javascript:void(0);" onclick="update_status('.$user->id.',active)"
																			 title="Suspended" alt="Suspended">
																		 <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><a>';
					else $status = '-';				
$edit	=	'<a href="'.base_url().'admincp/user/edit/'.$link.'" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
$delete	=	'<a href="javascript:void(0);" onclick="delete_record('.$user->id.',\'user\')" alt="Delete" title="Delete"><i class="fa fa-close" aria-hidden="true"></i></a>';
$downline	= '<a href="'.base_url().'admincp/user/downline/'.$link.'" alt="Downline" title="View Downline"><i class="fa fa-search-plus" aria-hidden="true"></i></a>';

					
				?>
               
                			<tr id="row_<?php echo $user->id?>">
                                <td><?php echo $key+1?></td>
                                <td><?php echo $user->name?></td>
                                <td><?php echo $gender?></td>
                                <td><?php echo $user->email?></td>
                                <td><?php echo ($user->contactno!='') ? $user->contactno : '-' ?></td>
                                <td><?php echo ($user->country_name!='') ? $user->country_name : '-' ?></td>
                                <td><?php echo '<span class="action_btn">'.$status.'</span><span class="action_btn">'.$edit.'</span>
								<span class="action_btn">'.$downline.'</span>'; ?></td>
                            </tr>         
                <?php endforeach; ?>
				</tbody>			
				<?php endif;?>    
                <tfoot>
                <tr>
                  <th>SNo</th>
                  <th>Name</th>
                  <th>Gender</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Country</th>
                  <th></th>
                </tr>
                </tfoot>
              </table>              
            </div>           
            <!-- /.box-body -->
          </div>
      
          <input type="hidden" id="csrftoken_name" name="csrftoken_name" value="<?php echo $this->security->get_csrf_token_name()?>">	
          <input type="hidden" id="csrftoken_value" name="csrftoken_value" value="<?php echo $this->security->get_csrf_hash()?>">
          <input type="hidden" id="addNewUserForUserLabel" name="addNewUserForUserLabel" value="<?php echo $addNewUserForUserLabel?>">
          <input type="hidden" id="parentUserID" name="parentUserID" value="<?php echo $parentUserID?>"/>
          
          
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->    
  <?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
<script>
$(document).ready(function(e) {
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
		  			"text": $.trim($('#addNewUserForUserLabel').val()),
					"action":function( e, dt, node, config){
						window.location.href=$.trim($('#baseURL').val())+'admincp/user/addnew/'+$.trim($('#parentUserID').val());
				} },
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
	
});
</script>
<?php $this->load->view('templates/footer_body_close')?>        