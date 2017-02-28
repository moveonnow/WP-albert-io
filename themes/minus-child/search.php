<?php
$options = thrive_get_theme_options();

$sidebar_is_active = is_active_sidebar( 'sidebar-1' );

$next_page_link = get_next_posts_link();
$prev_page_link = get_previous_posts_link();

$section_class = _thrive_get_element_class( "content_section", $options );

$exclude_woo_pages = array(
	intval( get_option( 'woocommerce_cart_page_id' ) ),
	intval( get_option( 'woocommerce_checkout_page_id' ) ),
	intval( get_option( 'woocommerce_pay_page_id' ) ),
	intval( get_option( 'woocommerce_thanks_page_id' ) ),
	intval( get_option( 'woocommerce_myaccount_page_id' ) ),
	intval( get_option( 'woocommerce_edit_address_page_id' ) ),
	intval( get_option( 'woocommerce_view_order_page_id' ) ),
	intval( get_option( 'woocommerce_terms_page_id' ) )
);
?>

<?php get_header(); ?>

	<div class="wrp cnt">

		<?php if ( $options['sidebar_alignement'] == "left" && $sidebar_is_active && $options['blog_layout'] != "full_width" && $options['blog_layout'] != "grid_full_width" ): ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>

		<?php if ( $options['blog_layout'] != "full_width" && $options['blog_layout'] != "grid_full_width" ): ?>
		<div class="bSeCont">
			<?php endif; ?>

			<section class="<?php echo $section_class; ?>">

				<?php if ( have_posts() ): ?>

				<article>
					<div class="scn awr aut">
						<h2><?php _e( "Search Results for", 'thrive' ); ?><span><?php echo get_search_query(); ?></span></h2>
					</div>
				</article>
				<div class="spr"></div>

					<?php while ( have_posts() ): ?>
						<?php the_post(); ?>
						<?php if ( in_array( get_the_ID(), $exclude_woo_pages ) ): continue; endif; ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
					<?php endwhile; ?>
					<?php if ( _thrive_check_focus_area_for_pages( "archive", "bottom" ) ): ?>
						<?php if ( strpos( $options['blog_layout'], 'masonry' ) === false && strpos( $options['blog_layout'], 'grid' ) === false ): ?>
							<?php thrive_render_top_focus_area( "bottom", "archive" ); ?>
							<div class="spr"></div>
						<?php endif; ?>
					<?php endif; ?>

					<div class="clear"></div>
					<?php if ( $next_page_link || $prev_page_link && ( $next_page_link != "" || $prev_page_link != "" ) ): ?>
						<div class="awr ctr pgn">
							<?php thrive_pagination(); ?>
						</div>
						<div class="bspr"></div>
					<?php endif; ?>
				<?php else: ?>
					<article>
						<div class="scn awr aut">
							<h2><?php _e( "No Results for", 'thrive' ); ?><span><?php echo get_search_query(); ?></span></h2>

							<?php echo get_search_form(); ?>
						</div>
					</article>
				<div class="spr"></div>
				<?php endif ?>
				<div class="clear"></div>
			</section>
			<?php if ( $options['blog_layout'] != "full_width" && $options['blog_layout'] != "grid_full_width" ): ?>
		</div>
	<?php endif; ?>

		<?php if ( $options['sidebar_alignement'] == "right" && $sidebar_is_active && $options['blog_layout'] != "full_width" && $options['blog_layout'] != "grid_full_width" ): ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>

		<div class="clear"></div>
	</div>

<?php get_footer(); ?>