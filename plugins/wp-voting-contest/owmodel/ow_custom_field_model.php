<?php
if(!class_exists('Ow_Custom_Field_Model')){
	class Ow_Custom_Field_Model {
	    
		public static function ow_insert_contestant_custom_field($input_data){
			global $wpdb;
			$insert = $wpdb->query("INSERT INTO ".OW_VOTES_ENTRY_CUSTOM_TABLE." (question_type, question, system_name, response, required, admin_only,required_text, sequence,wp_user,admin_view,pretty_view,ow_file_size,grid_only,list_only,show_labels)"
					 . " VALUES ('" . $input_data['custfield_type'] . "', '" . $input_data['custfield'] . "', '" . $input_data['system_name'] . "', '"  . $input_data['custfield_values'] . "', '" . $input_data['required'] . "', '" . $input_data['admin_only'] . "', '" . $input_data['required_text'] . "', '" . $input_data['sequence'] . "','".$input_data['current_id']. "','".$input_data['admin_view']."','".$input_data['pretty_view']."','".$input_data['ow_file_size']."','".$input_data['grid_only']."','".$input_data['list_only']."','".$input_data['show_labels']."')");
			return $insert;
		}
		
		public static function ow_update_contestant_custom_field($custom_fields_id,$input_data){
			global $wpdb;
			$update = $wpdb->query("UPDATE " . OW_VOTES_ENTRY_CUSTOM_TABLE . " SET question_type = '" . $input_data['custfield_type'] . "', question = '" . $input_data['custfield'] . "', response = '" . $input_data['custfield_values'] . "', required = '" . $input_data['required'] . "',admin_only = '" . $input_data['admin_only'] . "', required_text = '" . $input_data['required_text'] . "',pretty_view = '" . $input_data['pretty_view']  . "', ow_file_size = '" . $input_data['ow_file_size'] . "', sequence = '" . $input_data['sequence'] . "',admin_view = '" . $input_data['admin_view'] . "',grid_only = '" . $input_data['grid_only'] . "',list_only = '" . $input_data['list_only'] . "',show_labels = '" . $input_data['show_labels'] . "' WHERE id = '" . $custom_fields_id . "'");
			return $update;
		}
		
		public static function ow_get_contestant_custom_field_by_id($custom_fields_id)
		{
			global $wpdb;
			$sql = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE id = '" . $custom_fields_id . "'";
			$custom_fields = $wpdb->get_row($sql);
			return $custom_fields;
		}
		
		public static function ow_check_delete_custom_field_by_id($custom_fields_id){
			global $wpdb;
			$sql = " SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE ." WHERE id = '" .$custom_fields_id."' AND system_name != 'contestant-desc' AND system_name != 'contestant-title'";
			$rs = $wpdb->get_results( $sql );
			return $rs;
		}
		
		public static function ow_delete_contestant_custom_field($custom_fields_id)
		{
			global $wpdb;
			$delete_val = strtotime("now");
			$wpdb->query("UPDATE " . OW_VOTES_ENTRY_CUSTOM_TABLE . " SET delete_time = '" . $delete_val . "' WHERE id = '" .$custom_fields_id. "'");
		}
		
		public static function ow_custom_field_update_sequence($i,$row_id){
			global $wpdb;
			$wpdb->query("UPDATE " . OW_VOTES_ENTRY_CUSTOM_TABLE . " SET sequence=" . $i . " WHERE id='" .$row_id. "'");
		}
		
		/*********** User functions **********/
		
		public static function ow_voting_user_get_all_custom_fields(){
			global $wpdb;
			$sql = "SELECT * FROM " .OW_VOTES_USER_CUSTOM_TABLE." WHERE delete_time = 0 order by sequence";
			$question = $wpdb->get_results($sql);
			return $question;
	    }
		
		public static function ow_insert_user_custom_field($input_data){
			global $wpdb;
			
			$insert = $wpdb->query("INSERT INTO ".OW_VOTES_USER_CUSTOM_TABLE." (question_type, question, system_name, response, required, admin_only,required_text, sequence,wp_user)"
					 . " VALUES ('" . $input_data['custfield_type'] . "', '" . $input_data['custfield'] . "', '" . $input_data['system_name'] . "', '"  . $input_data['custfield_values'] . "', '" . $input_data['required'] . "', '" . $input_data['admin_only'] . "', '" . $input_data['required_text'] . "', '" . $input_data['sequence'] . "','".$input_data['current_id']. "')");
			return $insert;
		}
		
		public static function ow_get_user_custom_field_by_id($custom_fields_id)
		{
			global $wpdb;
			$sql = "SELECT * FROM " . OW_VOTES_USER_CUSTOM_TABLE . " WHERE id = '" . $custom_fields_id . "'";
			$custom_fields = $wpdb->get_row($sql);
			return $custom_fields;
		}
		
		public static function ow_update_user_custom_field($custom_fields_id,$input_data){
			global $wpdb;
			$update = $wpdb->query("UPDATE " . OW_VOTES_USER_CUSTOM_TABLE . " SET question_type = '" . $input_data['custfield_type'] . "', question = '" . $input_data['custfield'] . "', response = '" . $input_data['custfield_values'] . "', required = '" . $input_data['required'] . "',admin_only = '" . $input_data['admin_only'] . "', required_text = '" . $input_data['required_text'] ."', sequence = '" . $input_data['sequence'] . "' WHERE id = '" . $custom_fields_id . "'");
			return $update;
		}
		
		public static function ow_check_delete_user_custom_field_by_id($custom_fields_id){
			global $wpdb;
			$sql = " SELECT * FROM " . OW_VOTES_USER_CUSTOM_TABLE ." WHERE id = '" .$custom_fields_id."'";
			$rs = $wpdb->get_results( $sql );
			return $rs;
		}
		
		public static function ow_delete_contestant_user_custom_field($custom_fields_id)
		{
			global $wpdb;
			$delete_val = strtotime("now");
			$wpdb->query("UPDATE " . OW_VOTES_USER_CUSTOM_TABLE . " SET delete_time = '" . $delete_val . "' WHERE id = '" .$custom_fields_id. "'");
		}
		
		public static function ow_custom_field_user_update_sequence($i,$row_id){
			global $wpdb;
			$wpdb->query("UPDATE " . OW_VOTES_USER_CUSTOM_TABLE . " SET sequence=" . $i . " WHERE id='" .$row_id. "'");
		}
		
	}
}else
die("<h2>".__('Failed to load Voting Custom Field model')."</h2>");
?>