<?php
    /********* For installation ***********/
	add_action( 'mail_hook_add_contestant', array('Ow_Vote_Shortcode_Controller','ow_votes_add_contestant_mail_function'), 10, 2 );
	add_action( 'user_register', array('Ow_Vote_Ajax_Controller','ow_votes_register_extra_fields')); 
	add_action( 'personal_options_update', array('Ow_Vote_Ajax_Controller','ow_votes_register_extra_fields'));	
	add_action( 'edit_user_profile_update', array('Ow_Vote_Ajax_Controller','ow_votes_register_extra_fields'));
	add_filter( 'user_profile_update_errors', array('Ow_Vote_Ajax_Controller','ow_votes_registration_errors'), 10, 2);
	
	add_filter('show_user_profile', array('Ow_Vote_Ajax_Controller','ow_voting_modify_contact_methods'));
	add_action('edit_user_profile', array('Ow_Vote_Ajax_Controller','ow_voting_modify_contact_methods'));    
	add_action('user_new_form_tag', array('Ow_Vote_Ajax_Controller','ow_voting_modify_contact_methods'));
	add_action( 'delete_user', array('Ow_Vote_Ajax_Controller','ow_votes_delete_user_custom_entry'));
	
	add_action('wp_logout',array('Ow_Vote_Common_Controller','ow_votes_redirect_go_home'));
	
	add_action('ow_update_fields',array('Ow_Vote_Ajax_Controller','ow_votes_update_contestant_field'),10,3);
	
   
?>