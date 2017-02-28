<?php
if(!function_exists('ow_voting_user_login_view')){
    function ow_voting_user_login_view($votes_settings,$custom_field){
		wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array( 'votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH  ) );	
		if (is_ssl())
			$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		else
			$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		?>		
		<div id="ow_vote_login_panel">
			<div class="inner-container login-panel">
			    
				<div class="ow_tabs">
					<div class="ow_tab_buttons">
						<a href="javascript:void(0)" class="ow_tabs_login"><?php _e("LOGIN",'voting-contest'); ?></a>
				     <?php
				     if(!$votes_settings['vote_hide_account'] == 'on'){
				     if( get_option('users_can_register') ) { ?>
					    <a href="javascript:void(0)" class="ow_tabs_register"><?php _e("CREATE ACCOUNT",'voting-contest'); ?></a>					  <?php } }?>
					</div>	    
					
					<div class="clearfix"></div>
					
				    <div class="ow_tabs_content">
					<div class="ow_tabs_login_content">
					    <h3 class="m_title"><?php _e("YOU MUST BE REGISTERED AND LOGGED TO CONTINUE",'voting-contest');?></h3>
				
					    <form id="login_form" name="login_form" method="post" class="zn_form_login" action="<?php echo site_url('wp-login.php', 'login_post') ?>">
					    
						   
						    <div>
							    <input type="text" name="log" class="inputbox" placeholder="<?php _e("Username",'voting-contest');?>">
						    </div>
						    <div>
							    <input type="password" name="pwd" class="inputbox" placeholder="<?php _e("Password",'voting-contest');?>">
						    </div>
						    <?php do_action('login_form');?>
						    <div class="remember_style">
							    <label class="zn_remember"><input type="checkbox" name="rememberme" value="forever"><?php _e(" Remember Me",'voting-contest');?></label>
						    </div>
						    
						    <div>
							    <input type="submit" name="submit_button" class="zn_sub_button" value="<?php _e("LOG IN",'voting-contest');?>">
						    </div>
						    
						    <input type="hidden" value="login" class="" name="zn_form_action">
						    <input type="hidden" value="ow_vote_pretty_login" class="" name="action">
						    <input type="hidden" value="<?php echo $current_url; ?>" class="zn_login_redirect" name="submit">
						    <div class="links"><a href="javascript:void(0)" onClick="ow_vote_ppOpen('#ow_vote_forgot_panel', '300');"><?php _e("FORGOT YOUR PASSWORD?",'voting-contest');?></a></div>
					    </form>
					    
					    <?php 
					    
					    if($votes_settings['twitter_login'] == 'on' || $votes_settings['facebook_login'] == 'on'){
						echo "<p class='ow_social_text'>".__('Login with : ','voting-contest')."</p>";
					    }
					    
					    if($votes_settings['facebook_login'] == 'on'):
						    if(!is_user_logged_in()):    
						    ?>  
						    <div class="ow_voting_facebook_login">
							    <input type="hidden" name="vote_fb_image" id="vote_fb_image" value="<?php echo OW_ASSETS_IMAGE_PATH.'sign-in-with-fb-gray.png'; ?>" />
							    <input type="hidden" name="vote_fb_appid" id="vote_fb_appid" value="<?php echo $votes_settings['vote_fb_appid']; ?>" />
							    <!--<fb:login-button scope="public_profile,email" onlogin="checkLoginState();" class="fb_login_button">
							    </fb:login-button>
							    -->
						    </div>
						    <?php endif; ?>
					    <?php endif; ?>                    
					    <?php
					    if($votes_settings['twitter_login'] == 'on'):
						    if(!is_user_logged_in()):                                      
						    ?>  
						    <div class="voting_twitter_login">                  
							    <input type="hidden" name="vote_tw_appid" id="vote_tw_appid" value="<?php echo $votes_settings['vote_tw_appid']; ?>" />     
							    <input type="hidden" name="vote_tw_secret" id="vote_tw_secret" value="<?php echo $votes_settings['vote_tw_secret']; ?>" />  
							    <input type="hidden" name="current_callback_url" id="current_callback_url" value="<?php echo get_permalink(); ?>" /> 
							    <a href="javascript:voting_save_twemail_session()" title="<?php _e('Sign in with Twitter','voting-contest'); ?>">
								    <img src="<?php echo OW_ASSETS_IMAGE_PATH.'sign-in-with-twitter-gray.png'; ?>" alt="sign-in-with-twitter-gray" />
							    </a>
																			     
						    </div>
						    <?php endif; ?>
					    <?php endif; ?>
					    
					    <div class="clear"></div>					    
					</div>
					<div class="ow_tabs_register_content">
					    <?php echo ow_voting_user_registration_view($votes_settings,$custom_field); ?>					    
					</div>
				    </div>
				</div>
			    
				
				
			</div>
		</div>
		<?php
    }
}else{
    die("<h2>".__('Failed to load Voting User Login View','voting-contest')."</h2>");
}

if(!function_exists('ow_voting_user_registration_view')){
    function ow_voting_user_registration_view($votes_settings,$custom_field){
		wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array( 'votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH  ) );
		if (is_ssl())
			$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		else
			$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		
		if($votes_settings['twitter_login'] == 'on'){
            if(!is_user_logged_in()){ 
		?>
			<div id="twitter_register_panel">
			    <div class="inner-container forgot-panel">
			        <h3 class="m_title"><?php _e("ENTER YOUR EMAIL ADDRESS",'voting-contest');?></h3>		
					<form id="twitter_save_form" name="twitter_save_form" method="post" class="zn_form_save_email">
							<p>
								<input type="text" id="user_email_save" name="user_login" class="inputbox" placeholder="<?php _e("E-mail",'voting-contest');?>"/>
							</p>                                               
							<p>
								<input type="submit" id="check_email" name="submit" class="zn_sub_button" value="<?php _e("CONTINUE",'voting-contest');?>">
							</p>    					
					</form>
					<div class="links">
							<a href="#" class="ow_tabs_already" onClick="ow_vote_ppOpen('#ow_vote_login_panel', '300');"><?php _e("RETURN BACK!",'voting-contest');?></a>
					</div>
                
                </div>
            </div>
            <?php
			}
		}?>
		
		<?php if( get_option('users_can_register') ) {
			?>
		<div id="ow_vote_register_panel">
			
			<div class="inner-container register-panel">
				<h3 class="m_title"><?php _e("CREATE ACCOUNT",'voting-contest');?></h3>
				
				<form id="register_form" name="login_form" method="post" class="zn_form_login" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>">
					<div class="register-panel_inner">
						<label>
							<strong>
							<?php _e('Username','voting-contest'); ?></strong>
							<span class="required-mark">*</span>
						</label>
						<p>
							<input type="text" id="reg-username" name="user_login" class="inputbox required_vote_custom" placeholder="<?php _e("Username",'voting-contest');?>" />
						</p>
					</div>
                            
                    <div class="register-panel_inner">
						<label>
							<strong><?php _e('Email Address','voting-contest'); ?></strong>
							<span class="required-mark">*</span>
						</label>
						<p>
							<input type="text" id="reg-email" name="user_email" class="inputbox required_vote_custom" placeholder="<?php _e("Your email",'voting-contest');?>" />
						</p>
                    </div>
                            
                    <div class="register-panel_inner">
                        <label>
							<strong><?php _e('Password','voting-contest'); ?></strong>
							<span class="required-mark">*</span>
						</label>
						<p>
							<input type="password" id="reg-pass" name="user_password" class="inputbox required_vote_custom" placeholder="<?php _e("Your password",'voting-contest');?>" />
						</p>
                    </div>
                            
                    <div class="register-panel_inner">
						<label>
							<strong><?php _e('Confirm Password','voting-contest'); ?></strong>
							<span class="required-mark">*</span>
						</label>
						<p>
							<input type="password" id="reg-pass2" name="user_password2" class="inputbox required_vote_custom" placeholder="<?php _e("Verify password",'voting-contest');?>" />
						</p>
                    </div>
					
					<?php
					if(!empty($custom_field)){
						foreach($custom_field as $custom_fields){
							if($custom_fields->admin_only == "Y"){  
							?>
							<div class="register-panel_inner">
								<label>
									<strong><?php echo $custom_fields->question; ?></strong>
									<?php if($custom_fields->required=='Y') {?>
									<span class="required-mark">*</span>
									<?php } ?>
								</label>
								<p>
									
									<?php
									if($custom_fields->required=='Y'){$class="required_vote_custom";}else{$class="";}
									switch($custom_fields->question_type){
										
										case 'TEXT':
											?>
											 <input id="<?php echo $custom_fields->system_name; ?>" type="<?php echo strtolower($custom_fields->question_type); ?>" class="inputbox <?php echo $class; ?>" name="<?php echo $custom_fields->system_name; ?>" placeholder="<?php _e($custom_fields->question);?>"/>
											<?php
										break;
									
										case 'TEXTAREA':
										?>
										<textarea rows="1" id="<?php echo $custom_fields->system_name; ?>" placeholder="<?php _e($custom_fields->question);?>" name="<?php echo $custom_fields->system_name; ?>" class="<?php echo $class; ?>" ></textarea>	
										<?php
										break;
									
										case 'SINGLE':
											$values = explode(',',$custom_fields->response);
											foreach($values as $val){
											?>   
											<span class="add_contestant_radio"> 
											<input class="<?php echo $class; ?> reg_radio_<?php echo $custom_fields->system_name; ?>" type="radio" name="<?php echo $custom_fields->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_fields->system_name; ?>" /> <span class="question_radio <?php echo $custom_fields->system_name; ?>" ><?php echo $val; ?></span>
											</span> 
											<?php } 
										break;
										
										case 'MULTIPLE':
											$values = explode(',',$custom_fields->response);
											foreach($values as $val){
											?>
											<span class="add_contestant_radio"> 
											<input class="<?php echo $class; ?> reg_check_<?php echo $custom_fields->system_name; ?>" type="checkbox"  name="<?php echo $custom_fields->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_fields->system_name; ?>" />
											<span class="question_check <?php echo $custom_fields->system_name; ?>" ><?php echo $val; ?></span>  </span> 
											<?php }
										break;
									
										case 'DROPDOWN':
											$values = explode(',',$custom_fields->response); ?>
											<select class="<?php echo $class; ?>" style="width: 100%;padding: 0.428571rem;border: 1px solid #CCCCCC;border-radius: 3px 3px 3px 3px;" name="<?php echo $custom_fields->system_name; ?>" id="<?php echo $custom_fields->system_name; ?>">
											<option value=""><?php _e('Select','voting-contest'); ?></option>
											<?php foreach($values as $val){ ?>
												  <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
											<?php } ?>
											</select>
											<?php
										break;
									    
									        case 'DATE':
											?>
											 <input id="<?php echo $custom_fields->system_name; ?>" type="text" class="inputbox <?php echo $class; ?> date_picker"  name="<?php echo $custom_fields->system_name; ?>"  placeholder="<?php _e($custom_fields->question);?>"/>
											<?php if($custom_fields->required=='Y'){?>
											<?php
											}
										break;
									}
									?>
									
								</p>
							</div>
							<?php
							}
						}
					}
					?>
					<script>
					     jQuery(document).ready(function(){
							var valid = true;			    
							jQuery('.date_picker').owvotedatetimepicker({
								format:'m-d-Y',
								step:10,
								timepicker: false,
				            });
						 });
					</script>
					
					<div class="owt_other_register_fields">
					    <?php do_action( 'register_form' ); ?>
					</div>
					<?php
					?>
					<div class="ow_register_submit">
					<input type="submit" id="signup" name="submit" class="zn_sub_button" value="<?php _e("CREATE MY ACCOUNT",'voting-contest');?>"/>
					</div>
					<input type="hidden" value="register" class="" name="zn_form_action">
					<input type="hidden" value="ow_vote_pretty_login" class="" name="action">					
					<input type="hidden" value="<?php echo $current_url; ?>" class="zn_login_redirect" name="submit">
					<div class="links"><a class="ow_tabs_already" href="javascript:void(0)"><?php _e("ALREADY HAVE AN ACCOUNT?",'voting-contest');?></a></div>
				</form>
			</div>
		</div>
		<?php } ?>
		
		<?php
    }
}else{
    die("<h2>".__('Failed to load Voting User Registration View','voting-contest')."</h2>");
}

if(!function_exists('ow_voting_user_forget_view')){
    function ow_voting_user_forget_view(){
		?>
		<div id="ow_vote_forgot_panel">
    			<div class="inner-container forgot-panel">
    				<h3 class="m_title"><?php _e("FORGOT YOUR DETAILS?",'voting-contest');?></h3>
    				<form id="forgot_form" name="login_form" method="post" class="zn_form_lost_pass" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>">
    					<p>
						<input type="text" id="forgot-email" name="user_login" class="inputbox" placeholder="<?php _e("Username or E-mail",'voting-contest');?>"/>
    					</p>
                                               
    					<p>
    						<input type="submit" id="recover" name="submit" class="zn_sub_button" value="<?php _e("SEND MY DETAILS!",'voting-contest');?>">
    					</p>
    					<div class="links"><a href="javascript:void(0)" onClick="ow_vote_ppOpen('#ow_vote_login_panel', '300');"><?php _e("AAH, WAIT, I REMEMBER NOW!",'voting-contest');?></a></div>
    				</form>
    				
    			</div>
		</div>
		<?php
	}
}else{
    die("<h2>".__('Failed to load Voting User Forget View','voting-contest')."</h2>");
}
?>
