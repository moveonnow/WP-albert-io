<?php
if(!function_exists('ow_votes_activation_init')){
		function ow_votes_activation_init() {
				global $wpdb;
				Ow_Installation_Model::create_tables_owvoting();
				$defaults = array(
						'imgheight' => 92,
						'imgwidth' => 132,
						'imgdisplay' => FALSE,
						'title' => FALSE,
						'orderby' => 'date',
						'order' => 'desc',
						'termdisplay' => FALSE,
						'onlyloggedinuser' => FALSE,
						'frequency' => 1,
						'vote_votingtype' => FALSE,
						'vote_publishing_type' => FALSE,
						'deactivation' => 'on',
						'vote_tobestarteddesc' => __('Contest not yet open for voting','voting-contest'),
						'vote_reachedenddesc' => __('There is no Contest at this time','voting-contest'),
						'vote_entriescloseddesc' => __('Contest already Started.','voting-contest'),
						'votes_timertextcolor' => '#000000',
						'votes_timerbgcolor' => '#ffffff'
					);
				$args = get_option(OW_VOTES_SETTINGS);
				$args = wp_parse_args($args, $defaults);
				update_option(OW_VOTES_SETTINGS, $args);
				update_option(OW_VOTES_GENERALEXPIRATIONFIELD, '0');							
				
				/* Style Option Releases */
				$color_check_option = get_option(OW_VOTES_COLORSETTINGS);
				if(OW_VOTE_VERSION == '3.0.5' || $color_check_option == null){
				$color_settings = array();
				$color_settings[OW_DEF_THEME] = array(
								'votes_counter_font_size'=> '14',
								'votes_timertextcolor' => '#000000',
								'votes_timerbgcolor'   => '#ffffff',
								'votes_navigation_font_size' => '14',
								'votes_navigation_text_color' => '#FFFFFF',
								'votes_navigation_text_color_hover' => '',
								'votes_navigationbgcolor' => '#305891',
								'votes_list_active' => '#F26E2A',
								'votes_list_inactive' => '#FFFFFF',
								'votes_grid_active' => '#F26E2A',
								'votes_grid_inactive' => '#FFFFFF',
								'votes_cont_title_font_size' => '16',
								'votes_cont_title_color' => '#FFFFFF',
								'votes_cont_title_color_hover' => '#F26E2A',
								'votes_cont_title_color_grid' => '#F26E2A',
								'votes_cont_title_color_hover_grid' => '#000000',
								'votes_cont_title_bgcolor' => '#30598f',
								'votes_cont_title_color_single' => '#F26E2A',
								'single_navigation_button' => '#F26E2A',
								'single_navigation_button_hover' => '#d75614',
								'single_contestant_content_bg' => '#ebebeb',
								'votes_cont_content_color_single' => '',
								'votes_popup_content_bg' => '',
								'votes_popup_additional_info_color' => '',
								'votes_popup_additional_info_bg' => '',
								'votes_popup_content_color' => '',
								'votes_single_social_sharing_url_color' => '#30598f',
								'votes_single_social_sharing' => '#30598f',
								'votes_single_social_sharing_bg' => '#ebebeb',
								'votes_cont_desc_font_size' => '16',
								'votes_cont_dese_color' => '#000000',
								'votes_cont_desc_bgcolor' => '#FFFFFF',								
								'votes_readmore_font_size' => '14',
								'votes_readmore_fontcolor' => '#F26E2A',
								'votes_readmore_fontcolor_hover' => '#000000',
								'votes_readmore_bgcolor' => '',
								'votes_readmore_bgcolor_hover' => '',
								'votes_readmore_padding_top' => '',
								'votes_readmore_padding_bottom' => '',
								'votes_readmore_padding_left' => '5',
								'votes_readmore_padding_right' => '',
								'votes_bar_border_color' => '#DDDDDD',
								'votes_bar_border_size' => '1',
								'votes_count_font_size' => '16',
								'votes_count_font_color' => '#FFFFFF',
								'votes_count_bgcolor' => '#3276b1',
								'votes_button_font_size' => '16',
								'votes_button_font_color' => '#FFFFFF',
								'votes_button_font_color_hover' => '',
								'votes_button_bgcolor' => '#F26E2A',
								'votes_button_bgcolor_hover' => '#ca581d',
								'votes_social_font_size' => '25',
								'votes_social_icon_color' => '#F26E2A',
								'votes_social_icon_color_hover' => '#30598F',
								'votes_pagination_font_size' => '14',
								'votes_pagination_font_color' => '#352600',
								'votes_pagination_active_font_color' => '',
								'votes_pagination_active_bg_color' => '',
								'votes_pagination_hover_bg_color' => '',
								'votes_pagination_hover_font_color' => '',
								'login_tab_active_bg_color' => '#EDEDED',
								'login_tab_hover_bg_color' => '#EDEDED',
								'login_tab_font_color' => '#CCC',
								'login_tab_active_font_color' => '#FF8901',
								'login_tab_hover_font_color' => '#FF8901',
								'login_body_background_color' => '#EDEDED',
								'login_button_background_color' => '#FF8901',
								'login_button_hover_bg_color' => '#787878',
								'login_button_font_color' => '#FFFFFF',
								'login_button_hover_font_color' => '#FFFFFF',
								'popup_body_text_color' => '#000000',
								'vote_navbar_button_background' => '',
								'vote_navbar_active_button_background' => '#21416c',
								'vote_navbar_mobile_font'	=> '#FFFFFF'
								);
				update_option(OW_VOTES_COLORSETTINGS,$color_settings);
				update_option(OW_VOTES_ACTIVE_THEME,OW_DEF_THEME);
				
				$p= Ow_Vote_Common_Settings_Controller::ow_voting_format_css($color_settings[OW_DEF_THEME]);
				$a = fopen(OW_ASSETS_COLORCSS_PATH, 'w');
				fwrite($a, $p);
				fclose($a);
				chmod(OW_ASSETS_COLORCSS_PATH, 0777);					
				}

			 
		}
}else
die("<h2>".__('Failed to load Voting activation initial','voting-contest')."</h2>");

if(!function_exists('ow_votes_uninstall')){
		function ow_votes_uninstall(){
				global $wpdb;				
				Ow_Installation_Model::ow_voting_delete_tables();				
				$mycustomposts = get_posts(array('post_type' => OW_VOTES_TYPE, 'numberposts' => -1, 'post_status' => 'any'));
				if (count($mycustomposts) > 0) {
					foreach ($mycustomposts as $mypost) {
						wp_delete_post($mypost->ID, true);
					}
				}
				$taxonomy = OW_VOTES_TAXONOMY;
				
				$terms = get_terms($taxonomy, array('hide_empty' => false));
				$count = count($terms);
				if ($count > 0) {
					foreach ($terms as $term) {
						wp_delete_term($term->term_id, $taxonomy);
						delete_option($term->term_id . '_' . OW_VOTES_TAXACTIVATIONLIMIT);
						delete_option($term->term_id . '_' . OW_VOTES_TAXSTARTTIME);
						delete_option($term->term_id . '_' . OW_VOTES_TAXEXPIRATIONFIELD);
						delete_option($term->term_id . '_' . OW_VOTES_SETTINGS);
					}
				}
				delete_option(OW_VOTES_SETTINGS);
				delete_option(OW_VOTES_GENERALSTARTTIME);
				delete_option(OW_VOTES_GENERALEXPIRATIONFIELD);
		}
}else
die("<h2>".__('Failed to load Voting uninstall initial','voting-contest')."</h2>");

if(!function_exists('ow_votes_deactivation_init')){				
		function ow_votes_deactivation_init() {
				$option = get_option(OW_VOTES_SETTINGS);
				if (!$option['deactivation']) {
					ow_votes_uninstall();
				}
		}
}else
die("<h2>".__('Failed to load Voting Deactivation initial','voting-contest')."</h2>");
