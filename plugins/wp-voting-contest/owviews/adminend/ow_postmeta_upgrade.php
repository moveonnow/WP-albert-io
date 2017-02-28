<?php
if(!function_exists('ow_postmeta_upgrade')){
    function ow_postmeta_upgrade(){
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
	?>
	<div class="wrap">   
		<h2><?php _e('Upgrade Voting','voting-contest'); ?></h2>
		<div class="narrow">
			<p> <?php _e('This process will upgrade the plugin and enhance the search area with custom fields search.','voting-contest'); ?></p>
			<p style="color: #ff0000;"> <?php _e('Note : If you do not upgrade, Search will use only the titles of the contestants.','voting-contest'); ?></p>				
			<input type="submit" value="Upgrade" class="button" id="votes_upgrade_btn" name="votes_upgrade_btn">
			<div class="ow_upgrade_result"><img src="<?php echo OW_LOADER_IMAGE; ?>"/></div>
		</div>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function () {                        
			jQuery('#votes_upgrade_btn').click(function (e){
				e.preventDefault();
				jQuery('div.ow_upgrade_result').show();
				jQuery.ajax({
					url: ajaxurl,
					data:{
						action:'ow_voting_upgrade',		
					},
					type: 'POST',
					cache: false,
					success: function (resp) {
						jQuery('div.ow_upgrade_result').html(resp);
					},
					error: function (jqXHR , textStatus, errorThrown ) {
						jQuery('div.links', form).html(errorThrown);
					}
				});	  
			});
		});
	</script>

	<?php
    }
}else{
    die("<h2>".__('Failed to load Voting Postmeta Upgrade view','voting-contest')."</h2>");
}
?>
