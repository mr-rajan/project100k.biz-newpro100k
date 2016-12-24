 <?php $this->load->view('templates/usercp/leftsidebar')?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if($this->session->flashdata('admincp_userlist_flash_message')) { 
				echo '<div id="sessionMessageDiv" style="padding:10px;">'.$this->session->flashdata('admincp_userlist_flash_message').'</div>';
				
			} ?>
    <section class="content-header">
      <h1>My Downline</h1>
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
            <div class="box-body">
              <table id="downlinelists" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name</th>
                  <th>Gender</th>
                  <th>Email</th>                  
                  <th>Contact</th>
                  <th>Country</th>
                  <th>Profile Picture</th>
                </tr> 
                </thead>
                <?php 
						if(!empty($downlineLists)):?>
                <tbody>       
				<?php		
							foreach($downlineLists as $key=>$user):				    
									if($user->gender=='M') $gender = '<i class="fa fa-male" aria-hidden="true"></i> Male';
									elseif($user->gender=='F') $gender = '<i class="fa fa-female" aria-hidden="true"></i> Female';
									else $gender = 'Other';	
					if($user->status == 'Active') $status	=	'<a href="javascript:void(0);" onclick="update_status('.$user->id.', suspend)" title="Active" alt="Active">
																		 <i class="fa fa-check-circle" aria-hidden="true"></i><a>';
					elseif($user->status == 'Inactive') $status	=	'<a href="javascript:void(0);" onclick="update_status('.$user->id.', active)" 
																			title="Inactive" alt="Inactive">
																		 <i class="fa fa-minus-circle" aria-hidden="true"></i><a>';
					elseif($user->status == 'Suspend') $status	=	'<a href="javascript:void(0);" onclick="update_status('.$user->id.',active)"
																			 title="Suspended" alt="Suspended">
																		 <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><a>';
					else $status = '-';
				$edit	=	'<a href="'.base_url().'index.php/admincp/user/edit/'.$user->id.'" alt="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>';
				$delete	=	'<a href="javascript:void(0);" onclick="delete_record('.$user->id.')" alt="Delete" title="Delete"><i class="fa fa-close" aria-hidden="true"></i></a>';
					
				?>
               
                			<tr id="row_<?php echo $user->id?>">
                                <td><?php echo $key+1?></td>
                                <td><?php echo $user->name?></td>
                                <td><?php echo $gender?></td>                                
                                <td><?php echo $user->email?></td>
                                <td><?php echo ($user->contactno!='') ? $user->contactno : '-' ?></td>
                                <td><?php echo ($user->country_name!='') ? $user->country_name : '-' ?></td>
                                <td align="center"><?php echo img(array('src'=>base_url().'themes/images/noimage.png','title'=>'Picture Not Available'))?></td>
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
                   <th>Profile Picture</th>
                </tr> 
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <input type="hidden" name="csrftoken_name" value="<?php echo $this->security->get_csrf_token_name()?>">	
          <input type="hidden" name="csrftoken_value" value="<?php echo $this->security->get_csrf_hash()?>">
      <!-- end -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->          