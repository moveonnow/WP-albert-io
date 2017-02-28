<?php
$options                         = thrive_get_theme_options();
$GLOBALS['thrive_theme_options'] = $options;

$comment_nb_class = ( $options['sidebar_alignement'] == "right" ) ? "comment_nb" : "right_comment_nb";

$featured_image_data  = thrive_get_post_featured_image( get_the_ID(), $options['featured_image_style'] );
$featured_image       = $featured_image_data['image_src'];
$featured_image_alt   = $featured_image_data['image_alt'];
$featured_image_title = $featured_image_data['image_title'];

$data_href_string = "";
if ( ( $options['blog_layout'] == "grid_full_width" || $options['blog_layout'] == "grid_sidebar" || $options['featured_image_style'] == "round" ) ) {
	$logo_size = @getimagesize( $featured_image );
	if ( $logo_size ) {
		list( $img_width, $img_height, $img_type, $img_attr ) = @getimagesize( $featured_image );
		$data_href_string = " data-height='" . $img_height . "' data-width='" . $img_width . "'";
	} else {
		$data_href_string = " ";
	}

}

$fname        = get_the_author_meta( 'first_name' );
$lname        = get_the_author_meta( 'last_name' );
$author_name  = get_the_author_meta( 'display_name' );
$display_name = empty( $author_name ) ? $fname . " " . $lname : $author_name;

?>
<?php tha_entry_before(); ?>

	<article class="<?php if ( is_sticky() ): ?>sticky<?php endif; ?> <?php if ( $options['blog_layout'] == "grid_full_width" || $options['blog_layout'] == "grid_sidebar" ): ?> gdl left<?php endif; ?>">
		<div class="awr">
			<?php if ( ( $options['blog_layout'] != "grid_full_width" && $options['blog_layout'] != "grid_sidebar" ) && ( $options['featured_image_style'] == "thumbnail" || $options['featured_image_style'] == 'no_image' ) ): ?>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php endif; ?>

			<?php if ( $options['blog_layout'] == "grid_full_width" || $options['blog_layout'] == "grid_sidebar" ): ?>

				<?php if ( $featured_image ): ?>
					<div class="rnd">
						<a href="<?php the_permalink(); ?>" style="background-image: url('<?php echo $featured_image; ?>')"></a>
					</div>

				<?php else: ?>
					<div class="rnd">
						<a href="<?php the_permalink(); ?>" style="background-image: url('<?php echo get_template_directory_uri() ?>/images/default_featured.png')"></a>
					</div>

				<?php endif; ?>

			<?php else: ?>
				<?php if ( $options['featured_image_style'] == "wide" && $featured_image ): ?>
					<div class="fwit"><a class="psb" href="<?php the_permalink(); ?>"> <img src="<?php echo $featured_image; ?>" alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>"> </a></div>
				<?php endif; ?>

				<?php if ( $options['featured_image_style'] == "thumbnail" && $featured_image ): ?>
					<a class="aIm pst right" href="<?php the_permalink(); ?>"> <img class="fwI" src="<?php echo $featured_image; ?>" alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>"></a>
				<?php endif; ?>

				<?php if ( $options['featured_image_style'] == "round" && $featured_image ): ?>

					<div class="rnd right">
						<a href="<?php the_permalink(); ?>" style="background-image: url('<?php echo $featured_image; ?>')"></a>
					</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ( $options['blog_layout'] == "grid_full_width" || $options['blog_layout'] == "grid_sidebar" ): ?>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php elseif ( $options['featured_image_style'] == "wide" || $options['featured_image_style'] == "round" ): ?>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php endif; ?>

							<?php
				if ( isset( $options['display_meta'] ) && $options['display_meta'] == 1 ):
					?>
					<footer class="left">
						<div class="meta">
							<?php if ( isset( $options['meta_author_name'] ) && $options['meta_author_name'] == 1 ): ?>
								<span>
                                <?php _e( "Posted by", 'thrive' ); ?>
									<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
                            </span><br/>
							<?php endif; ?>
							<span>
                            <?php if ( isset( $options['meta_post_date'] ) && $options['meta_post_date'] == 1 ): ?>
	                            <?php if ( $options['relative_time'] == 1 ): ?>
		                            <?php echo thrive_human_time( get_the_time( 'U' ) ); ?>
	                            <?php else: ?>
		                            <?php echo get_the_date(); ?>
	                            <?php endif; ?>
                            <?php endif; ?>

								<?php echo do_shortcode( '[rt_reading_time label="" postfix="min read"]' ); ?>

								<?php if ( isset( $options['meta_post_category'] ) && $options['meta_post_category'] == 1 ): ?>
									<?php
									$categories = get_the_category();
									if ( $categories && count( $categories ) > 0 ):
										?>
										<?php //_e( "in", 'thrive' ); ?>
										<?php foreach ( $categories as $key => $cat ): ?>
										<a href="<?php echo get_category_link( $cat->term_id ); ?>">
											<?php echo $cat->cat_name; ?>
										</a>
										<?php if ( $key != count( $categories ) - 1 && isset( $categories[ $key + 1 ] ) ): ?><span>,</span><?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
                        </span>
						</div>
						<div class="clear"></div>
					</footer>
					<div class="clear"></div>
				<?php endif; ?>

			<?php if ( $options['blog_layout'] == "grid_full_width" || $options['blog_layout'] == "grid_sidebar" ): ?>
				<div class="text-truncate fht">
					<p>
						<?php echo _thrive_get_post_text_content_excerpt( get_the_content(), get_the_ID(), 80 ); ?>
					</p>
				</div>
				<div class="clear"></div>

				<div class="glb">
					<?php $read_more_text = ( $options['other_read_more_text'] != "" ) ? $options['other_read_more_text'] : "Read more"; ?>
					<?php if ( $options['other_read_more_type'] == "button" ): ?>
						<a class="btn read small" href="<?php the_permalink(); ?>"><span><?php echo $read_more_text ?></span></a>
					<?php else: ?>
						<a href='<?php the_permalink(); ?>' class='right'><?php echo $read_more_text ?></a>
					<?php endif; ?>
				</div>
			<?php else: ?>

				<?php if ( $options['other_show_excerpt'] != 1 ): ?>
					<?php the_content(); ?>
				<?php else: ?>
					<?php the_excerpt(); ?>
					<?php $read_more_text = ( $options['other_read_more_text'] != "" ) ? $options['other_read_more_text'] : "Read more"; ?>
					<?php if ( $options['other_read_more_type'] == "button" ): ?>
						<a class="btn read small" href="<?php the_permalink(); ?>"><span><?php echo $read_more_text ?></span></a>
					<?php else: ?>
						<a href='<?php the_permalink(); ?>' class='right'><?php echo $read_more_text ?></a>
					<?php endif; ?>
				<?php endif; ?>
				<div class="clear"></div>

			<?php endif; ?>
			<?php tha_entry_bottom(); ?>
		</div>
	</article>
<?php _thrive_render_bottom_related_posts( get_the_ID(), $options ); ?>
<?php tha_entry_after(); ?>
<?php if ( $options['blog_layout'] != "grid_sidebar" && $options['blog_layout'] != "grid_full_width" ): ?>
	<div class="spr"></div>
<?php endif; ?>