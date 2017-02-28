<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Custom_Field_Controller')){
    class Ow_Vote_Custom_Field_Controller{
			
		//Contestant custom fields metabox
		public static function ow_votes_contestant_custom_field_meta_box()
		{
			global $wpdb;	    			
			$custom_field_action = ($_POST['vote_action'])?$_POST['vote_action']:$_GET['vote_action'];
			$custom_fields_id = $_GET['field_id'];
			switch ($custom_field_action) {
				case 'new_customfield':
					Ow_Vote_Custom_Field_Controller::ow_votes_contestant_add_custom_field();
					break;
				
				case 'insert_customfield':
					Ow_Vote_Custom_Field_Controller::ow_votes_contestant_insert_custom_field();
					break;
				
				case 'edit_fields':
					Ow_Vote_Custom_Field_Controller::ow_vote_contestant_custom_field_edit($custom_fields_id);
					break;
				
				case 'edit_customfield':
					Ow_Vote_Custom_Field_Controller::ow_votes_contestant_insert_custom_field($custom_fields_id);
					break;
				
				case 'delete_fields':
					Ow_Vote_Custom_Field_Controller::ow_votes_contestant_delete_custom_field($custom_fields_id);
					break;	
			}
			require_once(OW_VIEW_PATH.'ow_vote_contestant_custom_field_list_view.php');
			$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
			ow_vote_contestant_custom_field_list_view($custom_fields);
		}
			
		public static function ow_votes_contestant_add_custom_field()
		{
			require_once(OW_VIEW_PATH.'ow_vote_contestant_custom_field_crud_view.php');
			ow_voting_add_custom_field_contestant();
			return;
		}
			
		public static function ow_vote_contestant_custom_field_edit($custom_fields_id){
			require_once(OW_VIEW_PATH.'ow_vote_contestant_custom_field_crud_view.php');
			$field_values = Ow_Custom_Field_Model::ow_get_contestant_custom_field_by_id($custom_fields_id);
			ow_voting_add_custom_field_contestant($custom_fields_id,$field_values);
			return;
		}
		
		public static function ow_votes_contestant_insert_custom_field($custom_fields_id=NULL){
			global $wpdb,$current_user; 
			$go_insert = true;
			
			if($_POST['custfield']!='')
				$insert_data['custfield'] = str_replace("'", "&#039", $_POST['custfield']);
			else{
				$go_insert=false;
			}
			$insert_data['custfield_type'] = $_POST['custfield_type'];
			$insert_data['custfield_values'] = empty($_POST['values']) ? NULL : Ow_Vote_Common_Controller::ow_vote_hyphenize_string($_POST['values']);
			if($insert_data['custfield_values'] == NULL){
				$insert_data['custfield_values'] = empty($_POST['file_types']) ? NULL : Ow_Vote_Common_Controller::ow_vote_hyphenize_string($_POST['file_types']);
			}
			$insert_data['ow_file_size'] = !empty($_POST['file_types_size']) ? $_POST['file_types_size']:0;  
			$insert_data['required'] = !empty($_POST['required']) ? $_POST['required']:'N';  
			$insert_data['required_text'] = $_POST['required_text']; 
			$insert_data['admin_only'] = !empty($_POST['admin_only']) ? $_POST['admin_only']:'N';
			$insert_data['show_labels'] = !empty($_POST['show_labels']) ? $_POST['show_labels']:'N';
			$insert_data['grid_only'] = !empty($_POST['grid_only']) ? $_POST['grid_only']:'N';
			$insert_data['list_only'] = !empty($_POST['list_only']) ? $_POST['list_only']:'N';
			$insert_data['sequence'] = $_POST['sequence'] ?  $_POST['sequence']:'0';
			$insert_data['system_name'] = uniqid();
			$insert_data['admin_view'] = $_POST['admin_view']; 
			$insert_data['pretty_view'] = $_POST['pretty_view'];
			$insert_data['current_id']=$current_user->ID;
			
			
			if($go_insert){
				if($custom_fields_id==''){
					$system_name = $insert_data['system_name'];
					$insert_cf = Ow_Custom_Field_Model::ow_insert_contestant_custom_field($insert_data);
					if ($insert_cf){?>
						<div id="message" class="updated fade"><p><strong><?php _e('The Custom Field ','voting-contest'); ?><?php echo htmlentities2($insert_data['custfield']);?> <?php _e('has been added.','voting-contest'); ?></strong></p></div>
					<?php
					}else { ?>
						<div id="message" class="error"><p><strong><?php _e('The Custom Field ','voting-contest'); ?> <?php echo htmlentities2($insert_data['custfield']);?> <?php _e('was not saved.','voting-contest'); ?> <?php $wpdb->print_error(); ?>.</strong></p></div>
					<?php
					}
				}else{
					$system_name = $_POST['system_name'];
					$update_cf = Ow_Custom_Field_Model::ow_update_contestant_custom_field($custom_fields_id,$insert_data);
					if ($update_cf){?>
						<div id="message" class="updated fade"><p><strong><?php _e('The Custom Field ','voting-contest'); ?><?php echo htmlentities2($insert_data['custfield']);?> <?php _e('has been Updated.','voting-contest'); ?></strong></p></div>
					<?php
					}
				}
				
				//Update for DatePicker Field
				if(isset($_POST['datepicker_only']) && $_POST['custfield_type'] == "DATE")
					update_option($system_name,$_POST['datepicker_only']);
						
			}else{
			?>
				<div id="message" class="error"><p><strong><?php _e('Please enter the title for custom field.','voting-contest'); ?>.</strong></p></div>
			<?php   
			}
		}
		
		public static function ow_votes_contestant_delete_custom_field($custom_fields_id){
			if($_POST['delete_question']!=''){
				if (is_array($_POST['checkbox'])){
					while(list($key,$value)=each($_POST['checkbox'])){
						$del_id=$key;          
						$go_delete = false;
						$check_entry = Ow_Custom_Field_Model::ow_check_delete_custom_field_by_id($del_id);
						if( is_array($check_entry) && count($check_entry) > 0 ) {
							$go_delete = true;
						} 
						if ( $go_delete ) {
							Ow_Custom_Field_Model::ow_delete_contestant_custom_field($del_id);
						}
					}
				}
			}
			if(!empty($custom_fields_id)){
				$go_delete = false;
				$check_entry = Ow_Custom_Field_Model::ow_check_delete_custom_field_by_id($custom_fields_id);
				if( is_array($check_entry) && count($check_entry) > 0 ) {
					$go_delete = true;
				} 
				if ( $go_delete ) {
					Ow_Custom_Field_Model::ow_delete_contestant_custom_field($custom_fields_id);
				}
			}
			?>
			<?php if($go_delete){ ?>
				<div id="message" class="updated fade">
				  <p><strong>
					<?php _e('Contenstant Fields have been successfully deleted.','voting-contest');?>
					</strong></p>
				</div>
			<?php
			}
		}
				
		/************* Registration Custom Fields **************/
		
		public static function ow_votes_user_custom_field_meta_box()
		{
			global $wpdb;	    			
			$custom_field_action = ($_POST['useraction'])?$_POST['useraction']:$_GET['useraction'];			
			$custom_fields_id = $_GET['field_id'];
			switch ($custom_field_action) {
				case 'new_customfield':
					Ow_Vote_Custom_Field_Controller::ow_votes_user_add_custom_field();
					break;
				
				case 'insert_customfield':
					Ow_Vote_Custom_Field_Controller::ow_votes_user_insert_custom_field();
					break;
				
				case 'edit_fields':
					Ow_Vote_Custom_Field_Controller::ow_vote_user_custom_field_edit($custom_fields_id);
					break;
				
				case 'edit_customfield':
					Ow_Vote_Custom_Field_Controller::ow_votes_user_insert_custom_field($custom_fields_id);
					break;
				
				case 'delete_fields':
					Ow_Vote_Custom_Field_Controller::ow_votes_user_delete_custom_field($custom_fields_id);
					break;	
			}
			require_once(OW_VIEW_PATH.'ow_vote_user_custom_field_list_view.php');
			$custom_fields = Ow_Custom_Field_Model::ow_voting_user_get_all_custom_fields();
			ow_vote_user_custom_field_list_view($custom_fields);
		}
		
		public static function ow_votes_user_add_custom_field(){
			require_once(OW_VIEW_PATH.'ow_vote_user_custom_field_crud_view.php');
			ow_voting_add_custom_field_user();
			return;
		}
		
		public static function ow_vote_user_custom_field_edit($custom_fields_id){
			require_once(OW_VIEW_PATH.'ow_vote_user_custom_field_crud_view.php');
			$field_values = Ow_Custom_Field_Model::ow_get_user_custom_field_by_id($custom_fields_id);
			ow_voting_add_custom_field_user($custom_fields_id,$field_values);
			return;
		}
		
		public static function ow_votes_user_insert_custom_field($custom_fields_id=NULL){
			global $wpdb,$current_user; 
			$go_insert = true;
			
			if($_POST['custfield']!='')
				$insert_data['custfield'] = str_replace("'", "&#039", $_POST['custfield']);
			else{
				$go_insert=false;
			}
			
			$insert_data['custfield_type'] = $_POST['custfield_type'];
			$insert_data['custfield_values'] = empty($_POST['values']) ? NULL : str_replace("'", "&#039;", $_POST['values']);
			$insert_data['required'] = !empty($_POST['required']) ? $_POST['required']:'N';  
			$insert_data['required_text'] = $_POST['required_text']; 
			$insert_data['admin_only'] = !empty($_POST['admin_only']) ? $_POST['admin_only']:'N';
			$insert_data['sequence'] = $_POST['sequence'] ?  $_POST['sequence']:'0';
			$insert_data['system_name'] = uniqid();
			$insert_data['current_id']=$current_user->ID;
			
			if($go_insert){
				if($custom_fields_id==''){
					$insert_cf = Ow_Custom_Field_Model::ow_insert_user_custom_field($insert_data);
					if ($insert_cf){?>
						<div id="message" class="updated fade"><p><strong><?php _e('The Custom Field ','voting-contest'); ?><?php echo htmlentities2($insert_data['custfield']);?> <?php _e('has been added.','voting-contest'); ?></strong></p></div>
					<?php
					}else { ?>
						<div id="message" class="error"><p><strong><?php _e('The Custom Field ','voting-contest'); ?> <?php echo htmlentities2($insert_data['custfield']);?> <?php _e('was not saved.','voting-contest'); ?> <?php $wpdb->print_error(); ?>.</strong></p></div>
					<?php
					}
				}else{
					$update_cf = Ow_Custom_Field_Model::ow_update_user_custom_field($custom_fields_id,$insert_data);
					if ($update_cf){?>
						<div id="message" class="updated fade"><p><strong><?php _e('The Custom Field ','voting-contest'); ?><?php echo htmlentities2($insert_data['custfield']);?> <?php _e('has been Updated.','voting-contest'); ?></strong></p></div>
					<?php
					}
				}
			}else{
			?>
				<div id="message" class="error"><p><strong><?php _e('Please enter the title for custom field.','voting-contest'); ?>.</strong></p></div>
			<?php   
			}
		}
		
		public static function ow_votes_user_delete_custom_field($custom_fields_id=NULL){
			if($_POST['delete_question']!=''){
				if (is_array($_POST['checkbox'])){
					while(list($key,$value)=each($_POST['checkbox'])){
						$del_id=$key;          
						$go_delete = false;
						$check_entry = Ow_Custom_Field_Model::ow_check_delete_user_custom_field_by_id($del_id);
						if( is_array($check_entry) && count($check_entry) > 0 ) {
							$go_delete = true;
						} 
						if ( $go_delete ) {
							Ow_Custom_Field_Model::ow_delete_contestant_user_custom_field($del_id);
						}
					}
				}
			}
			if(!empty($custom_fields_id)){
				$go_delete = false;
				$check_entry = Ow_Custom_Field_Model::ow_check_delete_user_custom_field_by_id($custom_fields_id);
				if( is_array($check_entry) && count($check_entry) > 0 ) {
					$go_delete = true;
				} 
				if ( $go_delete ) {
					Ow_Custom_Field_Model::ow_delete_contestant_user_custom_field($custom_fields_id);
				}
			}
			?>
			<?php if($go_delete){ ?>
				<div id="message" class="updated fade">
				  <p><strong>
					<?php _e('User Fields have been successfully deleted.','voting-contest');?>
					</strong></p>
				</div>
			<?php
			}
		}
    }
}else
die("<h2>".__('Failed to load Voting Custom Field Controller','voting-contest')."</h2>");

return new Ow_Vote_Custom_Field_Controller();
