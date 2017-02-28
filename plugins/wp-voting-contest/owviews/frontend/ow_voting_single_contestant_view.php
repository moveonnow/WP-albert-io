<?php
if(!function_exists('ow_voting_single_contestant_view')){
    function ow_voting_single_contestant_view($option){
		
		do_action('single_contestants_head');
		
		global $wpdb,$post;
		add_action('wp_head', 'ow_sharing_contestant_function');
		get_header();
		
		$global_options = Ow_Vote_Common_Controller::ow_vote_get_all_global_settings($option);
		if(!empty($global_options)){
			foreach($global_options as $variab => $glob_opt){
				$$variab = $glob_opt;
			}
		}		
		
		if($onlyloggedinuser!='' && !is_user_logged_in()){
			Ow_Vote_Shortcode_Controller::ow_votes_custom_registration_fields_show();
		}

		
		
		wp_enqueue_script("jquery");
		
		wp_register_style('OW_FRONT_CONTESTANT_STYLES', OW_ASSETS_FRONT_END_CSS_PATH);
		wp_enqueue_style('OW_FRONT_CONTESTANT_STYLES');
		
		
		wp_register_style('OW_FRONT_COLOR', OW_ASSETS_COLOR_RELPATH);
		wp_enqueue_style('OW_FRONT_COLOR');
		
		
		wp_register_script('ow_votes_block', OW_ASSETS_JS_PATH . 'ow_vote_block_div.js');
		wp_enqueue_script('ow_votes_block',array('jquery'));	
		
		if($option['vote_disable_jquery_pretty']!='on'){
			wp_register_style('ow_vote_css_pretty', OW_ASSETS_CSS_PATH.'ow_vote_prettyPhoto.css');
			wp_enqueue_style('ow_vote_css_pretty');
		
			wp_register_script('ow_votes_pretty', OW_ASSETS_JS_PATH . 'ow_vote_prettyPhoto.js');
			wp_enqueue_script('ow_votes_pretty',array('jquery'));
		}
		
		if($option['vote_disable_jquery_fancy']!='on'){
			wp_register_style('ow_vote_css_fancy_box', OW_ASSETS_CSS_PATH.'ow_vote_fancybox.css');
			wp_enqueue_style('ow_vote_css_fancy_box');
		
			wp_register_script('ow_vote_fancy_box', OW_ASSETS_JS_PATH . 'ow_vote_fancybox.js');
			wp_enqueue_script('ow_vote_fancy_box',array('jquery'));
		}
		
		wp_register_script('ow_fb_script_js', OW_ASSETS_JS_PATH . 'ow_votes_fbscript.js');
		wp_enqueue_script('ow_fb_script_js',array('jquery'));
		
		wp_register_script('ow_votes_shortcode', OW_ASSETS_JS_PATH . 'ow_vote_shortcode_jquery.js');
		wp_enqueue_script('ow_votes_shortcode',array('jquery'));
		
		wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array('votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH ) );
		
		$votes_view = get_post_meta($post->ID, OW_VOTES_VIEWS, true);
		update_post_meta($post->ID, OW_VOTES_VIEWS, $votes_view + 1);
		
		//For css design stuffs
		if($vote_sidebar=='on')
			$align_center = 'ow_align_center';
		elseif($vote_select_sidebar=='')
			$align_center = 'ow_align_center';
		else
			$align_center = 'ow_no_align_center';

		$post_id = $post->ID;
		$terms = get_the_terms($post_id, OW_VOTES_TAXONOMY);
		
		foreach ($terms as $term) {
			$termids[] = $term->term_id;
			$term_id = $term->term_id;				
			$cat_name    = $term->name;
		}
		$category_options = get_option($term_id . '_' . OW_VOTES_SETTINGS);
		
		$enc_termid = Ow_Vote_Common_Controller::ow_voting_encrypt($term_id);
		$enc_postid = Ow_Vote_Common_Controller::ow_voting_encrypt(get_the_ID());
		
		$main_navigation = $category_options['middle_custom_navigation'];
		$permalink = $_SESSION['ow_voting_page_permalink'];
		
		if (false !== strpos($permalink,'?'))
			$url_prefix = '&amp;';
		else
			$url_prefix = '?';
		
		
		if($category_options['vote_contest_rules']==''){
			$no_border = 'ow_vote_no_border';
		}else{
			$no_border = '';
		}
		
		if(isset($global_options['single_contestants_video_width'])){
		    $video_width = $global_options['single_contestants_video_width'];
		}
		
		if(isset($global_options['single_page_title'])){
		    $single_title_position = $global_options['single_page_title'];
		}
		
				
		
		if($category_options['imgcontest']=='music'){
			wp_register_style('ow_vote_css_media', OW_ASSETS_CSS_PATH.'ow_audio-js.css');
			wp_enqueue_style('ow_vote_css_media');
			
			wp_register_style('ow_vote_css_media_hu', OW_ASSETS_CSS_PATH.'skins/ow_hu.css');
			wp_enqueue_style('ow_vote_css_media_hu');
			
			wp_register_style('ow_vote_css_media_tube', OW_ASSETS_CSS_PATH.'skins/ow_tube.css');
			wp_enqueue_style('ow_vote_css_media_tube');
			
			wp_register_style('ow_vote_css_media_vim', OW_ASSETS_CSS_PATH.'skins/ow_vim.css');
			wp_enqueue_style('ow_vote_css_media_vim');
		}
		?>

		<section class="ow_vote_single_section">
			<div class="ow_vote_single_container">
				<div class="ow_contestant_values <?php echo $align_center; ?>">
				
					<div class="ow_vote_contest_top_bar">
						<div class="ow_tog menudiv">
							<a href="javascript:" class="togglehide"><span class="ow_vote_icons votecontestant-menu-down"></span></a>
						</div>
						<ul class="ow_vote_menu_links">
							<li class="ow_vote_navmenu_link">
								<a href="<?php echo ($main_navigation!='')?$main_navigation:$permalink;?>">
									<span class="ow_vote_icons voteconestant-camera"></span><?php _e('Gallery','voting-contest'); ?>
								</a>
							</li>
							
							<?php if($category_options['top_ten_count']!='') { ?>
							<li class="ow_vote_navmenu_link <?php echo $no_border; ?>" >
								<a href="<?php echo $permalink.$url_prefix.'contest=topcontestant&amp;contest_id='.base64_encode($term_id); ?>">
									<span class="ow_vote_icons voteconestant-star"></span><?php _e('Top 10','voting-contest'); ?>
								</a>
							</li>
							<?php } ?>
							<?php
							if($category_options['vote_contest_rules']!=''){ ?>
								<li class="ow_vote_navmenu_link ow_vote_no_border">
								<a href="<?php echo $permalink.$url_prefix.'contest=contestrules&amp;contest_id='.base64_encode($term_id); ?>">
								<span class="ow_vote_icons voteconestant-gift"></span><?php _e('Rules and Prizes','voting-contest'); ?>
								</a>
								</li>
							<?php } ?>
							
							<li class="ow_vote_float_right ow_vote_no_border">
								<span class="category_head"><?php _e("Category : ","voting-contest"); ?></span>
								<span class="single-category_head"><a href="<?php echo $main_navigation; ?>"><?php echo $cat_name; ?></a></span>
							</li>
							
						</ul>
						
					</div>
					
					<?php
						$image_contest = $category_options['imgcontest'];
						$video_class = ($image_contest =='video')?'ow_video_content_desc':'';							
						$authordisplay = $category_options['authordisplay'];
						$authornamedisplay = $category_options['authornamedisplay'];
					?>
					
					<div class="ow_vote_content_container <?php echo $video_class; ?>">
					
						<?php
							//Check Contestant is Ended and Hide the Vote Button
							$votes_start_time=get_option($term_id . '_' . OW_VOTES_TAXSTARTTIME);
							$votes_end_time  = get_option($term_id. '_' . OW_VOTES_TAXEXPIRATIONFIELD);
							$current_time = current_time( 'timestamp', 0 );
							
							//StartTime Limit
							if($votes_start_time !='' && strtotime($votes_start_time) > $current_time){
							echo "<input type='hidden' id='ow_contest_closed_".$term_id."' value='start' />";
							$closed_desc = $global_options['vote_tobestarteddesc'];
							echo "<input type='hidden' class='ow_contest_closed_desc' value='".$closed_desc."' />";
							}
							
							$exipration = $term_id. '_' . OW_VOTES_TAXEXPIRATIONFIELD;
							$dateexpiry =  get_option($exipration);
							$cur_time = current_time( 'timestamp', 0 );
							if($dateexpiry==''){
								$dateexpiry = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
							}
						if(strtotime($dateexpiry) >= $cur_time){
						?>
						<div class="ow_vote_btn_container">
							<div class="ow_voting_left"> 	    
								<?php echo Ow_Vote_Single_Contestants::vote_previous_post_link('%link', '%title',true,$termids); ?>	    
							</div>
							
							<div class="ow_voting_button_now">	     	     
								
							    <?php	
							    $email_class= "";
								
								//Grab Email Address for IP and COOKIE
								if($vote_grab_email_address == "on" && $vote_tracking_method != 'email_verify'){
									Ow_Vote_Shortcode_Controller::ow_voting_email_grab();
									$email_class = "ow_voting_grab"; 
								}
								
							    //Vote Settings
							    if($vote_tracking_method == 'cookie_traced'){
								    $browseragent = Ow_Vote_Save_Controller::ow_cookie_voting_getBrowser();               
								    $voter_cookie = $browseragent['name'].'@'.$term_id.'@'.get_the_ID();          
								    $ip = $voter_cookie;
							    }
							    else if($vote_tracking_method == 'email_verify'){
								   $email_class = "ow_voting_email"; 
								   if(is_user_logged_in() && $global_options['onlyloggedinuser'] == 'on'){
									$current_user = wp_get_current_user();
									$ip = $current_user->user_email;
									if(isset($_SESSION['votes_current_email']) && isset($_SESSION['votes_random_string'])){
									    $email_class = "";
									}
								   }
								   else{
									if(isset($_SESSION['votes_current_email']) && isset($_SESSION['votes_random_string'])){
									    $ip = $_SESSION['votes_current_email'];
									    $email_class = "";
									}
									else
									    $ip = "";				
								   }
								   Ow_Vote_Shortcode_Controller::ow_votes_custom_email_form();
							    }
							    else{ 
							       if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') {
								    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
								    } else {
								    $ip = $_SERVER['REMOTE_ADDR'];
							       }
							    }
							    
							    
							    if($onlyloggedinuser!='' && !is_user_logged_in()){
								    $ow_login_class="ow_logged_in_enabled";
							    }
							    else{
									$ow_login_class="ow_loggin_disabled";
								}
								    
							    if(strtotime($dateexpiry) >= $cur_time){
								    if(is_user_logged_in()){
										    $user_id = get_current_user_id();
										    $ip = $user_id;
								    }
								    $is_votable = Ow_Vote_Save_Controller::check_contestant_is_votable(get_the_ID(), $ip, $term_id);
								    
								    if(!$is_votable){
									    if($vote_votingtype != null && $frequency == 11){
										    $grey_class = (Ow_Vote_Save_Controller::is_current_user_voted(get_the_ID(), $ip, $term_id))?'':'ow_voting_grey_button';
									    }
									    else
										    $grey_class = '';
									    ?>
									    <div class="ow_votes_btn_single<?php echo $post_id; ?>">
									    <a class="ow_votebutton <?php echo $email_class.' '.$grey_class .' '.$ow_login_class; ?> voter_a_btn_term<?php echo $term_id; ?>"
									    data-vote-count="<?php echo $category_options['vote_count_per_contest'];?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $term_id; ?>" data-vote-id="<?php echo get_the_ID(); ?>">
									    <span class="ow_vote_icons votecontestant-check" aria-hidden="true"></span>
										<span class="ow_vote_button_content votr_btn_cont<?php echo get_the_ID();?>"><?php _e('Voted','voting-contest') ;?></span>
									    </a>
									    </div>
									    <?php
								    }else{
									    ?>
									    <div class="ow_votes_btn_single<?php echo $post_id; ?>">
									    <a class="ow_votebutton <?php echo $email_class.' '.$ow_login_class; ?> voter_a_btn_term<?php echo $term_id; ?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $term_id; ?>"
									    data-vote-count="<?php echo $category_options['vote_count_per_contest'];?>"	data-vote-id="<?php echo get_the_ID(); ?>">
									    <span class="ow_vote_icons votecontestant-check" aria-hidden="true"></span>
										<span class="ow_vote_button_content votr_btn_cont<?php echo get_the_ID();?>"><?php _e('Vote Now','voting-contest') ;?></span>
									    </a>
									    </div>
									    <?php
								    }
								    
							    }
							    ?>
								
							</div>
							
							<div class="ow_voting_right"> 
								<?php echo Ow_Vote_Single_Contestants::votes_next_post_link('%link', '%title',true,$termids); ?>
							</div>
						</div>						
						<?php } ?>
						
						<?php
						//Check the title in the Form Contestant Builder
						$title_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_title();
						
						
						if($single_title_position == 'on') {
							if($title_rs[0]->admin_view == "Y"){        
								?>
								
								<h2 class="ow_vote_single-title">
									<?php the_title(); ?>
								</h2>
								<?php
							}
							
						}
						
						ow_votes_the_post_thumbnail($post_id,$termids,$category_options,$global_options,$title_rs[0]->pretty_view);
						?>
						
						<?php
						$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
						$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries($post_id);
						?>
						
						<div class="ow_single_page_content <?php echo $image_contest; ?>">							
							<?php
							    if($image_contest=='music'){
									$adv_excerpt = Ow_Vote_Excerpt_Controller::Instance();
									$shor_desc = $adv_excerpt->filter(get_the_excerpt());
									
									if (has_post_thumbnail(get_the_ID())) {
										if($audio_height !='' && $audio_width!=''){
											$short_cont_image = array( $audio_width, $audio_height);
										}else
										$short_cont_image = ($short_cont_image=='')?'thumbnail':$short_cont_image;
										
										$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $short_cont_image);
										$ow_image_src_msc = $ow_image_arr[0];
									}
									
									if($shor_desc != null){
											$shor_desc = strip_tags($shor_desc);
											$shor_desc_music = strip_tags($shor_desc); 
									}
								
								    $custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
								    if(!empty($custom_entries)){
									    $field_values = $custom_entries[0]->field_values;
									    if(base64_decode($field_values, true))
										    $field_val = maybe_unserialize(base64_decode($field_values));  
									    else
										    $field_val = maybe_unserialize($field_values);
										?>
										<div class="ow_audio_player">
											<div class="audio-js-box <?php echo $global_options['vote_audio_skin']; ?>">
												<audio class="audio-js" data-description="<?php echo $title_vote;?>" datafeatured-img ="<?php echo $ow_image_src_msc;?>" controls>
												  <source src="<?php echo $field_val['contestant-ow_video_url']; ?>" type="audio/mpeg">
												</audio>
											</div>
										</div>
										<?php
								    }else{
										?>
										<div class="ow_audio_player">
											<div class="audio-js-box <?php echo $global_options['vote_audio_skin']; ?>">
												<audio class="audio-js" data-description="<?php echo $title_vote;?>" datafeatured-img ="<?php echo $ow_image_src_msc;?>" controls>
												  <source src="<?php echo $shor_desc_music; ?>" type="audio/mpeg">
												</audio>
											</div>
										</div>
										<?php
										}
										
									if($single_title_position == 'off' || $single_title_position == null) {
										if($title_rs[0]->admin_view == "Y"){        
											?>
											
											<h2 class="ow_vote_single-title">
												<?php the_title(); ?>
											</h2>
											<?php
										}
										
									}
									echo $shor_desc;
									
									
							    }
							    else if($image_contest == 'video'){
								if(!empty($custom_entries)){
									    $field_values = $custom_entries[0]->field_values;
									    if(base64_decode($field_values, true))
										    $field_val = maybe_unserialize(base64_decode($field_values));  
									    else
										    $field_val = maybe_unserialize($field_values);
										    
								}
								$content_post = get_post($post_id);
								$content = $content_post->post_content;
								if($content != null){							
								    $content = apply_filters( 'the_content', $content );
								    $content = str_replace( ']]>', ']]&gt;', $content );
								}
									if(!empty($field_val)){
										
										$vote_single_video_width_value = $global_options['single_contestants_video_width_px'];
										if($vote_single_video_width_value == 'on') {
											$vote_single_video_width_value = 'px';
										}
										
										$num_value = $video_width;
										$int_value = (int)$num_value;
										
										if($video_width != '') {
											$ow_video_width_single_cont = $int_value.$vote_single_video_width_value;
										} else {
											$ow_video_width_single_cont = '100%';
										}										
										echo do_shortcode('[owvideo width='.$ow_video_width_single_cont.' align=center]'.$field_val['contestant-ow_video_url'].'[/owvideo]');
										//echo do_shortcode('[owvideo width='.$ow_video_width_single_cont.' align=center]'.get_post_meta($post_id,'contestant-ow_video_url',true).'[/owvideo]');
										
								    }
									
									if($single_title_position == 'off' || $single_title_position == null) {
										if($title_rs[0]->admin_view == "Y"){        
											?>
											
											<h2 class="ow_vote_single-title">
												<?php the_title(); ?>
											</h2>
											<?php
										}
										
									}
									
									if($content != null){
										echo $content;
									}
									
									
							    }
							    else{
									if($single_title_position == 'off' || $single_title_position == null) {
										if($title_rs[0]->admin_view == "Y"){        
											?>
											
											<h2 class="ow_vote_single-title">
												<?php the_title(); ?>
											</h2>
											<?php
										}
										
									}
								the_content();
							    }
							
							?>
						</div>
						
						<?php
						
						if(!empty($custom_entries)){
							$field_values = $custom_entries[0]->field_values;
							if(base64_decode($field_values, true))
								$field_val = maybe_unserialize(base64_decode($field_values));  
							else
								$field_val = maybe_unserialize($field_values);
								
						}						
						if(!empty($custom_fields)){
						    
							$k = 0;
							//Check for the Admin View to Show the Title
							foreach($custom_fields as $custom_field){
							    if($custom_field->admin_view=='Y' && !in_array($custom_field->system_name,array('contestant-desc','contestant-title','contestant-ow_video_url'))){
								$k++;
							    }
							}
							
							$j = 0;
							//Checking for the Null Values in the field
							foreach($field_val as $key=>$vals){
							    if($vals != null){
								$j++;
							    }
							}
							if($k > 0 && $j > 0){
							?>
							<div class="ow_contestant_custom_fields">
								<h2><?php _e('Additional Information','voting-contest'); ?></h2>
							<?php
							foreach($custom_fields as $custom_field){
								if($custom_field->system_name != 'contestant-desc' && $custom_field->system_name != 'contestant-title' && $custom_field->system_name != 'contestant-ow_video_url'){
								 								
							if($custom_field->admin_view=='Y'){
								if($field_val[$custom_field->system_name] != ''){
							?>
								<div class="ow_contestant_other_det">
								 <span><strong><?php echo $custom_field->question.': ';?></strong></span>
									<?php
										if($custom_field->question_type == 'DATE'){
											$date_format = get_option($custom_field->system_name);
											$date_field = $field_val[$custom_field->system_name];
											list($m, $d, $y) = preg_split('/-/', $date_field);
											$date_field = sprintf('%4d%02d%02d', $y, $m, $d);
											
											echo date($date_format,strtotime($date_field));
										}
										else if($custom_field->question_type == 'FILE'){
										   
										    $uploaded_file = get_post_meta(get_the_ID(),'ow_custom_attachment_'.$field_val[$custom_field->system_name],true);
										    ?>
										    <a class='ow_file_image <?php echo $custom_field->system_name; ?>' href="<?php echo $uploaded_file['url']; ?>">
											<img src="<?php echo OW_SMALLFILE_IMAGE; ?>" />
										    </a>
										    <?php
										}									    
										else if(is_array($field_val[$custom_field->system_name ])){
											$multiple = implode(', ',$field_val[$custom_field->system_name ]);
											echo $multiple;
										}else{
											echo stripcslashes($field_val[$custom_field->system_name ]);
										}
										?>
								</div>
										<?php
										}
									}
								}
							}
							echo '</div>'; // Close of Div .ow_contestant_custom_fields
						}
							?>
							
							<?php
						}

						$user_author = Ow_Contestant_Model::ow_voting_get_author_contestant($post);
						$author = $user_author->display_name;
						$author_email = $user_author->user_email;
						if($authordisplay=='on'){
							?>
							<div class="ow_show_author">
							<?php _e('Author : ','voting-contest') ?><span><?php echo $author; ?></span>
							</div>
							<?php }
							if($authornamedisplay=='on'){ ?>
							<div class="ow_show_author">
							<?php _e('Author Email: ','voting-contest') ?><span><?php echo $author_email; ?></span>
							</div>
							<?php
						}
						?>
						
					</div>
			
					<!-- Vote Counter and Share option  -->
					<div class="ow_votes_social_container">
						<?php if($category_options['votecount']==''){
							$totvotesarr = get_post_meta(get_the_ID(), OW_VOTES_CUSTOMFIELD);
							$totvotes = isset($totvotesarr[0]) ? $totvotesarr[0] : 0;
							if($totvotes == NULL) $totvotes = 0;
							?>
							<div class="ow_votes_counter_content votes_count_single<?php echo $post_id; ?>">
								<span aria-hidden="true" class="ow_vote_icons votecontestant-check"></span>
								<p class="votes_single_counter votes_count_single_count<?php echo $post_id; ?>"><?php echo $totvotes; ?></p>
							</div>
							<?php } ?>
							<div class="ow_votes_view_content">
								<span aria-hidden="true" class="ow_vote_icons votecontestant-eye-open"></span>
								<?php echo $votes_view; ?>
							</div>
							
							<div class="ow_vote_share_shrink ow_vote_float_right">
								<a class="ow_share_click_expand"><span>Share</span></a>
							</div>
					</div>
						
					
					<?php echo ow_social_share_icons($post_id,$termids,$category_options,$option); ?>	
					
					<?php
					if ( is_user_logged_in() && isset($_GET['con'])) { 					    
					    $author_id = base64_decode($_GET['con']);
					    $user_ID = get_current_user_id();					    
					    if ( $author_id == $user_ID ) { ?>
						<div class="single_page_payments"> <?php
						echo $output = apply_filters('ow_payment_single_page',$post_id,$term_id);
						?>
						</div>						
						<?php
					    } 
					}
					if (!is_user_logged_in() && isset($_GET['con'])) {
					    ?>
					    <script type="text/javascript">
						    jQuery(document).ready(function(){
							ow_vote_ppOpen('#ow_vote_login_panel', '300',1);				
							//Tab in the Login Popup
							jQuery('.ow_tabs_login_content').show();
							jQuery('.ow_tabs_register_content').hide();
							jQuery( '.ow_tabs_login' ).addClass('active');
						    });
					    </script>
					    <?php
					}
					?>
					
					<div class="ow_vote_content_comment"><?php comments_template(); ?></div>	
				</div>
				
			<?php
			if($vote_sidebar!='on'){
				if($vote_select_sidebar!=''){
					
					if($vote_select_sidebar=='Contestants_sidebar'){		
						echo '<div class="ow_votes_sidebar">';
						   dynamic_sidebar('contestants_sidebar');
						echo '</div>';
					}else{
						echo '<div class="ow_votes_sidebar">';
						get_sidebar($vote_select_sidebar);
						echo '</div>';
					}
				}
			}
			?>			
			</div>
			
		</section>
		<div class="ow_single_footer_div">
		<?php
		get_footer();
		?>
		</div>
		<?php
		exit; 
	}
}else{
    die("<h2>".__('Failed to load Voting Single Contestant view','voting-contest')."</h2>");
}

if(!function_exists('ow_sharing_contestant_function')){
	function ow_sharing_contestant_function(){
		global $wpdb,$post;
		$post_id = $post->ID;
		//for sharing
		$permalink1 = get_permalink( get_the_ID());
		$image_path = Ow_Vote_Common_Controller::ow_vote_get_contestant_image($post_id,'large');
		$ow_image_src = $image_path['ow_image_src'];
		$content_desc = strip_tags($post->post_content);
		
		$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
		if(!empty($custom_entries)){
			$field_values = $custom_entries[0]->field_values;
			if(base64_decode($field_values, true))
				$field_val = maybe_unserialize(base64_decode($field_values));  
			else
				$field_val = maybe_unserialize($field_values);
				
		}
		
		$shor_desc = $field_val['contestant-ow_video_url'];
		$link =  $shor_desc;
		$video_id = str_replace('https://www.youtube.com/watch?v=', '', $link);
		?>
			<!-- for Google -->
			<meta name="description" content="<?php echo $content_desc; ?>" />
			<meta name="keywords" content="" />
			
			<meta name="author" content="" />
			<meta name="copyright" content="" />
			<meta name="application-name" content="" />
			
			<!-- for Facebook -->          
			<meta property="og:title" content="<?php echo htmlentities(get_the_title(),ENT_QUOTES); ?>" />
			<meta property="og:type" content="article" />
			<meta property="og:url" content="<?php echo $permalink1;?>" />
			<meta property="og:image" content="http://img.youtube.com/vi/<?php echo $video_id; ?>/hqdefault.jpg" />
			<meta property="og:description" content='<?php echo $content_desc; ?>' />
			<meta name="og:author" content="Voting"/>
		
			<!-- for Twitter -->          
			<meta name="twitter:card" content="summary" />
			<meta name="twitter:title" content="<?php echo htmlentities(get_the_title(),ENT_QUOTES); ?>" />
			<meta name="twitter:description" content="<?php echo $content_desc; ?>" />
			<meta name="twitter:image" content="<?php echo $ow_image_src; ?>" />
		<?php
	}
}
else{
    die("<h2>".__('Failed to load Voting Single Contestant Share view','voting-contest')."</h2>");
}


if(!function_exists('ow_votes_the_post_thumbnail')){
	function ow_votes_the_post_thumbnail($post_id,$termids,$category_options,$global_options,$title_option = null){
	    
		$cat_id = $termids[0];		
		$tax_hide_photos_live = $category_options['tax_hide_photos_live'];
		$votes_start_time=get_option($cat_id . '_' . OW_VOTES_TAXSTARTTIME);
		
		$enc_termid = Ow_Vote_Common_Controller::ow_voting_encrypt($cat_id);
		$enc_postid = Ow_Vote_Common_Controller::ow_voting_encrypt(get_the_ID());
				
		$current_time = current_time( 'timestamp', 0 );
		if(($votes_start_time !='' && strtotime($votes_start_time) < $current_time)){
		    $tax_hide_photos_live = 'off';
		}
								
		if($tax_hide_photos_live == 'on'){
		    return;
		}
		
		if(is_array($category_options)){
			$image_contest = $category_options['imgcontest'];
			$votecount=$category_options['votecount'];
		}
		else{
			$image_contest = '';
			$votecount='';
		}
				
		if($image_contest=='photo' || $image_contest=='essay' || $image_contest==''){
			
			//$adv_excerpt = Ow_Vote_Excerpt_Controller::Instance();      
			//$shor_desc = $adv_excerpt->filter(get_the_excerpt());
			
			$get_content = get_post($post_id);
			$shor_desc= $get_content->post_content;
			$show_desc_pretty = Ow_Vote_Shortcode_Model::ow_vote_show_desc_prettyphoto();
			$pretty_excerpt = ($show_desc_pretty == 1)?strip_tags($shor_desc):'';
			
			$ow_image_alt_text=Ow_Vote_Common_Controller::ow_vote_seo_friendly_alternative_text(get_the_title());
			$image_path = Ow_Vote_Common_Controller::ow_vote_get_contestant_image($post_id,'large');
			$ow_image_src = $image_path['ow_image_src'];
			$ow_original_img = $image_path['ow_original_img'];		
			
		
			//PrettyPhoto Disable in Single Contestant Page
			if(isset($global_options['vote_prettyphoto_disable_single'])){
				$vote_prettyphoto_disable_single = $global_options['vote_prettyphoto_disable_single'];				
			}
			else{
				$vote_prettyphoto_disable_single = "";
			}
			
			$get_img_size=getimagesize(realpath($ow_image_src));
			$style_image_overlay = 'width:'.$get_img_size[0].'px;'.'height:'.$get_img_size[1].'px;';
			?>
			<div class="ow_vote_cont_img" data-id="<?php echo $cat_id; ?>">
			
			<?php $vote_single_img_width_value = $global_options['single_page_cont_image_px'];					
				if($vote_single_img_width_value == 'on') {
					$vote_single_img_width_value = 'px';
				}				
				$vote_single_img_width = $global_options['single_page_cont_image'];
				   $num = $vote_single_img_width;
				   $int = (int)$num;				   
				if($int == null){
					$int = 100;
				}
			?>
				
			<?php if($vote_prettyphoto_disable_single == null || $vote_prettyphoto_disable_single == 'off'): ?>
			<a class="single_contestant_pretty ow_hover_image" data-pretty-title="<?php echo $pretty_excerpt; ?>" href="<?php echo $ow_original_img; ?>" data-vote-id="<?php echo get_the_ID(); ?>">
			<div class="ow_overlay_bg ow_overlay_<?php echo get_the_ID(); ?>" style="<?php echo $style_image_overlay; ?>">
			    <span><i class="ow_vote_icons voteconestant-zoom"></i></span>
			</div>				
				<img style="width: <?php echo $int.$vote_single_img_width_value; ?>" class="ow_image_responsive" id="ow_image_responsive<?php echo get_the_ID(); ?>" src="<?php echo $ow_image_src; ?>" title="<?php echo $ow_image_alt_text; ?>" alt="<?php echo $ow_image_alt_text; ?>" data-pretty-alt="<?php echo ($title_option == 'Y')?$ow_image_alt_text:''; ?>" data-vote-id="<?php echo get_the_ID(); ?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $cat_id; ?>"/>
			</a>
			<?php else: ?>
				<img style="width: <?php echo $int.$vote_single_img_width_value; ?>" class="ow_image_responsive" id="ow_image_responsive<?php echo get_the_ID(); ?>" src="<?php echo $ow_image_src; ?>" title="<?php echo $ow_image_alt_text; ?>" alt="<?php echo $ow_image_alt_text; ?>" data-pretty-alt="<?php echo ($title_option == 'Y')?$ow_image_alt_text:''; ?>" data-vote-id="<?php echo get_the_ID(); ?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $cat_id; ?>"/>
			<?php endif; ?>
			</div>
		<?php
		}	
	}
}else{
    die("<h2>".__('Failed to load Voting Single Contestant Thumbnail view','voting-contest')."</h2>");
}

if(!function_exists('ow_social_share_icons')){
	function ow_social_share_icons($post_id,$termids,$category_options,$option){
		
		$permalink = get_permalink($post_id);
		$up_path =  wp_upload_dir();
		
		$facebook = $option['facebook'] ? $option['facebook'] : 'off';
		$twitter = $option['twitter'] ? $option['twitter'] : 'off';
		$pinterest = $option['pinterest'] ? $option['pinterest'] : 'off';
		$gplus = $option['gplus'] ? $option['gplus'] : 'off';
		$tumblr = $option['tumblr'] ? $option['tumblr'] : 'off';
		
		$file_facebook = $option['file_facebook'] ?$option['file_facebook']:'';
		$file_twitter = $option['file_twitter'] ?$option['file_twitter']:'';
		$file_pinterest = $option['file_pinterest'] ?$option['file_pinterest']:'';
		$file_gplus = $option['file_gplus'] ?$option['file_gplus']:'';
		$file_tumblr = $option['file_tumblr'] ?$option['file_tumblr']:'';
		
		
		$file_fb_default = $option['file_fb_default'] ?$option['file_fb_default']:'';
		$file_tw_default = $option['file_tw_default'] ?$option['file_tw_default']:'';
		$file_pinterest_default = $option['file_pinterest_default'] ?$option['file_pinterest_default']:'';
		$file_gplus_default = $option['file_gplus_default'] ?$option['file_gplus']:'';
		$file_tumblr_default = $option['file_tumblr_default'] ?$option['file_tumblr_default']:'';
	 	
		$image_path = Ow_Vote_Common_Controller::ow_vote_get_contestant_image($post_id,'large');
		$ow_image_src = $image_path['ow_image_src'];
		$ow_original_img = $image_path['ow_original_img'];
		
		
		
		?>
		<div class="ow_total_share_single ow_make_hide">
			<div class="ow_face_social_icons ow_pretty_content_social<?php echo $post_id; ?>">
			<div class="ow_fancy_content_social ow_fancy_content_social<?php echo get_the_ID(); ?>">
			<?php
			if($facebook!='off') {
				if($file_fb_default=='' && $file_facebook!=''){
					if(file_exists($up_path['path'].'/'.$file_facebook))
						$face_img_path = $up_path['url'].'/'.$file_facebook;
					else
						$face_img_path = OW_ASSETS_IMAGE_PATH.'facebook-share.png';
				}else{
					$face_img_path = OW_ASSETS_IMAGE_PATH.'facebook-share.png';
				}?>
				<a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>&amp;t=<?php echo htmlentities(get_the_title(),ENT_QUOTES); ?>">
					<img alt="Facebook share" src="<?php echo $face_img_path;?>">
				</a>
			
			<?php
			}
			if($twitter!='off') { 
				if($file_tw_default=='' && $file_twitter!=''){
					if(file_exists($up_path['path'].'/'.$file_twitter))
						$twt_img_path = $up_path['url'].'/'.$file_twitter;
					else
					$twt_img_path = OW_ASSETS_IMAGE_PATH.'tweet.png';
				}else{
					$twt_img_path = OW_ASSETS_IMAGE_PATH.'tweet.png';
				}?>
				<a target="_blank" href="http://twitter.com/home?status=<?php echo htmlentities(get_the_title(),ENT_QUOTES).'%20'.$permalink; ?>">
					<img alt="Tweet share" src="<?php echo $twt_img_path;?>">
				</a>
			
			<?php
			}
			
			if($pinterest!='off') {
				if($file_pinterest_default=='' && $file_pinterest!=''){
					if(file_exists($up_path['path'].'/'.$file_pinterest))
						$pinterest_img_path = $up_path['url'].'/'.$file_pinterest;
					else
					$pinterest_img_path = OW_ASSETS_IMAGE_PATH.'pinterest.png';
				}else{
					$pinterest_img_path = OW_ASSETS_IMAGE_PATH.'pinterest.png';
				}
				?>
				<a target="_blank" href="http://www.pinterest.com/pin/create/button/?url=<?php echo htmlentities($permalink).'&amp;media='.htmlentities($ow_image_src).'&amp;description='.htmlentities(get_the_title(),ENT_QUOTES);?>">
					<img alt="Tweet share" src="<?php echo $pinterest_img_path;?>">
				</a>
				<?php
			}
			
			if($gplus !='off') {       
				if($file_gplus_default=='' && $file_gplus!=''){
				  if(file_exists($up_path['path'].'/'.$file_gplus))
					$gplus_img_path  = $up_path['url'].'/'.$file_gplus;
				  else
					$gplus_img_path  = OW_ASSETS_IMAGE_PATH.'gplus.png';
				}else{
					$gplus_img_path = OW_ASSETS_IMAGE_PATH.'gplus.png';
				}
				?>
				<a target="_blank" href="https://plus.google.com/share?url=<?php echo $permalink; ?>" >
					<img alt="Google share" src="<?php echo $gplus_img_path;?>">
				</a>
				<?php
			}
			
			if($tumblr!='off') {
				if($file_tumblr_default=='' && $file_tumblr!=''){
					if(file_exists($up_path['path'].'/'.$file_tumblr))
						$tumb_img_path  = $up_path['url'].'/'.$file_tumblr;
					else
						$tumb_img_path    = OW_ASSETS_IMAGE_PATH.'tumblr.png';
				}else{
					$tumb_img_path   = OW_ASSETS_IMAGE_PATH.'tumblr.png';
				}
				?>
				<a target="_blank" href="http://www.tumblr.com/share/photo?source=<?php echo htmlentities($ow_image_src).'&amp;caption='.htmlentities(get_the_title(),ENT_QUOTES).'&amp;clickthru='.htmlentities($permalink); ?>" >
					<img alt="Tumblr share" src="<?php echo $tumb_img_path;?>">
				</a>
				<?php
			}
			?>
			</div>
			</div>
			
			<div class="share_text_box_single">
				<div class="copied_message"><span>Copied!</span></div>
				<div class="ow_vote_share_parent"><input type="text" id="ow_vote_share_url_copy" readonly name="share_url" class="ow_vote_share_url" value="<?php echo $permalink; ?>" /></div>
			</div>
		</div>
		
		<?php
	}
}else{
    die("<h2>".__('Failed to load Voting Social icons single Contestant view','voting-contest')."</h2>");
}

?>
