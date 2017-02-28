<?php
if(!function_exists('ow_voting_email_form')){
    function ow_voting_email_form($votes_settings){	    
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
				    <h3 class="m_title"><?php _e("CLICK SEND TO RECIEVE ACTIVATION CODE TO YOUR EMAIL ADDRESS",'voting-contest');?></h3>
				    <?php $disabled = "readonly";$current_user = wp_get_current_user();	$email = $current_user->user_email; ?>
				<?php else: ?>
				    <h3 class="m_title"><?php _e("ENTER YOUR EMAIL ADDRESS TO SEND ACTIVATION CODE",'voting-contest');?></h3>
				    <?php $disabled = $email  = ""; ?>
				<?php endif; ?>
				
				<form id="login_form" name="login_form" method="post" class="zn_email_verification" action="<?php echo site_url('wp-login.php', 'login_post') ?>">			
					<div>
					    <input type="text" name="ow_voting_email" class="inputbox ow_voting_email" value="<?php echo $email; ?>" placeholder="<?php _e("Email",'voting-contest');?>" <?php echo $disabled; ?>>
					</div>
					
					<?php do_action('ow_voting_email_form');?>			
										
					<div class="ow_email_button">
						<input type="submit" name="submit_button" class="zn_sub_button" value="<?php _e("SEND",'voting-contest');?>">
					</div>
					
					<input type="hidden" value="login" class="" name="zn_form_action">
					<input type="hidden" value="voting_email_verification" class="" name="action">
					<input type="hidden" value="<?php echo $current_url; ?>" class="zn_login_redirect" name="submit">
					
				</form>				
								
				<div class="clear"></div>
			    </div>
				<div class="ow_voting_verification_code_div">
				    <h3 class="m_title"><?php _e("ENTER YOUR ACTIVATION CODE SENT TO YOUR EMAIL",'voting-contest');?></h3>
				    <form id="login_form" name="login_form" method="post" class="zn_email_verification_code" action="<?php echo site_url('wp-login.php', 'login_post') ?>">			
					    <div>
						    <input type="text" maxlength=6 name="ow_voting_verifcation_code" class="inputbox ow_voting_verifcation_code" placeholder="<?php _e("Email Verification Code",'voting-contest');?>">
					    </div>
					    
					    <?php do_action('ow_voting_verification_code');?>			
										    
					    <div class="ow_email_button">
						    <input type="submit" name="submit_button" class="zn_sub_button" value="<?php _e("SUBMIT",'voting-contest');?>">
					    </div>
					    
					    <input type="hidden" value="login" class="" name="zn_form_action">
					    <input type="hidden" value="voting_email_verification" class="" name="action">
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
