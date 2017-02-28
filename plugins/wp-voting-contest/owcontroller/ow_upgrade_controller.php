<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Upgrade_Controller')){
	class Ow_Upgrade_Controller{
		
		public function __construct(){
			
			add_action('admin_notices', array($this,'ow_voting_postmeta_upgrade'));
			
			add_action( 'wp_ajax_ow_voting_upgrade',array($this,'ow_voting_upgrade_postmeta')  );
			add_action( 'wp_ajax_nopriv_ow_voting_upgrade', array($this,'ow_voting_upgrade_postmeta') );
			
		}
		
		public function ow_voting_postmeta_upgrade() {
			if(get_option('_ow_voting_upgrade') == null){
				?>	
					<div class="notice notice-success is-dismissible">
						<p><strong><?php _e('Click on Upgrade button to use the Custom Fields in the Voting Search Area', 'voting-contest'); ?></strong></p>
						<a class="button-primary" href="<?php echo get_admin_url(); ?>admin.php?page=votes-upgrade"><?php _e('Upgrade','voting-contest'); ?></a>
						<p></p>
					</div>	
				<?php
			}
		}
			
		public static function wp_voting_upgrade_postmeta(){
			require_once(OW_VIEW_PATH.'ow_postmeta_upgrade.php');
			ow_postmeta_upgrade();				
		}
		
		public function ow_voting_upgrade_postmeta(){
			$contest_post = Ow_Vote_Shortcode_Model::ow_get_show_all_contest_query(array('postperpage' => -1));			
			if ($contest_post->have_posts()) {
				$i = 0;
				while ($contest_post->have_posts()) {
						$contest_post->the_post();
						$post_id = get_the_ID();
						$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries($post_id);
						$field_values = maybe_unserialize(base64_decode($custom_entries[0]->field_values));
						
						foreach($field_values as $key => $field_val){
							if($key != 'contestant-title' && $key != 'contestant-desc'){
								update_post_meta($post_id,$key,$field_val);
							}
						}						
						//Setting the Time Limit
						set_time_limit(100);						
						$i++;
				}
			}
			update_option( '_ow_voting_upgrade', 1 );
			echo $i. " Contestants Custom Fields has been updated";
			exit();
		}
	}
}else
die("<h2>".__('Failed to load the Voting Upgrade Controller','voting-contest')."</h2>");

return new Ow_Upgrade_Controller();
