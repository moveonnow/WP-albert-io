<?php
session_start();
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Admin_Controller')){
	class Ow_Admin_Controller{
	    
	public static $addScript = false;
	
	public function __construct(){		
		    add_action('init', array($this,'ow_votes_register_taxonomy'));
		    add_action('admin_menu', array($this, 'ow_voting_admin_menu'));
		    add_action('parent_file', array($this,'ow_vote_tax_menu_correction'));
		    
		    //Register Widget for admin/Frontend
		    add_action( 'widgets_init', array($this,'ow_voting_sidebar_init'));			
		    //Script Exclude for Without Shortcode
		    add_filter('the_posts', array($this,'ow_votes_conditionally_add_scripts_and_styles'));		    
		    
		    //Widget shortcode
		    add_filter('widget_text', 'shortcode_unautop');
		    add_filter('widget_text', 'do_shortcode');
		    
		    //Infinite Scroll Random Orderby Fix
		    add_filter('posts_orderby', array($this,'ow_votes_posts_orderby_random'));
			
			//Add Tiny MCE Button for Shortcode
			add_action('admin_head', array($this,'ow_votes_addMCE_button'));
			
			add_filter('ow_add_form_field',array($this,'ow_add_form_field'),1,4);
			add_action('ow_save_contestant', array($this,'ow_save_music_url_postmeta'), 10, 2);
			add_filter('ow_display_form_field',array($this,'ow_display_form_field'),1,3);
			add_action('ow_save_custom_fields',array($this,'ow_save_music_doc_url_update'),10,2);
	}
	
	
	//Update Music Field in the admin end	
	public function ow_save_music_doc_url_update($post_id,$category_option){
		$ow_music_doc_url = $category_option['musicfileenable'];  
		if ($ow_music_doc_url == 'on') {					
			do_action('ow_update_fields',$post_id,'contestant-ow_video_url',$_POST['contestant-ow_music_url']);			
		}
	}
	
	//Display Music in Admin Metabox
	public function ow_display_form_field($custom_field,$category,$category_option){ 
			//Check The Field Name and Contest Category
			if($custom_field->system_name == "contestant-ow_music_url" && $category == "music" && $category_option['musicfileenable'] == "on"){
				require_once(OW_VIEW_PATH.'ow_admin_display.php');
				ob_start();
				ow_admin_display($custom_field,$category,$category_option);								
				$out = ob_get_contents();
				ob_end_clean();				
				echo $out;
			}
	}
	
	//Music Upload
	public function ow_add_form_field($system_name,$category,$cat_id,$customfield){ 
		//Check The Field Name and Contest Category
		if($system_name == "contestant-ow_music_url" && $category == "music"){
			require_once(OW_VIEW_FRONT_PATH.'ow_add_form_field.php');			
			ob_start();
			ow_add_form_field($system_name,$cat_id,$customfield);
			$out = ob_get_contents();
			ob_end_clean();				
			echo $out;
		}
	}
	
	//Save Music URL POST
	public function ow_save_music_url_postmeta($post_id, $category_option){
			
		if($category_option['imgcontest'] != "music"){
			return;
		}
		
		if($action=='contestant_approve'){
			return;
		}
		
		// Do nothing during a bulk edit
		if (isset($_REQUEST['bulk_edit']))
			return;
				
		if($_POST['contestant-ow_music_url'] != null){
			
			$owmusic_id = Ow_Vote_Common_Controller::ow_voting_decrypt($_POST['contestant-ow_music_url_hidden_attached']);			
			update_post_meta($post_id,'_ow_music_upload_url',$_POST['contestant-ow_music_url']);
			update_post_meta($post_id,'_ow_music_upload_attachment',$owmusic_id);			
						
		}
	}
		
	public function ow_votes_posts_orderby_random($orderby_statement){
		global $pagenow; 
		if($pagenow != 'edit.php' && !isset($_POST['filter_votes']) && ($votes_settings['orderby'] == 'rand' || $_SESSION['random_order'] == 1)){
			$seed = $_SESSION['seed'];
			if (empty($seed)) {
			  $seed = rand();
			  $_SESSION['seed'] = $seed;
			}
		    
			$orderby_statement = 'RAND('.$seed.')';			
		}
		return $orderby_statement;
		
	}
	    
	//Register taxonomy and post type
	public function ow_votes_register_taxonomy(){
		    $menupos=26; // This helps to avoid menu position conflicts with other plugins.
		    $cust_slug	=  get_option(OW_VOTES_SETTINGS);
	            $slug	=  $cust_slug['vote_custom_slug'];
		    $slug 	=  ($slug == null)?'contestants':$slug;
		    while (isset($GLOBALS['menu'][$menupos])) $menupos+=1;
		    register_post_type(OW_VOTES_TYPE, array('label' => __('Contestants','voting-contest'),
			    'description' => '',
			    'public' => true,
			    'show_ui' => true,
			    'show_in_menu' => false,
			    'capability_type' => 'post',
			    'hierarchical' => false,
			    'rewrite' => array('slug' => $slug),
			    'query_var' => true,
			    'supports' => array('title',
			    'editor',
			    'thumbnail',
			    'author',
			    'comments',
			    'page-attributes'),
			    'labels' => array(
			    'name' => __('Contestants','voting-contest'),
			    'singular_name' => __('Contest','voting-contest'),
			    'menu_name' => __('Contests','voting-contest'),
			    'name_admin_bar' => __('Contests','voting-contest'),
			    'add_new' => __('Add Contestant','voting-contest'),
			    'add_new_item' => __('Add New Contestant','voting-contest'),
			    'edit' => __('Edit','voting-contest'),
			    'edit_item' => __('Edit Contestant','voting-contest'),
			    'new_item' => __('New Contestant','voting-contest'),
			    'view' => __('View Contestant','voting-contest'),
			    'view_item' => __('View Contestant','voting-contest'),
			    'search_items' => __('Search Contestant','voting-contest'),
			    'not_found' => __('No Contestants Found','voting-contest'),
			    'not_found_in_trash' => __('No Contestants Found in Trash','voting-contest'),
			    'parent' => 'Parent Contestants',
			    'menu_position' => $menupos,
		    )));
		    
		    flush_rewrite_rules();

		    register_taxonomy(OW_VOTES_TAXONOMY,
				    array(
					    0 => OW_VOTES_TYPE,
				    ),
				    array('hierarchical' => true,
				    'label' => 'Contest Category',
				    'show_ui' => true,
				    'query_var' => true,
				    'rewrite' => false,
				    'singular_label' => __('Contest Category','voting-contest'))
		    );
		    
		    $vote_opt = get_option(OW_VOTES_SETTINGS);
		    if(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {	
			    do_action( 'ow_vote_twitter_auth_hook', $vote_opt );           
		    }			
		    
	}
	    

	public function ow_add_styles_to_front_end($vote_opt){		
			
		if(!is_admin()) {
			
			$vote_opt = get_option(OW_VOTES_SETTINGS);
			
			//Adding styles
			wp_enqueue_script('jquery');
			wp_register_style('OW_FRONT_CONTESTANT_STYLES', OW_ASSETS_FRONT_END_CSS_PATH);
			wp_enqueue_style('OW_FRONT_CONTESTANT_STYLES');
			
			wp_register_style('OW_FRONT_COLOR', OW_ASSETS_COLOR_RELPATH);
			wp_enqueue_style('OW_FRONT_COLOR');
			
			wp_register_script('ow_votes_block', OW_ASSETS_JS_PATH . 'ow_vote_block_div.js');
			wp_enqueue_script('ow_votes_block',array('jquery'));				
			
			wp_register_style('ow_vote_css_pretty', OW_ASSETS_CSS_PATH.'ow_vote_prettyPhoto.css');
			wp_enqueue_style('ow_vote_css_pretty');
			
			wp_register_style('ow_video_skin', OW_ASSETS_CSS_PATH.'/skins/ow_skin.css');
			wp_enqueue_style('ow_video_skin');
		
			wp_register_script('ow_votes_pretty', OW_ASSETS_JS_PATH . 'ow_vote_prettyPhoto.js');
			wp_enqueue_script('ow_votes_pretty',array('jquery'));			
		
			wp_register_style('ow_vote_css_fancy_box', OW_ASSETS_CSS_PATH.'ow_vote_fancybox.css');
			wp_enqueue_style('ow_vote_css_fancy_box');
		
			wp_register_script('ow_vote_fancy_box', OW_ASSETS_JS_PATH . 'ow_vote_fancybox.js');
			wp_enqueue_script('ow_vote_fancy_box',array('jquery'));		
		
			wp_register_script('ow_votes_validate_js', OW_ASSETS_JS_PATH . 'ow_vote_validate.js');
			wp_enqueue_script('ow_votes_validate_js',array('jquery'));				
	
			wp_register_script('ow_fb_script_js', OW_ASSETS_JS_PATH . 'ow_votes_fbscript.js','',time());
			wp_enqueue_script('ow_fb_script_js',array('jquery'));
			
			wp_register_script('ow_votes_count_down', OW_ASSETS_JS_PATH . 'ow_count_down.js');
			wp_enqueue_script('ow_votes_count_down',array('jquery'));

			wp_register_script('ow_votes_shortcode', OW_ASSETS_JS_PATH . 'ow_vote_shortcode_jquery.js');
			wp_enqueue_script('ow_votes_shortcode',array('jquery'));
			
			wp_register_script( 'ow_media_element', OW_ASSETS_JS_PATH . 'ow_audio.js');
			wp_enqueue_script('ow_media_element',array('jquery'));
			
			
			// File Uploader JS
			wp_register_script('ow_jquery_widget', OW_ASSETS_JS_PATH . 'jquery.ui.widget.js');		
			wp_enqueue_script('ow_jquery_widget',array('jquery'));
			
			wp_register_script( 'ow_media_upload', OW_ASSETS_JS_PATH . 'jquery.fileupload.js');
			wp_enqueue_script('ow_media_upload',array('jquery'));
			
			wp_register_script( 'ow_file_process', OW_ASSETS_JS_PATH . 'jquery.fileupload-process.js');
			wp_enqueue_script('ow_file_process',array('jquery'));
			
			wp_register_script( 'ow_file_validate', OW_ASSETS_JS_PATH . 'jquery.fileupload-validate.js');
			wp_enqueue_script('ow_file_validate',array('jquery'));
			
			
			wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array( 'votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH ) );
			
			wp_register_style('ow_datetimepicker_style', OW_ASSETS_CSS_PATH.'ow_datetimepicker.css');
			wp_enqueue_style('ow_datetimepicker_style');
			
			wp_register_script('ow_date_time_picker', OW_ASSETS_JS_PATH . 'ow_datetimepicker.js');
			wp_enqueue_script('ow_date_time_picker',array('jquery'));
						
			wp_register_script('ow_flowplayer', OW_ASSETS_JS_PATH . 'ow_flowplayer.min.js');
			wp_enqueue_script('ow_flowplayer',array('jquery'));

			//Check the flow-grid is set in the Global Settings			
			if($vote_opt['vote_enable_all_contest'] == 'flow-grid'){
				wp_register_script('gridScrollFx', OW_ASSETS_JS_PATH . 'gridScrollFx.js');
				wp_enqueue_script('gridScrollFx');
			}
			
			//Genuine Theme Fix
			if(get_current_theme() == "Genuine" || get_current_theme() == "Genuine Child Theme"){				
				wp_dequeue_script ('jquery-animate-enhanced-min');
			}
			/*			
			wp_register_style('ow_vote_search', OW_ASSETS_JS_PATH.'easy-autocomplete.min.css');
			wp_enqueue_style('ow_vote_search');
			
			wp_register_script( 'ow_autocomple', OW_ASSETS_JS_PATH . 'wp-jquery.easy-autocomplete.js');
			wp_enqueue_script('ow_autocomple',array('jquery'));
			*/	
		}
	}

	public function ow_votes_unload_scripts(){
		wp_dequeue_script ('theme-prettyphoto');
	}
		
		
	    
	//Admin menu start
	public function ow_voting_admin_menu(){
		    
		    add_menu_page('Contests-Voting', 'Contest', 'manage_options',OW_VOTES_TYPE, array( $this, 'ow_voting_overview'));
		    add_submenu_page(OW_VOTES_TYPE, __('Overview','voting-contest'), __('Overview','voting-contest'), 'manage_options', OW_VOTES_TYPE,array( $this, 'ow_voting_overview'));  
		    add_submenu_page(OW_VOTES_TYPE, __('Contest Category','voting-contest'),"<span class='vote_contest_cat'>".__('Contest Category','voting-contest')."</span>", 'publish_pages', 'edit-tags.php?taxonomy=contest_category&post_type=contestants', '');
		    add_submenu_page(OW_VOTES_TYPE, __('Contestants','voting-contest'), "<span class='vote_contest_contestants'>".__('Contestants','voting-contest')."</span>", 'publish_pages', 'edit.php?post_type=contestants', ''); 		
		    add_submenu_page('', __('Add Contestant','voting-contest'), __('Add Contestant','voting-contest'), 'publish_pages', 'post-new.php?post_type=contestants', '');				
		    add_submenu_page('', __('Move Contestant','voting-contest'), __('Move Contestant','voting-contest'), 'publish_pages', 'move_posts', array( 'Ow_Vote_Contestant_Controller','ow_voting_move_contestants'));	    
		    add_submenu_page('', __('Import Contestants','voting-contest'), __('Import Contestant','voting-contest'), 'publish_pages', 'votes_csv', array( 'Ow_Vote_Contestant_Controller','ow_voting_import_contestants'));		
		    add_submenu_page('', __('Export Contestants','voting-contest'), __('Export Contestant','voting-contest'), 'publish_pages', 'votes_export', array( 'Ow_Vote_Contestant_Controller','ow_voting_export_contestants'));			     
		    add_submenu_page(OW_VOTES_TYPE, __('Clear Voting Entries','voting-contest'), __('Clear Voting Entries','voting-contest'), 'publish_pages', 'votes_purge', array('Ow_Vote_Common_Settings_Controller','ow_voting_clear_voting_entries'));		
		    add_submenu_page('', __('Contestant fields','voting-contest'), __('Contestant fields','voting-contest'), 'publish_pages', 'fieldcontestant',array('Ow_Vote_Custom_Field_Controller','ow_votes_contestant_custom_field_meta_box'));		
		    add_submenu_page(OW_VOTES_TYPE, __('Registration fields','voting-contest'), __('Registration fields','voting-contest'), 'publish_pages', 'fieldregistration', array('Ow_Vote_Custom_Field_Controller','ow_votes_user_custom_field_meta_box'));		
		    add_submenu_page('', __('Voting Logs','voting-contest'), __('Voting Logs','voting-contest'), 'publish_pages', 'votinglogs', array( 'Ow_Vote_Contestant_Controller','ow_voting_vote_logs'));			   
		    add_submenu_page(OW_VOTES_TYPE, __('Settings','voting-contest'), "<span class='setting_vote_page'>".__('Settings','voting-contest')."</span>", 'publish_pages', 'votes_settings', array( 'Ow_Vote_Common_Settings_Controller','ow_voting_setting_common'));   
		    
		    add_submenu_page(OW_VOTES_TYPE, __('Plugin License','voting-contest'), __('License','voting-contest'), 'publish_pages', 'votes-license', array('Ow_Vote_License_Controller','wp_voting_software_license_page'));
			
			add_submenu_page(OW_VOTES_TYPE, __('Upgrade','voting-contest'), __('Upgrade','voting-contest'), 'publish_pages', 'votes-upgrade', array('Ow_Upgrade_Controller','wp_voting_upgrade_postmeta'));
	    }
	    
	public function ow_vote_tax_menu_correction($parent_file) {
	    global $current_screen,$submenu_file;
		    remove_action( 'admin_notices', 'update_nag', 3 );
		    $base = $current_screen->base;
		    $action = $current_screen->action;
		    $post_type = $current_screen->post_type;
		    $taxonomy = $current_screen->taxonomy;
		    if ($taxonomy == OW_VOTES_TAXONOMY){
			    $parent_file = OW_VOTES_TYPE;
			    $submenu_file = 'edit-tags.php?taxonomy='.OW_VOTES_TAXONOMY.'&post_type='.OW_VOTES_TYPE;
		    }
		    
		    //Pagination menu selection not a right way
		    if($parent_file == 'votes_setting_paginate'){ ?>
			    <script type="text/javascript">
			    jQuery(document).ready( function($) 
			    {
				    jQuery('li#toplevel_page_contestants').removeClass('wp-not-current-submenu');  
				    jQuery('li#toplevel_page_contestants').addClass('wp-has-current-submenu'); 
				    jQuery('li#toplevel_page_contestants a.toplevel_page_contestants').removeClass('wp-not-current-submenu');
				    jQuery('li#toplevel_page_contestants a.toplevel_page_contestants').addClass('wp-has-current-submenu');
	       
				    var reference = $('.setting_vote_page').parent().parent();
				    // add highlighting to our custom submenu
				    reference.addClass('current');
				    //remove higlighting from the default menu
				    reference.parent().find('li:first').removeClass('current');             
			    });     
			    </script>
			    <?php
		    }
		    return $parent_file;
	}
	    		
	//Overview page
	public function ow_voting_overview(){
		    require_once(OW_VIEW_PATH.'ow_overview_view.php');
	}
		
		//Register Sidebar
	public function ow_voting_sidebar_init() {
			register_sidebar( array(
				'name' => 'Contestants Sidebar',
				'id' => 'contestants_sidebar',
				'before_widget' => '<div class="contestants_sidebar">',
				'after_widget' => '</div>',
				'before_title' => '<h2 class="contestests_sidebar">',
				'after_title' => '</h2>'
			));
			register_widget( 'Ow_Widget_Leader_Controller' );
			register_widget( 'Ow_Widget_Recent_Controller' );
	}
	
	public function ow_votes_conditionally_add_scripts_and_styles($posts){
			global $wp_query; 	    
			if (empty($posts)) return $posts;
			
			$shortcode  = 'showcontestants';
			$shortcode1 = 'endcontestants';
			$shortcode2 = 'upcomingcontestants';
			$shortcode3 = 'topvotecontestants';
			$shortcode4 = 'rulescontestants';
			$shortcode5 = 'addcontestants';
			$shortcode6 = 'profilescreen';
			$shortcode7 = 'showallcontestants';
			$shortcode8 = 'addcontest';
	    
			$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
			
			foreach ($posts as $post) {
				if (isset($wp_query->query_vars['contestants']) || stripos($post->post_content, '[' . $shortcode ) !== false || stripos($post->post_content, '[' . $shortcode1 ) !== false || stripos($post->post_content, '[' . $shortcode2 ) !== false || stripos($post->post_content, '[' . $shortcode3 ) !== false || stripos($post->post_content, '[' . $shortcode4 ) !== false || stripos($post->post_content, '[' . $shortcode6 ) !== false || stripos($post->post_content, '[' . $shortcode5 ) !== false || stripos($post->post_content, '[' . $shortcode7 ) !== false || stripos($post->post_content, '[' . $shortcode8 ) !== false) {
					$_SESSION['ow_shortcode_count'] = 1;
					$shortcode_found = true; 
					break;
				}
			}
			
			//Check if it is multiple contestants page
			foreach ($posts as $post) {
				if((substr_count($post->post_content, '['.$shortcode) > 1) || (substr_count($post->post_content, '['.$shortcode1) >1 ) || (substr_count($post->post_content, '['.$shortcode2) > 1)){
					$_SESSION['ow_shortcode_count'] = 2;
				}
			}
			
		 
			if ($shortcode_found) {				
			    add_action( 'wp_enqueue_scripts',  array($this,'ow_add_styles_to_front_end'), 99);
			    add_action  ('wp_print_scripts',    array($this,'ow_votes_unload_scripts'), 99);
			}
		 
			return $posts;
		}
		
		public function ow_votes_addMCE_button(){
			global $typenow;
			// check user permissions
			if ( !current_user_can('edit_posts') &&  !current_user_can('edit_pages') ) {
				return;
			}
			// verify the post type
			if( ! in_array( $typenow, array( 'page' ) ) )
				return;
			// check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				add_filter( 'mce_external_plugins', array( $this ,'ow_mce_external_plugins' ) );
				add_filter( 'mce_buttons', array($this, 'ow_mce_buttons' ) );
			}
			
			wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
			wp_enqueue_style('OW_ADMIN_STYLES');
			
		}
		
		public function ow_mce_buttons( $buttons ) {				
			$buttons[] = 'ow_voting_button';			
			return $buttons;
		}
		
		public function ow_mce_external_plugins( $buttons ) {
			$plugin_array['ow_voting_button'] = plugins_url( '/assets/js/ow_voting_button.js', __DIR__ ); // CHANGE THE BUTTON SCRIPT HERE
			return $plugin_array;			
		}
	
		
	}
}else
die("<h2>".__('Failed to load the Voting Admin Controller','voting-contest')."</h2>");

return new Ow_Admin_Controller();
