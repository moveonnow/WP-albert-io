<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Save_Controller')){
	class Ow_Vote_Save_Controller{
	    
		public function __construct(){
			add_action('wp_ajax_ow_save_votes', array($this,'ow_contest_save_votes_for_post'));
			add_action('wp_ajax_nopriv_ow_save_votes', array($this,'ow_contest_save_votes_for_post'));
		}
		
		public function ow_contest_save_votes_for_post(){			
			global $wpdb;
			if($_SERVER[ 'HTTP_X_REQUESTED_WITH' ]=='XMLHttpRequest'){
				$pid = $_GET['pid'];
				$termid = $_GET['termid'];
				
				$termid = Ow_Vote_Common_Controller::ow_voting_decrypt($termid);
				$pid = Ow_Vote_Common_Controller::ow_voting_decrypt($pid);
				
				//Adding the code for the WP_ID
				$option = get_option(OW_VOTES_SETTINGS);
				$useragent = Ow_Vote_Save_Controller::ow_cookie_voting_getBrowser(); 
				if($option['onlyloggedinuser'] == 'on'){
					if($option['vote_tracking_method'] == 'cookie_traced'){
					   $voter_cookie = $useragent['name'].'@'.$termid.'@'.$pid;              
					   $ip = $voter_cookie;
					}
					else
					{
					$user_id = get_current_user_id();
					$ip = $user_id;
					}
				}
				else{
				   //Check Cookie Trace Here 
				   if($option['vote_tracking_method'] == 'cookie_traced'){
					   $freq = $option['frequency'];
					   
					   $voter_cookie = $useragent['name'].'@'.$_GET['termid'].'@'.$pid;              
					   $ip = $voter_cookie;               
				   }
				   //Check Email Verification Code Here 
				   else if($option['vote_tracking_method'] == 'email_verify'){
					if(is_user_logged_in() && $global_options['onlyloggedinuser'] == 'on'){
						$current_user = wp_get_current_user();
						$ip = $current_user->user_email;
					}
					else{
						if(isset($_SESSION['votes_current_email']) && isset($_SESSION['votes_random_string']))
						    $ip = $_SESSION['votes_current_email'];
						else
						    $ip = "";
						
					}
					if(isset($_SESSION['verified_votes_random_string']))
						unset($_SESSION['verified_votes_random_string']);
				   }
				   else{ 
					   if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') {
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
						} else {
						$ip = $_SERVER['REMOTE_ADDR'];
					   }
				   }
				   if(is_user_logged_in()){
						$user_id = get_current_user_id();
						$ip = $user_id; 
				   }
				}
				
				$paged = isset($_GET['paged']) ? $_GET['paged'] : 0;
				$args = '';
				$args .= 'id = '.$termid;
				if($paged > 0){
					$args .= ' paged = '.$paged;
				}
				$args .= ' ajaxcontent = 1';
				$result = array();
				$result['button_flag'] = 0; 
				$where = '';
				$freq = $option['frequency'];
				
				
				$is_votable = Ow_Vote_Save_Controller::check_contestant_is_votable($pid, $ip, $termid);
				
				// W3c Total cache Plugin
				if(function_exists('w3tc_pgcache_flush')){                
					$votes_page_id = $_GET['votes_page_id']; 
					w3tc_pgcache_flush();   
					w3tc_flush_all();					
					w3tc_pgcache_flush_post($votes_page_id);
				}
				
				//Wp Super Cache Plugin
				if ( function_exists('wp_cache_clear_cache') ) {
					wp_cache_clear_cache();
				}
				if( function_exists('wp_cache_post_change')){
					$votes_page_id = $_GET['votes_page_id']; 
					wp_cache_post_change( $votes_page_id );
				}

				if(!Ow_Vote_Common_Controller::ow_vote_is_contest_started($termid)) {
					$result['success'] = 0;
					$result['msg'] = __("Restricted",'voting-contest');
					$result['msg_html'] = "<div class='owt_danger'><i class='ow_vote_icons voteconestant-warning'></i>".$option['vote_tobestarteddesc']."</div>";
				}
				else if(Ow_Vote_Common_Controller::ow_vote_is_contest_reachedend($termid)) {
					$result['success'] = 0;
					$result['msg'] = __("Restricted",'voting-contest');
					$result['msg_html'] = "<div class='owt_danger'><i class='ow_vote_icons voteconestant-warning'></i>".$option['vote_reachedenddesc']."</div>";
				}
				else if(get_post_status( $pid ) != 'publish' ) {
					$result['success'] = 0;
					$result['msg'] = __('Not Available','voting-contest');
					$result['msg_html'] = "<div class='owt_danger'><i class='ow_vote_icons voteconestant-warning'></i>".__('Warning! Contestants not Available ','voting-contest')."</div>";
				}
				else if(!$is_votable){       
					if($option['vote_votingtype']!='' && $freq == 11){
						$result['success'] = 0;
						$result['msg'] = __("Restricted",'voting-contest');
						$result['msg_html'] = "<div class='owt_warning'><i class='ow_vote_icons voteconestant-warning'></i>".__('Warning! Vote Limit Reached ','voting-contest')."</div>";
					}
					else if($option['vote_votingtype']=='' && $freq == 11) {
						$result['success'] = 0;
						$result['msg'] = __("Not Allowed ",'voting-contest');
						$result['msg_html'] = "<div class='owt_warning'><i class='ow_vote_icons voteconestant-warning'></i>".__('Warning! Multiple Votes Not Allowed For Same Contestant ','voting-contest')."</div>";
					}
					else{
						$result['success'] = 0;
						$result['msg'] = __('Already Voted','voting-contest');
						$result['msg_html'] = "<div class='owt_warning'><i class='ow_vote_icons voteconestant-warning'></i>".__('Warning! You have already registered your vote ','voting-contest')."</div>";
					}
				}
				else{
					$cur_vote = get_post_meta($pid, OW_VOTES_CUSTOMFIELD);
					if($option['vote_tracking_method'] == 'cookie_traced')
					{
						$current_time = $_GET['current_time'];   
						$current_time = explode(" ",$_GET['current_time']);       
						$hrs = explode(":",$current_time[4]);
						$timestamptime = mktime($hrs[0],$hrs[1],$hrs[2],date('n', strtotime($current_time[2])),$current_time[1],$current_time[3] );
					
						if($useragent['name'] == "GC"){
							$gmt_offset = $_GET['gmt_offset']/60; 
							if($gmt_offset < 0){ 
								 if(!is_int($gmt_offset_hr)){
									 $gmt_offset_hr = (int)$gmt_offset;                       
									 $timestamptime = strtotime("-".$gmt_offset_hr." hours 30 minutes", $timestamptime);
								 }
								 else{
									 $gmt_offset_hr = (int)$gmt_offset;                       
									 $timestamptime = strtotime("-".$gmt_offset_hr." hours", $timestamptime);
								 }
							}
							else{
								 if(!is_int($gmt_offset_hr)){
									 $gmt_offset_hr = (int)$gmt_offset;                        
									 $timestamptime = strtotime("+".$gmt_offset_hr." hours 30 minutes", $timestamptime);
								 }
								 else{
									 $gmt_offset_hr = (int)$gmt_offset;                       
									 $timestamptime = strtotime("+".$gmt_offset_hr." hours", $timestamptime);
								 }
							}                   
						}
						$total_cook_size = "";
						foreach($_COOKIE as $key => $cook):                  
							 if (isset($_COOKIE[$key])) {                            
								   $data = $_COOKIE[$key];
								   $serialized_data = serialize($data);
								   $size = strlen($serialized_data);                     
								   $total_cook_size += $size ;                          
							  }
						endforeach;
						
						if($total_cook_size > 4000){
							$result['success'] = 0;
							$result['msg'] = __('Vote Limit Exceeded','voting-contest');
							header('content-type: application/json; charset=utf-8');
							echo $_GET['callback'] . '(' . json_encode($result) . ')';
							die();
						}
						Ow_Vote_Save_Controller::ow_votes_set_cookies($timestamptime,$option,$pid,$termid,$voter_cookie);
					}
					$vote_count = $_GET['votes_count'];
			
					//Adding ip_always in the WP_VOTES_TABLE
					if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') {
						$ip_always = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else {
						$ip_always = $_SERVER['REMOTE_ADDR'];
					}
					
					//Adding the Email Grab in WP_VOTES_TABLE					
					if($option['vote_grab_email_address'] == "on"){
						if($option['vote_tracking_method'] != 'email_verify'){
							$email_always = $_SESSION['ow_voting_email_grab'];
						}
						else{
							//in email_verify $ip is the email address
							$email_always = $ip;
						}
					}
					else{
						if($option['vote_tracking_method'] == 'email_verify'){
							//in email_verify $ip is the email address
							if(is_user_logged_in()){
								$current_user = wp_get_current_user();	
								$email_always = $current_user->user_email;
							}
							else{
								$email_always = $ip;
							}
						}
						else{
							$email_always = 0;
						}
					}
					
					Ow_Votes_Save_Model::ow_update_vote_contestant($ip,$vote_count,$pid,$termid,$ip_always,$email_always);
					$total_v = Ow_Votes_Save_Model::ow_get_total_vote_count($pid);
					$result['button_flag'] = 2;
					$result['frequency'] = $freq;
					$result['success'] = 1;
					$result['msg'] = __('Voted Successfully ','voting-contest');
					$result['msg_html'] = "<div class='owt_success'><i class='ow_vote_icons voteconestant-success'></i>".__('You\'ve Voted!','voting-contest')."</div>";
					$result['votes'] = $total_v;
					$result['args'] = $args;
					
					if($option['vote_votingtype']!='' && $freq == 11){
						 $result['button_flag'] = 1; 
						 $result['tax_id'] = $termid;    
					}
					if($option['vote_votingtype']=='' && $freq == 11) {
						$result['button_flag'] = 2;
					}
				}
				header('content-type: application/json; charset=utf-8');
				echo $_GET['callback'] . '(' . json_encode($result) . ')';
				die();
			}
			else{
				wp_redirect( home_url() );
				die();	
			}
		}
		
		public static function ow_votes_set_cookies($timestamptime,$option,$pid,$termid,$voter_cookie,$cook_name=""){        
			$freq = $option['frequency'];
			
			if($freq == 12 || $freq == 24 || $freq == 2 || $freq != 0){
				$vote_frequency_hours = $option['vote_frequency_hours'];
				$cookie_time = $timestamptime+3600*$vote_frequency_hours*1;
				$cookie_name = ($option['vote_votingtype'] == 0)?'voter_cook'.$termid:'voter_term'.$termid.'_'.$pid;
			}
			if($freq == 1){
				 $cookie_time = strtotime('tomorrow',$timestamptime)- 1;
				 $cookie_name = ($option['vote_votingtype'] == 0)?'voter_cook'.$termid:'voter_term'.$termid.'_'.$pid;
			}  
			if($freq == 11){
				 //Changed the Name for the Votes Per category
				 $cookie_time = $timestamptime+3600*24*365;
				 $cookie_name = ($option['vote_votingtype'] == 0)?'voter_term_perm'.$termid:'voter_term_perm'.$termid.'_'.$pid;
			}             
			if($freq != 0){
				 if($cook_name != "")
					$cookie_name = $cook_name;       
				
				$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
				if($voter_cookie_count == null)
					$voter_cookie_count = 0 ; 
			   
				 //Make it as HTTP Cookie to prevent from XSS
				 setrawcookie($cookie_name, $voter_cookie,$cookie_time, COOKIEPATH, COOKIE_DOMAIN, false,true);
				 setrawcookie('voting_cook_counter_'.$termid, ($voter_cookie_count + 1),$cookie_time, COOKIEPATH, COOKIE_DOMAIN, false,true);
			}
		}
	
		public static function is_current_user_voted($pid, $ip, $termid){        
			$option = get_option(OW_VOTES_SETTINGS);
			$freq = $option['frequency']; 
			if($option['vote_tracking_method'] == 'cookie_traced' && $option['vote_votingtype'] != ''){
				$ua = Ow_Vote_Save_Controller::ow_cookie_voting_getBrowser();               
				$voter_cookie = $ua['name'].'@'.$termid.'@'.$pid;          
				if($_COOKIE['voter_term_perm'.$termid] == $voter_cookie){
					return true;
				}  
				else{
					return false;
				}
			}
			if($freq == 11 && $option['vote_votingtype']!='') {
				$where .= 'AND `termid` = '.$termid.' AND `post_id` = ' . $pid;
			}
			$voted = Ow_Votes_Save_Model::ow_check_is_votable($ip,$where);
			
			if(count($voted)){
				return true;
			}
			return false;
		}
		
		public static function is_current_user_voted_count($pid, $ip, $termid){        
			$option = get_option(OW_VOTES_SETTINGS);
			$freq = $option['frequency']; 
			if($freq == 11 && $option['vote_votingtype']!='') {
				$where .= 'AND `termid` = '.$termid;
			}
			$voted = Ow_Votes_Save_Model::ow_check_is_votable($ip,$where);
			
			return count($voted);
		}
		
		
		public static function is_current_user_voted_post_id($pid, $ip, $termid){        
			$option = get_option(OW_VOTES_SETTINGS);
			$freq = $option['frequency']; 
			if($option['vote_tracking_method'] == 'cookie_traced' && $option['vote_votingtype'] != ''){
				$ua = Ow_Vote_Save_Controller::ow_cookie_voting_getBrowser();               
				$voter_cookie = $ua['name'].'@'.$termid.'@'.$pid;          
				if($_COOKIE['voter_term_perm'.$termid] == $voter_cookie){
					return true;
				}  
				else{
					return false;
				}
			}
			
			$where .= 'AND `termid` = '.$termid.' AND `post_id` = ' . $pid;			
			
			$voted = Ow_Votes_Save_Model::ow_check_is_votable($ip,$where);
			
			if(count($voted)){
				return true;
			}
			return false;
		}
		
		public static function check_contestant_is_votable($pid, $ip, $termid){       
			$option = get_option(OW_VOTES_SETTINGS);
			$freq = $option['frequency'];
			$vote_frequency_count = $option['vote_frequency_count'];
			$where = '';
			
			if($option['vote_tracking_method'] == 'cookie_traced'){
				
				if($freq == 0){    		
					return true;
				}              
				$ua = Ow_Vote_Save_Controller::ow_cookie_voting_getBrowser();  
				$voter_cookie = $ua['name'].'@'.$termid.'@'.$pid;			
				
				if($freq == 11){
					if($option['vote_votingtype'] == 0){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($voter_cookie_count == $vote_frequency_count)
							return false;
						
						$cookie_id = $_COOKIE['voter_term_perm'.$termid];
												
						if($cookie_id != null){
							$first_voted_post_id = explode('@',$cookie_id);
							$first_voted_post_id = $first_voted_post_id[2];
							if($first_voted_post_id != $pid){
								return false;
							}
						}
						
					}
					else if($option['vote_votingtype'] == 1){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($vote_frequency_count == 1)
						{
						
						        if($_COOKIE['voter_term_perm'.$termid.'_'.$pid] != null){
							return false;
						}	
						}
						else
						{
						        if($voter_cookie_count == $vote_frequency_count )
							return false;
						
						        if($_COOKIE['voter_term_perm'.$termid.'_'.$pid] != null){
							return false;
						}
						}
					}
					else{
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						$cookie_id = $_COOKIE['voter_term_perm'.$termid.'_'.$pid];
						if($vote_frequency_count == 1)
						{
							
						        if($_COOKIE['voter_term_perm'.$termid.'_'.$pid] != $cookie_id)
							return false;  
						}
						else
						{
						        if($voter_cookie_count == $vote_frequency_count)
							return false;
						     
						        if($_COOKIE['voter_term_perm'.$termid.'_'.$pid] != $cookie_id)
							return false;
						}
					}                
				}
				else if($freq == 1){
					//Multiple Exclusive
					if($option['vote_votingtype'] == 1){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($vote_frequency_count == 1)
						{
							
						        if($_COOKIE['voter_term'.$termid.'_'.$pid] != null){
							return false;
						         }
						}
						else
						{
						        if($voter_cookie_count == $vote_frequency_count )
							return false;
						
						        if($_COOKIE['voter_term'.$termid.'_'.$pid] != null){
							return false;
						         }
						}
					}
					//Multiple Split
					if($option['vote_votingtype'] == 2){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($vote_frequency_count == 1)
						{
					
						        if($_COOKIE['voter_term'.$termid.'_'.$pid] != null){
							return false;
						         }
						}
						else
						{
						        if($voter_cookie_count == $vote_frequency_count)
							return false;
						        if($_COOKIE['voter_cook'.$termid] == $voter_cookie && $voter_cookie_count == $vote_frequency_count)
							return false;
						}
					}
					//Single
					if($option['vote_votingtype'] == 0){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($voter_cookie_count == $vote_frequency_count)
							return false;
						
						$cookie_id = $_COOKIE['voter_cook'.$termid];
												
						if($cookie_id != null){
							$first_voted_post_id = explode('@',$cookie_id);
							$first_voted_post_id = $first_voted_post_id[2];
							if($first_voted_post_id != $pid){
								return false;
							}
						}
						
					}
						
					
				}
				
				else {
					if($option['vote_votingtype'] == 0){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						
						if($_COOKIE['voter_cook'.$termid] != null){
							$first_voted_post_id = explode('@',$_COOKIE['voter_cook'.$termid]);
							$first_voted_post_id = $first_voted_post_id[2];
							//Check the First Added Cookie Based upon the post id
							if($first_voted_post_id != $pid){
								return false;
							}
						}
						
						if($_COOKIE['voter_cook'.$termid] != null && $voter_cookie_count == $vote_frequency_count)
							return false;
					}
					else if($option['vote_votingtype'] == 1){
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($vote_frequency_count == 1)
						{
						    
						        if($_COOKIE['voter_term'.$termid.'_'.$pid] != null){
							return false;
						}	
						}
						else
						{
						        if($voter_cookie_count == $vote_frequency_count )
							return false;
						
						        if($_COOKIE['voter_term'.$termid.'_'.$pid] != null){
							return false;
						}
						}
					}
					else{
						$voter_cookie_count = $_COOKIE['voting_cook_counter_'.$termid];
						if($vote_frequency_count == 1)
						{
							
						         if($_COOKIE['voter_term'.$termid.'_'.$pid] != null){
							return false;
							 }
						}
						else
						{
						        if($voter_cookie_count == $vote_frequency_count)
							return false;
						        if($_COOKIE['voter_cook'.$termid] == $voter_cookie && $voter_cookie_count == $vote_frequency_count)
							return false;
						}
					}
				}            
				return true;
				   
			}
			else{
				
				if($freq == 1){
					// Once per Calendar day
					$days = 1;
					$where .= 'AND (SELECT DATEDIFF("'.date("Y-m-d",current_time( 'timestamp', 0 )).'", `date`) < '.$days.' )';
				}
				else if($freq == 12 || $freq == 24 || $freq == 2 || $freq != 0){
					if($freq != 11){
						if($option['vote_frequency_hours'] != null){
							$time_in_secs = $option['vote_frequency_hours'] * 60 * 60 ;
						}
						else{
							$time_in_secs = $freq * 60 * 60 ;
						}
						// Once in 12 hrs
						$where .= 'AND (SELECT TIME_TO_SEC(TIMEDIFF("'.date("Y-m-d H:i:s",current_time( 'timestamp', 0 )).'", `date`))<='.$time_in_secs.')';
					}
				} 
				
							
				switch($freq){				
				
					case 11:					
						$where .= ' AND `termid` = '.$termid;						
						break;
					
				}
				$where .= ' AND `termid` = '.$termid;
				
				$voted = Ow_Votes_Save_Model::ow_check_is_votable($ip,$where);				
				
				
				switch($freq){
					
					case 0:
						if(count($voted) && $option['vote_votingtype'] == 0){
							$first_voted_post_id = $voted[0]->post_id;					
							if($pid !== $first_voted_post_id)
								return false;
						}
						break;
					
					case 1:
						if($option['vote_frequency_count'] == 1 && $option['vote_votingtype'] == 0){
							if(count($voted) >= 1){
								return false;
							}
						}
						if(count($voted) && $option['vote_votingtype'] == 0){
							$first_voted_post_id = $voted[0]->post_id;					
							if($pid !== $first_voted_post_id)
								return false;
						}
						if(count($voted) && $option['vote_votingtype'] == 1){
							foreach($voted as $post_id){
								$first_voted_post_id[] = $post_id->post_id;
							}											
							if(in_array($pid,$first_voted_post_id))
								return false;							
						}
						if($option['vote_frequency_count'] == 1){
							if(count($voted) && $option['vote_votingtype'] == 2){
							foreach($voted as $post_id){
								$first_voted_post_id[] = $post_id->post_id;
							}											
							if(in_array($pid,$first_voted_post_id))
								return false;							
							}
						}
						break;
					case 2:
						
						if($option['vote_frequency_count'] == 1 && $option['vote_votingtype'] == 0){
							if(count($voted) >= 1){
								return false;
							}
						}
						if(count($voted) && $option['vote_votingtype'] == 0){
							$first_voted_post_id = $voted[0]->post_id;					
							if($pid !== $first_voted_post_id)
								return false;
						}
						if(count($voted) && $option['vote_votingtype'] == 1){
							foreach($voted as $post_id){
								$first_voted_post_id[] = $post_id->post_id;
							}											
							if(in_array($pid,$first_voted_post_id))
								return false;							
						}
						if($option['vote_frequency_count'] == 1){
							if(count($voted) && $option['vote_votingtype'] == 2){
							foreach($voted as $post_id){
								$first_voted_post_id[] = $post_id->post_id;
							}											
							if(in_array($pid,$first_voted_post_id))
								return false;							
							}
						}
						
						break;
					case 11:
						if($option['vote_frequency_count'] == 1 && $option['vote_votingtype'] == 0){
							if(count($voted) >= 1){
								return false;
							}
						}
						if(count($voted) && $option['vote_votingtype'] == 0){
							$first_voted_post_id = $voted[0]->post_id;					
							if($pid !== $first_voted_post_id)
								return false;
						}
						if(count($voted) && $option['vote_votingtype'] == 1){
							foreach($voted as $post_id){
								$first_voted_post_id[] = $post_id->post_id;
							}											
							if(in_array($pid,$first_voted_post_id))
								return false;							
						}												
						break;
				}
				
				if($option['vote_frequency_count'] != 1){					
					if((count($voted) >= $option['vote_frequency_count']) && $freq !=0){
						return false;
					}
				}
				
				return true;
			}
		}
		
		public static function ow_cookie_voting_getBrowser() 
		{ 
			$u_agent = $_SERVER['HTTP_USER_AGENT']; 
			$bname = 'Unknown';
			$platform = 'Unknown';
			$version= "";
		
			//First get the platform?
			if (preg_match('/linux/i', $u_agent)) {
				$platform = 'linux';
			}
			elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
				$platform = 'mac';
			}
			elseif (preg_match('/windows|win32/i', $u_agent)) {
				$platform = 'windows';
			}
			
			// Next get the name of the useragent yes seperately and for good reason
			if((preg_match('/MSIE/i',$u_agent) || preg_match('/Trident/i',$u_agent)) && !preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'IE'; 
				$ub = "MSIE"; 
			} 
			elseif(preg_match('/Firefox/i',$u_agent)) 
			{ 
				$bname = 'MF'; 
				$ub = "Firefox"; 
			} 
			elseif(preg_match('/Chrome/i',$u_agent)) 
			{ 
				$bname = 'GC'; 
				$ub = "Chrome"; 
			} 
			elseif(preg_match('/Safari/i',$u_agent)) 
			{ 
				$bname = 'AS'; 
				$ub = "Safari"; 
			} 
			elseif(preg_match('/Opera/i',$u_agent)) 
			{ 
				$bname = 'O'; 
				$ub = "Opera"; 
			} 
			elseif(preg_match('/Netscape/i',$u_agent)) 
			{ 
				$bname = 'N'; 
				$ub = "Netscape"; 
			} 
			
			// finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			if (!preg_match_all($pattern, $u_agent, $matches)) {
				// we have no matching number just continue
			}
			
			// see how many we have
			$i = count($matches['browser']);
			if ($i != 1) {
				//we will have two since we are not using 'other' argument yet
				//see if version is before or after the name
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
					$version= $matches['version'][0];
				}
				else {
					$version= $matches['version'][1];
				}
			}
			else {
				$version= $matches['version'][0];
			}
			
			// check if we have a number
			if ($version==null || $version=="") {$version="?";}
			
			return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
			);
		} 
		
	}
}else
die("<h2>".__('Failed to load the Voting Save Controller','voting-contest')."</h2>");

return new Ow_Vote_Save_Controller();
