<?php
if(!function_exists('ow_voting_email_form')){
    function ow_voting_email_form($votes_settings){	    
		wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array( 'votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH  ) );	
		if (is_ssl())
			$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		else
			$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		?>		
		
		<?php
			$public_ley = '6Lf25hYUAAAAAEpdC5xON4INOq5YgbFgcIrm4l6l';
			$secret_key = '6Lf25hYUAAAAAFngEzG2VPIaN3gG48gnIlWjJqb0';
		?>  

		<div id="ow_vote_email_panel"> 
			<div class="inner-container login-panel"> <div class="g-recaptcha" data-sitekey="<?php echo $public_ley; ?>"></div>
			    <div class="ow_voting_verification">
				<?php if($votes_settings['onlyloggedinuser'] == 'on'): ?>
				    <h3 class="m_title"><?php _e("CLICK SEND TO RECIEVE ACTIVATION CODE TO YOUR EMAIL ADDRESS",'voting-contest');?></h3>
				    <?php $disabled = "readonly";$current_user = wp_get_current_user();	$email = $current_user->user_email; ?>
				<?php else: ?>
				    <h3 class="m_title"><?php _e("Enter your email address to send activation code",'voting-contest');?></h3>

				    <?php $disabled = $email  = ""; ?>
				<?php endif; ?>

				<form id="login_form" name="login_form" method="post" class="zn_email_verification" action="<?php echo site_url('wp-login.php', 'login_post') ?>">			
					<div>
					    <input type="text"  name="ow_voting_email" class="ow_voting_email" value="<?php echo $email; ?>" placeholder="<?php _e("Email",'voting-contest');?>" <?php echo $disabled; ?>>
						
						 

						<?php  /*  mod_start  */  ?>

					    <div class="ow_voting_checkbox_area">
					    
					    <?php

					    	$is_multiple = get_field('email_form_items_is_multi', 'option');

					    	if( have_rows('email_form_items', 'option') ): 

						    while( have_rows('email_form_items', 'option') ): the_row(); 

							    if ($is_multiple[0] === 'yes') {

							    		?>
							        	<div class="ow_voting_checkbox_div"><input type="checkbox" name="slist_<?php the_sub_field('email_form_items_name'); ?>" id="slist_<?php the_sub_field('email_form_items_name'); ?>" class=" slist_checkbox ow_voting_checkbox" value="value"><label for="slist_<?php the_sub_field('email_form_items_name'); ?>"><?php the_sub_field('email_form_items_label'); ?></label></div>
							        	<?php
							    }
							    else{

							        	?>
							        	<div class="ow_voting_checkbox_div"><input type="radio" name="slist_radio" id="slist_<?php the_sub_field('email_form_items_name'); ?>" class="ow_voting_checkbox slist_radio" value="slist_<?php the_sub_field('email_form_items_name'); ?>"><label for="slist_<?php the_sub_field('email_form_items_name'); ?>"><?php the_sub_field('email_form_items_label'); ?></label></div>
							        	<?php

							    }

							endwhile; 

							endif; 

						?>

						</div>

						<?php  /*  mod_end  */  ?>





					</div>
					
					<?php do_action('ow_voting_email_form');?>			
					
					<div class="ow_voting_acceptance">
						<?php the_field('acceptance_message', 'option'); ?>
						<div class="ow_voting_acceptance_div"><input type="checkbox" name="acceptance_checkbox" id="acceptance_checkbox_id" class="ow_voting_checkbox" value="value"><label for="checkbox_id">Accept</label></div>
					</div>




					<?php  /*  mod_start  */  ?>

					<div id="new_capch" class="inner-container"></div>
					
					<?php  /*  mod_end  */  ?>





					<div class="ow_email_button">
						<input type="submit" name="submit_button" class="zn_sub_button explore-button btn btn-secondary" value="<?php _e("SEND",'voting-contest');?>">
					</div>
					
					<input type="hidden" value="login" class="" name="zn_form_action">
					<input type="hidden" value="voting_email_verification" class="" name="action">
					<input type="hidden" value="<?php echo $current_url; ?>" class="zn_login_redirect" name="submit">
					
				</form>				
								
				<div class="clear"></div>
			    </div>
				<div class="ow_voting_verification_code_div">
				    <h3 class="m_title"><?php _e("Enter your activation code sent to your email",'voting-contest');?></h3>

				    <form id="login_form" name="login_form" method="post" class="zn_email_verification_code" action="<?php echo site_url('wp-login.php', 'login_post') ?>">			
					    <div>
						    <input type="text" maxlength=6 name="ow_voting_verifcation_code" class="ow_voting_verifcation_code" placeholder="<?php _e("Email Verification Code",'voting-contest');?>">
					    </div>
					    
					    <?php do_action('ow_voting_verification_code');?>			
										    
					    <div class="ow_email_button">
						    <input type="submit" name="submit_button" class="zn_sub_button explore-button btn btn-secondary" value="<?php _e("SUBMIT",'voting-contest');?>">
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
