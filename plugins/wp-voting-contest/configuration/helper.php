<?php

function ow_get_template_part(){
	
}

//Include the Javascript & Styles for the Voting System
add_action('ow_voting_scripts_styles','ow_voting_scripts_styles_includes');
function ow_voting_scripts_styles_includes(){
	wp_enqueue_script("jquery");
	
	wp_register_style('OW_FRONT_CONTESTANT_STYLES', OW_ASSETS_FRONT_END_CSS_PATH);
	wp_enqueue_style('OW_FRONT_CONTESTANT_STYLES');
	
	
	wp_register_style('OW_FRONT_COLOR', OW_ASSETS_COLOR_RELPATH);
	wp_enqueue_style('OW_FRONT_COLOR');
	
	
	wp_register_script('ow_votes_block', OW_ASSETS_JS_PATH . 'ow_vote_block_div.js');
	wp_enqueue_script('ow_votes_block',array('jquery'));	
	
	if($_SESSION['votingoption']['vote_disable_jquery_pretty']!='on'){
		wp_register_style('ow_vote_css_pretty', OW_ASSETS_CSS_PATH.'ow_vote_prettyPhoto.css');
		wp_enqueue_style('ow_vote_css_pretty');
	
		wp_register_script('ow_votes_pretty', OW_ASSETS_JS_PATH . 'ow_vote_prettyPhoto.js');
		wp_enqueue_script('ow_votes_pretty',array('jquery'));
	}
	
	if($_SESSION['votingoption']['vote_disable_jquery_fancy']!='on'){
		wp_register_style('ow_vote_css_fancy_box', OW_ASSETS_CSS_PATH.'ow_vote_fancybox.css');
		wp_enqueue_style('ow_vote_css_fancy_box');
	
		wp_register_script('ow_vote_fancy_box', OW_ASSETS_JS_PATH . 'ow_vote_fancybox.js');
		wp_enqueue_script('ow_vote_fancy_box',array('jquery'));
	}
	
	wp_register_script('ow_fb_script_js', OW_ASSETS_JS_PATH . 'ow_votes_fbscript.js');
	wp_enqueue_script('ow_fb_script_js',array('jquery'));
	
	wp_register_script('ow_votes_shortcode', OW_ASSETS_JS_PATH . 'ow_vote_shortcode_jquery.js');
	wp_enqueue_script('ow_votes_shortcode',array('jquery'));
	
	wp_localize_script( 'ow_votes_shortcode', 'vote_path_local', array('votesajaxurl' => admin_url( 'admin-ajax.php' ),'vote_image_url'=>OW_ASSETS_IMAGE_PATH ) );
}


//Include the Main Variables in the Single Contestants Page
add_action('ow_voting_global_variables','ow_voting_global_variables_includes');
function ow_voting_global_variables_includes(){
	
	$option = $_SESSION['votingoption'];
	$global_options = Ow_Vote_Common_Controller::ow_vote_get_all_global_settings($option);
	if(!empty($global_options)){
		foreach($global_options as $variab => $glob_opt){
			$$variab = $glob_opt;
		}
	}		
	
	if($onlyloggedinuser!='' && !is_user_logged_in()){
		Ow_Vote_Shortcode_Controller::ow_votes_custom_registration_fields_show();
	}
	
}