<?php
if(!function_exists('ow_voting_shortcode_top_contestants_view')){
    function ow_voting_shortcode_top_contestants_view($args){
		$vote_opt = get_option(OW_VOTES_SETTINGS);
		$inter = Ow_Vote_Common_Controller::ow_vote_get_thumbnail_sizes($vote_opt['short_cont_image']);
		$height_tr =explode('--',$inter);
		$width_t =$height_tr[0];
		$height_t = $height_tr[1];

		$height = $height_t ? $height_t : '';
		$width = $width_t ? $width_t : '';
		
		extract( shortcode_atts( array(
		  'id' => NULL,
		  'height' => $height,
		  'width' => $width
		), $args ) );


		$global_options =Ow_Vote_Common_Controller::ow_vote_get_all_global_settings($vote_opt);
		
		if(isset($global_options['vote_video_width'])){
		    $video_width = $global_options['vote_video_width'];
		}
		
		if(isset($global_options['vote_audio_width'])){
		    $audio_width = $global_options['vote_audio_width'];
		}
		if(isset($global_options['vote_audio_height'])){
		    $audio_height = $global_options['vote_audio_height'];
		}

		$contest_post = Ow_Vote_Shortcode_Model::ow_get_top_contest_query($id);
		$category_options = get_option($id. '_' . OW_VOTES_SETTINGS);
		$votes_start_time=get_option($show_cont_args['id'] . '_' . OW_VOTES_TAXSTARTTIME);			
		$tax_hide_photos_live = $category_options['tax_hide_photos_live'];
		
			
		if($contest_post){
			if ($contest_post->have_posts()) {
				$votes_option = get_option($id . '_' . OW_VOTES_SETTINGS);
				$image_contest = $votes_option['imgcontest'];
				
				?>
				<div  class="ow_vote_view_<?php echo $id?> ow_top_contestant_view_whole <?php echo $grid_class; ?>" data-view="list" >
				<?php
				$rank = 0;
				while ($contest_post->have_posts()) {
					$contest_post->the_post();
					$totvotesarr = array();
					$totvotesarr = get_post_meta(get_the_ID(), OW_VOTES_CUSTOMFIELD);
					$totvotes = isset($totvotesarr[0]) ? $totvotesarr[0] : 0;
					if($totvotes == NULL) $totvotes = 0;
					
					
					$votes_view = get_post_meta(get_the_ID(), OW_VOTES_VIEWS, true);
					$ow_image_alt_text=Ow_Vote_Common_Controller::ow_vote_seo_friendly_alternative_text(get_the_title());
					
					$image_contest = $votes_option['imgcontest'];					
					$video_class = ($image_contest =='video')?'ow_video_top_contest':'ow_'.$image_contest.'_top_contest';
				?>
				<div class="ow_vote_showcontent_view ow_top_contestant_view <?php echo $video_class; ?>" data-id="<?php echo $id; ?>">
					<div class="ow_vote_show_top_list">
						<div class="ow_show_top_contestant">
							<?php
							$style_image = '';
							if($height!=''){
								$style_image .='height:'.$height.'px;'; 	
							}
							if($width!=''){
								$style_image .='width:'.$width.'px;'; 
							}
							
							if($tax_hide_photos_live != 'on'){
							if (has_post_thumbnail(get_the_ID())) {
								$short_cont_image = ($short_cont_image=='')?'thumbnail':$short_cont_image;
								$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $short_cont_image);
								$ow_original_img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())).'?'.uniqid();
								$ow_image_src = $ow_image_arr[0];
	
								$get_img_size=getimagesize(realpath($ow_image_src));
								if(empty($get_img_size)){									$img_width = $width;
								}else
								$img_width =($width!='')?$width:$get_img_size[0];
								
								echo '<input type="hidden" class="ow_vote_img_width'.$id.'" value="'.$img_width.'">';
							}else{
								$ow_image_src = OW_NO_IMAGE_CONTEST;
							}
							
							
							
							if($image_contest=='photo' || $image_contest=='essay'){
							?>
							<img class="ow_vote_img_style<?php echo $id; ?>" src="<?php echo $ow_image_src; ?>" style="<?php echo $style_image;?>" title="<?php echo $ow_image_alt_text; ?>" alt="<?php echo $ow_image_alt_text; ?>" />
							<?php } ?>
							<?php } ?>
							
							<?php
							
							$perma_link = get_permalink(get_the_ID());
							$title_len= strlen(get_the_title());
							if($title_len > 100){
								$title_details = mb_substr(get_the_title(),'0','100').'..';
							}else{
								$title_details = get_the_title();
							}
							
							?>
							
							<div class="vote_right_side_content ow_right_dynamic_content<?php echo $id; ?>">
								<h2 class="<?php echo (isset($ow_vote_full_width_class)); ?>">
									<a href="<?php echo $perma_link; ?>"><?php echo $title_details; ?></a>
								</h2>
								<?php
								if($image_contest=='music'){
									
									$ow_image_src_msc = '';
									if (has_post_thumbnail(get_the_ID())) {
										if($audio_height !='' && $audio_width!=''){
											$short_cont_image = array( $audio_width, $audio_height);
										}else
										$short_cont_image = ($short_cont_image=='')?'thumbnail':$short_cont_image;
										
										$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $short_cont_image);
										$ow_image_src_msc = $ow_image_arr[0];
									}
											
								    $adv_excerpt = Ow_Vote_Excerpt_Controller::Instance();
								    $shor_desc = $adv_excerpt->filter(get_the_excerpt());
								    
								    

									$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
									if(!empty($custom_entries)){
										$field_values = $custom_entries[0]->field_values;
										if(base64_decode($field_values, true))
											$field_val = maybe_unserialize(base64_decode($field_values));  
										else
											$field_val = maybe_unserialize($field_values);
											
									}
									
									$shor_desc_music = $field_val['contestant-ow_video_url'];
								    
										
								    ?>
								    <div class="ow_show_text_desc ow_music_contest sow_show_desc_view_<?php echo $id.' '.$ow_vote_full_width_class; ?>">
											<div class="audio-js-box <?php echo $global_options['vote_audio_skin']; ?>">
												<audio class="audio-js" data-description="<?php echo $title_vote;?>" datafeatured-img ="<?php echo $ow_image_src_msc;?>" preload="none" controls>
												  <source src="<?php echo $shor_desc_music; ?>" type="audio/mpeg">
												</audio>
											</div>								    
									</div>
								    <?php
								}
								else if($image_contest == "video"){
								    ?>
								    <div class="ow_show_list_text_desc <?php echo (isset($ow_vote_full_width_class)); ?>">
								    <?php
								    if($shor_desc != null){
									echo $shor_desc; 
								    }
								    else{
									$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries(get_the_ID());
									if(!empty($custom_entries)){
										$field_values = $custom_entries[0]->field_values;
										if(base64_decode($field_values, true))
											$field_val = maybe_unserialize(base64_decode($field_values));  
										else
											$field_val = maybe_unserialize($field_values);
											
									}
									if(!empty($field_val)){
									    echo do_shortcode('[owvideo width='.$video_width.' align=left]'.$field_val['contestant-ow_video_url'].'[/owvideo]');
									}
								    }
								    ?>
								    </div>
								    <?php
								}
								else{
								?>
								<div class="ow_show_list_text_desc <?php echo (isset($ow_vote_full_width_class)); ?>">
									<?php
									$adv_excerpt = Ow_Vote_Excerpt_Controller::Instance();      
									$shor_desc = $adv_excerpt->filter(get_the_excerpt());
									$desc_len= strlen($shor_desc);
									if($desc_len > 100){
										$short_descrp = mb_substr($shor_desc,'0','75').'..';
									}else{
										$short_descrp = $shor_desc;
									}
									echo $short_descrp;
									?>									
								</div>
								<?php } ?>
							</div>
					
							<div class="ow_vote_top_count_views">
								<div class="ow_vote_top_count_view_sec">
									<?php if($votes_option['votecount']==''){?>
									<a href="<?php echo $perma_link; ?>">
										<span class="ow_vote_icons votecontestant-check ow_total_vote_heart"></span>
										<span class="ow_vote_cnt_top"><?php echo $totvotes; ?></span>
									</a>
									<?php } ?>
									<?php if($votes_view>0){ ?>
										<a class="ow_vote_page_views" href="<?php echo $perma_link;?>">
											<span class="ow_vote_icons votecontestant-eye-open ow_total_vote_heart"></span>
											<span class="ow_vote_cnt_top"><?php echo $votes_view;?></span>
										</a>
									<?php } ?>
								</div>
								
								<div class="ow_top_count_rank">
									<h1><span class="count"><?php
											echo $rank+1;
											$rank++;
									?></span></h1>
								</div>
								
							</div>
						</div>
					</div>
				</div>				
				<?php	
				}
				?>
				</div>
				<?php
			}
		}
		else{
			echo '<div class="ow_votes_error">'.__('There is an error with Top shortcode','voting-contest').'</div>';
		}
		
	}
}else{
    die("<h2>".__('Failed to load Voting Top Contest view','voting-contest')."</h2>");
}
?>
