<?php
$options = thrive_get_theme_options();
?>
<?php get_header(); ?>
<div class="wrp cnt">
	<section class="bSe fullWidth">
		<div class="awr">
			<div class="err">

				<div class="scn awr">
					<img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/404.svg'; ?>" alt="">
					<h2>
						<?php _e( "Oops! We can’t seem to find the page you’re looking for.", 'thrive' ); ?>
					</h2>

					<p><?php _e( "In the meantime, you can…", 'thrive' ); ?></p>

					<div class="csc">
						<div class="colm thc">
							<span><a href="https://www.albert.io/test-prep">Explore Albert’s content library</a></span>
							<span><a href="https://www.crowdcast.io/albertio">Sign up for an upcoming webinar</a></span>
							<span><a href="https://www.albert.io/school-licenses">Learn more about Albert’s licenses</a></span>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
</div>
<?php get_footer(); ?>
