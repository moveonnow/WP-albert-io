<?php
/*
Plugin Name: WP Voting Contest
Version: 3.3.6
Description: Quickly and seamlessly integrate an online contest with voting into your Wordpress 4.0+ website. You can start many types of online contests such as photo, video, audio, names with very little effort.
Author: Ohio Web Technologies
Author URI: http://www.ohiowebtech.com
Copyright (c) 2008-2016 Ohio Web Technologies All Rights Reserved.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/
error_reporting(0);
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

global $wpdb;

define('OW_VOTE_VERSION','3.3.6');
/*********** File path constants **********/
define('OW_VOTES_ABSPATH', dirname(__FILE__) . '/');
define('OW_VOTES_PATH', plugin_dir_url(__FILE__));
define('OW_VOTES_SL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('OW_VOTES_SL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('OW_VOTES_SL_PLUGIN_FILE', __FILE__);
define('OW_WP_VOTING_SL_STORE_API_URL', 'http://plugins.ohiowebtech.com');
define('OW_WP_VOTING_SL_PRODUCT_NAME', 'WordPress Voting Photo Contest Plugin');
load_plugin_textdomain( 'voting-contest', '', dirname( plugin_basename( __FILE__ ) ) . '/assets/lang' );
require_once('configuration/config.php');
require_once('configuration/helper.php');
register_activation_hook(__FILE__,'ow_votes_activation_init');
register_deactivation_hook(__FILE__,'ow_votes_deactivation_init');

if(!function_exists('votes_version_updater_admin')){    
    function votes_version_updater_admin()
    {
            $wp_voting_sl_license_key = trim(get_option('wp_voting_software_license_key'));
            $wp_voting_ = new Ow_Vote_Updater(OW_WP_VOTING_SL_STORE_API_URL, __FILE__, array(
                            'version' => '3.3.6',
                            'license' => $wp_voting_sl_license_key,
                            'item_name' => OW_WP_VOTING_SL_PRODUCT_NAME,
                            'author' => 'Ohio Web Technologies'
                            ));
    }
}
add_action( 'admin_init', 'votes_version_updater_admin' );