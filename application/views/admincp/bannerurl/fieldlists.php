
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

.gridOrder{
	display:none;
}
.valueTD {
    float: left;
    margin-right: 10px;
}
.valueTD > select, .editTD_save {
	display:none;   
}
.editTD{
	cursor:pointer;
}

</style>
    <!-- Main content -->
    <section class="content">
     <?php if($this->session->flashdata('admincp_bannerurlfieldlist_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_bannerurlfieldlist_flash_message').'</div>';
				
			} ?>
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
              <table id="urlbannerFieldlists"  class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                <thead>
                <tr>
                <?php /*?>  <th><input type="checkbox" class="rowCheck_all" name="rowCheck_all"></th><?php */?>
                  <th>SNo</th>
                  <th>Label/Title</th>
                  <th>Type</th>
                  <th>IsMandatory?</th>
                  <th>Add To Table Heading?</th>
                  <th>Visibilty Order</th>
                  <th>Column Sortable</th>
                <!--  <th>CreatedOn</th>-->
                  <th></th>
                </tr> 
                </thead>
                <?php 
						if(!empty($fieldInfo)):						
						?>
                <tbody>       
				<?php		
							foreach($fieldInfo as $key=>$record):	
								if(!empty($record)):		    									
										$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt->encode($record->id));
										if($record->status == 'Active') $status	=	'<a href="javascript:void(0);"  title="Active" alt="Active">
																							 <i class="fa fa-check-circle" aria-hidden="true"></i><a>';
										elseif($record->status == 'Suspend') $status	=	'<a href="javascript:void(0);" title="Suspend" alt="Suspend">
																							 <i class="fa fa-minus-circle" aria-hidden="true"></i><a>';					
										$type	=	'';
										switch($record->type){
												case 1: $type	=	'Textfield';break;
												case 2: $type	=	'Textarea';break;
												case 3: $type	=	'Dropdown';break;
												case 4: $type	=	'Checkbox';break;
												case 5: $type	=	'Radio (Choice)';break;
												case 6: $type	=	'File (Selectable)';break;
												default: $type	=	'Textfield';break;
										}
					
					$edit	=	'<a href="'.base_url().'admincp/bannerurl/editfield/'.$link.'" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
					$delete	=	'<a href="javascript:void(0);" onclick="delete_record('.$record->id.',\'bannerurlfield\')" alt="Delete" title="Delete">
					<i class="fa fa-close" aria-hidden="true"></i></a>';					
				?>
               
                			<tr id="field_row_<?php echo $record->id?>">
                            	<?php /*?><td><input type="checkbox" name="rowselected[]" value="<?php echo $record->id?>"></td><?php */?>
                                <td><?php echo $key+1?></td>
                                <td><?php echo $record->title?></td>
                                <td><?php echo $type?></td>  
                                <td>
                                <?php echo ($record->isMandatory == 'yes') ? 'Yes' : 'No'?>
                                <?php /*?><div class="wrapperValue_editTD">
                                    <div class="valueTD" id="div_valueTD_<?php echo $record->id?>">
                                        <select id="dp_valueTD_<?php echo $record->id?>">
                                        	<option value="yes" <?php echo ($record->isMandatory == 'yes') ? 'selected="selected"' : '' ?>>Yes</option>
                                            <option value="no" <?php echo ($record->isMandatory == 'no') ? 'selected="selected"' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="valueTD" id="lbl_valueTD_<?php echo $record->id?>">
                                   		<?php echo ($record->isMandatory == 'yes') ? 'Yes' : 'No'?>
                                    </div>
                                    <div class="editTD" id="edit_valueTD_<?php echo $record->id?>"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                    <div class="editTD_save" id="save_valueTD_<?php echo $record->id?>">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i></div>
                                </div><?php */?>
                                </td>  
                                <td>
                                <?php echo ($record->addtoTableHeading == 'yes') ? 'Yes' : 'No'?>
								<?php /*?><div class="wrapperValue_editTD">
                                    <div class="valueTD" id="div_tableheading_<?php echo $record->id?>">
                                        <select id="dp_tableheading_<?php echo $record->id?>">
                                        	<option value="yes" <?php echo ($record->addtoTableHeading == 'yes') ? 'selected="selected"' : '' ?>>Yes</option>
                                            <option value="no" <?php echo ($record->addtoTableHeading == 'no') ? 'selected="selected"' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="valueTD" id="lbl_tableheading_<?php echo $record->id?>">
                                   		<?php echo ($record->addtoTableHeading == 'yes') ? 'Yes' : 'No'?>
                                    </div>
                                    <div class="editTD" id="edit_tableheading_<?php echo $record->id?>"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                    <div class="editTD_save" id="save_tableheading_<?php echo $record->id?>">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i></div>
                                </div><?php */?>
								</td> 
                                <td><?php echo ($record->order)?></td>  
                                <td>
                                <?php echo ($record->sortable == 'yes') ? 'Yes' : 'No'?>
								<?php /*?><div class="wrapperValue_editTD">
                                    <div class="valueTD" id="div_sortable_<?php echo $record->id?>">
                                        <select id="dp_sortable_<?php echo $record->id?>">
                                        	<option value="yes" <?php echo ($record->sortable == 'yes') ? 'selected="selected"' : '' ?>>Yes</option>
                                            <option value="no" <?php echo ($record->sortable == 'no') ? 'selected="selected"' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="valueTD" id="lbl_sortable_<?php echo $record->id?>">
                                   		<?php echo ($record->sortable == 'yes') ? 'Yes' : 'No'?>
                                    </div>
                                    <div class="editTD" id="edit_sortable_<?php echo $record->id?>"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                    <div class="editTD_save" id="save_sortable_<?php echo $record->id?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></div>
                                </div><?php */?>
								</td> 
                               <?php /*?> <td><?php echo ($record->createdOn!='0000-00-00 00:00:00') ? date('d-m-Y H:i:s',strtotime($record->createdOn)) : 'NA'?></td><?php */?>
                                <td><?php echo '<span class="action_btn">'.$status.'</span><span class="action_btn">'.$edit.'</span>'; ?></td>
                            </tr>         
                <?php endif;endforeach; ?>
				</tbody>			
				<?php endif;?>    
                <tfoot>
                <tr>
                <?php /*?>  <th><input type="checkbox" class="rowCheck_all" name="rowCheck_all"></th><?php */?>
                  <th>SNo</th>
                  <th>Label/Title</th>
                  <th>Type</th>
                  <th>IsMandatory?</th>
                  <th>Add To Table Heading?</th>
                  <th>Visibilty Order</th>
                  <th>Column Sortable</th>
               <!--   <th>CreatedOn</th>-->
                  <th></th>
                </tr>
                </tfoot>
              </table>              
            </div>           
            <!-- /.box-body -->
          </div>
      
          <input type="hidden" id="csrftoken_name" name="csrftoken_name" value="<?php echo $this->security->get_csrf_token_name()?>">	
          <input type="hidden" id="csrftoken_value" name="csrftoken_value" value="<?php echo $this->security->get_csrf_hash()?>"> 
          <input type="hidden" id="long_uri_string" name="long_uri_string" value="<?php echo $long_uri_string?>"> 
          <input type="hidden" id="toastPriority" name="toastPriority"> 
          <input type="hidden" id="toastTitle" name="toastTitle"> 
          <input type="hidden" id="toastMessage" name="toastMessage">          
      <!-- end -->
    </section>
    <!-- /.content -->
<script> 
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

</script>
    


