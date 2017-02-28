<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Common_Settings_Controller')){
    class Ow_Vote_Common_Settings_Controller{
	
		public function __construct(){
			
		}
		
		//Delete the votes
		public static function ow_voting_clear_voting_entries(){
			$votes_delete = $_POST['votes_delete'];
			
			if ($votes_delete=='Delete') {    
				$term_id = $_POST['vote_contest_term'];
				$cls = 'updated';
				$msg = '';    
				$cntresult = Ow_Common_Settings_Model::ow_vote_clear_cate($term_id);
				if (count($cntresult) > 0)
					$msg = $result . __("Vote Entries Deleted",'voting-contest');
				else
					$msg = __("No Votes are polled",'voting-contest');
				
			}
			if (isset($msg)) {
				echo '<div class="' . $cls . '" style="line-height:40px;">' . $msg . '</div>';
			}
			require_once(OW_VIEW_PATH.'ow_clear_votes_view.php');
			ow_votes_clear_votes_view();
		}
		
		public static function ow_voting_setting_common(){
			require_once(OW_VIEW_PATH.'ow_common_settings_view.php');
			$get_action = ($_GET['vote_action'])?$_GET['vote_action']:'common';
			ow_common_settings_view($get_action);
			$save_func =  $_POST['setting_action'];
			
			switch($save_func){
				case 'common_save':
					Ow_Vote_Common_Settings_Controller::ow_save_settings_common('common');
				break;
				case 'contest_save':
					Ow_Vote_Common_Settings_Controller::ow_save_settings_common('contest');
				break;
				case 'share_save':
					Ow_Vote_Common_Settings_Controller::ow_save_settings_common('share');
				break;
				case 'script_save':
					Ow_Vote_Common_Settings_Controller::ow_save_settings_common('script');
				break;
				case 'vi_color_save':
					Ow_Vote_Common_Settings_Controller::ow_save_settings_common('vi_color_save');
				break;
			}
			
			$option = get_option(OW_VOTES_SETTINGS);
			switch($get_action){
				case 'contest':
					ow_contest_settings_view($option);
					break;
				
				case 'common':
					ow_common_page_settings_view($option);
					break;
				
				case 'color':
					$coloroption = get_option(OW_VOTES_COLORSETTINGS);					
					$current_active = get_option(OW_VOTES_ACTIVE_THEME);
					ow_color_page_settings_view($coloroption,$current_active);
					break;
					
				case 'share':
					ow_share_contestant_settings_view($option);
					break;
				
				case 'script':
					ow_script_contestant_settings_view($option);
					break;
				
				case 'expert':
					$excerpt = new Ow_Vote_Excerpt_Controller();
					$excerpt->page_options();
					break;
			}
			
			//Hook for the New Settings
			do_action( 'owvoting_new_settings',$get_action,$option);
				
		}
		
		public static function ow_save_settings_common($save_field=NULL){
			
			$path =  wp_upload_dir();
			$votesexpiration = $_POST['votes_expiration'];
			if($votesexpiration != '0' && trim($votesexpiration)){
					$votesexpiration = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $votesexpiration)));
				
			}else{
				$votesexpiration = '0';
			}
			$votesstarttime = $_POST['votes_starttime'];
			if($votesstarttime != '0' && trim($votesstarttime)){
					$votesstarttime = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $votesstarttime)));
				
			}else{
				$votesstarttime = '0';
			}
			
			$expoption = get_option(OW_VOTES_GENERALEXPIRATIONFIELD);
			$opt_values = get_option(OW_VOTES_SETTINGS);
			
			if($expoption == '0'){
				$votes_expiration = '';
			}else{			
				$votes_expiration = date('m-d-Y H:i', strtotime( str_replace('-', '/',$expoption )) );
			}
			
			$starttimeoption = get_option( VOTES_GENERALSTARTTIME);
			if($starttimeoption == '0'){
				$votes_starttime = '';
			}else{			
				$votes_starttime = date('m-d-Y H:i', strtotime( str_replace('-', '/',$starttimeoption )) );
			}
			
			switch($save_field){
				
				case 'common':
					$error_image='';
					$value = $_POST['vote_admin_mail']; 
					$value = sanitize_email( $value );
					if($_POST['vote_admin_mail']!=''){
						if ( ! is_email( $value ) ) {
						$value = get_option( $option ); // Resets option to stored value in the case of failed sanitization
						if ( function_exists( 'add_settings_error' ) )
							$error_image =  'The email address entered did not appear to be a valid email address. Please enter a valid email address.';
						}
					}
					$unset_array = array('short_cont_image','page_cont_image',
										'title','orderby','order','vote_sidebar','vote_select_sidebar','vote_readmore','vote_entry_form','vote_turn_related','vote_video_width','single_page_cont_image','single_page_cont_image_px','vote_video_height','single_page_title','vote_title_alocation','vote_notify_mail','vote_admin_mail','vote_admin_mail_content','vote_from_name',
										'votes_timertextcolor','votes_timerbgcolor','vote_show_date_prettyphoto','vote_prettyphoto_disable','vote_prettyphoto_disable_single','vote_hide_account','vote_openclose_menu','vote_enable_all_contest','vote_all_contest_design','vote_enable_ended','vote_count_showhide','single_contestants_video_width_px'
									);
					foreach($unset_array as $unset){
						unset($opt_values[$unset]);
					}
					
				break;
			
				case 'vi_color_save':				
					
					$current_theme = ($_POST['save_as_template'] == null)?str_replace(" ","_",rtrim($_POST['owt_color_select'])):str_replace(" ","_",rtrim($_POST['save_as_template']));					
					
					$color_array  =  $_POST;				
					unset($color_array['owt_color_select']);
					unset($color_array['Submit']);
					unset($color_array['setting_action']);
					
					$style_array = array($current_theme => $color_array);					
					$coloroption = get_option(OW_VOTES_COLORSETTINGS);
					
					
					/* Delete Theme Functionality */
					if($_POST['ow_delete_current_theme_flag'] == 1){						
						unset($coloroption[$current_theme]);
						$current_theme = OW_DEF_THEME;
						$color_array   = $coloroption[$current_theme];
					}
					
					
					update_option(OW_VOTES_ACTIVE_THEME,$current_theme);
										
					foreach($coloroption as $key => $color):
						if($key == $current_theme){
							$coloroption[$key] = $color_array;
						}
						else{
							$coloroption[$current_theme] = $color_array;
						}
					endforeach;
					
					//echo "<pre>";print_r($coloroption);echo "</pre>";exit;
					
					update_option(OW_VOTES_COLORSETTINGS,$coloroption);
					
					
					$filename = OW_ASSETS_COLORCSS_PATH;
					if (is_writable($filename)) {
					    $p= Ow_Vote_Common_Settings_Controller::ow_voting_format_css($color_array);
						$a = fopen($filename, 'w');
						fwrite($a, $p);
						fclose($a);
						chmod(OW_ASSETS_COLORCSS_PATH, 0777);
					} else { 
					    $error_image = 'Permission is not set to 777 for the file "wp-content/plugins/wp-voting-contest/assets/css/ow_votes_color.css"';
					}
				break;
			
				case 'contest':
					$error_image='';
					$unset_array = array(
					'vote_onlyloggedcansubmit','onlyloggedinuser','vote_tracking_method','vote_truncation_grid','vote_truncation_list','frequency',
					'vote_votingtype','vote_grab_email_address','vote_publishing_type','vote_tobestarteddesc','vote_reachedenddesc','vote_entriesclose');
					foreach($unset_array as $unset){
						unset($opt_values[$unset]);
					}
				break;
				
				case 'share':
					$error_image='';
					if($_FILES['facebook_image']['name']!=''){
						$paths = $_FILES['facebook_image']['name'];
						$ext = pathinfo($paths, PATHINFO_EXTENSION); 
						if($ext=='jpg' || $ext=='png' || $ext=='gif'){
						$name_facebook = $_FILES['facebook_image']['name'];
						move_uploaded_file($_FILES['facebook_image']['tmp_name'],$path['path'].'/'.$_FILES['facebook_image']['name']);
						}else{
							$error_image = 'Please upload the image in following formats (jpg, png, gif)';
						}
					}
										
					//pinterest_image
					if($_FILES['pinterest_image']['name']!=''){
						$paths = $_FILES['pinterest_image']['name'];
						$ext = pathinfo($paths, PATHINFO_EXTENSION); 
						if($ext=='jpg' || $ext=='png' || $ext=='gif'){
						$name_pinterest = $_FILES['pinterest_image']['name'];
						move_uploaded_file($_FILES['pinterest_image']['tmp_name'],$path['path'].'/'.$_FILES['pinterest_image']['name']);
						}else{
							$error_image = 'Please upload the image in following formats (jpg, png, gif)';
						}
					}
					
					//gplus_image
					if($_FILES['gplus_image']['name']!=''){
						$paths = $_FILES['gplus_image']['name'];
						$ext = pathinfo($paths, PATHINFO_EXTENSION); 
						if($ext=='jpg' || $ext=='png' || $ext=='gif'){
						$name_gplus = $_FILES['gplus_image']['name'];
						move_uploaded_file($_FILES['gplus_image']['tmp_name'],$path['path'].'/'.$_FILES['gplus_image']['name']);
						}else{
							$error_image = 'Please upload the image in following formats (jpg, png, gif)';
						}
					}
				
					//tumblr_image
					if($_FILES['tumblr_image']['name']!=''){
						$paths = $_FILES['tumblr_image']['name'];
						$ext = pathinfo($paths, PATHINFO_EXTENSION); 
						if($ext=='jpg' || $ext=='png' || $ext=='gif'){
						$name_tumblr  = $_FILES['tumblr_image']['name'];
						move_uploaded_file($_FILES['tumblr_image']['tmp_name'],$path['path'].'/'.$_FILES['tumblr_image']['name']);
						}else{
							$error_image = 'Please upload the image in following formats (jpg, png, gif)';
						}
					}
					
					if($_FILES['twitter_image']['name']!=''){
						$paths = $_FILES['twitter_image']['name'];
						$ext = pathinfo($paths, PATHINFO_EXTENSION); 
						if($ext=='jpg' || $ext=='png' || $ext=='gif'){
						$name_twitter = $_FILES['twitter_image']['name'];
						move_uploaded_file($_FILES['twitter_image']['tmp_name'],$path['path'].'/'.$_FILES['twitter_image']['name']);
						}else{
							$error_image = 'Please upload the image in following formats (jpg, png, gif)';
						}
					}
					
					$value = $_POST['vote_admin_mail']; 
					$value = sanitize_email( $value );
					if($_POST['vote_admin_mail']!=''){
						if ( ! is_email( $value ) ) {
						$value = get_option( $option ); // Resets option to stored value in the case of failed sanitization
						if ( function_exists( 'add_settings_error' ) )
							$error_image =  'The email address entered did not appear to be a valid email address. Please enter a valid email address.';
						}
					}
					
					$unset_array = array('facebook','file_facebook','file_fb_default','pinterest','file_pinterest','file_pinterest_default'
										 ,'gplus','file_gplus','file_gplus_default','tumblr','file_tumblr','file_tumblr_default',
										 'facebook_login','vote_fb_appid','twitter','file_twitter','file_tw_default','twitter_login'
										 ,'vote_tw_appid','vote_tw_secret'
									);
					
					foreach($unset_array as $unset){
						unset($opt_values[$unset]);
					}
				break;
				
				case 'script':
					$error_image='';

					$unset_array = array('deactivation','vote_disable_jquery','vote_disable_jquery_cookie','vote_disable_jquery_fancy',
										 'vote_disable_jquery_pretty',	'vote_disable_jquery_validate'
									);
					foreach($unset_array as $unset){
						unset($opt_values[$unset]);
					}
				break;
			}
			
			$args = array(
			'short_cont_image' => isset($_POST['short_cont_image']) ? $_POST['short_cont_image'] : $opt_values['short_cont_image'],
			'page_cont_image' => isset($_POST['page_cont_image']) ? $_POST['page_cont_image'] : $opt_values['page_cont_image'],
			'title' => isset($_POST['vote_title']) ? $_POST['vote_title'] : $opt_values['title'],
			'orderby' => isset($_POST['vote_orderby']) ? $_POST['vote_orderby'] :  $opt_values['orderby'],
			'order' => isset($_POST['vote_order']) ? $_POST['vote_order'] : $opt_values['order'],
			'vote_sidebar' => isset($_POST['vote_sidebar']) ? $_POST['vote_sidebar'] : $opt_values['vote_sidebar'],
			'vote_select_sidebar'=>isset($_POST['vote_select_sidebar']) ? $_POST['vote_select_sidebar'] : $opt_values['vote_select_sidebar'],
			'vote_readmore'=>isset($_POST['vote_readmore']) ? $_POST['vote_readmore'] :  $opt_values['vote_readmore'],
			'vote_entry_form'=>isset($_POST['vote_entry_form']) ? $_POST['vote_entry_form'] :  $opt_values['vote_entry_form'],
			'single_page_cont_image' => isset($_POST['single_page_cont_image']) ? $_POST['single_page_cont_image'] : $opt_values['single_page_cont_image'],
			'single_page_cont_image_px' => isset($_POST['single_page_cont_image_px']) ? $_POST['single_page_cont_image_px'] : $opt_values['single_page_cont_image_px'],
			'single_contestants_video_width' => isset($_POST['single_contestants_video_width']) ? $_POST['single_contestants_video_width'] : $opt_values['single_contestants_video_width'],
			'single_contestants_video_width_px' => isset($_POST['single_contestants_video_width_px']) ? $_POST['single_contestants_video_width_px'] : $opt_values['single_contestants_video_width_px'],
			'vote_video_width' => isset($_POST['vote_video_width']) ? $_POST['vote_video_width'] : $opt_values['vote_video_width'],
			'vote_audio_width' => isset($_POST['vote_audio_width']) ? $_POST['vote_audio_width'] : $opt_values['vote_audio_width'],
			'vote_audio_height' => isset($_POST['vote_audio_height']) ? $_POST['vote_audio_height'] : $opt_values['vote_audio_height'],
			'vote_audio_skin' => isset($_POST['vote_audio_skin']) ? $_POST['vote_audio_skin'] : $opt_values['vote_audio_skin'],
			'vote_essay_width' => isset($_POST['vote_essay_width']) ? $_POST['vote_essay_width'] : $opt_values['vote_essay_width'],
			'vote_turn_related' => isset($_POST['vote_turn_related']) ? $_POST['vote_turn_related'] : $opt_values['vote_turn_related'],
			
			'single_page_title'=>isset($_POST['single_page_title']) ? $_POST['single_page_title'] : $opt_values['single_page_title'],
			'vote_title_alocation'=>isset($_POST['vote_title_alocation']) ? $_POST['vote_title_alocation'] : $opt_values['vote_title_alocation'],
			'vote_notify_mail' => isset($_POST['vote_notify_mail']) ? $_POST['vote_notify_mail'] : $opt_values['vote_notify_mail'],
			'vote_admin_mail' => isset($_POST['vote_admin_mail']) ? $_POST['vote_admin_mail'] : $opt_values['vote_admin_mail'],
			'vote_admin_mail_content' => isset($_POST['vote_admin_mail_content']) ? $_POST['vote_admin_mail_content'] : $opt_values['vote_admin_mail_content'],
			'vote_from_name'=>isset($_POST['vote_from_name'])?$_POST['vote_from_name']:$opt_values['vote_from_name'],
			'votes_timertextcolor' => isset($_POST['votes_timertextcolor']) ? $_POST['votes_timertextcolor'] : $opt_values['votes_timertextcolor'],
			'votes_timerbgcolor' => isset($_POST['votes_timerbgcolor']) ? $_POST['votes_timerbgcolor'] : $opt_values['votes_timerbgcolor'],			
			'vote_show_date_prettyphoto' => isset($_POST['vote_show_date_prettyphoto']) ? $_POST['vote_show_date_prettyphoto'] : $opt_values['vote_show_date_prettyphoto'],
			'vote_prettyphoto_disable' => isset($_POST['vote_prettyphoto_disable']) ? $_POST['vote_prettyphoto_disable'] : $opt_values['vote_prettyphoto_disable'],
			'vote_prettyphoto_disable_single'=> isset($_POST['vote_prettyphoto_disable_single']) ? $_POST['vote_prettyphoto_disable_single'] : $opt_values['vote_prettyphoto_disable_single'],
			'vote_custom_slug' => isset($_POST['vote_custom_slug']) ? $_POST['vote_custom_slug'] : $opt_values['vote_custom_slug'],
			'vote_hide_account' => isset($_POST['vote_hide_account']) ? $_POST['vote_hide_account'] : $opt_values['vote_hide_account'],
			'vote_openclose_menu' => isset($_POST['vote_openclose_menu']) ? $_POST['vote_openclose_menu'] : $opt_values['vote_openclose_menu'],
			'vote_enable_all_contest' => isset($_POST['vote_enable_all_contest']) ? $_POST['vote_enable_all_contest'] : $opt_values['vote_enable_all_contest'],
			'vote_enable_ended' => isset($_POST['vote_enable_ended']) ? $_POST['vote_enable_ended'] : $opt_values['vote_enable_ended'],
			'vote_count_showhide' => isset($_POST['vote_count_showhide']) ? $_POST['vote_count_showhide'] : $opt_values['vote_count_showhide'],
			'vote_all_contest_design' => isset($_POST['vote_all_contest_design']) ? $_POST['vote_all_contest_design'] : $opt_values['vote_all_contest_design'],

			
			'vote_onlyloggedcansubmit' => isset($_POST['vote_onlyloggedcansubmit'])?$_POST['vote_onlyloggedcansubmit']:$opt_values['vote_onlyloggedcansubmit'],
			'onlyloggedinuser' => isset($_POST['vote_onlyloggedinuser']) ? $_POST['vote_onlyloggedinuser'] : $opt_values['onlyloggedinuser'],
			'vote_tracking_method' => isset($_POST['vote_tracking_method'])?$_POST['vote_tracking_method']:$opt_values['vote_tracking_method'],
			'vote_truncation_grid'=>isset($_POST['vote_truncation_grid']) ? $_POST['vote_truncation_grid'] : $opt_values['vote_truncation_grid'],
			'vote_truncation_list'=>isset($_POST['vote_truncation_list']) ? $_POST['vote_truncation_list'] : $opt_values['vote_truncation_list'],
			'vote_frequency_count' => isset($_POST['vote_frequency_count']) ? $_POST['vote_frequency_count'] : $opt_values['vote_frequency_count'],
			'vote_frequency_hours' => isset($_POST['vote_frequency_hours']) ? $_POST['vote_frequency_hours'] : $opt_values['vote_frequency_hours'],
			'frequency' => isset($_POST['vote_frequency']) ? $_POST['vote_frequency'] : $opt_values['frequency'],
			'vote_votingtype' => isset($_POST['vote_votingtype']) ? $_POST['vote_votingtype'] :$opt_values['vote_votingtype'],
			'vote_grab_email_address' => isset($_POST['vote_grab_email_address']) ? $_POST['vote_grab_email_address'] :$opt_values['vote_grab_email_address'],
			'vote_publishing_type' => isset($_POST['vote_publishing_type']) ? $_POST['vote_publishing_type'] :$opt_values['vote_publishing_type'],			
			'vote_tobestarteddesc' => isset($_POST['vote_tobestarteddesc']) ? htmlspecialchars(stripslashes($_POST['vote_tobestarteddesc']),ENT_QUOTES) : htmlspecialchars(stripslashes($opt_values['vote_tobestarteddesc']),ENT_QUOTES),
			'vote_reachedenddesc' => isset($_POST['vote_reachedenddesc']) ? htmlspecialchars(stripslashes($_POST['vote_reachedenddesc']),ENT_QUOTES) : htmlspecialchars(stripslashes($opt_values['vote_reachedenddesc']),ENT_QUOTES),
			'vote_entriescloseddesc' => isset($_POST['vote_entriescloseddesc']) ? htmlspecialchars(stripslashes($_POST['vote_entriescloseddesc']),ENT_QUOTES) : htmlspecialchars(stripslashes($opt_values['vote_entriescloseddesc']),ENT_QUOTES),
			
			'facebook' => isset($_POST['vote_facebook']) ? $_POST['vote_facebook'] : $opt_values['facebook'],
			'file_facebook' => isset($name_facebook) ? $name_facebook : $_POST['fb_uploaded_image'],
			'file_fb_default'=>isset($_POST['vote_facebook_default_img']) ? $_POST['vote_facebook_default_img'] : $opt_values['file_fb_default'],
			'pinterest' => isset($_POST['vote_pinterest']) ? $_POST['vote_pinterest'] : $opt_values['pinterest'],
			'file_pinterest' => isset($name_pinterest) ? $name_pinterest : $_POST['pinterest_uploaded_image'],
			'file_pinterest_default'=>isset($_POST['vote_pinterest_default_img']) ? $_POST['vote_pinterest_default_img'] : $opt_values['file_pinterest_default'],
			'gplus' => isset($_POST['vote_gplus']) ? $_POST['vote_gplus'] : $opt_values['gplus'],
			'file_gplus' => isset($name_gplus) ? $name_gplus: $_POST['gplus_uploaded_image'],
			'file_gplus_default'=>isset($_POST['vote_gplus_default_img']) ? $_POST['vote_gplus_default_img'] : $opt_values['file_gplus_default'],
			'tumblr' => isset($_POST['vote_tumblr']) ? $_POST['vote_tumblr'] : $opt_values['tumblr'],
			'file_tumblr' => isset($name_tumblr) ? $name_tumblr : $_POST['tumblr_uploaded_image'],
			'file_tumblr_default'=>isset($_POST['vote_tumblr_default_img']) ? $_POST['vote_tumblr_default_img'] : $opt_values['file_tumblr_default'],
			'facebook_login'=>isset($_POST['facebook_login']) ? $_POST['facebook_login'] : $opt_values['facebook_login'],
			'vote_fb_appid'=>isset($_POST['vote_fb_appid']) ? $_POST['vote_fb_appid'] : $opt_values['vote_fb_appid'],        
			'twitter' => isset($_POST['vote_twitter']) ? $_POST['vote_twitter'] : $opt_values['twitter'],
			'file_twitter' => isset($name_twitter) ? $name_twitter : $_POST['tw_uploaded_image'],
			'file_tw_default'=>isset($_POST['vote_twitter_default_img']) ? $_POST['vote_twitter_default_img'] : $opt_values['file_tw_default'],
			'twitter_login'=>isset($_POST['twitter_login']) ? $_POST['twitter_login'] : $opt_values['twitter_login'],
			'vote_tw_appid'=>isset($_POST['vote_tw_appid']) ? $_POST['vote_tw_appid'] : $opt_values['vote_tw_appid'], 
			'vote_tw_secret'=>isset($_POST['vote_tw_secret']) ? $_POST['vote_tw_secret'] : $opt_values['vote_tw_secret'],
			
			'deactivation' => isset($_POST['vote_deactivation']) ? $_POST['vote_deactivation'] : $opt_values['deactivation'],
			'vote_disable_jquery' => isset($_POST['disable_jquery']) ? $_POST['disable_jquery'] : $opt_values['vote_disable_jquery'],
			'vote_disable_jquery_cookie' => isset($_POST['disable_jquery_cookie']) ? $_POST['disable_jquery_cookie'] : $opt_values['vote_disable_jquery_cookie'],
			'vote_disable_jquery_fancy' => isset($_POST['disable_jquery_fancy']) ? $_POST['disable_jquery_fancy'] : $opt_values['vote_disable_jquery_fancy'],
			'vote_disable_jquery_pretty' => isset($_POST['disable_jquery_pretty']) ? $_POST['disable_jquery_pretty'] : $opt_values['vote_disable_jquery_pretty'],
			'vote_disable_jquery_validate' => isset($_POST['disable_jquery_validate']) ? $_POST['disable_jquery_validate'] : $opt_values['vote_disable_jquery_validate']
			
			);

			if($error_image=='') {
				update_option(OW_VOTES_SETTINGS, $args);
				update_option(OW_VOTES_GENERALEXPIRATIONFIELD, $votesexpiration);
				update_option(OW_VOTES_GENERALSTARTTIME, $votesstarttime);
			}
			
		    if($error_image=='') 
				echo '<div style="line-height:40px;" class="updated settings_upd">' . __('Settings Updated','voting-contest') . '</div>';
			else
				echo '<div style="line-height:40px;color:red;" class="updated settings_upd">' . $error_image . '</div>';
	
		}
		
    
		public static function ow_voting_format_css($coloroption){
			$format_css = "
			.ow_countdown_wrapper .dash .digit,.ow_countdown_tag,.countdown_end_timer{font-size:".$coloroption['votes_counter_font_size']."px !important;color:".$coloroption['votes_timertextcolor']." !important;}
			.ow_countdown_wrapper{background-color:".$coloroption['votes_timerbgcolor']." !important;}
			.ow_vote_menu_links li a{font-size:".$coloroption['votes_navigation_font_size']."px !important;color:".$coloroption['votes_navigation_text_color']." !important;}
			.ow_vote_menu_links li a:hover{color:".$coloroption['votes_navigation_text_color_hover']." !important;}
			.ow_vote_contest_top_bar{background:".$coloroption['votes_navigationbgcolor']." !important;}
			.ow_vote_contest_top_bar ul.ow_vote_menu_links li.ow_vote_navmenu_link a {background:".$coloroption['vote_navbar_button_background']." !important}
			.ow_vote_contest_top_bar ul.ow_vote_menu_links li.ow_vote_navmenu_link a:hover, .ow_vote_contest_top_bar ul.ow_vote_menu_links li.ow_vote_navmenu_link.active a {background:".$coloroption['vote_navbar_active_button_background']." !important}
			.menudiv .togglehide::after,.votecontestant-menu-down::before{color:".$coloroption['vote_navbar_mobile_font']." !important}
			.ow_vote_list .ow_show_contestant h2 a{font-size:".$coloroption['votes_cont_title_font_size']."px !important;color:".$coloroption['votes_cont_title_color']." !important;}
			.ow_vote_grid .ow_show_contestant h2 a{font-size:".$coloroption['votes_cont_title_font_size']."px !important;color:".$coloroption['votes_cont_title_color_grid']." !important;}
			h2.ow_vote_single-title, .ow_contestant_custom_fields h2 {color:".$coloroption['votes_cont_title_color_single']." !important;}
			div.pp_kalypso .pp_details {background:".$coloroption['votes_popup_content_bg']." !important;}
			.ow_single_page_content .vote_content p, .ow_contestant_other_det {color:".$coloroption['votes_cont_content_color_single']." !important;}
			.ow_voting_left a, .ow_voting_right a {background:".$coloroption['single_navigation_button']." !important;}
			.ow_voting_left a:hover, .ow_voting_right a:hover {background:".$coloroption['single_navigation_button_hover']." !important;}
			.pp_mult_desc .ow_contestant_custom_fields h2 {color:".$coloroption['votes_popup_additional_info_color']." !important;background:".$coloroption['votes_popup_additional_info_bg']." !important;}
			.pp_mult_desc .ow_contestant_other_det {color:".$coloroption['votes_popup_content_color']." !important;}
			.ow_vote_share_parent .ow_vote_share_url {color:".$coloroption['votes_single_social_sharing_url_color']." !important;}
			.ow_vote_single_container .ow_vote_content_container {background:".$coloroption['single_contestant_content_bg']." !important;}
			.ow_vote_list .ow_show_contestant h2 a:hover{color:".$coloroption['votes_cont_title_color_hover']." !important;}
			.ow_vote_grid .ow_show_contestant h2 a:hover{color:".$coloroption['votes_cont_title_color_hover_grid']." !important;}
			.ow_vote_list .ow_show_contestant h2{background-color:".$coloroption['votes_cont_title_bgcolor']." !important;}
			.ow_vote_list .ow_show_text_desc{font-size:".$coloroption['votes_cont_desc_font_size']."px !important;color:".$coloroption['votes_cont_dese_color']." !important;background-color:".$coloroption['votes_cont_desc_bgcolor']."!important;}
			.ow_vote_list .ow_show_read_more a,.ow_vote_grid .ow_show_read_more a{font-size:".$coloroption['votes_readmore_font_size']."px !important;color:".$coloroption['votes_readmore_fontcolor']." !important;background-color:".$coloroption['votes_readmore_bgcolor']." !important;padding-top:".$coloroption['votes_readmore_padding_top']."px !important;padding-bottom:".$coloroption['votes_readmore_padding_bottom']."px !important;padding-left:".$coloroption['votes_readmore_padding_left']."px !important;padding-right:".$coloroption['votes_readmore_padding_right']."px !important;}
			.ow_vote_list .ow_show_read_more a:hover,.ow_vote_grid .ow_show_read_more a:hover{color:".$coloroption['votes_readmore_fontcolor_hover']." !important;background-color:".$coloroption['votes_readmore_bgcolor_hover']." !important;}
			.ow_vote_list .ow_show_vote_cnt{border:".$coloroption['votes_bar_border_size']."px solid !important;border-color:".$coloroption['votes_bar_border_color']." !important;}
			.ow_pp_vote_count,.pp_social .ow_show_vote_square,.ow_show_vote_square{color:".$coloroption['votes_count_font_color']." !important;font-size:".$coloroption['votes_count_font_size']."px !important;background-color:".$coloroption['votes_count_bgcolor']." !important;}
			.ow_voting_right,.ow_voting_left,.ow_voting_button_now,.ow_pp_vote_btn_single,.pp_social .ow_show_vote_button,.ow_votebutton{color:".$coloroption['votes_button_font_color']." !important;font-size:".$coloroption['votes_button_font_size']."px !important;background-color:".$coloroption['votes_button_bgcolor']." !important;}
			.ow_show_vote_button a:hover{color:".$coloroption['votes_button_font_color_hover']." !important;background-color:".$coloroption['votes_button_bgcolor_hover']." !important;}
			.ow_show_vote_button.ow_voting_grey_button, a.ow_voting_grey_button{background-color:".$coloroption['votes_already_button_bgcolor']." !important;}
			 a.ow_voting_green_button{background: ".$coloroption['votes_highlight_button_bgcolor']." !important;}
			.ow_show_share_icons{font-size:".$coloroption['votes_social_font_size']."px !important;color:".$coloroption['votes_social_icon_color']." !important;}
			.ow_show_share_icons:hover{color:".$coloroption['votes_social_icon_color_hover']." !important;}
			.voteconestant-grid{color:".$coloroption['votes_list_inactive']." !important;}
			.voteconestant-list{color:".$coloroption['votes_grid_inactive']." !important;}
			.ow_vote_menu_links li .ow_list_active{color:".$coloroption['votes_list_active']." !important;}
			.ow_vote_menu_links li .ow_grid_active{color:".$coloroption['votes_grid_active']." !important;}
			.ow_votes_social_container{background-color:".$coloroption['votes_single_social_sharing']." !important;}
			.ow_votes-pagination a{font-size:".$coloroption['votes_pagination_font_size']."px !important;color:".$coloroption['votes_pagination_font_color']." !important;}
			.ow_votes-pagination .current{background-color:".$coloroption['votes_pagination_active_bg_color']." !important;color:".$coloroption['votes_pagination_active_font_color']." !important;}
			.ow_votes-pagination a:hover{background-color:".$coloroption['votes_pagination_hover_bg_color']." !important;color:".$coloroption['votes_pagination_hover_font_color']." !important;}
			.ow_tab_buttons a.active {background-color:".$coloroption['login_tab_active_bg_color']." !important;color:".$coloroption['login_tab_active_font_color']." !important;}
			.ow_tab_buttons a:hover {background-color:".$coloroption['login_tab_hover_bg_color']." !important;color:".$coloroption['login_tab_hover_font_color']." !important;}
			.ow_tab_buttons a {color:".$coloroption['login_tab_font_color']." !important;}
			.login-panel .ow_tabs_content, .forgot-panel {background-color:".$coloroption['login_body_background_color']." !important;}
			.login-panel .create_account, .login-panel input[type=submit], .register-panel input[type=submit], .forgot-panel input[type=submit], .login-panel .login_facebook {background-color:".$coloroption['login_button_background_color']." !important;color:".$coloroption['login_button_font_color']." !important;}
			.login-panel .create_account:hover, .login-panel input[type=submit]:hover, .register-panel input[type=submit]:hover, .forgot-panel input[type=submit]:hover {background-color:".$coloroption['login_button_hover_bg_color']." !important;color:".$coloroption['login_button_hover_font_color']." !important;}
			.login-panel .m_title, .register-panel .m_title, .forgot-panel .m_title, .zn_remember, .login-panel .links a, .register-panel .links a, .forgot-panel .links a, .register-panel_inner label {color:".$coloroption['popup_body_text_color']." !important;}
					";
			return $format_css;
		}
    }
    
}else
die("<h2>".__('Failed to load Voting Common Settings Controller','voting-contest')."</h2>");


return new Ow_Vote_Common_Settings_Controller();
