<?php
if(!function_exists('ow_voting_license_view')){
    function ow_voting_license_view($license,$status){
	?>
		<div class="wrap">
			<h2><?php _e('Plugin License Options','voting-contest'); ?></h2>
			<form method="post" action="options.php">
		
				<?php settings_fields('wp_voting_software_license'); ?>
		
				<table class="form-table">
					<tbody>
						<tr valign="top">	
							<th scope="row" valign="top">
								<?php _e('License Key','voting-contest'); ?>
							</th>
							<td>
								<input id="wp_voting_software_license_key" name="wp_voting_software_license_key" type="text" class="regular-text" value="<?php esc_attr_e($license); ?>" />
								<label class="description" for="wp_voting_software_license_key"><?php _e('Enter your license key','voting-contest'); ?></label>
							</td>
						</tr>
						<?php if (false !== $license) { ?>
							<tr valign="top">	
								<th scope="row" valign="top">
									<?php _e('Activate License','voting-contest'); ?>
								</th>
								<td>
									<?php if ($status !== false && $status == 'valid') { ?>
										<span style="color:green;"><?php _e('active','voting-contest'); ?></span>
									<?php
									} else {
										wp_nonce_field('wp_voting_software_nonce', 'wp_voting_software_nonce');
										?>
										<input type="submit" class="button-secondary" name="wp_voting_license_activate" value="<?php _e('Activate License','voting-contest'); ?>"/>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>	
				<?php submit_button(__('Save Changes','voting-contest')); ?>
			</form>
		</div>
<?php
	}
}else{
    die("<h2>".__('Failed to load Voting License view','voting-contest')."</h2>");
}
?>