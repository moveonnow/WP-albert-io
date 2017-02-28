<?php
if(!class_exists('Ow_Votes_Save_Model')){
	class Ow_Votes_Save_Model {
	  
		public static function ow_check_is_votable($ip,$where){
			global $wpdb;
			$vote_sql = 'SELECT * FROM `' . OW_VOTES_TBL . '` WHERE `ip` =  "' . $ip . '" '.$where;
			//echo $vote_sql;
			$voted = $wpdb->get_results($vote_sql);
			return $voted;
		}

		public static function ow_update_vote_contestant($ip,$vote_count,$pid,$termid,$ip_always = null,$email_always = null){
			global $wpdb;
			$catopt = get_option($termid. '_' . OW_VOTES_SETTINGS);
			$vote_count = $catopt['vote_count_per_contest'];		
			
			$save_sql = 'INSERT INTO `' . OW_VOTES_TBL . '` (`ip` , `votes` , `post_id` , `termid` , `date` ,`ip_always`,`email_always` )
					VALUES ( "' . $ip . '", "'.$vote_count.'"  , ' . $pid . ', "'.$termid.'", "'.date("Y-m-d H:i:s",current_time( 'timestamp', 0 )).'","' . $ip_always . '","' . $email_always . '" ) ';
			$wpdb->query($save_sql);  
		}
		
		public static function ow_get_total_vote_count($pid){
			global $wpdb;
			$new_sql = "SELECT SUM(votes)  FROM " . OW_VOTES_TBL ." WHERE post_id =".$pid;
			$total_v =  $wpdb->get_var($new_sql);
			update_post_meta($pid, OW_VOTES_CUSTOMFIELD, $total_v);
			return $total_v;
		}
		
		
	}
	
}else
die("<h2>".__('Failed to load Voting Save model')."</h2>");
?>