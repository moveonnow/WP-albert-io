<?php
if(!class_exists('Ow_Common_Settings_Model')){
	class Ow_Common_Settings_Model {
	    
		public static function ow_vote_clear_cate($term_id){
			global $wpdb;
			$where = '';
			
			if(isset($term_id) && $term_id > 0){
				   $where .= ' WHERE  `post_id` IN (SELECT DISTINCT `object_id` FROM `'.$wpdb->prefix.'term_relationships` INNER JOIN `'.$wpdb->prefix.'terms` as terms ON `term_id` = '.$term_id.' AND `term_taxonomy_id` = terms.term_id )';
			}
				
			$cnt = 'SELECT  `post_id` , count(*) as cnt FROM ' . OW_VOTES_TBL .$where.' GROUP BY `post_id`';
			$delquery = 'DELETE FROM ' . OW_VOTES_TBL .$where;
			$cntresult = $wpdb->get_results($cnt);
			$result = $wpdb->query($delquery);
		
		    foreach ($cntresult as $indpost) {
			   $exvote = get_post_meta($indpost->post_id, OW_VOTES_CUSTOMFIELD);
			   if ($exvote[0] > $indpost->cnt)
					update_post_meta($indpost->post_id, OW_VOTES_CUSTOMFIELD, ($exvote[0] - $indpost->cnt));
				else
					update_post_meta($indpost->post_id, OW_VOTES_CUSTOMFIELD, 0);
			}
			
			return $cntresult;	
	    }
		
	}
}else
die("<h2>".__('Failed to load Voting Common Settings model')."</h2>");
?>