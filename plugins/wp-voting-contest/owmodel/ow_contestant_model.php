<?php
if(!class_exists('Ow_Contestant_Model')){
	class Ow_Contestant_Model {
	    
	    public static function ow_get_votes_count_post($post){
			global $wpdb;
			$new_sql = "SELECT SUM(votes)  FROM " . OW_VOTES_TBL ." WHERE post_id =".$post->ID;
			$count_vote = $wpdb->get_var($new_sql);
			return $count_vote;
	    }
	    
	    public static function ow_get_contestant_custom_fields(){
			global $wpdb;
			$sql = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE delete_time = 0 AND system_name != 'contestant-desc' order by sequence";
			$custom_fields = $wpdb->get_results($sql);
			return $custom_fields;
	    }
	    
	    public static function ow_voting_insert_post_entry($cur_id,$val_serialized)
	    {
			global $wpdb;
			$wpdb->query("INSERT INTO " . OW_VOTES_POST_ENTRY_TABLE . " (post_id_map,field_values)". " VALUES ('".$cur_id."', '".$val_serialized. "')");
	    }
		
	    public static function ow_voting_export_contestants($term_id){
			global $wpdb;
			$where_con ='';
			if($term_id!='' && $term_id > 0){
				$where_con .= ' AND tt.term_id='.$term_id;
			}
			
			$sql1 = "SELECT * FROM ".$wpdb->prefix."posts "." as pos 
			   LEFT JOIN ".$wpdb->prefix."term_relationships as relterm ON (pos.ID=relterm.object_id)
			   LEFT JOIN ".$wpdb->prefix."term_taxonomy as tt ON (relterm.term_taxonomy_id = tt.term_taxonomy_id)
			   LEFT JOIN ".OW_VOTES_POST_ENTRY_TABLE." as votepost ON (pos.ID=votepost.post_id_map)
			   WHERE pos.post_type = '".OW_VOTES_TYPE."' AND pos.post_status!='auto-draft' AND pos.post_status!='trash' ".$where_con." Group by pos.ID";
			   
			$post_entries = $wpdb->get_results($sql1);
			
			return $post_entries;
	    }
		
		public static function ow_voting_get_all_terms(){
			$terms = get_terms(OW_VOTES_TAXONOMY, array('hide_empty' => false));
			return $terms;
	    }
	    
	    public static function ow_voting_get_all_custom_fields(){
			global $wpdb;
			$sql = "SELECT * FROM " .OW_VOTES_ENTRY_CUSTOM_TABLE." WHERE delete_time = 0 order by sequence";
			$custom_fields = $wpdb->get_results($sql);
			return $custom_fields;
	    }
	    
		public static function ow_voting_get_all_custom_entries($post_id){
			global $wpdb;
			$sql1 = "SELECT * FROM " . OW_VOTES_POST_ENTRY_TABLE . " WHERE post_id_map  = '".$post_id."'";
			$custom_entries = $wpdb->get_results($sql1);
			return $custom_entries;
		}
		
		public static function ow_voting_contestant_update_field($val_serialized,$post_id){
			global $wpdb;
			$wpdb->query("UPDATE " . OW_VOTES_POST_ENTRY_TABLE . " SET field_values = '" . $val_serialized . "' WHERE post_id_map = '" . $post_id . "'");
		}
		
		public static function ow_voting_contestant_insert_field($val_serialized,$post_id){
			global $wpdb;
			$wpdb->query("INSERT INTO " . OW_VOTES_POST_ENTRY_TABLE . " (post_id_map,field_values)". " VALUES ('".$post_id."', '".$val_serialized. "')"); 
		}
		
		public static function ow_voting_get_contestant_by_id($post_id){
			global $wpdb;
			$postsql = "SELECT ID FROM " .$wpdb->prefix.'posts'. " where post_type = '".OW_VOTES_TYPE."' AND ID=".$post_id;
			$contestant_post = $wpdb->get_results($postsql);
			return $contestant_post;
		}
		
	    public static function ow_voting_get_author_contestant($pos_val){
			global $wpdb;
			return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."users where ID='".$pos_val->post_author."'");
	    }
	    
	    public static function ow_voting_get_contest_name($pos_val){
			global $wpdb;
			return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."terms where term_id='".$pos_val->term_taxonomy_id."'");
	    }
	    
	    public static function ow_voting_get_contest_meta($pos_val){
			global $wpdb;
			return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."postmeta where post_id ='".$pos_val->ID."' AND meta_key ='".OW_VOTES_CUSTOMFIELD."' ");
	    }
	    
	    public static function ow_total_votes_count(){
			global $wpdb;
			$sql = "SELECT id FROM " . OW_VOTES_TBL ;
			$total   =  $wpdb->get_results($sql);
			return $total;
	    }
	    
	    public static function ow_voting_log_entries($log_entries){		
			global $wpdb;
		
			if($log_entries['orderby'] == 'log.post_email'){
				
				if($log_entries['rpp'] != 'all')
					$sql = "SELECT log.*,pst.post_title,pst.post_author FROM " . OW_VOTES_TBL ." as log LEFT JOIN ".$wpdb->prefix."posts as pst on log.post_id=pst.ID ORDER BY log.ip ".$log_entries['order']." LIMIT {$log_entries['startat']}, {$log_entries['rpp']} ";
				else
					$sql = "SELECT log.*,pst.post_title,pst.post_author FROM " . OW_VOTES_TBL ." as log LEFT JOIN ".$wpdb->prefix."posts as pst on log.post_id=pst.ID ORDER BY log.ip ".$log_entries['order'];
					
				//$sql = "SELECT log.*,pst.post_title,pst.post_author FROM " . OW_VOTES_TBL ." as log LEFT JOIN ".$wpdb->prefix."posts as pst on log.post_id=pst.ID ORDER BY log.ip ".$log_entries['order']." LIMIT {$log_entries['startat']}, {$log_entries['rpp']} ";
			}
			else{		
				if($log_entries['rpp'] != 'all')
					$sql = "SELECT log.*,pst.post_title,pst.post_author FROM " . OW_VOTES_TBL ." as log LEFT JOIN ".$wpdb->prefix."posts as pst on log.post_id=pst.ID ORDER BY ".$log_entries['orderby']." ".$log_entries['order']." LIMIT {$log_entries['startat']}, {$log_entries['rpp']} ";
				else
					$sql = "SELECT log.*,pst.post_title,pst.post_author FROM " . OW_VOTES_TBL ." as log LEFT JOIN ".$wpdb->prefix."posts as pst on log.post_id=pst.ID ORDER BY ".$log_entries['orderby']." ".$log_entries['order'];
			}
			$log_entries = $wpdb->get_results($sql);
			return $log_entries;
	    }
		
		public static function ow_vote_contestant_bulk_pending($exploded_ids){
			global $wpdb;
			 //Get the Status Changing Contestants
            $query = "SELECT ID FROM $wpdb->posts WHERE ID IN ({$exploded_ids}) AND post_status = 'pending'";          
            $result_ids = $wpdb->get_results($query,'ARRAY_A');
			return $result_ids;
		}
		
		public static function ow_voting_delete_entries($vote_id,$id){
			global $wpdb;
			$wpdb->delete( OW_VOTES_TBL, array( 'id' => $id ), array( '%d' )  );
			$vote_count = get_post_meta( $vote_id, OW_VOTES_CUSTOMFIELD, true );
			if($vote_count != 0)
				update_post_meta($vote_id, OW_VOTES_CUSTOMFIELD, $vote_count - 1, $vote_count);
		}
		
	        
		public static function ow_voting_delete_post_entry_track($ow_contestant_author,$term_id){
			global $wpdb;
			
			if (!filter_var($ow_contestant_author, FILTER_VALIDATE_IP) === false) {
				$where = ' ip = '.$ow_contestant_author;
			} else {
				$where = ' user_id_map = '.$ow_contestant_author;
			}			
			$save_sql = "UPDATE " . OW_VOTES_POST_ENTRY_TRACK . " SET count_post= count_post -1 WHERE ".$where." and ow_term_id='".$term_id."'";		
			$wpdb->query($save_sql);
		}
		
	}
}else
die("<h2>".__('Failed to load Voting contestant model')."</h2>");
?>
