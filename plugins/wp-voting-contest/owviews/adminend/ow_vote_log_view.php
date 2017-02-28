<?php
if(!function_exists('ow_vote_log_view')){
    function ow_vote_log_view($voting_logs,$log_entries,$trans_navigation,$records_per_page){
		//global $wpdb;
		wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
		wp_enqueue_style('OW_ADMIN_STYLES');		
	
	if(isset($_GET['delete_success']))
    {
        ?>
         <div id="message" class="updated fade">
			<p><strong>
                <?php if($_GET['delete_success'] == 1): ?>
					<?php _e('Vote Entry deleted successfully','voting-contest'); ?>
                <?php else: ?>
                    <?php _e('Vote Entries deleted successfully','voting-contest'); ?>
                <?php endif; ?>
			    </strong>
			</p>
	    </div>
        <?php
    }
	?>
	<div class="wrap">
		<h2><?php echo _e('Voting Logs','voting-contest'); ?></h2><br />
		
		<form id="form_voting_logs" name="form_voting_logs" method="POST" action="<?php echo admin_url().'admin.php?page=votinglogs'; ?>">
			<div class="tablenav top">
				
				<div class="alignleft actions bulkactions">
					<select name="action">
					<option selected="selected" value="-1">Bulk Actions</option>            	
						<option value="delete">Delete</option>
					</select>
					<input type="submit" value="Apply" class="button action" id="doaction" name="">
				</div>
			
				<div class="alignleft actions bulkactions">
					<select name="export" class="export_contestant">
						<option value="0"><?php _e('Select','voting-contest'); ?></option>
						<option value="CSV"><?php _e('CSV','voting-contest'); ?></option>
						<option value="html"><?php _e('HTML','voting-contest'); ?></option>
						<option value="excel_xlsx"><?php _e('Excel 2007 (XLSX)','voting-contest'); ?></option>
						<option value="excel_xls"><?php _e('Excel 2003 (XLS)','voting-contest'); ?></option>
						<option value="excel_ods"><?php _e('Open office (ODS)','voting-contest'); ?></option>
					</select>
					<input type="submit" value="Download" class="button action" id="doaction" name="">
				</div>
	
			</div>
			
			<table id="table" class="widefat manage-questions voting_logs">
			    <thead>
				    <tr>
					    <th class="manage-column" id="cb" scope="col" align="center" style="width:5%;">
						    <input type="checkbox" id="vote_delete_log_all" style="vertical-align: top; margin: 7px 0 3px 28px;" />
					    </th>  
					    <th class="manage-column column-title sortable <?php echo $log_entries['order']; ?>" id="values" scope="col" title="Click to Sort" style="width:25%;">				
						    <a href="<?php echo $voting_logs['actual_link'].'&orderby=pst.post_title&order='.$voting_logs['yet_to_order']; ?>">
							    <span><?php _e('Title','voting-contest');  ?></span>
							    <span class="sorting-indicator"></span>
						    </a>
					    </th>
					    <th class="manage-column column-title sortable <?php echo $log_entries['order']; ?>" id="values" scope="col" title="Click to Sort" style="width:15%;">
						    <a href="<?php echo $voting_logs['actual_link'].'&orderby=pst.post_author&order='.$voting_logs['yet_to_order']; ?>">
							    <span> <?php _e('Author','voting-contest'); ?></span>
							    <span class="sorting-indicator"></span>
						    </a>
						    
					    </th>
			    
					    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort"  style="width:10%;">
						    <?php _e('Voter','voting-contest'); ?>
					    </th>
						
						<th class="manage-column column-title" id="values" scope="col" title="Click to Sort"  style="width:10%;">
						    <?php _e('IP Address','voting-contest'); ?>
					    </th>
					    
					  <th class="manage-column column-title sortable <?php echo $log_entries['order']; ?>" id="values" scope="col" title="Click to Sort"  style="width:10%;">
						   <a href="<?php echo $voting_logs['actual_link'].'&orderby=log.post_email&order='.$voting_logs['yet_to_order']; ?>">
							    <span> <?php _e('Voter Email','voting-contest'); ?></span>
							    <span class="sorting-indicator"></span>
						    </a> 
						   
					    </th>
					    
					 
					    <th class="manage-column column-title sortable <?php echo $log_entries['order']; ?>" id="values" scope="col" title="Click to Sort" style="width:10%;">
						     <a href="<?php echo $voting_logs['actual_link'].'&orderby=log.date&order='.$voting_logs['yet_to_order']; ?>">
							    <span><?php _e('Vote Date','voting-contest'); ?></span>
							    <span class="sorting-indicator"></span>
						    </a>          
						    
					    </th>
					    <th class="manage-column column-title manage_cols" id="values" scope="col" title="Click to Sort"  style="width:10%;">
						    <?php _e('Delete Vote','voting-contest'); ?>
					    </th>
				    </tr>
			    </thead>
    
			    <tbody>
				<?php
				$browser_array = array (
				    'IE' => 'Internet Explorer',
				    'MF' => 'Mozilla Firefox',
				    'GC' => 'Google Chrome',
				    'AS' => 'Apple Safari',
				    'O'	 => 'Opera',
				    'N'	 => 'Netscape'
				);
			    
    
				if ( is_super_admin() ) { 
				    $logvoteentries = $voting_logs['log_entries'];
				    if (!empty($logvoteentries)) {
					$i = 0; 
					foreach ($logvoteentries as $logs) {
					    
						$tbl_id        = $logs->id;
						$vote_id       = $logs->post_id;                    
						$vote_catid    = $logs->termid;                            
						$vote_author_id= $logs->post_author;
						$vote_author   = ucfirst(get_the_author_meta( 'display_name', $vote_author_id ));
						$ip_always     = ($logs->ip_always == 0 || $logs->ip_always == null)?" - ":$logs->ip_always;
						$email_always     = $logs->email_always;						
						
						$voter_name    = $logs->ip;
						if(filter_var($voter_name, FILTER_VALIDATE_IP) !== false)
							$voter_name = $logs->ip;
						else if(count(explode('@',$voter_name)) == 2){							
							$voter_name = $logs->ip;
						}
						else if(count(explode('@',$voter_name)) > 1){
							$browser = explode('@',$voter_name);
							$voter_name = $browser_array[$browser[0]];
						}						
						else{
							$voter_name = ucfirst(get_the_author_meta( 'display_name', $voter_name ));
							$voter_email_flag = 1;
						}
						
						if($voter_email_flag == 1){
						    $voter_email = get_the_author_meta( 'user_email', $logs->ip );
						    if($voter_email == null){
								$voter_email = $voter_name;
						    }
						}
						else{
						    $voter_email = 0 ;
						}
						
						if($voter_email == 0){
							$voter_email = $email_always;
						}
						
						$vote_count    = $logs->votes;
						$voted_date    = $logs->date;
						
						$tr_class = ($i%2 == 1)?'':'alternate';         
						$i++;           					
					?>
					    <tr id="<?php echo $tbl_id ?>" class="<?php echo $tr_class; ?>">
						    
						<td align="center">
						<!--<input name="row_id" type="hidden" value="<?php echo $question_id ?>" />-->
						 <?php if($system_name != "contestant-desc" && $system_name != "contestant-title"): ?>                            
							<input  style="margin:7px 0 22px 8px; vertical-align:top;" name="checkbox[<?php echo $tbl_id ?>]" value="<?php echo $vote_id; ?>" type="checkbox" class="question_checkbox"  title="Delete <?php echo $logs->post_title; ?>" />
						 <?php endif; ?>
						</td>
												
						<td class="post-title page-title column-title"><strong><a href="post.php?post=<?php echo $vote_id ?>&action=edit"><?php echo $logs->post_title; ?></a></strong>
					
						</td>
						<td class="author column-author"><?php echo $vote_author ?></td>
						
						<td class="author column-author"><?php echo $voter_name;  ?></td>
						<td class="author column-author"><?php echo $ip_always;  ?></td>
						<td class="author column-author"><?php echo $voter_email; ?></td>
						<td class="author column-author"><?php echo $voted_date; ?></td>
						<td class="author column-author"><button class="delete_vote" id="<?php echo $tbl_id ?>" name="<?php echo $vote_id; ?>"><?php _e('Delete Vote','voting-contest'); ?></button></td>
						    
					    </tr>
					<?php
					}
				    }
				    else{
					?>
					    <tr>
						    <td colspan="7"><?php _e('No Vote Entries Found','voting-contest'); ?></td>
					    </tr>
					<?php
				    }
				}
			    ?>
			    </tbody>
			    <input type="hidden" name="page" value="votinglogs" />
    
   
			    <tfoot>
				<tr>
				    <td colspan="7">
					<?php $logs_per_page = array('10' => '10','20' => '20', '25' => '25', '50' => '50', 'all' =>'All'); ?>          
					<label><?php _e('Logs Per Page','voting-contest'); ?></label>
					<select name="logs_per_page" id="logs_per_page">
						<?php foreach($logs_per_page as $key => $logs): ?>
							<?php $selected = ($key == $_POST['logs_per_page'])?'selected':''; ?>
							<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $logs; ?></option>
						<?php endforeach; ?>
					</select>
				    </td>
				</tr>
			    </tfoot>
			</table>
			 <?php
				if ($trans_navigation) 
				{   
				   echo '<div class="tablenav top">';    
				   echo "<div class='tablenav-pages'><span class='pagination-links'>$trans_navigation</span></div>";               echo '</div>';    
				}  
				?>
				<?php if(isset($_GET['paged'])): ?>
					<input type="hidden" value="<?php echo $_GET['paged']; ?>" name="paged" id="paged" />
				<?php endif; ?>
		</form>
		
	</div>
	
	<script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#logs_per_page').change(function (){
                jQuery('#form_voting_logs').submit();
            });
            
            jQuery('#vote_delete_log_all').click(function(event) {
             if(this.checked) {
                  // Iterate each checkbox
                  jQuery(':checkbox').each(function() {
                      this.checked = true;
                  });
              }
              else {
                jQuery(':checkbox').each(function() {
                      this.checked = false;
                  });
              }
            });
	    
	    jQuery('.export_contestant').change(function (e){
		jQuery('#form_voting_logs').append("<input type='hidden' name='export_contestant' value='1' />");
	    });
            
            jQuery('.delete_vote').click(function (e){
                e.preventDefault();
                if (confirm('<?php _e("Are you sure want to delete?","voting-contest"); ?>')){
                    var tbl_id = this.id;
                    var vote_id = this.name;                  
                    jQuery('#form_voting_logs').append("<input type='hidden' name='delete_tbl_id' value="+tbl_id+" ><input type='hidden' name='delete_vote_id' value="+vote_id+" >");
                    jQuery('#form_voting_logs').submit();
			    
                }else{
                     return true;
                }
               
            });
        });
	</script>
    <?php
    }
}else{
    die("<h2>".__('Failed to load Voting log view','voting-contest')."</h2>");
}
?>
