<?php
	if (is_admin()){
		
		$auto_ctrl_files = array(
					'Ow_Widget_Recent_Controller','Ow_Widget_Leader_Controller',
					'Ow_Vote_Common_Controller','Ow_Vote_Taxonomy_Controller',
					'Ow_Vote_Contestant_Controller','Ow_Admin_Controller','Ow_Vote_Save_Controller',
					'Ow_Vote_Ajax_Controller','Ow_Vote_Custom_Field_Controller','Ow_Vote_Common_Settings_Controller',
					'Ow_Vote_Excerpt_Controller','Ow_Vote_License_Controller','Ow_Vote_Updater','Ow_Vote_OW_Video','Ow_Upgrade_Controller'
					);
    
		$auto_model_files = array('Ow_Installation_Model','Ow_Contestant_Model','Ow_Custom_Field_Model'
								  ,'Ow_Common_Settings_Model','Ow_Vote_Shortcode_Model','Ow_Votes_Save_Model'
								);
		
	}else{
		
		$auto_ctrl_files = array(
					'Ow_Widget_Recent_Controller','Ow_Widget_Leader_Controller',
					'Ow_Vote_Common_Controller', 'Ow_Admin_Controller',
					'Ow_Vote_Excerpt_Controller','Ow_Vote_Shortcode_Controller',
					'Ow_Vote_Save_Controller','Ow_Vote_Ajax_Controller','Ow_Vote_Single_Contestants','Ow_Vote_OW_Video'
				       );
		$auto_model_files = array('Ow_Vote_Shortcode_Model','Ow_Contestant_Model','Ow_Custom_Field_Model','Ow_Votes_Save_Model');
		
	}
	
    /***************** File paths ******************/
    define('OW_MODEL_PATH',OW_VOTES_ABSPATH.'owmodel/');
    define('OW_CONTROLLER_PATH',OW_VOTES_ABSPATH.'owcontroller/');
    define('OW_CONTROLLER_XL_PATH',OW_VOTES_ABSPATH.'owcontroller/xl_classes/');
    define('OW_VIEW_PATH',OW_VOTES_ABSPATH.'owviews/adminend/');
    define('OW_VIEW_FRONT_PATH',OW_VOTES_ABSPATH.'owviews/frontend/');
	
    define('OW_ASSETS_ADMIN_CSS_PATH',OW_VOTES_SL_PLUGIN_URL.'assets/css/ow_admin_styles.css');
    define('OW_ASSETS_FRONT_END_CSS_PATH',OW_VOTES_SL_PLUGIN_URL.'assets/css/ow_votes_display.css');
    define('OW_ASSETS_COLORCSS_PATH',OW_VOTES_ABSPATH.'assets/css/ow_votes_color.css');	
    define('OW_ASSETS_COLOR_RELPATH',OW_VOTES_SL_PLUGIN_URL.'assets/css/ow_votes_color.css');	
    define('OW_ASSETS_CSS_PATH',OW_VOTES_SL_PLUGIN_URL.'assets/css/');
    define('OW_ASSETS_IMAGE_PATH',OW_VOTES_SL_PLUGIN_URL.'assets/image/');
    define('OW_ASSETS_JS_PATH',OW_VOTES_SL_PLUGIN_URL.'assets/js/');
    define('OW_ASSETS_UPLOAD_PATH',OW_VOTES_ABSPATH.'assets/uploads/');
    define('OW_NO_IMAGE_CONTEST',OW_ASSETS_IMAGE_PATH.'/img_not_available.png');
    define('OW_LOADER_IMAGE',OW_ASSETS_IMAGE_PATH.'/fancy/fancy_loader.gif');
	define('OW_VOTING_BUTTON',OW_ASSETS_IMAGE_PATH.'/ow_button-own-icon.png');
    define('OW_FILE_IMAGE',OW_ASSETS_IMAGE_PATH.'ow_file.png');
    define('OW_SMALLFILE_IMAGE',OW_ASSETS_IMAGE_PATH.'ow_file_small.png');
    define('OW_CANCEL_IMAGE',OW_ASSETS_IMAGE_PATH.'cancel_icon.png');
	
    /*********** Program constants ***********/
    define('OW_VOTES_VERSION', '3.0.5');
    define('OW_VOTES_TYPE', 'contestants');
    define('VOTES_TYPE_PAGIN', 'votes_setting_paginate');
    define('OW_VOTES_TAXONOMY', 'contest_category');
    define('OW_VOTES_CUSTOMFIELD', 'votes_count');
    define('OW_VOTES_EXPIRATIONFIELD', 'votes_expiration');
    define('OW_VOTES_SETTINGS', 'votes_settings');
    define('OW_VOTES_COLORSETTINGS', 'votes_color_settings');
    define('OW_VOTES_ACTIVE_THEME', 'votes_current_theme');
    define('OW_VOTES_SHOW_DESC', 'list');
    define('OW_VOTES_ENTRY_LIMIT_FORM', '');
    define('OW_VOTES_TEXTDOMAIN', 'wp-pagenavi');
    define('OW_VOTES_TAXEXPIRATIONFIELD', 'votes_taxexpiration');
    define('OW_VOTES_TAXACTIVATIONLIMIT', 'votes_taxactivationlimit');
    define('OW_VOTES_TAXSTARTTIME', 'votes_taxstarttime');
    define('OW_VOTES_GENERALEXPIRATIONFIELD', 'votes_generalexpiration');
    define('OW_VOTES_GENERALSTARTTIME', 'votes_generalstarttime');
    define('OW_VOTES_CONTESTPHOTOGRAPHERNAME', 'contestant_photographer_name');	
    define('OW_VOTES_CONTENT_LENGTH', get_option('votesadvancedexcerpt_length'));
    define('OW_VOTES_CONTENT_ELLIPSES', get_option('votesadvancedexcerpt_ellipsis'));
    define('OW_VOTES_VIEWS', 'votes_viewers');
    define('OW_DEF_THEME', 'summer');
    define('OW_DEF_PUBLISHING', 'pending');
     
    /*************** Table constants **************/
    define('OW_VOTES_TBL', $wpdb->prefix . 'votes_tbl');
    define("OW_VOTES_ENTRY_CUSTOM_TABLE", $wpdb->prefix . "votes_custom_field_contestant");    
    define("OW_VOTES_POST_ENTRY_TABLE", $wpdb->prefix . "votes_post_entry_contestant"); 
    define("OW_VOTES_USER_ENTRY_TABLE", $wpdb->prefix . "votes_user_entry_contestant"); 
    define("OW_VOTES_USER_CUSTOM_TABLE", $wpdb->prefix . "votes_custom_registeration_contestant");
    define("OW_VOTES_POST_ENTRY_TRACK", $wpdb->prefix . "votes_post_contestant_track");
  
	
    /******** Intialize the needed classes **********/
	    
    controller_autoload($auto_ctrl_files);
    model_autoload($auto_model_files);

    require_once('installation.php');
    require_once('ow_hooks.php');	
    include_once OW_CONTROLLER_PATH.'/pagination/wp-pagenavi.php';	
    function controller_autoload($class_name) 
    {
		if(!empty($class_name)){
			foreach($class_name as $class_nam):
				$filename = strtolower($class_nam).'.php';
				$file = OW_CONTROLLER_PATH.$filename;
			
				if (file_exists($file) == false)
				{
					return false;
				}
				include_once($file);
			endforeach;
		}
    }
	    
    function model_autoload($class_name) 
    {
		if(!empty($class_name)){
			foreach($class_name as $class_nam):
				$filename = strtolower($class_nam).'.php';
				$file = OW_MODEL_PATH.$filename;
			
				if (file_exists($file) == false)
				{
					return false;
				}
				include_once($file);
			endforeach;
		}
    }
	
?>
