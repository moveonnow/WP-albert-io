<?php
if(!function_exists('ow_votes_contestant_move_view')){
    function ow_votes_contestant_move_view(){
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
	
	wp_register_script('ow_vote_contestant', OW_ASSETS_JS_PATH . 'ow_vote_contestant.js');
	wp_enqueue_script('ow_vote_contestant',array('jquery'));
	?>
	<div class="wrap">
	    <input type="hidden" name="required_missing_text" id="required_missing_text" value="<?php _e('Required Field Missing','voting-contest')?>" />
	    <h2><?php _e('Move Contestants','voting-contest'); ?></h2>
	    <div class="narrow">
		<form method="post" enctype="multipart/form-data" name="move_contest_form" id="move_contest_form">
		    <table class="form-table"> 
			<tr valign="top">
			    <th scope="row"><label class="move_contestant" for="existing_contest_term"><?php _e('Move Contestants from this Category: ','voting-contest'); ?></label></th>
			    <td>
				<?php
				    wp_dropdown_categories(array('hide_empty' => true,
				    'name' => 'existing_contest_term',
				    'id' => 'existing_contest_term',
				    'hierarchical' => 1,
				    'show_count' => 1,
				    'taxonomy' => OW_VOTES_TAXONOMY,
				    'show_option_none' => __('Select the Category','voting-contest')));
				?>
			    </td>
			</tr>
	
			<tr valign="top">
			    <th scope="row"><label class="move_contestant" for="selected_term_post"><?php _e('List of Contestants in Category','voting-contest'); ?></label></th>
			    <td>
				<div id="selected_term_post_listing" style="max-height: 200px; overflow: auto; padding-top: 10px;">
				    <?php _e('Select the Category to get Contestants','voting-contest'); ?>
				    <input type="hidden" name="selected_post[]" class="selected-post" value="-1" />
				</div>
			    </td>
			</tr>
			<tr valign="top">
			    <th scope="row"><label class="move_contestant" for="mapped_contest_term"><?php _e('To Category ','voting-contest'); ?></label></th>
			    <td>
				<?php
				    wp_dropdown_categories(array('hide_empty' => false,
				    'name' => 'mapped_contest_term',
				    'id' => 'mapped_contest_term',
				    'hierarchical' => 1,
				    'show_count' => 1,
				    'taxonomy' => OW_VOTES_TAXONOMY,
				    'show_option_none' => __('Select the Category','voting-contest')));
				?>
			    </td>
			</tr>
		    </table>
		    <p class="submit"><input type="submit" value="<?php _e('Move Contestants','voting-contest'); ?>" class="button" id="move_contest_submit" name="move_contest_submit" /></p>
		</form>
	    </div>
	</div>
	<?php
    }
}else{
    die("<h2>".__('Failed to load Voting contestant move view','voting-contest')."</h2>");
}
?>
