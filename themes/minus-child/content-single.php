<?php
$options = thrive_get_options_for_post( get_the_ID() );
$theme_options                   = thrive_get_theme_options();
$GLOBALS['thrive_theme_options'] = $theme_options;

$comment_nb_class = ( $options['sidebar_alignement'] == "right" ) ? "comment_nb" : "right_comment_nb";

$featured_image_data  = thrive_get_post_featured_image( get_the_ID(), $options['featured_image_style'] );
$featured_image       = $featured_image_data['image_src'];
$featured_image_alt   = $featured_image_data['image_alt'];
$featured_image_title = $featured_image_data['image_title'];

$fname        = get_the_author_meta( 'first_name' );
$lname        = get_the_author_meta( 'last_name' );
$author_name  = get_the_author_meta( 'display_name' );
$display_name = empty( $author_name ) ? $fname . " " . $lname : $author_name;

$template_name = _thrive_get_item_template( get_the_ID() );
if ( $template_name == "Landing Page" ) {
	$options['display_meta'] = 0;
}
$current_content = get_the_content();
?>
<?php tha_entry_before(); ?>

	<article>
		<div class="awr <?php if ( $template_name == "Narrow" || $template_name == "Landing Page" || $template_name == "Full Width" ): ?>lnd<?php endif; ?>">
			<?php if ( $options['featured_image_style'] == "wide" && $featured_image ): ?>
				<img class="fwit" src="<?php echo $featured_image; ?>" alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>">
			<?php endif; ?>

			<?php if ( $options['show_post_title'] != 0 ): ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php endif; ?>

			<?php if ( isset( $options['display_meta'] ) && $options['display_meta'] == 1 && ! is_page() ): ?>
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
								<?php _e( "in", 'thrive' ); ?>
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

			<?php if ( $options['featured_image_style'] == "thumbnail" && $featured_image ): ?>
				<img class="fwI alignright" src="<?php echo $featured_image; ?>" alt="<?php echo $featured_image_alt; ?>" title="<?php echo $featured_image_title; ?>">
			<?php endif; ?>

			<?php if ( $options['featured_image_style'] == "round" && $featured_image ): ?>
				<div class="rnd prnd alignright" style="background-image: url('<?php echo $featured_image; ?>')"></div>
			<?php endif; ?>
			<?php the_content(); ?>

			<div class="clear"></div>
			<?php if ( $options['enable_social_buttons'] == 1 ): ?>
				<?php get_template_part( 'share-buttons' ); ?>
			<?php endif; ?>
			<div class="clear"></div>
			<?php
			wp_link_pages( array(
				'before'         => '<br><p class="ctr pgn">',
				'after'          => '</p>',
				'next_or_number' => 'next_and_number',
				'echo'           => 1
			) );
			?>


			<?php tha_entry_bottom(); ?>

			<?php

					$tags = get_the_terms( get_the_ID(), 'post_tag' );

					$post_tag = $tags[0]->term_id;

					if ( function_exists( 'get_field' ) ) :

						$btn_text = get_field( 'bottom_button_text', 'post_tag_' . $post_tag );
						$btn_link = get_field( 'bottom_button_link', 'post_tag_' . $post_tag );

						if ( '' != $btn_text && '' != $btn_link ) { ?>

							<div class="bottom-post post-tags">

								<?php

									printf( '<a href="%s">%s</a>', $btn_link, $btn_text );

								?>

							</div>

						<?php

						}


					endif; ?>

		</div>

		<?php echo get_the_tag_list( '<div class="post-tags">', '', '</div>' ); ?>

	</article>
<?php _thrive_render_bottom_related_posts( get_the_ID(), $options ); ?>
<?php tha_entry_after(); ?>