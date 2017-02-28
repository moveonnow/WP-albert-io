<?php
if(!function_exists('ow_votes_clear_votes_view')){
    function ow_votes_clear_votes_view(){
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
	?>
	<div class="wrap">   
		<h2><?php _e('Clear Voting Entries','voting-contest'); ?></h2>
		<div class="narrow">
			<form action="<?php echo admin_url().'admin.php?page=votes_purge'; ?>" method="post" name="votes_delete_form" id="votes_delete_form">
				<p> <?php _e('Select the Contestants Category to Delete the Vote.','voting-contest'); ?></p>
				<p style="color: #ff0000;"> <?php _e('Note : If you do not select a Contestant Category, all votes will be deleted from all Contest Categories.','voting-contest'); ?></p>
				<table class="form-table"> 
					
					<tr valign="top">
						<th scope="row">  <?php _e('Select the Contest','voting-contest'); ?>  </th>
						<td>   
							<?php
							wp_dropdown_categories(array('hide_empty' => true,
								'name' => 'vote_contest_term',
								'id' => 'vote_contest_term',
								'hierarchical' => 1,
								'show_count' => 1,
								'taxonomy' => OW_VOTES_TAXONOMY,
								'show_option_none' => __('Select the Category','voting-contest')));
							?>
						</td>
					</tr>
				</table>
				<p class="submit">
				<input type="hidden" id="votes_delete" name="votes_delete" value="<?php _e('Delete','voting-contest'); ?>" />
				<input type="submit" value="<?php _e('Delete','voting-contest'); ?>" class="button" id="votes_delete_btn" name="votes_delete_btn" /></p>
			</form>
		</div>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function () {                        
			jQuery('#votes_delete_btn').click(function (e){
				e.preventDefault();
				if (confirm('<?php _e("Are you sure want to delete?","voting-contest"); ?>')){                      
					jQuery('#votes_delete_form').submit();    	    
				}else{
					 return true;
				}
			   
			});
		});
	</script>

	<?php
    }
}else{
    die("<h2>".__('Failed to load Voting Clear Votes view','voting-contest')."</h2>");
}
?>
