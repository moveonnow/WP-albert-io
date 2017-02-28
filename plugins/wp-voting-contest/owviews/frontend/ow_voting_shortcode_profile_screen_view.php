<?php
if(!function_exists('ow_voting_profile_contestants_view')){
    function ow_voting_profile_contestants_view($contest_args,$vote_opt,$custom_field){
		global $user_ID, $current_user, $wp_roles;
		if(is_user_logged_in()){
			$facebook = $vote_opt['facebook'];
			$twitter = $vote_opt['twitter'];
			$pinterest= $vote_opt['pinterest'];
			$gplus = $vote_opt['gplus'];
			$tumblr = $vote_opt['tumblr'];
						
			extract( shortcode_atts( array(
				 'form' => '1',
				 'contests' => '1',              
			), $contest_args, 'profilescreen' ));
		   
			if($contest_args['contests'] == '1' || !isset($contest_args['contests'])){
				$paged = get_query_var('paged') ? get_query_var('paged') : 1;
				$post_per_page = ($contest_args['postperpage'] == null)?get_option('posts_per_page'):$contest_args['postperpage'];
				$postargs = array(
					'post_type'     => OW_VOTES_TYPE,
					'post_status'   => array( 'pending', 'publish','future' ),
					'orderby'       => 'id',
					'author'        => $user_ID,	
					'posts_per_page'=> $post_per_page,
					'paged'         => $paged,
				);
								
				$contest_post = new WP_Query($postargs);
				if ($contest_post->have_posts()) {
					?>
					<div class="ow_voting-profile">
						<input type="hidden" name="confirm_delete_single" id="confirm_delete_single" value="<?php _e('Are you sure you want to delete?','voting-contest'); ?>"/>
						<div class="table-container">
						<table class="responsive-table">
							<thead>
								<tr>
									<th><?php _e('Image','voting-contest'); ?></th>
									<th><?php _e('Upload Date','voting-contest'); ?></th>
									<th><?php _e('Category','voting-contest'); ?></th>  
									<th><?php _e('Contestants Name','voting-contest'); ?></th>                        
									<th><?php _e('Votes','voting-contest'); ?></th>
									<th><?php _e('Delete','voting-contest'); ?></th>
									<th><?php _e('Share','voting-contest'); ?></th>
									<th><?php _e('Status','voting-contest'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
							while ( $contest_post->have_posts() ) {
								$class = ($i == 0)?"class='first-row'":'';
								$contest_post->the_post();
								
								if (has_post_thumbnail($contest_post->post->ID)) {
									$attachment_id = get_post_thumbnail_id($contest_post->post->ID );
									$image1 = wp_get_attachment_image($attachment_id,'thumbnail');
									$short_cont_image = ($short_cont_image=='')?'thumbnail':$short_cont_image;
									$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $short_cont_image);
									$ow_image_src = $ow_image_arr[0];
								}else{
									$ow_image_src = OW_NO_IMAGE_CONTEST;
								}
								
								$perma = get_permalink(get_the_ID()); 
								$status = $contest_post->post->post_status;
								$ow_image_alt_text=Ow_Vote_Common_Controller::ow_vote_seo_friendly_alternative_text(get_the_title());
								?>
								<tr>
									<td class="contest_image">
										<img src="<?php echo $ow_image_src; ?>" alt="<?php echo $ow_image_alt_text; ?>"/>
									</td>
									<td><?php echo get_the_date(); ?></td>
									<?php $terms = wp_get_post_terms($contest_post->post->ID, OW_VOTES_TAXONOMY);
									$cat_name    = $terms[0]->name;
									?>
									<td><?php echo $cat_name;?></td>
									<td>
										<?php 
										if($status == 'publish')
											echo "<a href='".$perma."'>".get_the_title()."</a>"; 
										else
											echo get_the_title();
										?>
									</td>
									<td><?php echo get_post_meta( $contest_post->post->ID , OW_VOTES_CUSTOMFIELD, true ); ?></td>
									<td>
										<form name="delete_contestants<?php echo $contest_post->post->ID; ?>" id="delete_contestants<?php echo $contest_post->post->ID; ?>" method="POST">
										<input type="hidden" id="votes_single" name="votes_single" value="<?php echo $contest_post->post->ID; ?>" />
											<a href="javascript:" onclick="javascript:confirm_delete_single('<?php echo $contest_post->post->ID; ?>');" title="<?php _e('Delete','voting-contest'); ?>">
											<img src="<?php echo OW_ASSETS_IMAGE_PATH.'delete.png'; ?>" alt="<?php _e('Delete','voting-contest'); ?>" class="submit_image"  />
											</a>
										</form>
									</td>
									<td>
										<?php $perma_link = get_permalink(get_the_ID()); ?>
										<div class="ow_show_share_icons_div">
											<?php if($facebook!='off') { ?>
												<a class="ow_show_share_icons" title="<?php _e('Share on Facebook','voting-contest'); ?>" data-ref="&#xe027;" target="_blank" 
												href="http://www.facebook.com/sharer.php?u=<?php echo $perma_link.'&amp;t='.urlencode(get_the_title()); ?>">
												</a>
											<?php }if($twitter!='off') { ?>
												<a class="ow_show_share_icons" title="<?php _e('Share on Twitter','voting-contest'); ?>" data-ref="&#xe086;" target="_blank"
												href="http://twitter.com/home?status=<?php echo urlencode(get_the_title()).'%20'.$perma_link;?>">
												</a>
											<?php }if($pinterest!='off') { ?>
												<a class="ow_show_share_icons" title="<?php _e('Share on Pinterest','voting-contest'); ?>" data-ref="&#xe064;" target="_blank"
												href="http://www.pinterest.com/pin/create/button/?url=<?php echo urlencode($perma_link).'&description='.urlencode(get_the_title()).'&media='.urlencode($ow_image_src)?>">
												</a>
											<?php }if($tumblr!='off') {?>
												<a class="ow_show_share_icons" title="<?php _e('Share on Tumblr','voting-contest'); ?>" data-ref="&#xe085;" target="_blank"
												 href="http://www.tumblr.com/share/photo?source=<?php echo urlencode($ow_image_src).'&caption='.urlencode(get_the_title()).'&clickthru='.urlencode($perma_link); ?>">
												</a>
											<?php }if($gplus!='off') { ?>
												<a class="ow_show_share_icons" title="<?php _e('Share on Google Plus','voting-contest'); ?>" data-ref="&#xe039;" target="_blank"
												href="https://plus.google.com/share?url=<?php echo $perma_link; ?>">
												</a>
											<?php } ?>
										</div>
									</td>
									<td><?php echo ucfirst($status); ?></td>
								</tr>
								<?php
							}
							?>
							<tbody>
						</table>
						</div>
						<?php 
							$pagination_type =  voting_wp_pagenavi(array('query' => $contest_post),'profile');
							
							//Load More Option				    
							if($pagination_type == 3 || ($_SESSION['ow_shortcode_count'] > 1 && $pagination_type == 4)){
							$pagination_option = get_option('contestpagenavi_options');					
							?>
							<div class="ow_jx_response ow_jx_response_<?php echo $id; ?>">
								
							</div>
							<div class="ow_vote_fancybox_result_infinite ow_jx_loader_<?php echo $id; ?>"></div>
							
							<button class="ow_load_more" id="ow_load_<?php echo $id; ?>"><?php echo $pagination_option['load_more_button_text']; ?></button>
							
							<input type="hidden" id="ow_category_options_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($category_options)); ?>" />
							<input type="hidden" id="ow_show_cont_args_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($show_cont_args)); ?>" />
							<input type="hidden" id="ow_show_global_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($global_options)); ?>" />
							
							<input type="hidden" id="ow_postperpage_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
							<input type="hidden" id="ow_offset_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
							<?php
							}
							//Infinite Scroll
							else if($pagination_type == 4){
							$pagination_option = get_option('contestpagenavi_options');					
							?>
							<div class="ow_jx_response ow_jx_response_<?php echo $id; ?>"></div>
							<div class="ow_vote_fancybox_result_infinite ow_jx_loader_<?php echo $id; ?>"></div>
							<input type="hidden" id="ow_infinite" value="1" />
							<input type="hidden" id="ow_category_options_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($category_options)); ?>" />
							<input type="hidden" id="ow_show_cont_args_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($show_cont_args)); ?>" />
							<input type="hidden" id="ow_show_global_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($global_options)); ?>" />
							<input type="hidden" id="ow_postperpage_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
							<input type="hidden" id="ow_offset_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
							<?php
							}
							else{
							echo $pagination_type;
							}
						
						?>
					</div>
					<?php
				}else {
					_e('No Contestants Found','voting-contest');
				}
				wp_reset_postdata();
			}
			
			if($contest_args['form'] == '1' || !isset($contest_args['form'])){
				get_currentuserinfo();
				//require_once( ABSPATH . WPINC . '/registration.php' );
				$error = new WP_Error();
				if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ){
					$error = Ow_Vote_Common_Controller::ow_voting_profile_update($_POST,$error);
					if ( count($error->errors) == 0 ) {
						?>
						<div class="vote-profile-status">
							<div class="success-rows"><?php _e('Profile Updated Successfully','voting-contest'); ?></div>
						</div>
						<?php
					}else{
						?>
						<div class="vote-profile-status">                        
							<?php foreach($error->errors as $err): ?>
							<div class="ow_profile_required_mark"><?php echo $err[0]; ?></div>
							<?php endforeach; ?>
						</div>
						<?php  
					}
				}
				?>
				<form method="post" id="adduser" class="zn_form_profile " action="<?php the_permalink(); ?>">
					<h3 class="m_title"><?php _e('Update Profile','voting-contest')?></h3>
					<div class="register_panel_add">
						
						<div class="register-panel_inner">
							<label for="user_login">
								<strong><?php _e('Username', 'voting-contest'); ?></strong>
								<span class="required-mark">*</span>
							</label>
							<p><input class="inputbox" name="user_login" type="text" id="user_login" value="<?php the_author_meta( 'user_login', $user_ID ); ?>" disabled="disabled" /></p>
						</div>
						
						<div class="register-panel_inner">
							<label for="email">
								<strong><?php _e('E-mail', 'voting-contest'); ?></strong>
								<span class="required-mark">*</span>
							</label>
							<p><input class="inputbox required_vote_custom" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $user_ID ); ?>" /></p>
						</div>
                  
						<div class="register-panel_inner">
							  <label for="first-name">
								  <strong><?php _e('First Name', 'voting-contest'); ?></strong>                            
							  </label>
							  <p><input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $user_ID ); ?>"  /></p>
						</div>
				  
						<div class="register-panel_inner">
							  <label for="last-name">
								  <strong><?php _e('Last Name', 'voting-contest'); ?></strong>                            
							  </label>
							  <p><input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $user_ID); ?>"  /></p>
						</div>
						
						<div class="register-panel_inner">
							  <label for="nickname">
								  <strong><?php _e('Nickname', 'voting-contest'); ?></strong>                            
							  </label>
							  <p><input class="text-input" name="nickname" type="text" id="nickname" value="<?php the_author_meta( 'nickname', $user_ID ); ?>"  /></p>
						</div>

						<div class="register-panel_inner">
							<label for="pass1">
								<strong><?php _e('Password', 'voting-contest'); ?></strong>
								<span class="required-mark">*</span>
							</label>
							<p><input class="text-input" name="pass1" type="password" id="pass1" /></p>
						</div>
						
						<div class="register-panel_inner">
							<label for="pass2">
								<strong><?php _e('Repeat Password ', 'voting-contest'); ?></strong>
								<span class="required-mark">*</span>
							</label>
							<p><input class="text-input" name="pass2" type="password" id="pass2" /></p>
						</div>
						
						<?php
							$registered_entries = Ow_Vote_Shortcode_Model::ow_votes_user_entry_table($user_ID);
							if(!empty($registered_entries)){
								if(base64_decode($registered_entries[0]->field_values, true))
								   $registration = unserialize(base64_decode($registered_entries[0]->field_values));  
								else
								   $registration = unserialize($registered_entries[0]->field_values);  
							}else{
								$registration=array();
							}
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
													 <input id="<?php echo $custom_fields->system_name; ?>" type="<?php echo strtolower($custom_fields->question_type); ?>" class="inputbox <?php echo $class; ?>" name="<?php echo $custom_fields->system_name; ?>" placeholder="<?php _e($custom_fields->question);?>" value="<?php echo $registration[$custom_fields->system_name]; ?>"/>
													<?php
												break;
											
												case 'TEXTAREA':
												?>
												<textarea rows="1" id="<?php echo $custom_fields->system_name; ?>" placeholder="<?php _e($custom_fields->question);?>" name="<?php echo $custom_fields->system_name; ?>" class="<?php echo $class; ?>" ><?php echo stripslashes($registration[$custom_fields->system_name]); ?></textarea>	
												<?php
												break;
											
												case 'SINGLE':
													$values = explode(',',$custom_fields->response);
													foreach($values as $val){
													?>   
													<span class="add_contestant_radio"> 
													<input class="<?php echo $class; ?> reg_radio_<?php echo $custom_fields->system_name; ?>" type="radio" name="<?php echo $custom_fields->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_fields->system_name; ?>"
													<?php if(is_array($registration[$custom_fields->system_name])){if(in_array($val,$registration[$custom_fields->system_name])){echo "checked";}} ?> /> <span class="question_radio <?php echo $custom_fields->system_name; ?>" ><?php echo $val; ?></span>
													</span> 
													<?php } 
												break;
												
												case 'MULTIPLE':
													$values = explode(',',$custom_fields->response);
													foreach($values as $val){
													?>
													<span class="add_contestant_radio"> 
													<input class="<?php echo $class; ?> reg_check_<?php echo $custom_fields->system_name; ?>" type="checkbox"  name="<?php echo $custom_fields->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_fields->system_name; ?>"
													<?php if(is_array($registration[$custom_fields->system_name])){if(in_array($val,$registration[$custom_fields->system_name])){echo "checked";}} ?>/>
													<span class="question_check <?php echo $custom_fields->system_name; ?>" ><?php echo $val; ?></span>  </span> 
													<?php }
												break;
											
												case 'DROPDOWN':
													$values = explode(',',$custom_fields->response); ?>
													<select class="<?php echo $class; ?>" style="width: 100%;padding: 0.428571rem;border: 1px solid #CCCCCC;border-radius: 3px 3px 3px 3px;" name="<?php echo $custom_fields->system_name; ?>" id="<?php echo $custom_fields->system_name; ?>">
													<option value=""><?php _e('Select','voting-contest'); ?></option>
													<?php foreach($values as $val){ ?>
														  <option value="<?php echo $val; ?>" <?php echo($registration[$custom_fields->system_name]==$val)?'selected="selected"':'';?>>
															<?php echo $val; ?>
														  </option>
													<?php } ?>
													</select>
													<?php
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
							<div class="register-panel_inner">
							<p class="form-submit">
							<?php echo $referer; ?>
							<input name="updateuser" type="submit" id="updateuser" class="zn_sub_button_edit" value="<?php _e('Update', 'voting-contest'); ?>" />
							<?php wp_nonce_field( 'update-user' ) ?>
							<input name="action" type="hidden" id="action" value="update-user" />
							</p><!-- .form-submit -->
							</div>
						</div>
					</form>
				<?php
			}
		   
		}else{
			Ow_Vote_Shortcode_Controller::ow_votes_custom_registration_fields_show();
            _e('Login to Access this Section','voting-contest');
            ?>
            <a href="javascript:" class="ow_tabs_login" onclick="ow_vote_ppOpen('#ow_vote_login_panel', '300',1);"><?php _e('Login','voting-contest'); ?></a>
            <?php
		}
	}
}else{
    die("<h2>".__('Failed to load Voting Profile Contest view','voting-contest')."</h2>");
}
?>