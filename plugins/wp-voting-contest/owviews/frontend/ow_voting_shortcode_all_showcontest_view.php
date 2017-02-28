<?php
if(!function_exists('ow_voting_shortcode_all_showcontest_view')){
    function ow_voting_shortcode_all_showcontest_view($show_cont_args,$vote_opt,$ajax = null,$selected = null,$ow_search = null){
		
		$selected = ($selected == null)? get_query_var( 'ow_cont') : $selected;
		$sort 	  = get_query_var('ow_sort');
		$ow_search = ($ow_search == null)? get_query_var( 'ow_search') : $ow_search; 
		
		$terms = Ow_Contestant_Model::ow_voting_get_all_terms();
		
		$category_options = get_option($selected.'_'.OW_VOTES_SETTINGS);
		
		//Check vote_enable_ended is Enabled in Common Settings
		if($vote_opt['vote_enable_ended'] == 'on'){
			foreach($terms as $term){	
					$unblocked_terms[$term->term_id]= $term->name;											
			} 
			$contest_post = Ow_Vote_Shortcode_Model::ow_get_show_all_contest_query_sql($show_cont_args,$selected,$sort,$ajax,'',$ow_search);	
		}
		else{
			foreach($terms as $term){			
				$check_status = Ow_Vote_Common_Controller::ow_votes_is_addform_blocked($term->term_id);				
				if(!$check_status){
					$unblocked_terms[$term->term_id]= $term->name;
				}
				else{
					//Send ID to Exclude the Taxonomy 
					$blocked_terms[]= $term->term_id;
				}
			}
			//print_R($blocked_terms);
			$contest_post = Ow_Vote_Shortcode_Model::ow_get_show_all_contest_query_sql($show_cont_args,$selected,$sort,$ajax,$blocked_terms,$ow_search);	
		}
		
		if($ajax != 1){										
				
				$check_grid_dzn = $vote_opt['vote_enable_all_contest'];
				
				if($check_grid_dzn == 'normal-grid') {
					$grid_option = 'normal-grid';
				} elseif($check_grid_dzn == 'flow-grid') {
					$grid_option = 'grid';
				} else{
					$grid_option = 'masonry-grid';
				}
				
				$swipe_option   = ($vote_opt['vote_all_contest_design'] == null)?'swipe-down':$vote_opt['vote_all_contest_design'];
						
				$filter_arr = array(
									'new_contestant' => __('Newest','voting-contest'),
									'old_contestant' => __('Oldest','voting-contest'),
									//'votes_top'		 => __('Most voted','voting-contest'),
									//'votes_down'	 => __('Least voted','voting-contest')
									);
				?>
				<div class="ow_vote_contest_all_top_bar ow_vote_contest_top_bar">
					
					<div class="ow_vote_all_contest_search"><?php echo do_shortcode('[owsearch]'); ?></div>
					
					<div class="ow_select_style ow_sort">
						<select id="ow_sort_select" name="ow_sort_select">
							<option><?php _e('Sort','voting-contest'); ?></option>
							<?php
								foreach($filter_arr as $key => $filter){
									$selected_val = ($sort == $key)?'selected':'';
									echo '<option value="'.$key.'" '.$selected_val.'>'.$filter.'</option>';
								}
							?>
						</select>						
					</div>
					
					<div class="ow_select_style">
						<select id="ow_tax_select" name="ow_tax_select">
							<option value="-1"><?php _e('All Categories','voting-contest'); ?></option>
							<?php
								foreach($unblocked_terms as $key => $term_cat){
									$selected_val_term = ($selected == $key)?'selected':'';
									echo '<option value="'.$key.'" '.$selected_val_term.'>'.$term_cat.'</option>';
								}
							?>
						</select>						
					</div>
					
					
				</div>
		<?php
		}
		
		if(!empty($show_cont_args)){
			foreach($show_cont_args as $args => $opt_glob){
				$$args = $opt_glob;
			}
		}
				
		if($contest_post){
				$i=1;
				global $post; 			
				if($ajax != 1):
				?>				
				<section class="grid-wrap ow_vote_view_0 ow_views_container" id="ow_views_container_0">
				<ul class="grid <?php echo $swipe_option; ?> ow_contest-posts-container0 ow_vote_post_container_show" id="<?php echo $grid_option; ?>">
				<?php endif ; ?>
				
				<?php 
				foreach ($contest_post as $post) {
						
						setup_postdata($post);
						
												
						
						$totvotesarr = get_post_meta(get_the_ID(), OW_VOTES_CUSTOMFIELD);
						$totvotes = isset($totvotesarr[0]) ? $totvotesarr[0] : 0;
						if($totvotes == NULL) $totvotes = 0;
						
						$votes_view = get_post_meta($post->ID, OW_VOTES_VIEWS, true);
						$votes_view = isset($votes_view) ? $votes_view : 0;
						if($votes_view == NULL) $votes_view = 0;
						
						$post_id 	= get_the_ID();
						$terms_pid 	= get_the_terms($post_id, OW_VOTES_TAXONOMY);
						foreach ($terms_pid as $term) {
							$termids[] = $term->term_id;
							$term_id = $term->term_id;				
							$cat_name    = $term->name;
						}
						
						$title_len= strlen(get_the_title());
						if($title_len > 25){
							$ow_title= mb_substr(get_the_title(),'0','25').'..';
						}else{
							$ow_title= get_the_title();
						}
						
						$cattitle_len = strlen($cat_name);
						if($cattitle_len > 20){
							$cat_title = mb_substr($cat_name,'0','20').'..';
						}else{
							$cat_title= $cat_name;
						}
						
						if (has_post_thumbnail(get_the_ID())) {
							$short_cont_image = ($short_cont_image=='')?'medium':$short_cont_image;
							$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $short_cont_image);
							$ow_original_img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())).'?'.uniqid();
							$ow_image_src = $ow_image_arr[0];

							$get_img_size=getimagesize(realpath($ow_image_src));
						   
							$img_width =($width!='')?$width:$get_img_size[0];
							
							if($style_image_overlay != null){						
								if($height == ''){
									$style_image_overlay = 'width:'.$img_width.'px;'.'height:'.$get_img_size[1].'px;';
								}
							}							
							
						}else{					
							
							$category_options = get_option($term_id.'_'.OW_VOTES_SETTINGS);
							$contest_type = $category_options['imgcontest'];
							
							if($contest_type == 'video'){
								$ow_image_src = Ow_Vote_Common_Controller::ow_vote_contestant_thumbnail($post_id);
							}
							else{
								$ow_image_src = OW_NO_IMAGE_CONTEST;									
							}
														
						}
								
						?>
						<li>
							<a href="<?php echo Ow_Vote_Common_Controller::ow_votes_get_contestant_link($post_id); ?>">
								<img width="300px" src="<?php echo $ow_image_src; ?>" alt="<?php echo $ow_title; ?>">
								<div>
									
									<span class="ow_all_font_size"><?php echo $ow_title; ?></span>
									<p>
										<?php if($vote_opt['vote_count_showhide'] == 'on'): ?>
										<span aria-hidden="true" class="ow_vote_icons votecontestant-check"></span><span class="ow_all_font_size"><?php echo $totvotes; ?></span>
										<?php endif; ?>
										<span aria-hidden="true" class="ow_vote_icons votecontestant-eye-open"></span><span class="ow_all_font_size"><?php echo $votes_view; ?></span>
										<span aria-hidden="true" class="ow_vote_icons voteconestant-star"></span><span class="ow_all_font_size"><?php echo $cat_title; ?></span>
									</p>
								</div>								
							</a>
						</li>
						<?php
				}
				if($ajax != 1):
				?>
				</ul>
				</section>
				<?php endif; ?>
				<input type="hidden" id="all_contest_page" value="1" /> 
				<?php
				
				if(isset($pagination) && $pagination != 0){
					
					$id = ($selected)? $selected : 0;
					//echo "<pre>";print_R($contest_post);
				    $pagination_type =  voting_wp_pagenavi();
				    
				    //Load More Option				    
				    if($pagination_type == 3 || ($_SESSION['ow_shortcode_count'] > 1 && $pagination_type == 4)){
					$pagination_option = get_option('contestpagenavi_options');					
					?>
						<div class="ow_jx_response ow_jx_response_<?php echo $id; ?>">
							
						</div>
						<div class="ow_vote_fancybox_result_infinite ow_jx_loader_<?php echo $id; ?>"></div>
						<?php if($ajax != 1): ?>
						<button class="ow_load_more_all" id="ow_load_<?php echo $id; ?>"><?php echo $pagination_option['load_more_button_text']; ?></button>				
						<input type="hidden" id="ow_category_options_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($category_options)); ?>" />
						<input type="hidden" id="ow_show_cont_args_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($show_cont_args)); ?>" />
						<input type="hidden" id="ow_show_global_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($vote_opt)); ?>" />
						
						<input type="hidden" id="ow_postperpage_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
						<input type="hidden" id="ow_offset_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
						<input type="hidden" class="ow_ajax_flag" value="1" />
						<?php endif; ?>
					<?php
				    }
				    //Infinite Scroll
				    else if($pagination_type == 4){
					$pagination_option = get_option('contestpagenavi_options');					
					?>
					<div class="ow_jx_response ow_jx_response_<?php echo $id; ?>"></div>
					<div class="ow_vote_fancybox_result_infinite ow_jx_loader_<?php echo $id; ?>"></div>
					<input type="hidden" id="ow_infinite_all" value="1" />
					<input type="hidden" id="ow_category_options_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($category_options)); ?>" />
					<input type="hidden" id="ow_show_cont_args_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($show_cont_args)); ?>" />
					<input type="hidden" id="ow_show_global_<?php echo $id; ?>" value="<?php echo base64_encode(serialize($vote_opt)); ?>" />
					<input type="hidden" id="ow_postperpage_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
					<input type="hidden" id="ow_offset_<?php echo $id; ?>" value="<?php echo $show_cont_args['postperpage']; ?>" />
					<input type="hidden" class="ow_ajax_flag" value="1" />
					<input type="hidden" class="ow_all_cat_id" value="<?php echo $id; ?>" />
					<?php
				    }
				    else{
					echo $pagination_type;
				    }
				    
				}
				wp_reset_postdata();			
				
				if($check_grid_dzn == 'flow-grid') { ?>
					<script type="text/javascript">
						new GridScrollFx( document.getElementById( 'grid' ), {
							viewportFactor : 0.4
						} );
					</script>
				<?php
				} 
				
			
		}
		else{
				if($ajax == 1){
					echo '<input type="hidden" class="ow_load_stop" value="-1" />';
				}
			}
		
	}
}else{
    die("<h2>".__('Failed to load Voting All Contestants Shortcode view','voting-contest')."</h2>");
}

?>
