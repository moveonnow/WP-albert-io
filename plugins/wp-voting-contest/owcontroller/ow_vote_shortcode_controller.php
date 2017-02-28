<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Shortcode_Controller')){
	class Ow_Vote_Shortcode_Controller{
	    
	    public function __construct(){			
			add_shortcode('showcontestants', array($this,'ow_votes_show_contestants'));
			add_shortcode('endcontestants', array($this,'ow_voting_end_contestants'));
			add_shortcode('upcomingcontestants', array($this,'ow_voting_start_contestants'));
			add_shortcode('topvotecontestants', array($this,'ow_voting_top_vote_contestants'));
			add_shortcode('rulescontestants', array($this,'ow_voting_rules_contestants'));
			add_shortcode('addcontestants', array($this,'ow_voting_add_contestants'));
			add_shortcode('profilescreen', array($this,'ow_voting_show_profilescreen'));	
			add_shortcode('showallcontestants', array($this,'ow_votes_show_all_contestants'));
			add_shortcode('owsearch', array($this,'ow_contestants_search'));
			add_shortcode('addcontest', array($this,'ow_voting_nocategory_form'));	
			
			
			unset($_SESSION['GET_VIEW_SHORTCODE']);
	    }
		
		/***************** Show Contestants **************/
		public function ow_votes_show_contestants($show_cont_args){			
			$vote_opt = get_option(OW_VOTES_SETTINGS);
			$category_options = get_option($show_cont_args['id']. '_' . OW_VOTES_SETTINGS);
			$inter = Ow_Vote_Common_Controller::ow_vote_get_thumbnail_sizes($vote_opt['short_cont_image']);
			$height_tr =explode('--',$inter);
			$width_t =$height_tr[0];
			$height_t = $height_tr[1];

			$height = $height_t ? $height_t : '';
			$width = $width_t ? $width_t : '';
			$title = $vote_opt['title'] ? $vote_opt['title'] : NULL;
			$orderby = $vote_opt['orderby'] ? $vote_opt['orderby'] : 'votes';
			$order = $vote_opt['order'] ? $vote_opt['order'] : 'desc';
			$onlyloggedinuser = $vote_opt['onlyloggedinuser'] ? $vote_opt['onlyloggedinuser'] : FALSE;
						
			$votes_start_time=get_option($show_cont_args['id'] . '_' . OW_VOTES_TAXSTARTTIME);			
			$tax_hide_photos_live = $category_options['tax_hide_photos_live'];
												
			if($tax_hide_photos_live == 'on'){				
				$current_time = current_time( 'timestamp', 0 );
				if(($votes_start_time !='' && strtotime($votes_start_time) > $current_time)){
				    $tax_hide_photos_live = 0;
				    $category_thumb = 0 ;
				}
				else{						
				    $category_thumb = ($category_options['imgdisplay']=='on')?1:0;
				    $tax_hide_photos_live = 1;
				}				
			}			
			else{
				$tax_hide_photos_live = 1;
				$category_thumb = ($category_options['imgdisplay']=='on')?1:0;
			}			
			
			$category_term_disp = ($category_options['termdisplay']=='on')?1:0;		
			
			$sort_by = isset($show_cont_args['orderby'])?0:1;
			$show_cont_args = wp_parse_args($show_cont_args, array(
				'height' => $height,
				'width' => $width,
				'title' => $title,
				'orderby' => $orderby,
				'order' => $order,
				'postperpage' => get_option('posts_per_page'),
				'taxonomy' => OW_VOTES_TAXONOMY,
				'id' => 0,
				'paged' => 0,
				'ajaxcontent' => 0,
				'showtimer' => 1,
				'showform' => -1,
				'forcedisplay' => 1,
				'thumb' => $category_thumb,
				'termdisplay' => $category_term_disp,
				'pagination'=>1,
				'sort_by'=>$sort_by,
				'onlyloggedinuser' => $onlyloggedinuser,
				'tax_hide_photos_live' => $tax_hide_photos_live
			));

			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_showcontest_view.php');
		
			ob_start();
			ow_voting_shortcode_showcontestant_view($show_cont_args,$vote_opt,$category_options);
			return ob_get_clean();
		}
		
		/************ Add contestants  **************/
		public function ow_voting_add_contestants($contest_args){			
			$vote_opt = get_option(OW_VOTES_SETTINGS);
			$category_options = get_option($contest_args['id']. '_' . OW_VOTES_SETTINGS);
			
			$check_status = Ow_Vote_Common_Controller::ow_votes_is_addform_blocked($contest_args['id']);
			$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_addcontest_view.php');
			
			ob_start();
			ow_voting_shortcode_addcontestant_view($contest_args,$vote_opt,$category_options,$check_status,$custom_fields);
			Ow_Vote_Shortcode_Model::ow_voting_insert_contestants($contest_args,$vote_opt,$category_options,$custom_fields,$check_status);
			return ob_get_clean();
		}
		
		/************ Votes Timer Start **************/
		public function ow_voting_start_contestants($contest_args){			
			if(!$contest_args['id']) {
			   return '<div class="ow_votes_error">'.__('Timer Not Available','voting-contest').'</div>';
			}
			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_startcontest_view.php');
			ob_start();
			ow_voting_shortcode_startcontest_view($contest_args);
			return ob_get_clean();
		}
		
		/************ Votes Timer End ****************/
		public function ow_voting_end_contestants($contest_args){			
			if(!$contest_args['id']) {
			   return '<div class="ow_votes_error">'.__('Timer Not Available','voting-contest').'</div>';
			}
			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_endcontest_view.php');
			ob_start();
			ow_voting_shortcode_endcontest_view($contest_args);
			return ob_get_clean();
			
		}
		
		/***************** Top contestants *************/
		public function ow_voting_top_vote_contestants($args){			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_top_contestants_view.php');
			ob_start();
			ow_voting_shortcode_top_contestants_view($args);
			return ob_get_clean();
		}
		
		/*************** Rules Contest ****************/
		public function ow_voting_rules_contestants($args){			
			$out_html='';
			ob_start();
			extract(shortcode_atts( array(
			'id' => NULL,			
			), $args ));
			
			
			$votes_option = get_option($id . '_' . OW_VOTES_SETTINGS);
			if($votes_option['vote_contest_rules']!=''){
				$out_html .= '<div class="ow_vote_cotest_rules">'.wpautop(html_entity_decode($votes_option['vote_contest_rules'])).'</div>';
			}
			ob_end_clean();
			echo $out_html;
			return;
			
		}
		
		/*************** Profile Screen *****************/
		public function ow_voting_show_profilescreen($args){				
			if(isset($_POST['votes_single'])){           
				$delete_post = Ow_Vote_Shortcode_Model::ow_voting_delete_single_contestants($_POST['votes_single']);
				echo $delete_post;
			}
			$vote_opt = get_option(OW_VOTES_SETTINGS);
			$custom_field = Ow_Custom_Field_Model::ow_voting_user_get_all_custom_fields();
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_profile_screen_view.php');
			ob_start();
			ow_voting_profile_contestants_view($args,$vote_opt,$custom_field);
			return ob_get_clean();
		}
		
		/*************** Show All Contestants *******************/
		public function ow_votes_show_all_contestants($show_cont_args){
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_all_showcontest_view.php');
			$vote_opt = get_option(OW_VOTES_SETTINGS);
			ob_start();
			
			$show_cont_args = wp_parse_args($show_cont_args, array(
				'height' => $height,
				'width' => $width,
				'title' => $title,
				'orderby' => $orderby,
				'order' => $order,
				'postperpage' => get_option('posts_per_page'),
				'taxonomy' => OW_VOTES_TAXONOMY,
				'id' => 0,
				'paged' => 0,
				'ajaxcontent' => 0,
				'showtimer' => 1,
				'showform' => -1,
				'forcedisplay' => 1,
				'thumb' => $category_thumb,
				'termdisplay' => $category_term_disp,
				'pagination'=>1,
				'sort_by'=>$sort_by,
				'onlyloggedinuser' => $onlyloggedinuser,
				'tax_hide_photos_live' => $tax_hide_photos_live
			));
			
			ow_voting_shortcode_all_showcontest_view($show_cont_args,$vote_opt);
			return ob_get_clean();
		}
		
		/*************** Search Functionality *******************/
		public function ow_contestants_search($show_cont_args){
			require_once(OW_VIEW_FRONT_PATH.'ow_contestants_search.php');
			$vote_opt = get_option(OW_VOTES_SETTINGS);
			ob_start();
			ow_contestants_search($show_cont_args,$vote_opt);
			return ob_get_clean();
		}
		
		
		/************ Add contests woithout Category ID *********/
		public function ow_voting_nocategory_form($contest_args){			
			$vote_opt = get_option(OW_VOTES_SETTINGS);				
			$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();			
			$terms = Ow_Contestant_Model::ow_voting_get_all_terms();	
			foreach($terms as $term){
				$check_status = Ow_Vote_Common_Controller::ow_votes_is_addform_blocked($term->term_id);				
				if(!$check_status){
					$unblocked_terms[$term->term_id]= $term->name;
				}			
			}		
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_nocategory_view.php');			
			ob_start();
			ow_voting_nocategory_form_view($contest_args,$vote_opt,$custom_fields,$unblocked_terms);
			if(isset($_POST['ow_select_term'])){
				$contest_args['id']= $_POST['ow_select_term'];
				$category_options = get_option($contest_args['id']. '_' . OW_VOTES_SETTINGS);
				Ow_Vote_Shortcode_Model::ow_voting_insert_contestants($contest_args,$vote_opt,$category_options,$custom_fields,false);
			}
			return ob_get_clean();
		}
		
	
		/*************** Total Count *******************/
		public static function ow_votes_total_count_votes($id,$category_options){			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_common_view.php');
			if($category_options['total_vote_count']=='on'){
				ob_start();
				$total_count_query = Ow_Vote_Shortcode_Model::ow_get_total_vote_count_by_term_id($id);
				ow_voting_shortcode_total_count_view($id,$total_count_query);
				return ob_get_clean();
			}
		}
		
		//Show login/ register on front end
		public static function ow_votes_custom_registration_fields_show(){			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_user_register_view.php');
			$votes_settings = get_option(OW_VOTES_SETTINGS);
			$custom_field = Ow_Custom_Field_Model::ow_voting_user_get_all_custom_fields();
			echo '<div class="hide">';
			echo ow_voting_user_login_view($votes_settings,$custom_field);
			//echo ow_voting_user_registration_view($votes_settings,$custom_field);
			echo ow_voting_user_forget_view();
			echo '</div>';
		}
		
		
		//Show Email Verification Form on front end
		public static function ow_votes_custom_email_form(){
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_email_form.php');
			$votes_settings = get_option(OW_VOTES_SETTINGS);			
			echo '<div class="hide">';
			echo ow_voting_email_form($votes_settings);
			echo '</div>';
		}
		

		/* mod_start */

		//Show Rules
		public static function ow_votes_rules(){
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_email_form.php');
			$votes_settings = get_option(OW_VOTES_SETTINGS);			
			echo '<div class="hide">';
			echo ow_voting_email_form($votes_settings);
			echo '</div>';
		}

		/*  mod_end  */


		//Grab Email Form on front end for IP and COOKIE
		public static function ow_voting_email_grab(){
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_email_grab.php');
			$votes_settings = get_option(OW_VOTES_SETTINGS);			
			echo '<div class="hide">';
			echo ow_voting_email_grab($votes_settings);
			echo '</div>';
		}
		
		//On add contestant Mail sent to admin
		public static function ow_votes_add_contestant_mail_function($post_id,$cont_details){
			$option_setting = get_option(OW_VOTES_SETTINGS);
			if($option_setting['vote_notify_mail']=='on'){
				if($option_setting['vote_admin_mail']!='')
					$admin_email = $option_setting['vote_admin_mail'];
				else
					$admin_email = get_settings('admin_email');
					
				if($option_setting['vote_admin_mail_content'] != null)	{
					$email_description = $option_setting['vote_admin_mail_content'];
				}
					
					
				$subject = "New Contestant Entry Is Submitted";
				
				require_once(OW_VIEW_FRONT_PATH.'ow_voting_mail_content_view.php');
				$message = ow_voting_mail_addcontestant_view($option_setting,$post_id,$cont_details,$email_description);
				if($option_setting['vote_from_name']!='')
					$headers[] = 'From: '.$option_setting['vote_from_name'];
					$headers[] = "Content-type: text/html";
					wp_mail($admin_email, $subject,$message ,$headers);	
			}
		}
		
		//On add contestant Mail sent to admin
		public static function ow_votes_verification_mail_function($verificationcode,$senderemail){
			$option_setting = get_option(OW_VOTES_SETTINGS);
			
			if($option_setting['vote_admin_mail']!='')
				$admin_email = $option_setting['vote_admin_mail'];
			else
				$admin_email = get_settings('admin_email');
				
			$subject = __("Email Verification Code","voting-contest");
			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_mail_content_view.php');
			$message = ow_voting_mail_verification_code($verificationcode);
			if($option_setting['vote_from_name']!='')
				$headers[] = 'From: '.$option_setting['vote_from_name'];
				$headers[] = "Content-type: text/html";
				wp_mail($senderemail, $subject,$message ,$headers);	
			
		}

		
		
	}
}else
die("<h2>".__('Failed to load the Voting Shortcode Controller','voting-contest')."</h2>");

return new Ow_Vote_Shortcode_Controller();
