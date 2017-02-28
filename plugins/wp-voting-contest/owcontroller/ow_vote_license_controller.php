<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_License_Controller')){
	class Ow_Vote_License_Controller{
		
		public function __construct(){
			add_action('admin_init', array($this,'wp_voting_software_register_option'));
			add_action('admin_init', array($this,'wp_voting_software_activate_license'));
		}
		
		public static function wp_voting_software_license_page() {
			$license = get_option('wp_voting_software_license_key');
			$status = get_option('wp_voting_software_license_status');
			require_once(OW_VIEW_PATH.'ow_voting_license_view.php');
			ow_voting_license_view($license,$status);
		}
		
		public function wp_voting_software_register_option() {
			register_setting('wp_voting_software_license', 'wp_voting_software_license_key', array($this,'wp_voting_sanitize_license'));
		}
		
		public function wp_voting_sanitize_license($new) {
			$old = get_option('wp_voting_software_license_key');
			if ($old && $old != $new) {
				delete_option('wp_voting_software_license_status'); 
			}
			return $new;
		}
	
		public function wp_voting_software_activate_license() {
			
			if (isset($_POST['wp_voting_license_activate'])) {
				if (!check_admin_referer('wp_voting_software_nonce', 'wp_voting_software_nonce'))
					return; 
				$license = trim(get_option('wp_voting_software_license_key'));
				$api_params = array(
					'edd_action' => 'activate_license',
					'license' => $license,
					'item_name' => urlencode(OW_WP_VOTING_SL_PRODUCT_NAME) 
				);				
				$response = wp_remote_get(add_query_arg($api_params, OW_WP_VOTING_SL_STORE_API_URL));				
				if (is_wp_error($response)){
					return false;
				}
				$license_data = json_decode(wp_remote_retrieve_body($response));
				update_option('wp_voting_software_license_status', $license_data->license);
			}
		}
	}
}else
die("<h2>".__('Failed to load the Voting License Controller','voting-contest')."</h2>");

return new Ow_Vote_License_Controller();
