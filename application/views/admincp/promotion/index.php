 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($this->session->flashdata('admincp_promotion_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_promotion_flash_message').'</div>';
				
			} ?>
    <section class="content-header">
    
      <h1>Promotion Category Lists</h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li>Promotion Category Lists</li>
      </ol>
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
              <table id="promotionlists"  class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                <thead>
                <tr>
                  <th>SNo</th>
                  <th>Title</th>
                  <th>Active Ads</th>
                  <th>Suspended Ads</th>
                  <th>CreatedOn</th>
                  <th>UpdatedOn</th>
                  <th></th>
                </tr> 
                </thead>
                <?php 
						if(!empty($promotionCategoryLists)):?>
                <tbody>       
				<?php		
							foreach($promotionCategoryLists as $key=>$record):				    									
					$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($record->id));
					if($record->status == 'Active') $status	=	'<a href="javascript:void(0);"  title="Active" alt="Active">
																		 <i class="fa fa-check-circle" aria-hidden="true"></i><a>';
					elseif($record->status == 'Suspend') $status	=	'<a href="javascript:void(0);" title="Suspend" alt="Suspend">
																		 <i class="fa fa-minus-circle" aria-hidden="true"></i><a>';					
					
					$edit	=	'<a href="'.base_url().'admincp/promotion/edit/'.$link.'" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
					$delete	=	'<a href="javascript:void(0);" onclick="delete_record('.$record->id.',\'promotion\')" alt="Delete" title="Delete">
					<i class="fa fa-close" aria-hidden="true"></i></a>';
					
				?>
               
                			<tr id="row_<?php echo $record->id?>">
                                <td><?php echo $key+1?></td>
                                <td><?php echo $record->title?></td>
                                <td><?php echo $record->active_records?></td>  
                                <td><?php echo $record->inactive_records?></td>   
                                <td><?php echo ($record->createdOn!='0000-00-00 00:00:00') ? date('d-m-Y H:i:s',strtotime($record->createdOn)) : 'NA'?></td>
                                <td><?php echo ($record->updatedOn!='0000-00-00 00:00:00') ? date('d-m-Y H:i:s',strtotime($record->updatedOn)) : 'NA'?></td>
                                <td><?php echo '<span class="action_btn">'.$status.'</span><span class="action_btn">'.$edit.'</span>';?></td>
                            </tr>         
                <?php endforeach; ?>
				</tbody>			
				<?php endif;?>    
                <tfoot>
                <tr>
                  <th>SNo</th>
                  <th>Title</th>
                  <th>Active Ads</th>
                  <th>Suspended Ads</th>
                  <th>CreatedOn</th>
                  <th>UpdatedOn</th>
                  <th></th>
                </tr>
                </tfoot>
              </table>              
            </div>           
            <!-- /.box-body -->
          </div>
      
          <input type="hidden" id="csrftoken_name" name="csrftoken_name" value="<?php echo $this->security->get_csrf_token_name()?>">	
          <input type="hidden" id="csrftoken_value" name="csrftoken_value" value="<?php echo $this->security->get_csrf_hash()?>">
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->  
<?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
  <script>
  var promotion_rows_selected = []; 
	var table = $('#promotionlists').DataTable({
      "paging": true,
	  "responsive":true,
      "lengthChange": true,      
      "searching": true,
      "ordering": true,		  
      "info": true,	
	  "autoWidth": false,
	  "processing": true,
      "serverSide": false,
	  "aoColumnDefs": [{ "bSortable": false,"aTargets": [6] } ],
	  'select': {
         'style': 'multi'
      },
      "dom": 'lf<"floatright"B>rtip',
	  "buttons": [
	  			 {
		  			"text":'Add New Category',
					"action":function( e, dt, node, config){window.location.href=$.trim($('#baseURL').val())+'admincp/promotion/addnew';}
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
					if(promotion_rows_selected.toString()!=''){
								if(confirm('Do you really want to delete selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'delete', 'controller':'promotion', 'ids':promotion_rows_selected.toString()},
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
					if(promotion_rows_selected.toString()!=''){
								if(confirm('Are you sure to activate account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'active', 'controller':'promotion', 'ids':promotion_rows_selected.toString()},
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
					if(promotion_rows_selected.toString()!=''){
								if(confirm('Are you sure to suspend account of selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'suspend', 'controller':'promotion', 'ids':promotion_rows_selected.toString()},
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
	$('#promotionlists tbody').on( 'click', 'tr', function () {		 
      $(this).toggleClass('selected');
        // Get row data
	  var rowData = table.row($(this));
       // Get row ID
      //var rowId = rowData[0];
	  var rowId = $(this).attr('id').split('_');
	   
      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId[1], promotion_rows_selected);
	 
      // If checkbox is checked and row ID is not in list of selected row IDs
      if($(this).hasClass('selected') && index === -1){
         promotion_rows_selected.push(rowId[1]);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!$(this).hasClass('selected') && index !== -1){
         promotion_rows_selected.splice(index, 1);
      }
    	
    });
	
  </script>
  <?php $this->load->view('templates/footer_body_close')?>     

