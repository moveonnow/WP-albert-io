<?php
$options       = thrive_get_theme_options();
$sidebar_class = ( $options['sidebar_alignement'] == "right" || $options['sidebar_alignement'] == "left" ) ? $options['sidebar_alignement'] : "";
?>
<?php tha_sidebars_before(); ?>

<?php tha_sidebar_top(); ?>
	<div class="sAsCont">
		<aside class="sAs <?php echo $sidebar_class; ?>">

			<?php if ( is_single() ) : ?>

				<?php

					$tags = get_the_terms( get_the_ID(), 'post_tag' );

					$post_tag = $tags[0]->term_id;

					if ( function_exists( 'get_field' ) ) :

						$btn_text = get_field( 'side_button_text', 'post_tag_' . $post_tag );
						$btn_link = get_field( 'side_button_link', 'post_tag_' . $post_tag );

						if ( '' != $btn_text && '' != $btn_link ) { ?>

							<div class="post-tags">

								<?php

									printf( '<a href="%s">%s</a>', $btn_link, $btn_text );

								?>

							</div>

						<?php

						}

					?>

				<?php endif; ?>

			<?php endif; ?>

			<?php if ( _thrive_check_is_woocommerce_page() ): ?>
				<?php if ( ! dynamic_sidebar( 'sidebar-woo' ) ) : ?>
					<!--IF THE WOO COMMERCE SIDEBAR IS NOT REGISTERED-->
				<?php endif; // end post sidebar widget area  ?>
			<?php elseif ( ! is_page() ): ?>
				<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
					<!--IF THE MAIN SIDEBAR IS NOT REGISTERED-->
				<?php endif; // end post sidebar widget area  ?>
			<?php else: ?>
				<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>
					<!--IF THE MAIN SIDEBAR IS NOT REGISTERED-->
				<?php endif; // end page sidebar widget area  ?>
			<?php endif; ?>

		</aside>
	</div>
<?php tha_sidebar_bottom(); ?>

<?php tha_sidebars_after(); ?>