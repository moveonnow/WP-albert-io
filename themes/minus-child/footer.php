<?php tha_content_bottom(); ?>
<?php
$options        = thrive_get_options_for_post( get_the_ID() );
$active_footers = _thrive_get_footer_active_widget_areas();

$f_class  = _thrive_get_footer_col_class( count( $active_footers ) );
$num_cols = count( $active_footers );
?>
<div class="clear"></div>
</div>
<?php tha_content_after(); ?>
<?php tha_footer_before(); ?>
<footer>
	<div class="ftw">
		<?php tha_footer_top(); ?>
		<div class="wrp cnt">
			<?php
			$num = 0;
			foreach ( $active_footers as $name ):
				$num ++;
				?>
				<div class="<?php echo $f_class; ?> <?php echo ( $num == $num_cols ) ? 'lst' : ''; ?>">
					<?php dynamic_sidebar( $name ); ?>
				</div>
			<?php endforeach; ?>
			<div class="clear"></div>
		</div>
	</div>
	<div class="fmn">
		<div class="wrp cnt">

			<a class="footer-logo" href="http://www.albert.io">
				<img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/albert_logo_white.svg'; ?>" alt="">
			</a>

			<?php if ( has_nav_menu( "footer" ) ): ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'depth' => 1, 'menu_class' => 'footer_menu' ) ); ?>
			<?php endif; ?>
			<p class="copy">
				<?php if ( isset( $options['footer_copyright'] ) && $options['footer_copyright'] ): ?>
					<?php echo str_replace( '{Y}', date( 'Y' ), $options['footer_copyright'] ); ?>
				<?php endif; ?>
				<?php if ( isset( $options['footer_copyright_links'] ) && $options['footer_copyright_links'] == 1 ): ?>
					&nbsp;&nbsp;-&nbsp;&nbsp;Designed by
					<a href="//www.thrivethemes.com" target="_blank" style="text-decoration: underline;">Thrive Themes</a>
					| Powered by <a style="text-decoration: underline;" href="//www.wordpress.org" target="_blank">WordPress</a>
				<?php endif; ?>
			</p>

		</div>
	</div>
	<?php tha_footer_bottom(); ?>
</footer>
<?php tha_footer_after(); ?>

<?php if ( isset( $options['analytics_body_script'] ) && $options['analytics_body_script'] != "" ): ?>
	<?php echo $options['analytics_body_script']; ?>
<?php endif; ?>
<?php wp_footer(); ?>
<?php tha_body_bottom(); ?>
</body>
</html>