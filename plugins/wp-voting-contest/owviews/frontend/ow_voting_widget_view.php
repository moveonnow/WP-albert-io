<?php
if(!function_exists('ow_voting_display_widget_view')){
    function ow_voting_display_widget_view($args,$param){
		$width = get_option( 'thumbnail_size_w' );
		$height = get_option( 'thumbnail_size_h' );
		$query = new WP_Query( $args );

		$category_options = get_option($param['contest_tax']. '_' . OW_VOTES_SETTINGS);
		$image_contest = $category_options['imgcontest'];
		$video_class = ($image_contest!='')?'ow_video_contest_leader_widget':'';
		if ( $query->have_posts() ) {
		?>
			<div class="ow_voting_widget_leaders">
				<?php if($param['display_category'] == '1'){ ?>
					<h3>
					<?php
					$term = get_term( $param['contest_tax'], OW_VOTES_TAXONOMY );
					echo $term->name;
					?>
					</h3>
				<?php } ?>
				<?php
				while ( $query->have_posts() ) {
				$query->the_post();
				?>
				<div class='ow_leader_contests <?php echo $video_class; ?>'>
					<?php if($param['display_photo'] == '1'){ ?>
					<div class='leader_thumb'>
						<a href="<?php the_permalink(); ?>">
						<?php
						$image_src = OW_NO_IMAGE_CONTEST;
						if(has_post_thumbnail()){
							the_post_thumbnail('thumbnail');
							$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumbnail');
							$ow_image_src = $ow_image_arr[0];
							$get_img_size=getimagesize($ow_image_src);
							if(empty($get_img_size)){
								echo "<img src='".$image_src."' style='width:".$width."px;height:".$height."px;' />";
							}
						}else{
							if($image_contest == 'video'){								
								echo "<img src='".Ow_Vote_Common_Controller::ow_vote_contestant_thumbnail(get_the_ID())."' style='width:".$width."px;' />";
							}
							else{
								echo "<img src='".$image_src."' style='width:".$width."px;height:".$height."px;' />";
							}
						}
						?>
						</a>
					</div>
					<?php } ?>
					<div class='leader_contents'>
						<div class="ow_leader_title">
							<a href="<?php the_permalink(); ?>" class="href_title">
							<span class='leader_title'><?php the_title(); ?></span>
							</a>
						</div>
						
						<?php if($param['display_author'] == '1'){ ?>
							<div class="ow_leader_author">
								<span class='leader_author'>By: <?php the_author(); ?></span>
							</div>
						<?php } ?>
						
						<?php if($category_options['votecount']==''){ ?>
						<div class='leader_votes'>
							<?php echo "Votes : ".get_post_meta(get_the_ID(),OW_VOTES_CUSTOMFIELD,true); ?>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
		<?php 
		}else {
			_e('No Contests Found','voting-contest');
		}
		wp_reset_postdata();
    }
}else{
    die("<h2>".__('Failed to load Voting Display Widget view','voting-contest')."</h2>");
}


if(!function_exists('ow_voting_display_recent_view')){
    function ow_voting_display_recent_view($args,$param){	
		$width = get_option( 'thumbnail_size_w' );
		$height = get_option( 'thumbnail_size_h');
		
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
		?>
			<div class="ow_voting_widget_leaders">
			<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					
					$category_detail = get_the_terms( get_the_ID(),OW_VOTES_TAXONOMY );
					$category_options = get_option($category_detail[0]->term_id. '_' . OW_VOTES_SETTINGS);
					$image_contest = $category_options['imgcontest'];					
					
					?>
					<div class='ow_recent_contests'>
						<?php if($param['display_photo'] == '1'){ ?>
						<div class='ow_recent_thumb'>
							<a href="<?php the_permalink(); ?>">
							<?php
							$image_src = OW_NO_IMAGE_CONTEST;
							if(has_post_thumbnail()){
								$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumbnail');
								$ow_image_src = $ow_image_arr[0];
								$get_img_size=getimagesize($ow_image_src);
								if(empty($get_img_size)){
									echo "<img src='".$image_src."' style='width:".$width."px;height:".$height."px;' />";
								}else{
									the_post_thumbnail('thumbnail');
								}
							}else{
								if($image_contest == 'video'){								
									echo "<img src='".Ow_Vote_Common_Controller::ow_vote_contestant_thumbnail(get_the_ID())."' style='width:".$width."px;' />";
								}
								else{
									echo "<img src='".$image_src."' style='width:".$width."px;height:".$height."px;' />";
								}
							}
							?>
							</a>
						</div>
						<?php } ?>
						
						<div class='leader_contents'>
							
							<div class="ow_leader_title">
								<a href="<?php the_permalink(); ?>" class="href_title">
								<span class='leader_title'><?php the_title(); ?></span>
								</a>
							</div>
							
							<?php if($param['display_category'] == '1'){  ?>
								<div class="ow_leader_category">
									<span><?php echo $category_detail[0]->name; ?></span>
								</div>
							<?php } ?>
							
							<?php if($param['display_author'] == '1'){ ?>
								<div class="ow_leader_author">
									<span class='leader_author'>By: <?php the_author(); ?></span>
								</div>
							<?php } ?>							
							
						</div>
						
					</div>
					<?php
				}
			?>
			</div>
		<?php
		}else {
			_e('No Contests Found','voting-contest');
		}
		wp_reset_postdata();
		//exit;
	}
}else{
    die("<h2>".__('Failed to load Voting Display Recent view','voting-contest')."</h2>");
}
?>
