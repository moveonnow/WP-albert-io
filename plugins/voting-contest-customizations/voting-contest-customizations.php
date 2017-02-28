<?php

/**
 * The plugin bootstrap file
 *
 * 
 *
 * @link              aei874@mail.ru
 * @since             1.0.1
 * @package           Voting Contest Customizations
 *
 * @wordpress-plugin
 * Plugin Name:       Voting Contest Customizations
 * Plugin URI:        https://github.com/moveonnow/wp_voting_contest_customizations
 * Description:       Use to add an options page to the OW Voting Contest Plugin
 * Version:           1.0.1
 * Author:            Eduard Abdullin
 * Author URI:        aei874@mail.ru
 * Text Domain:       https://github.com/moveonnow/wp_voting_contest_customizations
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
register_activation_hook( __FILE__, 'activate_voting_contest_customizations' );
register_deactivation_hook( __FILE__, 'deactivate_voting_contest_customizations' );
require plugin_dir_path( __FILE__ ) . 'includes/class-voting-contest-customizations.php';
*/

if( function_exists('acf_add_options_page') ) {
			
			acf_add_options_page(array(
				'page_title' 	=> 'Voting Contest Customizations',
				'menu_title'	=> 'Voting Contest Customizations',
				'menu_slug' 	=> 'voting-contest-customizations-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			));
			
			/*
			acf_add_options_sub_page(array(
				'page_title' 	=> 'Subpage',
				'menu_title'	=> 'Subpage',
				'parent_slug'	=> 'voting-contest-customizations-settings',
			));
			*/
}

?>