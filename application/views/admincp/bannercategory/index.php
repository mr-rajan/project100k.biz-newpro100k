 <?php $this->load->view('templates/admincp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($this->session->flashdata('admincp_bannercategory_indexpage_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_bannercategory_indexpage_flash_message').'</div>';
				
			} ?>
    <section class="content-header">
    
      <h1><?php echo $categoryName?> Lists</h1>
      
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url().'admincp/account'?>"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="<?php echo base_url().'admincp/bannerurl'?>">Cateogory Lists</a></li>
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

 <?php	  $rowHeads_array = array();
		  if(!empty($bannerCategoryTableHeaders)):
			  $rowHeads_array	=	array(array('fldName'=>'','rowHeadTitle'=>'SNo'));
			  foreach($bannerCategoryTableHeaders as $key=>$tableHeader):	
				 $fieldType	=	$this->base_model->is_Record_Exists('tbl_bannerurl_masterfields',array('id','type'),
																	array('title'=>$tableHeader['headingTitle'], 
																   'bannerurlCategoryId'=>$decryptedCatgId));
				 $temp		=	array('id'	  => $fieldType->id,
									  'fldName' => strtolower(str_replace(array(' ','-'),array('_'),$tableHeader['headingTitle'])),
									  'rowHeadTitle'=>ucwords($tableHeader['headingTitle']),
									  'type'  => $fieldType->type);									
				 array_push($rowHeads_array,$temp);
			  endforeach;
			  array_push($rowHeads_array,array('fldName'=>'createdOn','rowHeadTitle'=>'Created On'));
			  array_push($rowHeads_array,array('fldName'=>'','rowHeadTitle'=>''));
		 endif;	
		// print'<pre>';print_r($rowHeads_array);print'</pre>';
?>

            <!-- /.box-header -->
            <div class="box-body">
              <table id="urlbannerdynamic_catg"  class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                <thead>
                <tr>
                 		<?php   if(!empty($rowHeads_array )){
									foreach($rowHeads_array as $tableRowHeadKey => $tableRowHeadValue){
										echo '<th>'.$tableRowHeadValue['rowHeadTitle'].'</th>';
									}
								}
								else echo 'No field(s) and record(s) are available.';
						?>
                </tr> 
                </thead>
                <tbody>
                 <?php 
                 if(!empty($ads_lists)):
					 foreach($ads_lists as $listKey=>$listValue):
						 echo '<tr id="row_'.$listValue->id.'">';
								for($i=0;$i<count($rowHeads_array);$i++)
								{
									if($i==0){
										echo '<td>'.($listKey+1).'</td>';
									}
									elseif($i==(count($rowHeads_array)-1)){
										$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($listValue->id));
										
										if($listValue->ads_status == 'Active') 		$status	=	'<a href="javascript:void(0);" title="Active" alt="Active">
													 										<i class="fa fa-check-circle" aria-hidden="true"></i><a>';
										elseif($listValue->ads_status == 'Inactive') $status	=	'<a href="javascript:void(0);" title="Inactive" alt="Inactive">
													 										<i class="fa fa-minus-circle" aria-hidden="true"></i><a>';
										elseif($listValue->ads_status == 'Suspend')  $status	=	'<a href="javascript:void(0);" title="Suspended" alt="Suspended">
																						 	<i class="fa fa-exclamation-triangle" aria-hidden="true"></i><a>';
										else $status = '-';
										$edit	=	'<a href="'.base_url().'admincp/bannercategory/edit/'.$tblName.'/'.$link.'" alt="Edit" title="Edit">
													 <i class="fa fa-edit" aria-hidden="true"></i></a>';
										$delete	=	'<a href="javascript:void(0);" 
													 onclick="delete_record('.$listValue->id.',\'bannercategoryads\')" alt="Delete" title="Delete">
												 <i class="fa fa-close" aria-hidden="true"></i></a>';
										echo '<th><span class="action_btn">'.$status.'</span><span class="action_btn">'.$edit.'</span>
											</th>';
									}
									else{
										if(@strlen($listValue->$rowHeads_array[$i]['fldName'])>20) 
											@$printVal	=	substr($listValue->$rowHeads_array[$i]['fldName'],0,20).'...';
										else 	
											@$printVal	=	$listValue->$rowHeads_array[$i]['fldName'];	
										
										switch(@$rowHeads_array[$i]['type'])
										{													
													  case 3: 
																$optionInfo	=	$this->base_model->is_Record_Exists('tbl_bannerurl_field_options',
																									array('optionvalue'),
																									array('masterfieldsID'=>$rowHeads_array[$i]['id'], 
																									'id'=>$listValue->$rowHeads_array[$i]['fldName']));
																@$printVal	=	$optionInfo->optionvalue;
																break;
													  case 4: 
																$ids		=	explode('~',$listValue->$rowHeads_array[$i]['fldName']);
																$optionInfo	=	$this->base_model->get_RecordsWithIn($ids, $rowHeads_array[$i]['id']);
																@$printVal	=	$optionInfo;
																break;
													  case 5: 
																$optionInfo	=	$this->base_model->is_Record_Exists('tbl_bannerurl_field_options',
																									array('optionvalue'),
																									array('masterfieldsID'=>$rowHeads_array[$i]['id'], 
																									'id'=>$listValue->$rowHeads_array[$i]['fldName']));
																@$printVal	=	$optionInfo->optionvalue;
																break;	
													  case 6:
													            $uploadPath			 =	'themes/other_uploaded_images/';
																if($listValue->$rowHeads_array[$i]['fldName'] != '' 
																&& file_exists($uploadPath.$listValue->$rowHeads_array[$i]['fldName']))
																{
																	$printVal	=	'<img src="'.base_url().'/'.$uploadPath.$listValue->$rowHeads_array[$i]['fldName'].'"
																	                  width="20" height="20"/>';	
																}
																else
																$printVal	=	'NA';
																break;
																													 
													  default:break;
											    }		
										echo '<td>'.$printVal.'</td>';
									}
								}
						echo '</tr>';		
					endforeach;
				endif;
				?>
                </tbody>
                <tfoot>
                <tr>
                 		<?php
                                if(!empty($rowHeads_array )){
									foreach($rowHeads_array as $tableRowHeadKey => $tableRowHeadValue){
										echo '<th>'.$tableRowHeadValue['rowHeadTitle'].'</th>';
									}
								}
						?>
                </tr>
                </tfoot>
              </table>              
            </div>           
            <!-- /.box-body -->
          </div>
          
          <input type="hidden" id="request_catgId" value="<?php echo $request_catgId?>">
          <input type="hidden" id="total_rowheadcount" value="<?php echo count($bannerCategoryTableHeaders)?>">
          <input type="hidden" id="categoryName" value="<?php echo 'Add New '.$categoryName?>">
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
 $(document).ready(function(e) { 
  var bannerurl_rows_selected = []; //alert($('#sortableColumnKeys').val());
	var table = $('#urlbannerdynamic_catg').DataTable({
      "paging": true,
	  "responsive":true,
      "lengthChange": true,      
      "searching": true,
      "ordering": true,		  
      "info": true,	
	  "autoWidth": false,
	  "processing": true,
      "serverSide": false,
	  "aoColumnDefs": [{ "bSortable": false,"aTargets": [<?php echo $unSortableColumnKeys?>] } ],
	  'select': {
         'style': 'multi'
      },
      "dom": 'lf<"floatright"B>rtip',
	  "buttons": [
	  			 {
		  			"text": $.trim($('#categoryName').val()),
					"action":function( e, dt, node, config){window.location.href=$.trim($('#baseURL').val())+'admincp/bannercategory/addnew/'+$.trim($('#request_catgId').val());}
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
													'action':'delete', 'controller':'bannercategory', 'ids':bannerurl_rows_selected.toString(), 'tbl':'<?php echo $tblName?>'},
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
								if(confirm('Are you sure to activate selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'active', 'controller':'bannercategory', 'ids':bannerurl_rows_selected.toString(), 'tbl':'<?php echo $tblName?>'},
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
								if(confirm('Are you sure to suspend selected record(s)?')){									
									$.ajax({ 
											'type':'post',
											'url': $.trim($('#baseURL').val())+'admincp/action/process',
											'data': {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 
													'action':'suspend', 'controller':'bannercategory', 'ids':bannerurl_rows_selected.toString(), 'tbl':'<?php echo $tblName?>'},
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
	$('#urlbannerdynamic_catg tbody').on( 'click', 'tr', function () {		 
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
});	
  </script>
  <?php $this->load->view('templates/footer_body_close')?>     

