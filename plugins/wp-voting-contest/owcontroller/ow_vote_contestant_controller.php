<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Contestant_Controller')){
    class Ow_Vote_Contestant_Controller{
	
		public function __construct(){
			//Add the field colums to the contestant
			add_filter('manage_edit-' . OW_VOTES_TYPE . '_columns',array($this,'ow_contestant_post_add_columns'));
			add_filter('manage_edit-' . OW_VOTES_TYPE . '_sortable_columns',array($this,'ow_votes_custom_post_page_sort'), 10, 2);
			//Get the values of the custom added fields
			add_action('manage_' . OW_VOTES_TYPE . '_posts_custom_column', array($this,'ow_custom_new_votes_column'), 10, 2);
			
			//Custom contestant meta boxes
			add_action('add_meta_boxes', array($this,'ow_custom_meta_box_contestant'));
			add_action('save_post', array($this,'ow_custom_meta_box_save_contestant'), 10, 3);
			 //Tab menu on contestant
			add_action( 'wp_after_admin_bar_render', array($this,'ow_contestant_custom_menu_bar'));
			//add_action('plugins_loaded',array($this,'ow_vote_export_contestants'));
			
			//Bulk Approval
			add_action('admin_footer-edit.php', array($this,'ow_voting_bulk_add_approve'));
			add_action('load-edit.php', array($this,'ow_voting_bulk_add_approve_action'));
			add_action('admin_notices',array($this,'ow_voting_bulk_add_approve_notices'));
			
			//Adding the enctype in Wordpress form 
			add_action('post_edit_form_tag', array($this,'ow_post_edit_form_tag'));

			//Add Sorting Code in the Contestants
			add_action( 'pre_get_posts', array($this,'ow_manage_wp_posts_be_qe_pre_get_posts'), 1 );
			add_filter( 'posts_clauses', array($this,'contest_category_clauses'), 10, 2 ); 
			
		}

		public function ow_manage_wp_posts_be_qe_pre_get_posts($query){
			
			if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

				switch( $orderby ) {  
				  
				   case 'votes':
					$query->set( 'meta_key', OW_VOTES_CUSTOMFIELD );							  
				        $query->set( 'orderby', 'meta_value' );
					break;
							  
				}
			  
			}
			
		}
		
		public function contest_category_clauses($clauses, $wp_query){
			global $wpdb;
			
			if ( isset( $wp_query->query['orderby'] ) && 'contest_category' == $wp_query->query['orderby'] ) {
				$clauses['join'] .= " LEFT JOIN (
					SELECT object_id, GROUP_CONCAT(name ORDER BY name ASC) AS color
					FROM $wpdb->term_relationships
					INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
					INNER JOIN $wpdb->terms USING (term_id)
					WHERE taxonomy = 'contest_category'
					GROUP BY object_id
				) AS color_terms ON ($wpdb->posts.ID = color_terms.object_id)";
				$clauses['orderby'] = 'color_terms.color ';
				$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
			}
		
			return $clauses;
			
				
		}
		
		public function ow_post_edit_form_tag() {
			echo ' enctype="multipart/form-data"';		    
		}

		//Add columns to custom post(contestants)
		public function ow_contestant_post_add_columns()
		{
			$add_columns['cb'] = '<input type="checkbox" />';
			$add_columns['cb'] = '<input type="checkbox" />';
			$add_columns['image'] = __('Featured Image', 'voting-contest');
			$add_columns['title'] = __('Title', 'voting-contest');
			$add_columns[OW_VOTES_TAXONOMY] = __('Contest Category', 'voting-contest');
			$add_columns['votes'] = __('Votes', 'voting-contest');
			$add_columns['date'] = __('Date', 'voting-contest');
			return $add_columns;
		}
		
		//Specify the columns that need to be sortable
		public function ow_votes_custom_post_page_sort($columns) {
			$columns[OW_VOTES_TAXONOMY]= 'contest_category';
			$columns['votes']='votes';	 
			return $columns;
		}
			
		//Get the values of the post contestants    
		public function ow_custom_new_votes_column($column, $post_id) {
			switch ($column) {
			case 'voteid':
				echo $post_id;
			break;
			case OW_VOTES_TAXONOMY:
				$terms = get_the_terms($post_id, OW_VOTES_TAXONOMY);
				if (!empty($terms)) {
				$out = array();
				foreach ($terms as $c) {
					$_taxonomy_title = esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'category', 'display'));
					$out[] = "<a href='edit.php?" . OW_VOTES_TAXONOMY . "=$c->slug&post_type=" . OW_VOTES_TYPE . "'>$_taxonomy_title</a>";
				}
				echo join(', ', $out);
				} else {
				_e('Uncategorized','voting-contest');
				}
			break;
		
			case 'image':
				if (has_post_thumbnail($post_id)) {
				$image_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'thumbnail');
				$image_src = $image_arr[0];			
				$image1 = Ow_Vote_Common_Controller::ow_voting_resize_thumb(get_post_thumbnail_id($contest_post->ID),'',50,50,true);
				echo "<img src=".$image1['url']." width=".$image1['width']." height=".$image1['height']." class='left-img-thumb' />";
					
					
				} else {
				echo 'No Featured Image';
				}
				break;
					case 'description':
				if ($excerptv = get_the_excerpt($post_id)) {
				echo $excerptv ;
				} else {
				echo 'No Description';
				}
				break;
					case 'slug':
				if ($slugv = votes_slug($post_id)) {
				echo $slugv ;
				} else {
				echo 'contest';
				}
			break;
			case 'votes':
				$votes = get_post_meta($post_id, OW_VOTES_CUSTOMFIELD,'true');
				echo $votes;
			break;
			}
		}
		
		//Add the custom meta boxes on add/edit
		public function ow_custom_meta_box_contestant(){
			add_meta_box('votesstatus', __('Votes For this Contestant','voting-contest'), array($this,'ow_votes_count_meta_box'), OW_VOTES_TYPE, 'normal', 'high');
			add_meta_box('votecustomfields', __('Custom Fields','voting-contest'), array($this,'ow_votes_contestant_custom_field_meta_box'), OW_VOTES_TYPE, 'normal', 'high');
			add_meta_box('votescustomlink', __('Custom Link for Redirection','voting-contest'), array($this,'ow_votes_custom_link'), OW_VOTES_TYPE, 'normal', 'high');
		}
		
		public function ow_votes_contestant_custom_field_meta_box(){
			global $post,$wpdb;		
			$terms = get_the_terms( $post->ID, OW_VOTES_TAXONOMY );	
			$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
			$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries($post->ID);
			require_once(OW_VIEW_PATH.'ow_contestant_metabox_view.php');
			ow_votes_custom_field_metabox_view($custom_fields,$custom_entries,$terms);
		}
		
		public function ow_votes_custom_link(){
			global $post,$wpdb;
			$custom_link = get_post_meta($post->ID,'ow_contestant_link',true);
			require_once(OW_VIEW_PATH.'ow_contestant_metabox_view.php');
			ow_votes_custom_link_metabox_view($custom_link);
		}
		
		public function ow_custom_meta_box_save_contestant($post_id, $post, $update)
		{
			
			$slug = OW_VOTES_TYPE;

			// If this isn't a 'contestants' post, don't update it.
			if ( $slug != $post->post_type ) {
				return;
			}
			
			$wp_list_table = _get_list_table('WP_Posts_List_Table');
			$action = $wp_list_table->current_action();
			if($action=='contestant_approve'){
				return;
			}
			
			// Do nothing during a bulk edit
			if (isset($_REQUEST['bulk_edit']))
				return;
			
			if($_SERVER[ 'HTTP_X_REQUESTED_WITH' ]!='XMLHttpRequest'){
				$vote_count_values = get_post_meta( $post_id, OW_VOTES_CUSTOMFIELD);
				if(isset($_POST['ow_contestant_link'])){
					update_post_meta($post_id, 'ow_contestant_link', $_POST['ow_contestant_link']);	
				}
				
				if(empty($vote_count_values))
				update_post_meta($post_id, OW_VOTES_CUSTOMFIELD, 0); 
				
				$contestant_post  = Ow_Contestant_Model::ow_voting_get_contestant_by_id($post_id);
			    if(!empty($contestant_post)){
					   $custom_field = Ow_Contestant_Model::ow_get_contestant_custom_fields();
						$error = new WP_Error(); 
						if(!empty($custom_field)){
							$posted_val=array();
							
							foreach($custom_field as $custom_fields){
								
								
								//Check the Custom Field File Types								
								if($custom_fields->question_type == "FILE"){
									$supportedFormatfile = explode(',',$custom_fields->response);
									$uploadedMeta_file = wp_check_filetype_and_ext($custom_fields->system_name, $_FILES[$custom_fields->system_name]['name']);
									
									
									if(($_FILES[$custom_fields->system_name]['error']) || ($_FILES[$custom_fields->system_name]['size'] <=0 )) {
										$error->add('Invalid File', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Problem in File Upload','voting-contest'));
									}
									else if(!in_array($uploadedMeta_file['ext'], $supportedFormatfile)) {										
							$error->add('Invalid File Format', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Invalid File Format. (Note: Supported File Formats ','voting-contest').implode($supportedFormatfile, ', ').')');
									}
									else{
										
										$custom_files[$custom_fields->system_name]=$_FILES[$custom_fields->system_name];  
									}
									$count_files[] = $custom_fields->system_name;
									
								}
								
								if($custom_field->question_type != "FILE"){
									$posted_val[$custom_fields->system_name]=$_POST[$custom_fields->system_name];
								}
								if($custom_fields->required=='Y' && $custom_field->question_type != "FILE"){ 
									 if($_POST[$custom_fields->system_name]==''){
									   $error_msg = ($custom_fields->required_text!='')?$custom_fields->required_text:$custom_fields->question.'Field required';                                             
									 }
								}
								
								//Updating the Custome Fields in POST META for search Fix in Future -- After Voting Upgrade Module Version
								if($custom_fields->system_name != 'contestant-title' && $custom_fields->system_name != 'contestant-desc'){
									update_post_meta($post_id, $custom_fields->system_name, $_POST[$custom_fields->system_name]);
								}
								
							}
							
						}
						
						if(!empty($custom_files)){
							$i = 0;
							foreach($count_files as $key){
								if(isset($_FILES[$key]['tmp_name'])){									
									$upload = wp_upload_bits($_FILES[$key]['name'], null, file_get_contents($_FILES[$key]['tmp_name']));
									update_post_meta($post_id, 'ow_custom_attachment_'.$i, $upload);			
								}
								
								$posted_val[$key] = $i;
								$i++;
							}
							
						}
						else{							
							$j=0;    
							foreach($count_files  as $key){
								$posted_val[$key] = $j;
								$j++;
							}
						}
						
						$val_serialized = base64_encode(maybe_serialize($posted_val));
						$field_val = Ow_Contestant_Model::ow_voting_get_all_custom_entries($post_id);
						if(!empty($field_val)){
							  Ow_Contestant_Model::ow_voting_contestant_update_field($val_serialized,$post_id);
						}else{
							if (array_filter($posted_val)) {
							  Ow_Contestant_Model::ow_voting_contestant_insert_field($val_serialized,$post_id);
							}  
						}
						
						//skip auto save
						if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
							return $post_id;
						}
						//check for you post type only
						if( $post->post_type == "homepage" ) {
							if( isset($_POST['link_homepage']) ) { update_post_meta( $post->ID, 'link_homepage', $_POST['link_homepage'] );}
						}
				}//Check post contestestant
			}
		}
		
		//Votes count metabox
		public function ow_votes_count_meta_box() {
			global $post,$wpdb;
			require_once(OW_VIEW_PATH.'ow_contestant_metabox_view.php');
			$cnt = Ow_Contestant_Model::ow_get_votes_count_post($post);
			ow_votes_count_metabox_view($cnt);
		}
		
		//Tab menu on the contestant page
		public function ow_contestant_custom_menu_bar()
		{
			require_once(OW_VIEW_PATH.'ow_contestant_common_view.php');
			ow_votes_admin_menu_custom();
		}
		
		public static function ow_voting_move_contestants(){
			global $wpdb;
			require_once(OW_VIEW_PATH.'ow_move_contestant_view.php');
			if ( current_user_can('edit_posts') ) {
			if (isset($_REQUEST['move_contest_submit'])) {
				$posts = $_POST['selected_post'];        
				$old_cat = absint($_POST['existing_contest_term']);
				$new_cat = ($_POST['mapped_contest_term'] == -1) ? -1 : absint($_POST['mapped_contest_term']);
				if(count($posts)){
				foreach ($posts as $post) {
				$current_cats = wp_get_object_terms($post, OW_VOTES_TAXONOMY,array('fields' => 'ids'));
				$current_cats = array_diff($current_cats, array($old_cat));
				if ($new_cat != -1) {
					$current_cats[] = $new_cat;
				}
		
				if (count($current_cats) <= 0) {
					$cls = 'error';
					$msg = 'Invalid Category';
				}
				else {
					$current_cats = array_values($current_cats);
					$term = get_term($new_cat, OW_VOTES_TAXONOMY);           
					wp_set_post_terms( $post, $current_cats, OW_VOTES_TAXONOMY);
					$cls = 'updated';
					$msg = count($posts).' '.__('Contestants Successfully Moved','voting-contest');
						
					$wpdb->update( 
						OW_VOTES_TBL, 
						array( 
							'termid' => $current_cats[0]
						), 
						array( 'post_id' => $post ), 
						array( 
							'%d'                		
						), 
						array( '%d' ) 
					);
				}
				}
				echo '<div style="line-height:40px;" class="' . $cls . '">' . $msg . '</div>';
				}
			}
			}
			ow_votes_contestant_move_view();
		}
			
		public static function ow_voting_import_contestants(){
			
			global $wpdb;
			require_once(OW_VIEW_PATH.'ow_import_contestant_view.php');
			
			if (isset($_POST['contest_csv_submit'])){
			set_time_limit(0);
			include OW_CONTROLLER_XL_PATH.'ow_vote_spreadsheetreader.php';
			
			$inserted = array();
			$csv_termid = $_POST['contest_csv_term'];
			if (isset($csv_termid) && $csv_termid > 0) {
				
				$csv_term = get_term($csv_termid, OW_VOTES_TAXONOMY);
				$tempFile = $_FILES['contest_csv_file']['tmp_name'];
				$targetPath = OW_ASSETS_UPLOAD_PATH;
				$sourceCSV = $_FILES['contest_csv_file']['name'];
				$get_source_file_ext = $ext = pathinfo($sourceCSV, PATHINFO_EXTENSION);
				$accepted_formats = array('ods','ODS','xlsx','XLSX','csv','CSV','xls','XLS');
				if (in_array($get_source_file_ext, $accepted_formats)) {
				$targetFile = str_replace('//', '/', $targetPath) . $_FILES['contest_csv_file']['name'];
				if ($_FILES['contest_csv_file']['error'] == 0 && move_uploaded_file($tempFile, $targetFile)) {
					
					if($get_source_file_ext=='xls' || $get_source_file_ext=='XLS')
						$keyd = 1;
					else
						$keyd = 0;
						
					$Reader = new Ow_Vote_SpreadsheetReader($targetFile);
					foreach ($Reader as $key=>$Row)
					{
					if($key!=$keyd){
						//Not post title null
						if (trim($Row[0]) != '') {
						$attr = array('post_title' => $Row[0],
						'post_content' => wpautop(convert_chars(($Row[1]))),
						'post_type' => OW_VOTES_TYPE,
						'post_status' => 'publish');
	
						if (!empty($Row[2])) {
							$image_url = trim($Row[2]);
						}
						
						$cur_id = wp_insert_post($attr);
						update_post_meta($cur_id, OW_VOTES_CUSTOMFIELD, 0);
						
						$questions = Ow_Contestant_Model::ow_get_contestant_custom_fields();
						if(!empty($questions)){
							$posted_val=array();
							$i=3;
							foreach($questions as $custom_fields){
							   $posted_val[$custom_fields->system_name]=$Row[$i];
							   $i++;
							}
						}
						$val_serialized = base64_encode(maybe_serialize($posted_val));
						Ow_Contestant_Model::ow_voting_insert_post_entry($cur_id,$val_serialized);
						update_post_meta($cur_id, OW_VOTES_EXPIRATIONFIELD, '0');
						wp_set_object_terms($cur_id, $csv_term->slug, OW_VOTES_TAXONOMY);
						if ($image_url != '') {
							Ow_Vote_Common_Controller::ow_voting_create_or_set_featured_image($image_url, $cur_id);
						}
						$inserted[] = $cur_id;
						}
					}
					}
					$cls = "updated";
					$msg = count($inserted) . " Contestants Uploaded";
				}
				else
				{
					$cls = "error";
					$msg = __('Error in Uploading','voting-contest');
				}
				}else
				{
				$cls = "error";
				$msg = __('Invalid File format','voting-contest');
				}
	
			}else
			{
				$cls = "error";
				$msg = __('Invalid category.','voting-contest');
			}
			echo '<div style="line-height:40px;" class="' . $cls . '">' . $msg . '</div>';
			}
			ow_voting_import_contestants_view();
		}
			
		public static function ow_voting_export_contestants(){
			global $wpdb;
			require_once(OW_VIEW_PATH.'ow_export_contestant_view.php');
			ow_voting_export_contestants_view();
		}
		
		public static function ow_voting_vote_logs(){
			global $wpdb;
			require_once(OW_VIEW_PATH.'ow_vote_log_view.php');
			
			$log_entries['orderby'] = ($_GET['orderby'] == null)?'log.date':$_GET['orderby'];   
			$log_entries['order'] = ($_GET['order'] == null)?'desc':$_GET['order'];
		
			//Get counts
			$total   =  Ow_Contestant_Model::ow_total_votes_count();
					
			if(empty($_GET['paged'])) {
			$paged = 1;
			} 
			else {
			$paged = ((int) $_GET['paged']);
			}
			$records_per_page = $_POST['logs_per_page'];
			
			if ( isset( $records_per_page ) && $records_per_page )
			$log_entries['rpp'] = $records_per_page;
			else
			$log_entries['rpp'] = 10;        
						   
			$log_entries['startat'] = ($paged - 1) * $log_entries['rpp'];
			
			$trans_navigation = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),    
			'format' => '',    
			'total' => ceil(count($total) / $log_entries['rpp']),    
			'current' => $paged,        
			));
			
			$log_no = (isset($log_entries['rpp']))?'&logs_per_page='.$log_entries['rpp']:'';            
			$voting_logs['actual_link'] = admin_url().'admin.php?page=votinglogs'.$log_no; 
			$voting_logs['yet_to_order'] = ($log_entries['order'] == 'asc')?'desc':'asc';
			
			if($_POST['action'] == "delete" ){
				foreach($_POST['checkbox'] as $key => $check_val){
					Ow_Contestant_Model::ow_voting_delete_entries($check_val,$key);
				}
				$redirect_link = admin_url().'admin.php?page=votinglogs&delete_success=2';
				echo '<meta http-equiv="refresh" content="0;url='.$redirect_link.'">'; 
			}
			
			if(isset($_POST['delete_tbl_id']) && isset($_POST['delete_vote_id']))
			{
				Ow_Contestant_Model::ow_voting_delete_entries($_POST['delete_vote_id'],$_POST['delete_tbl_id']);		
				$redirect_link = admin_url().'admin.php?page=votinglogs&delete_success=1';
				echo '<meta http-equiv="refresh" content="0;url='.$redirect_link.'">';     
			}
				
			$voting_logs['log_entries'] = Ow_Contestant_Model::ow_voting_log_entries($log_entries);
			
			ow_vote_log_view($voting_logs,$log_entries,$trans_navigation);
			
		}
		
		public function ow_voting_bulk_add_approve(){
			global $post_type;
			if($post_type == OW_VOTES_TYPE && ($_REQUEST['post_status'] == '' || $_REQUEST['post_status'] == 'pending')) {
			  ?>
			  <script type="text/javascript">
				jQuery(document).ready(function() {
				  jQuery('<option>').val('contestant_approve').text('<?php _e('Approve')?>').appendTo("select[name='action']");   
				  jQuery('<option>').val('contestant_approve').text('<?php _e('Approve')?>').appendTo("select[name='action2']");     
				});
			  </script>
			  <?php
			}
		}
		
		public function ow_voting_bulk_add_approve_action(){
			$screen = get_current_screen();
			if (!isset($screen->post_type) || OW_VOTES_TYPE !== $screen->post_type) {
				return; 
			}
			$wp_list_table = _get_list_table('WP_Posts_List_Table');
			$action = $wp_list_table->current_action(); 
			$approved = 0;
			
			switch($action) 
			{       
				case 'contestant_approve':          
					// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
					if(isset($_REQUEST['post'])) {
							$post_ids = array_map('intval', $_REQUEST['post']);
					}        
					if(empty($post_ids)) return;               
            
					$sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
					if ( ! $sendback )
						$sendback = admin_url( "edit.php?post_type=".OW_VOTES_TYPE );         
                        
					$pagenum = $wp_list_table->get_pagenum();
					$sendback = add_query_arg( 'paged', $pagenum, $sendback );                    
					$exploded_ids = implode(',',$post_ids);
					$result_ids =   Ow_Contestant_Model::ow_vote_contestant_bulk_pending($exploded_ids);
                         
					//Change the Status of the Contestants            
					foreach($post_ids as $pid):  
						$contestants = array( 'ID' => $pid, 'post_status' => 'publish' );
						wp_update_post($contestants);   						
					endforeach;
					
					$sendback = add_query_arg( array('approved' => $approved, 'ids' => count($result_ids) ), $sendback );
				break;
            
				default: return;
			}
		}
		
		public function ow_voting_bulk_add_approve_notices(){
			global $post_type, $pagenow;
			if($pagenow == 'edit.php' && $post_type == OW_VOTES_TYPE) {
				if (isset($_REQUEST['approved'])) {
					//Print notice in admin bar
                    $message = sprintf( _n( 'Contestants approved.', '%s Contestants approved.', $_REQUEST['approved'] ), number_format_i18n( $_REQUEST['ids']) ) ;
					if(!empty($message)) {
							echo "<div class=\"updated\"><p>{$message}</p></div>";
					}
				}
			}
		}
	
    }
}else
die("<h2>".__('Failed to load Voting Contestant Controller','voting-contest')."</h2>");

return new Ow_Vote_Contestant_Controller();
