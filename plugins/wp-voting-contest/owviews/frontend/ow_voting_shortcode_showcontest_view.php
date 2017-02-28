<?php
if(!function_exists('ow_voting_shortcode_showcontestant_view')){ 
    function ow_voting_shortcode_showcontestant_view($show_cont_args,$vote_opt,$category_options){
		if(get_query_var('page') == 0){
			$_SESSION['seed'] = "";
		}
		

		?>


		<?php

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

		/* Mod */

			?>

			<style type="text/css">

				.cnt .fullWidth{
					max-width: <?php the_field('contest_container_width', 'option') ?>px !important 
				}

				.cnt  .ow_vote_grid .ow_vote_img_style2 {
					margin-left:0px !important;
					width: <?php the_field('contest_contestant_width', 'option') ?>px !important;
					height: <?php the_field('contest_contestant_height', 'option') ?>px !important;
				}

				.cnt .ow_vote_grid .ow_show_contestant{
					width:<?php the_field('contest_contestant_width', 'option') ?>px !important;
					height: <?php the_field('contest_contestant_height', 'option') ?>px !important;	
				}

			</style>

			<?php
		
		/* end  */

		$tax_hide_photos_live = $show_cont_args['tax_hide_photos_live'];	
		$permalink = get_permalink( get_the_ID());

		//AmyTheme Fix
		if(get_current_theme() == "AMY Theme"){
		    $_SESSION['GET_VIEW_SHORTCODE'] = 0;
		}
		
		if(!isset($_SESSION['GET_VIEW_SHORTCODE'])){
			$_SESSION['GET_VIEW_SHORTCODE']=1;
		}
		else{
			$_SESSION['GET_VIEW_SHORTCODE']=$_SESSION['GET_VIEW_SHORTCODE']+1;
		}
				
		$_SESSION['ow_voting_page_permalink']=$permalink;
		
		$global_options =Ow_Vote_Common_Controller::ow_vote_get_all_global_settings($vote_opt);
		if(!empty($global_options)){
			foreach($global_options as $variab => $glob_opt){
				$$variab = $glob_opt;
			}
		}
		
		if(!empty($show_cont_args)){
			foreach($show_cont_args as $args => $opt_glob){
				$$args = $opt_glob;
			}
		}
		
		//Open/Close Mobile Menu
		if(!empty($vote_openclose_menu) && $vote_openclose_menu == 'on'){
			$menu_class = 'menu_open';
			echo "<style>@media (min-width:250px) and (max-width:767px) {ul.ow_vote_menu_links{display:block !important;}}</style>";			
		}
		else{
			$menu_class = "";
			echo "<style>@media (min-width:250px) and (max-width:767px) {ul.ow_vote_menu_links{display:none !important;}}</style>";			
		}
		
		$enc_termid = Ow_Vote_Common_Controller::ow_voting_encrypt($id);
		
		
		if (trim($title)){ ?>
			<div class="ow_contest_caption"><h1><?php echo $title; ?></h1></div>
		<?php
		}

		if(isset($_GET['contest']))
		    $action_url  = $_GET['contest'];
		else
		    $action_url  = '';
		    
		
		if(isset($_GET['contest_id']))
		    $contest_id = base64_decode($_GET['contest_id']);
		//Show timer on start/end contestants	
		if($showtimer) {
			if(Ow_Vote_Common_Controller::ow_vote_is_contest_started($id )){
				$out_html = do_shortcode('[endcontestants id='.$id.' message=1]');
			}else
				$out_html = do_shortcode('[upcomingcontestants id='.$id.' showcontestants=0 message=0]');
			
			echo $out_html;
		}		
		$total_count=Ow_Vote_Shortcode_Controller::ow_votes_total_count_votes($id,$category_options);
		echo $total_count;
			
		//Get the query to execute
		$contest_post = Ow_Vote_Shortcode_Model::ow_get_show_contest_query($show_cont_args);
		
		if(isset($global_options['vote_video_width'])){
		    $video_width = $global_options['vote_video_width'];
		}
		
		if(isset($global_options['vote_audio_width'])){
		    $audio_width = $global_options['vote_audio_width'];
		}
		if(isset($global_options['vote_audio_height'])){
		    $audio_height = $global_options['vote_audio_height'];
		}
		
		if(isset($global_options['vote_essay_width'])){
		    $essay_width = $global_options['vote_essay_width'];
		}
		
				
		if($vote_prettyphoto_disable != 'on'){ //PrettyPhoto Disable is on
		    echo "<input type='hidden' class='vote_prettyphoto_disable' value='1' />";
		}
		else{
		    echo "<input type='hidden' class='vote_prettyphoto_disable' value='0' />";
		}
		?>
		

		<?php /*  mod_start  */  ?>

		<script src="https://www.google.com/recaptcha/api.js"></script>
		
		<div id="capch" class="hide">
			<div id="capch_inner">
				
				<?php
					$public_key = '6Lf25hYUAAAAAEpdC5xON4INOq5YgbFgcIrm4l6l';
					$secret_key = '6Lf25hYUAAAAAFngEzG2VPIaN3gG48gnIlWjJqb0';
				?>     
				
				<div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>

			</div>    
		</div>

		<div id="ow_vote_rules" class="hide"> 
			 <?php echo do_shortcode('[rulescontestants id='.$id.']'); ?>   
		</div>	

		<?php /*  mod_end  */ ?>

		<div class="ow_vote_show_contestants">
			<?php if($category_options['imgcontest']=='music'){ ?>
			<style>
				.ow_music_contest.ow_vote_grid .ow_msc_featureimage img{<?php echo ($audio_width)?'width: '.$audio_width.'px !important;':''; ?> <?php echo ($audio_height)?'height: '.$audio_height.'px !important;':''; ?>}
				.ow_music_contest.ow_vote_grid .ajs-controls{<?php echo ($audio_width)?'width: '.$audio_width.'px !important;':''; ?> <?php echo ($audio_height)?'height: '.$audio_height.'px !important;':''; ?>}
				.ow_music_contest.ow_vote_grid .vote_right_side_content{<?php echo ($audio_width)?'width: '.$audio_width.'px !important;':''; ?>}
			</style>
			<?php } if($category_options['imgcontest']=='video'){?>
			<style>
			     .ow_video_contest.ow_vote_grid .ow_show_text_desc,.ow_video_contest.ow_vote_grid .ow_video_get{width: <?php echo $video_width; ?>px;}		
			</style>
			<?php } if($category_options['imgcontest']=='essay'){?>
			<style>
			    .ow_vote_grid .ow_essay_get{width: <?php echo $essay_width; ?>px;}
			</style>
			<?php } ?>
			
			<div class="ow_vote_contest_top_bar">
				<div class="ow_tog menudiv">
					<a href="javascript:" class="togglehide"><span class="ow_vote_icons votecontestant-menu-down <?php echo $menu_class; ?>"></span></a>
				</div>
				<?php
					if (false !== strpos($permalink,'?')){
						$url_prefix = '&amp;';
					}
					else{
						$url_prefix = '?';
					}
					
					if(($vote_onlyloggedcansubmit!=''||$onlyloggedinuser!='') && !is_user_logged_in()){
						if($_SESSION['GET_VIEW_SHORTCODE']==1)
						Ow_Vote_Shortcode_Controller::ow_votes_custom_registration_fields_show();
					}
					
					if($vote_onlyloggedcansubmit!='' && !is_user_logged_in()){
						$login_class="ow_logged_in_enabled";
					}
					else{$login_class="ow_loggin_disabled";}
				?>
				<ul class="ow_vote_menu_links">					
					<?php					
					if(($showform != 0) && $global_options['vote_entry_form'] != 1){				    
					    $votes_start_time=get_option($id . '_' . OW_VOTES_TAXSTARTTIME);
					    $votes_end_time  = get_option($id. '_' . OW_VOTES_TAXEXPIRATIONFIELD);
					    $current_time = current_time( 'timestamp', 0 );
					   
					    if((($votes_end_time != '' && strtotime($votes_end_time) > $current_time) || $votes_end_time == '') &&
					       (($votes_start_time !='' && strtotime($votes_start_time) > $current_time && $votes_end_time=='')
					       || ($votes_start_time == '' && $votes_end_time != '' && strtotime($votes_end_time) < $current_time)
					       || ($votes_start_time=='' && $votes_end_time == '') || ($votes_start_time=='' && $votes_end_time != '')
					       || $showform == 1
					       || ($votes_end_time != '' && strtotime($votes_end_time) > $current_time && $votes_start_time !='' && strtotime($votes_start_time) > $current_time))){
					    ?>
					    <li class="ow_vote_navmenu_link">
						
						    <a class="<?php echo $login_class; ?> ow_vote_submit_entry" data-id="<?php echo $id; ?>">
							<span class="ow_vote_icons voteconestant-edit" aria-hidden="true"></span>
							<?php _e('Submit Entry','voting-contest'); ?>
						    </a>
						    <input type="hidden" name="open_button_text" class="open_btn_text<?php echo $id; ?>"
									       value="<span class='ow_vote_icons voteconestant-edit' aria-hidden='true'></span>
									       <?php _e('Submit Entry','voting-contest'); ?>"/>
						    <input type="hidden" name="close_button_text"  class="close_btn_text<?php echo $id; ?>"
															    value="<?php _e('Close','voting-contest'); ?>"/>
					    </li>
					    <?php
					    }
					    //else{						
						//StartTime Limit
						if($votes_start_time !='' && strtotime($votes_start_time) > $current_time){
						    echo "<input type='hidden' id='ow_contest_closed_".$id."' value='start' />";
						    $closed_desc = $global_options['vote_tobestarteddesc'];
						    echo "<input type='hidden' class='ow_contest_closed_desc' value='".$closed_desc."' />";
						}
						
					    //}
					}
					?>
					
					<li class="ow_vote_navmenu_link <?php echo (isset($action_url) && $action_url=='gallery' && $contest_id==$id)?'ow_active_gallery active':''; ?>">
						<a href="<?php echo $permalink.$url_prefix.'contest=gallery&amp;contest_id='.base64_encode($id);?>">
							<span class="ow_vote_icons voteconestant-camera"></span><?php _e('Gallery','voting-contest'); ?>
						</a>
					</li>
					
					<?php
					if($category_options['top_ten_count']!='' && $contest_post) { ?>
					<li class="ow_vote_navmenu_link <?php echo (isset($action_url) && $action_url=='topcontestant' && $contest_id==$id)?'ow_active_topcontest active':''; ?>" >
						<a href="<?php echo $permalink.$url_prefix.'contest=topcontestant&amp;contest_id='.base64_encode($id); ?>">
							<span class="ow_vote_icons voteconestant-star"></span><?php _e('Top 10','voting-contest'); ?>
						</a>
					</li>
					<?php } ?>
					
					<?php
					
					if($category_options['vote_contest_rules']!=''){ ?>
						<li id="vote_id_<?php echo $id; ?>" class="ow_vote_navmenu_link ow_vote_navmenu_link_rules <?php echo ((isset($action_url) && $action_url=='contestrules') && $contest_id==$id)?'ow_active_contest_rules active':''; ?>">
							<a href="<?php echo $permalink.$url_prefix.'contest=contestrules&amp;contest_id='.base64_encode($id); ?>">
								<span class="ow_vote_icons voteconestant-gift"></span><?php _e('Rules and Prizes','voting-contest'); ?>
							</a>
						</li>
					<?php } ?>
					
					<?php if($sort_by==1){ ?>
					<li class="ow_vote_menu_sort">
						<form name="select_filter" id="ow_vote_select_filter<?php echo $id; ?>" method="post">
						<?php
						
						if(isset($_SESSION['ow_vote_search_filter'.$id])){
						    $session_filter = $_SESSION['ow_vote_search_filter'.$id];
						}
						else{
						    $session_filter = '';
						}						
						?>
						<select name="filter_votes" class="ow_vote_filter_votes" id="<?php echo $id; ?>">
						<option value="sort">Sort</option>
						<option value="new_contestant" <?php echo($session_filter=='new_contestant')?'selected':'';?>><?php _e('Newest','voting-contest'); ?></option>
						<option value="old_contestant" <?php echo($session_filter=='old_contestant')?'selected':'';?>><?php _e('Oldest','voting-contest'); ?></option>
						<?php if($category_options['votecount'] != 'on'): ?>
						<option value="votes_top" <?php echo($session_filter=='votes_top')?'selected':'';?>><?php _e('Most voted','voting-contest'); ?></option>
						<option value="votes_down"<?php echo($session_filter=='votes_down')?'selected':'';?> ><?php _e('Least voted','voting-contest'); ?></option>
						<?php endif; ?>
						</select>
						<input type="hidden" name="filter_view" id="ow_filter_view<?php echo $id; ?>" />
						<input type="hidden" name="category_id" value="<?php echo $id; ?>">
						</form>
					</li>
					<?php } ?>
					
					<?php
						if($action_url =='topcontestant' && $contest_id != $id){
						
							if($view==''){
							//By default list view
							$view = 'list';$default_view='list';
							}
						}
					if($category_options['imgcontest']=='photo' && current_user_can('administrator')){
						 apply_filters('ow_navigation_bar_hook',$id);	
					}
					if($action_url !='topcontestant' && $action_url != 'contestrules') { ?>
							<?php if($view==''){
								//By default list view
								$view = 'list';$default_view='list';
								if(isset($_POST['filter_view'])){$view =$_POST['filter_view']; } 
							?>
							<?php }
							
							if($category_options['list_grid_hide']==''){
							?>
							<li class="ow_vote_float_right ow_vote_no_border">
								<a class="ow_vote_list_show_contest ow_vote_icons voteconestant-list <?php echo ($view=='list')?'ow_list_active':'';?>" data-id="<?php echo $id;?>" id="list_show<?php echo $id; ?>">
								</a>
								<a class="ow_vote_grid_show_contest ow_vote_icons voteconestant-grid <?php echo ($view=='grid')?'ow_grid_active':'';?>" data-id="<?php echo $id;?>" id="grid_show<?php echo $id; ?>">
								</a>
							</li>
							<?php }
					} ?>
				</ul>
			</div>
			
			<?php
			if($showform!=0){
				$add_contestant_html = do_shortcode('[addcontestants id='.$id.' showcontestants=0 displayform=1 contestshowfrm=0 message=0]');	
				echo $add_contestant_html;
			}
		
			switch($action_url){
				case 'topcontestant':
					if($contest_id==$id){
						$html_out = do_shortcode('[topvotecontestants id='.$id.']');
						wp_reset_postdata();
						echo $html_out."</div>";
						return;
					}
				break;
				case 'contestrules':					
					return;
				break;
			}
			

		if($contest_post=='error'){
			return '<div class="ow_vote_show_contestants">'._e('No contestants to display.','voting-contest').'</div>';
		}

		$image_contest = $category_options['imgcontest'];
		
		$video_class = ($image_contest =='on' || $image_contest =='video')?'ow_video_contest':'ow_'.$image_contest.'_contest';
		$video_class_get = ($image_contest =='on' || $image_contest =='video')?'ow_video_get':'ow_'.$image_contest.'_get';
			
		$title_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_title();
		echo '<input type="hidden" id="contest_post_'.$id.'" value="'.$id.'" />';
		$uploads = wp_upload_dir();
		$export_path = $uploads['url'].'/first_last.pdf';
		echo '<input type="hidden" class="ow_upload_directory" value="'.$export_path.'" />';
		if($contest_post){
			if ($contest_post->have_posts()) {
				?>				
				<div id="ow_views_container_<?php echo $id; ?>" class="ow_vote_view_<?php echo $id.' ow_vote_'.$view.' '.$video_class;?> ow_views_container" data-view="<?php echo $view; ?>">
					<div class="ow_contest-posts-container<?php echo $id; ?> ow_vote_post_container_show">
					<?php
					while ($contest_post->have_posts()) {
						$contest_post->the_post();
						$totvotesarr = get_post_meta(get_the_ID(), OW_VOTES_CUSTOMFIELD);
						$totvotes = isset($totvotesarr[0]) ? $totvotesarr[0] : 0;
						if($totvotes == NULL) $totvotes = 0;
				
						$enc_postid = Ow_Vote_Common_Controller::ow_voting_encrypt(get_the_ID());
						
						/*********** Style ***********/
						$style_image = $style_image_overlay = '';
						if($height!=''){
							$style_image .='height:'.$height.'px;';
							$style_image_overlay .='height:'.$height.'px;';
						}
						if($width!=''){
							$style_image .='width:'.$width.'px;';
							$style_image_overlay .='width:'.$width.'px;';
						}
						$adv_excerpt = Ow_Vote_Excerpt_Controller::Instance();
						$shor_desc = $adv_excerpt->filter(get_the_excerpt());
						$term = get_term( $id, OW_VOTES_TAXONOMY );
					?>
					<div class="ow_vote_showcontent_view <?php echo $video_class_get; ?> ow_vote_showcontent_<?php echo $id; ?>" data-id="<?php echo $id; ?>">
						<div class="ow_vote_show">
							<div class="ow_show_contestant ow_pretty_content<?php echo get_the_ID(); ?>">
								<?php
								//Title code
								if($vote_truncation_list!=''){
									$title_string = strlen(get_the_title());
									if($title_string > $vote_truncation_list){
										$list_details = mb_substr(get_the_title(),'0',$vote_truncation_list).'..';
									}else{
										$list_details= get_the_title();
									}
								}
								else{
									$title_len= strlen(get_the_title());
									if($title_len > 50){
										$list_details= mb_substr(get_the_title(),'0','50').'..';
									}else{
										$list_details= get_the_title();
									}
								}
							
								if($vote_truncation_grid!=''){
									$grid_details = strlen(get_the_title());
									if($grid_details > $vote_truncation_grid){
										$grid_details = mb_substr(get_the_title(),'0',$vote_truncation_grid).'..';
									}else{
										$grid_details= get_the_title();
									}
								}else{
									$title_len= strlen(get_the_title());
									if($title_len > 15){
										$grid_details = substr(get_the_title(),'0','15').'..';
									}else{
										$grid_details = get_the_title();
									}
								}
								
								if($view=='list' || $view ==''){
									$title_vote = $list_details;
								}elseif($view=='grid'){
									$title_vote = $grid_details;
								}
								
								
								//Image code
								$ow_image_alt_text=Ow_Vote_Common_Controller::ow_vote_seo_friendly_alternative_text(get_the_title());	
								$perma_link = get_permalink(get_the_ID());
								
								if($tax_hide_photos_live == 0){
								    $thumb = 0;
								}
								if($thumb!='' && $thumb!=0){								    
									$show_desc_pretty = Ow_Vote_Shortcode_Model::ow_vote_show_desc_prettyphoto();
									$pretty_excerpt = ($show_desc_pretty == 1)?strip_tags($shor_desc):'';
									if (has_post_thumbnail(get_the_ID())) {
									    $short_cont_image = ($short_cont_image=='')?'thumbnail':$short_cont_image;
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
									    
									    echo '<input type="hidden" class="ow_vote_img_width'.$id.'" value="'.$img_width.'">';
										
									}else{
									    $ow_image_src = OW_NO_IMAGE_CONTEST;
									    $ow_original_img = OW_NO_IMAGE_CONTEST;							    
									    $no_image_overlay = Ow_Vote_Common_Controller::ow_vote_image_proportionate($ow_image_src,$width);
									    if($height == ''){
									    $style_image_overlay = 'width:'.$no_image_overlay[0].'px;'.'height:'.$no_image_overlay[1].'px;';
									    }
									}
									$perma_link = Ow_Vote_Common_Controller::ow_votes_get_contestant_link(get_the_ID());
									$ow_original_img = ($vote_prettyphoto_disable != 'on')?$ow_original_img:$perma_link;
									if(($vote_title_alocation!='on') || ($view=='list' || $view=='')){
									?>
									<div class="vote_left_side_content vote_left_sid<?php echo $id; ?>">
										<a alt="<?php echo $ow_image_alt_text; ?>" class="ow_hover_image_disable"  data-pretty-title="<?php echo $pretty_excerpt; ?>" href="<?php echo $ow_original_img; ?>" data-vote-id="<?php echo get_the_ID(); ?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $id; ?>" data-vote-gallery="ow_vote_prettyPhoto[<?php echo $term->name; ?>]">
											<?php if($vote_prettyphoto_disable != 'on'): //PrettyPhoto Disable is on ?>
											<div class="ow_overlay_bg ow_overlay_<?php echo get_the_ID(); ?>" style="<?php echo $style_image_overlay; ?>">
												<span><i class="ow_vote_icons voteconestant-zoom"></i></span>
											</div>
											<?php endif; ?>
											<img class="ow_vote_img_style<?php echo $id; ?>" id="ow_image_responsive<?php echo get_the_ID(); ?>" src="<?php echo $ow_image_src; ?>" style="<?php echo $style_image; ?>" alt="<?php echo $ow_image_alt_text; ?>" data-pretty-alt="<?php echo ($title_rs[0]->pretty_view == 'Y')?$ow_image_alt_text:''; ?>"/>
										</a>
									</div>
									<?php
									}
									$ow_vote_full_width_class = '';
								}else{
									$ow_vote_full_width_class = 'ow_full_width_class';
								}
								?>
								<div class="vote_right_side_content ow_right_dynamic_content<?php echo $id; ?>">
									<input type="hidden" class="ow_title_alocation_description<?php echo $id; ?>" value="<?php echo $vote_title_alocation; ?>">
									<input type="hidden" class="ow_image_contest<?php echo $id; ?>" value="<?php echo $image_contest; ?>">
									<input type="hidden" class="ow_show_description<?php echo $id; ?>" value="<?php echo $category_options['show_description']; ?>">
										<?php
										if($image_contest=='photo'){
											if(($vote_title_alocation=='on' && $view=='grid') || ($view=='list' || $view=='')) {
												ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
												if($ow_vote_full_width_class=='' && $vote_title_alocation=='on' && $view=='grid'){
													?>
													<div class="vote_left_side_content vote_left_sid<?php echo $id; ?>">
														<a alt="<?php echo $ow_image_alt_text; ?>" class="ow_hover_image"  data-pretty-title="<?php echo $pretty_excerpt; ?>" href="<?php echo $ow_original_img; ?>" data-vote-id="<?php echo get_the_ID(); ?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $id; ?>" data-vote-gallery="ow_vote_prettyPhoto[<?php echo $term->name; ?>]">
															<?php if($vote_prettyphoto_disable != 'on'): //PrettyPhoto Disable is on ?>
															<div class="ow_overlay_bg ow_overlay_<?php echo get_the_ID(); ?>" style="<?php echo $style_image_overlay; ?>">
																<span><i class="ow_vote_icons voteconestant-zoom"></i></span>
															</div>
															<?php endif; ?>
															<img class="ow_vote_img_style<?php echo $id; ?>" id="ow_image_responsive<?php echo get_the_ID(); ?>" src="<?php echo $ow_image_src; ?>" style="<?php echo $style_image; ?>" alt="<?php echo $ow_image_alt_text; ?>" data-pretty-alt="<?php echo ($title_rs[0]->pretty_view == 'Y')?$ow_image_alt_text:''; ?>"/>
														</a>
													</div>
													<?php
												}
											}
										}
										if($image_contest == "essay"){
											ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
										}
										?>
										<?php if($image_contest=='music'){
											$ow_image_src_msc = '';
											if (has_post_thumbnail(get_the_ID())) {
												if($audio_height !='' && $audio_width!=''){
													$short_cont_image = array( $audio_width, $audio_height);
												}else
												$short_cont_image = ($short_cont_image=='')?'thumbnail':$short_cont_image;
												
												$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $short_cont_image);
												$ow_image_src_msc = $ow_image_arr[0];
											}
											
											if($shor_desc != null){
													$shor_desc_music = strip_tags($shor_desc); 
											}
											$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
											if(!empty($custom_entries)){
												$field_values = $custom_entries[0]->field_values;
												if(base64_decode($field_values, true))
													$field_val = maybe_unserialize(base64_decode($field_values));  
												else
													$field_val = maybe_unserialize($field_values);

												if(($vote_title_alocation=='on' && $view=='grid') || ($view=='list' || $view=='')) { 
													ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
												}?>
												<div class="ow_show_text_desc ow_show_desc_view_<?php echo $id.' '.$ow_vote_full_width_class; ?> ow_msc_content<?php echo $id; ?>">
													<div class="audio-js-box <?php echo $global_options['vote_audio_skin']; ?>">
														<audio class="audio-js" data-description="<?php echo $title_vote;?>" datafeatured-img ="<?php echo $ow_image_src_msc;?>" preload="none" controls >
														  <source src="<?php echo $field_val['contestant-ow_video_url']; ?>" type="audio/mpeg">
														</audio>
													</div>
												</div>
												<?php
												if($vote_title_alocation=='off' && $view=='grid') { 
													ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
												}											
											}else{
												if(($vote_title_alocation=='on' && $view=='grid') || ($view=='list' || $view=='')) {
													ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
												}?>
												<div class="ow_show_text_desc ow_music_contest ow_show_desc_view_<?php echo $id.' '.$ow_vote_full_width_class; ?> ow_msc_content<?php echo $id; ?>">
													<div class="audio-js-box <?php echo $global_options['vote_audio_skin']; ?>">
														<audio class="audio-js" data-description="<?php echo $title_vote;?>" datafeatured-img ="<?php echo $ow_image_src_msc;?>" preload="none" controls>
														  <source src="<?php echo $shor_desc_music; ?>" type="audio/mpeg">
														</audio>
													</div>
												</div>
												<?php
												if($vote_title_alocation=='off' && $view=='grid') { 
													ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
												}
											}
											echo $shor_desc;
										}
										else if($image_contest == "video"){
											$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
											if(!empty($custom_entries)){
												$field_values = $custom_entries[0]->field_values;
												if(base64_decode($field_values, true))
													$field_val = maybe_unserialize(base64_decode($field_values));  
												else
													$field_val = maybe_unserialize($field_values);
													
											}
											if(!empty($field_val['contestant-ow_video_url'])){
												if(($vote_title_alocation=='on' && $view=='grid') || ($view=='list' || $view=='')) {
													ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
												}
												?>
												<div class="ow_show_text_desc video_contest_desc<?php echo $id;?>">
												<?php
												echo do_shortcode('[owvideo width='.$video_width.' align=left]'.$field_val['contestant-ow_video_url'].'[/owvideo]');
												echo '</div>';
											}
											if($vote_title_alocation=='off' && $view=='grid') { 
												ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
											}
											?>
											<div class="ow_show_text_desc ow_show_desc_view_<?php echo $id.' '.$ow_vote_full_width_class; ?>">
											<?php
												if($shor_desc != null){
													echo $shor_desc; 
												}
												echo '</div>';    
										}
										else{
											if($vote_title_alocation=='off' && $view=='grid' && $image_contest != "essay") { 
												ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay);
											}
											?>
											<div class="ow_show_text_desc ow_show_desc_view_<?php echo $id.' '.$ow_vote_full_width_class; ?>">
											<?php
											echo $shor_desc;
											echo '</div>';    
										}
										?>
										
										<?php
											$authordisplay = $category_options['authordisplay'];
											$authornamedisplay = $category_options['authornamedisplay'];
											$author = get_the_author();
											$author_email = get_the_author_meta( 'user_email' );
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
										<?php
										
										$custom_fields  = Ow_Contestant_Model::ow_voting_get_all_custom_fields(get_the_ID()); 
										$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
										$condition = ($view == 'grid')?'grid_only':'list_only';
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
												if(($custom_field->grid_only=='Y' || $custom_field->list_only=='Y') && !in_array($custom_field->system_name,array('contestant-desc','contestant-title','contestant-ow_video_url'))){
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
											<?php
											$ow = 0;
											foreach($custom_fields as $custom_field){												
												if($custom_field->system_name != 'contestant-desc' && $custom_field->system_name != 'contestant-title' && $custom_field->system_name != 'contestant-ow_video_url'){
													$ow++;
													$list_only_class = ($custom_field->list_only == 'Y')?'ow_list_only':'';
													$grid_only_class = ($custom_field->grid_only == 'Y')?'ow_grid_only':'';
													
													$list_only_class = ($custom_field->list_only == 'N' && $custom_field->grid_only == 'N')?'hide':'';
													
												//if($custom_field->grid_only=='Y'){
													if($field_val[$custom_field->system_name] != ''){
												?>
														<div class="ow_contestant_other_det ow_custom_fields<?php echo $ow; ?> <?php echo $list_only_class.' '.$grid_only_class; ?>">
															<?php if($custom_field->show_labels == "Y"){ ?>
																<span><strong><?php echo $custom_field->question.': ';?></strong></span>
															<?php } ?>
															<span>
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
															</span>
														</div>
														<?php
														}
													//}
												}
											}
											echo '</div>'; // Close of Div .ow_contestant_custom_fields
											}										
										}
										if($view == "grid"){
											?><script type="text/javascript">jQuery(document).ready(function(){jQuery('.ow_grid_only').show();jQuery('.ow_list_only').hide();});</script><?php 
										}
										if($view == "list"){
											?><script type="text/javascript">jQuery(document).ready(function(){jQuery('.ow_grid_only').hide();jQuery('.ow_list_only').show();});</script><?php 
										}
										?>		
										<?php
										if($shor_desc!='' && $vote_readmore=='off'){ ?>
										<div class="ow_show_read_more <?php echo $ow_vote_full_width_class; ?>">
											<?php $perma_link = Ow_Vote_Common_Controller::ow_votes_get_contestant_link(get_the_ID()); ?>
											<a href="<?php echo $perma_link; ?>" title="<?php echo $ow_image_alt_text; ?>">
											<?php _e('More Info','voting-contest'); ?></a>
										</div>
										<?php } ?>
																	
								</div>	
							</div>
							
							<div class="ow_show_vote_cnt ow_pretty_content_social<?php echo get_the_ID(); ?>">
								<div class="ow_show_share_icons_div ow_fancy_content_social<?php echo get_the_ID(); ?>">
									<?php if($facebook!='off') { ?>

										<?php

											/* Facebook */

											$share_facebook = get_field('share_facebook', 'option'); 
											$share_facebook = str_replace('{url}', $perma_link, $share_facebook);
											$share_facebook = str_replace('{title}', get_the_title(), $share_facebook);
											$share_facebook = htmlentities($share_facebook);


											/* Twitter */

											$share_twitter = get_field('share_twitter', 'option'); 
											$share_twitter = str_replace('{url}', $perma_link, $share_twitter);
											$share_twitter = str_replace('{title}', get_the_title(), $share_twitter);
											$share_twitter = htmlentities($share_twitter);


											/* Pinterest */

											$share_pinterest = get_field('share_pinterest', 'option'); 
											$share_pinterest = str_replace('{url}', $perma_link, $share_pinterest);
											$share_pinterest = str_replace('{title}', get_the_title(), $share_pinterest);
											$share_pinterest = str_replace('{media}', $ow_image_src, $share_pinterest);
											$share_pinterest = htmlentities($share_pinterest);


											/* Tumblr */

											$share_tumblr = get_field('share_tumblr', 'option'); 
											$share_tumblr = str_replace('{url}', $perma_link, $share_tumblr);
											$share_tumblr = str_replace('{title}', htmlentities(get_the_title(),ENT_QUOTES), $share_tumblr);
											$share_tumblr = str_replace('{media}', $ow_image_src, $share_tumblr);
											$share_tumblr = htmlentities($share_tumblr);


											/* Google Plus */
											
											$share_plus = get_field('share_plus', 'option'); 
											$share_plus = str_replace('{url}', $perma_link, $share_plus);
											$share_plus = htmlentities($share_plus);



										?>
										<a class="ow_show_share_icons" title="<?php _e('Share on Facebook','voting-contest'); ?>" data-ref="&#xe027;" target="_blank" 
										href="http://www.facebook.com/sharer.php?<?php echo $share_facebook; ?>">
										</a>
									<?php }if($twitter!='off') { ?>
										<a class="ow_show_share_icons" title="<?php _e('Share on Twitter','voting-contest'); ?>" data-ref="&#xe086;" target="_blank"
										href="http://twitter.com/home?<?php echo $share_twitter; ?>">
										</a>
									<?php }if($pinterest!='off') { ?>
										<a class="ow_show_share_icons" title="<?php _e('Share on Pinterest','voting-contest'); ?>" data-ref="&#xe064;" target="_blank"
										href="http://www.pinterest.com/pin/create/button/?<?php echo $share_pinterest; ?>">
										</a>
									<?php }if($tumblr!='off') {?>
										<a class="ow_show_share_icons" title="<?php _e('Share on Tumblr','voting-contest'); ?>" data-ref="&#xe085;" target="_blank"
										 href="http://www.tumblr.com/share/photo?<?php echo $share_tumblr; ?>">
										</a>
									<?php }if($gplus!='off') { ?>
										<a class="ow_show_share_icons" title="<?php _e('Share on Google Plus','voting-contest'); ?>" data-ref="&#xe039;" target="_blank"
										href="https://plus.google.com/share?url=<?php echo $share_plus; ?>">
										</a>
									<?php } ?>
								</div>
								
								<?php if($category_options['votecount']==''){?>
								<div class="ow_show_vote_square">
									<span class="ow_vote_cnt_num ow_vote_count<?php echo get_the_ID(); ?>"><?php echo $totvotes; ?></span>
									<span class="ow_vote_cnt_content"><?php _e('Votes','voting-contest'); ?></span>
								</div>
								<?php }
								
								$email_class= "";
								
								//Grab Email Address for IP and COOKIE
								if($vote_grab_email_address == "on" && $vote_tracking_method != 'email_verify'){
									Ow_Vote_Shortcode_Controller::ow_voting_email_grab();
									$email_class = "ow_voting_grab"; 
								}
								
								//Vote Settings
								if($vote_tracking_method == 'cookie_traced'){
									$browseragent = Ow_Vote_Save_Controller::ow_cookie_voting_getBrowser();               
									$voter_cookie = $browseragent['name'].'@'.$id.'@'.get_the_ID();          
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
								 
								   if(!isset($_SESSION['verified_votes_random_string']) || (isset($_SESSION['votes_random_string']) && $_SESSION['verified_votes_random_string']!=$_SESSION['votes_random_string']))
								   $email_class = "ow_voting_email";				   
								   
								   Ow_Vote_Shortcode_Controller::ow_votes_custom_email_form();
								}
								else{ 
								   if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') {
									$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
									} else {
									$ip = $_SERVER['REMOTE_ADDR'];
								   }
								}
								$exipration = $id. '_' . OW_VOTES_TAXEXPIRATIONFIELD;
								$dateexpiry =  get_option($exipration);
								$cur_time = current_time( 'timestamp', 0 );
								if($dateexpiry==''){
									$dateexpiry = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
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
									$is_votable = Ow_Vote_Save_Controller::check_contestant_is_votable(get_the_ID(), $ip, $id);
									$count_user_vote = Ow_Vote_Save_Controller::is_current_user_voted_count(get_the_ID(), $ip, $id);
									$count_user_vote = ($count_user_vote!='')?$count_user_vote:'0';
									if(!$is_votable){
									    $green_class = (Ow_Vote_Save_Controller::is_current_user_voted_post_id(get_the_ID(), $ip, $id))?'ow_voting_green_button':'';
										if($vote_votingtype != null && $frequency == 11){
										    $grey_class = (Ow_Vote_Save_Controller::is_current_user_voted(get_the_ID(), $ip, $id))?'':'ow_voting_grey_button';
											if($vote_votingtype==1 || $vote_votingtype_val==2){
												if($grey_class=='' && $green_class!='')
												$grey_class=$green_class;
											}
										}
										else
											$grey_class = ($green_class)?$green_class:'ow_voting_grey_button';
										?>
											<div class="ow_show_vote_button">
												<a class="ow_votebutton <?php echo $email_class.' '.$grey_class .' '.$ow_login_class; ?> voter_a_btn_term<?php echo $id; ?>" data-vote-count="<?php echo $category_options['vote_count_per_contest'];?>" data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $id; ?>" data-vote-id="<?php echo get_the_ID(); ?>" data-frequency-count="<?php echo $vote_frequency_count; ?>" data-current-user-votecount="<?php echo $count_user_vote; ?>" data-voting-type="<?php echo $vote_votingtype_val; ?>" >
												<span class="ow_vote_button_content votr_btn_cont<?php echo get_the_ID();?> voter_btn_term<?php echo $id; ?>"><?php _e('Voted','voting-contest') ;?></span>
												</a>
											</div>
										<?php
									}else{
										?>
										<div class="ow_show_vote_button">
											<a class="ow_votebutton <?php echo $email_class.' '.$ow_login_class; ?> voter_a_btn_term<?php echo $id; ?>"  data-enc-id="<?php echo $enc_termid; ?>" data-enc-pid="<?php echo $enc_postid; ?>" data-term-id="<?php echo $id; ?>" data-vote-count="<?php echo $category_options['vote_count_per_contest'];?>" data-vote-id="<?php echo get_the_ID(); ?>" data-frequency-count="<?php echo $vote_frequency_count; ?>" data-current-user-votecount="<?php echo $count_user_vote; ?>" data-voting-type="<?php echo $vote_votingtype_val; ?>" >
											<span class="ow_vote_button_content votr_btn_cont<?php echo get_the_ID();?> voter_btn_term<?php echo $id; ?>"><?php _e('Vote Now','voting-contest') ;?></span>
											</a>
										</div>
										<?php
									}
									
								}
								?>
								
							</div>
							
						</div>				
					</div>
				<?php
				}
				
				if(isset($pagination) && $pagination != 0){
				    $pagination_type =  voting_wp_pagenavi(array('query' => $contest_post),$id);
				    
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
				    
				}
				wp_reset_postdata();
				?>
				</div>				
				</div>
				<?php
			}else{
				echo '<div class="ow_votes_error">'.__('No Contestants to display.','voting-contest').'</div>';				
			}
			}else{
				echo '<div class="ow_votes_error">'.__('There is an error with shortcode. Please check the overview page for examples','voting-contest').'</div>';
			}
			?>
		</div>
	<?php
    }
}else{
    die("<h2>".__('Failed to load Voting Shortcode view','voting-contest')."</h2>");
}

if(!function_exists('ow_title_show_category')){
	function ow_title_show_category($vote_title_alocation,$view,$ow_vote_full_width_class,$id,$perma_link,$title_vote,$grid_details,$term,$termdisplay){		
		 $perma_link = Ow_Vote_Common_Controller::ow_votes_get_contestant_link(get_the_ID());
		 ?>
		 <div class="ow_vote_title_content<?php echo $id; ?>">
			<h2 class="<?php echo $ow_vote_full_width_class; ?> ow_list_title<?php echo $id; ?>">
				<a href="<?php echo $perma_link; ?>"><?php echo $title_vote; ?></a>
			</h2>
			<h2 style="display: none;" class="<?php echo $ow_vote_full_width_class; ?> ow_grid_title<?php echo $id; ?>">
				<a href="<?php echo $perma_link; ?>"><?php echo $grid_details; ?></a>
			</h2>									
			<?php
			if(strlen($term->name)>29)
				$category_name = substr($term->name,'0','30').' <b>..</b>';
			else
				$category_name = $term->name;
				
			if ($termdisplay==1) {
				?>
				<div class="ow_show_category <?php echo $ow_vote_full_width_class; ?>">
				<?php _e('Category : ','voting-contest') ?><span><?php echo $category_name; ?></span>
				</div>
			<?php	
			}?>
		</div>
		<?php 
	}
}
?>




