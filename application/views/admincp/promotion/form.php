 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">   
      <h1><?php echo (!empty($promotionInfo) && $hdnPromotionID!='') ? '<b>Edit:</b> '.$catgTitle: 'Add New Category'?></h1>
      
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="<?php echo base_url().'admincp/promotion'?>">Category Lists</a></li>
        <li><?php echo (!empty($promotionInfo) && $hdnPromotionID!='') ? '<b>Edit:</b> '.$catgTitle: 'Add New Category'?></li>
      </ol>
    </section>
    <input type="hidden" id="promotionfieldflash" value="<?php echo ($this->session->userdata('admincp_promotionfieldlist_flash_message')) ? '1' : '0'?>"/>
<style type="text/css">
.disabledTab {
    pointer-events: none;
}
</style>
    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
       <!-- form start -->
        <div class="box box-info">       		  
                <ul class="nav nav-tabs" id="myTab" style="margin:5px 0 0 2px;">
                    <li class="active"><a data-toggle="tab" href="#form">Heading</a></li>
                    <li ><a data-toggle="tab" href="#formattributes">Manage Fields</a></li>                        
                </ul> 
            <div class="box-body">              				
                    <div class="tab-content">                    
                        <div id="form" class="tab-pane fade in active">
							<?php   $form_attributes = array('name'=>'promotionForm','class'=>'form-horizontal' );
									echo form_open_multipart('admincp/promotion/save',$form_attributes);
									
									if($this->session->flashdata('admincp_promotionform_flash_message')) {
										echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_promotionform_flash_message').'</div>';
									} 			 
                           
                            ///.box-header 
               
                                    $postedValue_title	=	$postedValue_Status	=	'';
                                    if(empty($promotionInfo) && !empty($postedValue)){
                                            $postedValue_title		=	$postedValue['title'];
                                            $postedValue_Status		=	$postedValue['status'];
                                    }
                                    elseif(!empty($promotionInfo) && empty($postedValue)){
                                            $postedValue_title		=	$promotionInfo->title;
                                            $postedValue_Status		=	$promotionInfo->status;
                                    }
                                    elseif(!empty($promotionInfo) && !empty($postedValue)){
                                            $postedValue_title		=	$postedValue['title'];
                                            $postedValue_Status		=	$postedValue['status'];
                                    }
                            ?>
                                <div class="box-header with-border" style="margin-bottom:15px;">
                                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel" 
                                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/promotion'?>'">Cancel</button>
                                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
                                </div>
                        
                        	<div class="form-group has-feedback">
                            <label for="inputEmail3" class="col-sm-2 control-label">Title <span class="required">*</span></label>
                            <div class="col-sm-10">
                            <?php $data = array('name'=>'title', 'id'=>'title', 
                                            'value'=>$postedValue_title, 
                                            'required'=>'required', 'class'=>'form-control', 'placeholder'=>'Title' );
                                  echo  form_input($data);
                            ?>                    
                            </div>
                            </div>
                            
                            	<div class="form-group has-feedback">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status <span class="required">*</span></label>
                  <div class="col-sm-10">
					              <select name="status" class="form-control select2" style="width: 100%;" required="required" >
                                      <option value="" <?php  echo ($postedValue_Status == '') ? 'selected' : ''?>>-Select-</option>
                                      <option value="Active" <?php echo ($postedValue_Status == 'Active') ? 'selected' : ''?>>Active</option>
                                      <option value="Suspend" <?php  echo ($postedValue_Status == 'Suspend') ? 'selected' : ''?>>Suspend</option>
								  </select>
				
                  </div>
                </div>
                				
                                 <input type="hidden" name="hdnPromotionID" id="hdnPromotionID" value="<?php echo $hdnPromotionID?>">
                                  <input type="hidden" name="catg_uri_string" id="catg_uri_string" value="<?php echo $catg_uri_string?>">
                
              				    <div class="box-footer">                    
                    <button class="btn btn-info pull-right" type="button" name="cancel" value="cancel"
                    onclick="javascript:window.location.href='<?php echo base_url().'admincp/promotion'?>'">Cancel</button>
                    <button class="btn btn-info pull-right" type="reset" name="reset" value="reset">Reset</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="save">Save</button>
                    <button class="btn btn-info pull-right" type="submit" name="submit" value="savenc">Save &amp; Continue</button>
              </div>
              					 <?php echo form_close(); ?>
                        </div>
                        <div id="formattributes" class="tab-pane"> 
                   			<?php $this->load->view('admincp/promotion/fieldlists')?>
                   		</div>
                 </div>   
           </div>   
              
          </div>       
          
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->    
<?php $this->load->view('templates/admincp/admin_footer_bottom')?>
<?php $this->load->view('templates/footer_js_script')?>
<script type="text/javascript">
$(document).ready(function(e) {
   if($.trim($('#promotionfieldflash').val())=='1'){;
	   $('.nav-tabs li:eq(1) a').tab('show'); 
   }
   else{
		 $('.nav-tabs li:eq(0) a').tab('show');    
   }
   var promotion_fields_rows_selected = []; 
	var table = $('#promotionFieldlists').DataTable({
      "paging": true,
	  "responsive":true,
      "lengthChange": true,      
      "searching": true,
      "ordering": true,		  
      "info": true,	
	  "autoWidth": false,
	  "processing": true,
      "serverSide": false,
	  "aoColumnDefs": [{ "bSortable": false,"aTargets": [7] } ],
	  'select': {
         'style': 'multi'
      },
      "dom": 'lf<"floatright"B>rtip',
	  "buttons": [
	  			 {
		  			"text":'Add New Field',
					"action":function( e, dt, node, config){
						window.location.href=$.trim($('#baseURL').val())+'admincp/promotion/addnewfield/'+$.trim($('#hdnPromotionID').val());}
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
					if(promotion_fields_rows_selected.toString()!=''){
								if(confirm('Do you really want to delete selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'delete', 'controller':'promotionfield', 'ids':promotion_fields_rows_selected.toString(), 
													'catg_uri_string':$('#catg_uri_string').val(),'catgId':$('#hdnPromotionID').val()},
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
					if(promotion_fields_rows_selected.toString()!=''){
								if(confirm('Are you sure to activate selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'active', 'controller':'promotionfield', 'ids':promotion_fields_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){//alert('--'.respData);return false;
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
					if(promotion_fields_rows_selected.toString()!=''){
								if(confirm('Are you sure to suspend selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'suspend', 'controller':'promotionfield', 'ids':promotion_fields_rows_selected.toString()},
											'beforeSend': function(){ $('#overlay').fadeIn('slow');},
											'success':function(respData){///alert('--'.respData);return false;
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
	$('#promotionFieldlists tbody').on( 'click', 'tr', function () {		 
      $(this).toggleClass('selected');
        // Get row data
	  var rowData = table.row($(this));
       // Get row ID
      //var rowId = rowData[0];
	  var rowAttr = $(this).attr('id').split('_');
	  var rowId	  =	rowAttr[2];
      // Determine whether row ID is in the list of selected row IDs 
      var index   = $.inArray(rowId, promotion_fields_rows_selected);
	//  alert('--'+index+'=='+row)
      // If checkbox is checked and row ID is not in list of selected row IDs
      if($(this).hasClass('selected') && index === -1){
         promotion_fields_rows_selected.push(rowId);
      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!$(this).hasClass('selected') && index !== -1){
         promotion_fields_rows_selected.splice(index, 1);
      }
    //	alert('--'+promotion_fields_rows_selected.toString());
    });	
	
});
$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});


</script>
<?php $this->load->view('templates/footer_body_close')?>        