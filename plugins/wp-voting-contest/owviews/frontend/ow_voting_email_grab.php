<?php
if(!function_exists('ow_voting_email_grab')){
    function ow_voting_email_grab($votes_settings){	    
		wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array( 'votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH  ) );	
		if (is_ssl())
			$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		else
			$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		?>		
		<div id="ow_vote_email_panel">
			<div class="inner-container login-panel">
			    <div class="ow_voting_verification">
					
				<?php if($votes_settings['onlyloggedinuser'] == 'on'): ?>				    
				    <?php $disabled = "readonly";$current_user = wp_get_current_user();	$email = $current_user->user_email; ?>
				<?php else: ?>				    
				    <?php $disabled = $email  = ""; ?>
				<?php endif; ?>
				
				<h3 class="m_title"><?php _e("ENTER YOUR EMAIL ADDRESS",'voting-contest');?></h3>
				
				<form id="login_form" name="login_form" method="post" class="zn_email_grab" action="<?php echo site_url('wp-login.php', 'login_post') ?>">			
					<div>
					    <input type="text" name="ow_voting_email_grab" class="inputbox ow_voting_email_grab" value="<?php echo $email; ?>" placeholder="<?php _e("Email",'voting-contest');?>" <?php echo $disabled; ?>>
					</div>
					
					<?php do_action('ow_voting_email_grab_form');?>			
										
					<div class="ow_email_button">
						<input type="submit" name="submit_button" class="zn_sub_button" value="<?php _e("VOTE",'voting-contest');?>">
					</div>
					
					<input type="hidden" value="login" class="" name="zn_form_action">
					<input type="hidden" value="ow_voting_grab_email" class="" name="action">
					<input type="hidden" value="<?php echo $current_url; ?>" class="zn_login_redirect" name="submit">
					
				</form>				
								
				<div class="clear"></div>
			    </div>
				
				
			</div>
		</div>
		<?php
    }
}else{
    die("<h2>".__('Failed to load Voting Email Login View','voting-contest')."</h2>");
}
?>
