<?php
if(!class_exists('Ow_Vote_Shortcode_Model')){
	class Ow_Vote_Shortcode_Model {
	   
	   public static function ow_get_show_contest_query($show_cont_args,$ajax = null){
			global $wpdb;
		
			if(isset($_POST['filter_votes']))
				$search_filter=$_POST['filter_votes'];
			
			if(isset($_POST['category_id']))	
				$search_id=$_POST['category_id'];
			
			if(isset($_SESSION['ow_vote_search_filter'.$show_cont_args['id']]))	
				$session_filter = $_SESSION['ow_vote_search_filter'.$show_cont_args['id']];
			
			if(($session_filter=='') &&  $search_filter!='sort'){
				$_SESSION['ow_vote_search_filter'.$search_id] = $search_filter;
				$session_filter = $_SESSION['ow_vote_search_filter'.$search_id];
			}
			if(isset($session_filter)){
				if(($session_filter!=$search_filter) && ($search_filter!='sort') && ($search_filter!='')){
					$_SESSION['ow_vote_search_filter'.$search_id] = $search_filter;
				}
			}
		
			if((($session_filter!='') && ($search_filter!='sort'))||$search_id!=$show_cont_args['id']){
				$search_filter = $_SESSION['ow_vote_search_filter'.$show_cont_args['id']];
			}else{
				unset($_SESSION['ow_vote_search_filter'.$show_cont_args['id']]);
				unset($_SESSION['ow_vote_search_filter'.$search_id]);
			}
			
			//Unset the Session If Orderby is set and It is ajax 
			if($show_cont_args['orderby'] != "" && !isset($_POST['filter_votes']) && $ajax == null){ 
			 unset($_SESSION['ow_vote_search_filter'.$show_cont_args['id']]);
			 unset($_SESSION['ow_vote_search_filter'.$search_id]);
			}
			
			if($show_cont_args['id']==0)
			return 'error';
			
			//If in case user tries to add multiple ids remove one id
			$ids = explode(',', $show_cont_args['id']);
			if (count($ids) > 1) {			
				   return FALSE;
			}
			
			if ($show_cont_args['id'] != 0 && explode(',', $show_cont_args['id']))
				$show_cont_args['id'] = explode(',', $show_cont_args['id']);
			
			if (isset($show_cont_args['paged']) && $show_cont_args['paged'] > 0)
				$paged = $show_cont_args['paged'];
			else{		
				if ( get_query_var('paged') ) {			
					$paged = get_query_var('paged');
				} elseif ( get_query_var('page') ) {			
					$paged = get_query_var('page');		 
				} else {
					$paged = 1;
				}	
			}
			
			if(isset($show_cont_args['exclude']) && $show_cont_args['exclude'] != null):
				$excluded_ids = explode(',',$show_cont_args['exclude']);
			else:
				$excluded_ids = array();
			endif;
			
			if($ajax == null){
				$postargs = array(
					'post_type' => OW_VOTES_TYPE,
					'post_status' => 'publish',
					'posts_per_page' => $show_cont_args['postperpage'],
					'tax_query' => array(
						array(
								'taxonomy' => $show_cont_args['taxonomy'],
								'field' => 'id',
								'terms' => $show_cont_args['id'],
								'include_children' => false
							)
					),
					'paged' => $paged,
					'post__not_in' => $excluded_ids
				);
			}
			else{
				$postargs = array(
					'post_type' => OW_VOTES_TYPE,
					'post_status' => 'publish',
					'posts_per_page' => $show_cont_args['postperpage'],
					'offset' => $_POST['offset'],
					'tax_query' => array(
						array(
								'taxonomy' => $show_cont_args['taxonomy'],
								'field' => 'id',
								'terms' => $show_cont_args['id'],
								'include_children' => false
							)
					),
					//'paged' => $paged,
					'post__not_in' => $excluded_ids
				);
			}
			
			if($show_cont_args['order']=='on')
			{
				$show_args='asc';				
			}
			else
			{
				$show_args='desc';
			}
				
				
			if($search_filter=='' || $search_filter=='sort'){					
				if($show_cont_args['orderby'] == 'votes') {
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = $show_args;					
				}
				elseif($show_cont_args['orderby'] == 'top') {
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = 'DESC';	
				}
				elseif($show_cont_args['orderby'] == 'bottom') {
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = 'ASC';
				}else{
					$postargs['orderby'] = $show_cont_args['orderby'];
					$postargs['order'] = $show_args;
				}
			}
			
			
			
			if($search_filter!=''){
				if($search_filter=='new_contestant'){
					$postargs['orderby'] = 'date';
					$postargs['order'] = 'desc';
				}elseif($search_filter=='old_contestant'){
					$postargs['orderby'] = 'date';
					$postargs['order'] = 'asc';
				}elseif($search_filter=='votes_top'){
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = 'DESC';
				}elseif($search_filter=='votes_down'){
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = 'ASC';
				}
			}
			
			if($postargs['orderby'] == 'rand'){
				$_SESSION['random_order'] = 1;
			}
			else{
				unset($_SESSION['random_order']);
			}
			
			
			if (is_array($show_cont_args['id']) && count($show_cont_args['id']) > 1) {
				add_filter('posts_where_request', array('Ow_Vote_Shortcode_Model','ow_votes_expiration_basedon_general'));
			}
			else {
				global $taxid;
				$taxid = isset($show_cont_args['id'][0]) ? $show_cont_args['id'][0] : 0;
				add_filter('posts_where_request',array('Ow_Vote_Shortcode_Model','ow_votes_expiration_basedon_taxid'));
			}
			
			$contest_post = new WP_Query($postargs);
			
			return $contest_post;
		}
		
		//Just For Upgrade Controller
		public static function ow_get_show_all_contest_query($show_cont_args = null,$selected = null,$search_filter = null,$ajax = null,$blocked_terms = null,$ow_search = null){
			
			if (isset($show_cont_args['paged']) && $show_cont_args['paged'] > 0)
				$paged = $show_cont_args['paged'];
			else{		
				if ( get_query_var('paged') ) {			
					$paged = get_query_var('paged');
				} elseif ( get_query_var('page') ) {			
					$paged = get_query_var('page');		 
				} else {
					$paged = 0;
				}	
			}			
						
			
			
			if($selected == null || $selected == -1){
				if($ajax == 1){
					$postargs = array(
						'post_type' => OW_VOTES_TYPE,
						'post_status' => 'publish',
						'posts_per_page' => $show_cont_args['postperpage'],
						'offset' => $_POST['offset'],
						'tax_query' => array(
							array(
									'taxonomy' => OW_VOTES_TAXONOMY,
									'field' => 'id',
									'terms' => $blocked_terms,
									'operator' => 'NOT IN',
								)
						),
					);
				}
				else{
					$postargs = array(
						'post_type' => OW_VOTES_TYPE,
						'post_status' => 'publish',
						'posts_per_page' => $show_cont_args['postperpage'],
						'paged' => $paged,
						'tax_query' => array(
							array(
									'taxonomy' => OW_VOTES_TAXONOMY,
									'field' => 'id',
									'terms' => $blocked_terms,
									'operator' => 'NOT IN',
								)
						),
					);
				}
			}
			else{				
				if($ajax == 1){		
					$postargs = array(
						'post_type' => OW_VOTES_TYPE,
						'post_status' => 'publish',
						'posts_per_page' => $show_cont_args['postperpage'],
						'offset' => $_POST['offset'],
						'tax_query' => array(
							array(
									'taxonomy' => OW_VOTES_TAXONOMY,
									'field' => 'id',
									'terms' => $selected,
								)
						),						
					);
				}
				else{
					$postargs = array(
						'post_type' => OW_VOTES_TYPE,
						'post_status' => 'publish',
						'posts_per_page' => $show_cont_args['postperpage'],
						'tax_query' => array(
							array(
									'taxonomy' => OW_VOTES_TAXONOMY,
									'field' => 'id',
									'terms' => $selected,
								)
						),
						'paged' => $paged,
					);
				}
			}		
			
			if($search_filter != null){
				if($search_filter=='new_contestant'){
					$postargs['orderby'] = 'date';
					$postargs['order'] = 'desc';
				}elseif($search_filter=='old_contestant'){
					$postargs['orderby'] = 'date';
					$postargs['order'] = 'asc';
				}elseif($search_filter=='votes_top'){
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = 'DESC';
				}elseif($search_filter=='votes_down'){
					$postargs['meta_key'] = OW_VOTES_CUSTOMFIELD;
					$postargs['orderby'] = 'meta_value_num';
					$postargs['order'] = 'ASC';
				}
			}
			
						
			if($postargs['orderby'] == 'rand'){
				$_SESSION['random_order'] = 1;
			}
			else{
				unset($_SESSION['random_order']);
			}
			
			$contest_post = new WP_Query($postargs); 
			return $contest_post;
		
			
		}
		
		
		public static function ow_get_show_all_contest_query_sql($show_cont_args = null,$selected = null,$search_filter = null,$ajax = null,$blocked_terms = null,$ow_search = null){
			
			global $wpdb, $wp_query;		
			
			if (isset($show_cont_args['paged']) && $show_cont_args['paged'] > 0)
				$paged = $show_cont_args['paged'];
			else{		
				if ( get_query_var('paged') ) {			
					$paged = get_query_var('paged');
				} elseif ( get_query_var('page') ) {			
					$paged = get_query_var('page');		 
				} else {
					$paged = 1;
				}	
			}
			
			$no_of_posts = $show_cont_args['postperpage'];
			
			if($search_filter != null){
				if($search_filter=='new_contestant'){
					$orderby 	= $wpdb->prefix."posts.post_date ";
					$order 		= 'desc';
				}elseif($search_filter=='old_contestant'){
					$orderby 	= $wpdb->prefix."posts.post_date ";
					$order 		= 'asc';
				}elseif($search_filter=='votes_top'){					
					$orderby 	= $wpdb->prefix."postmeta.meta_value ";
					$order 		= 'DESC';
				}elseif($search_filter=='votes_down'){					
					$orderby 	= $wpdb->prefix."postmeta.meta_value ";
					$order 		= 'ASC';
				}				
			}
			else{
					$orderby 	= $wpdb->prefix."posts.post_date ";
					$order 		= 'DESC';
			}
			
						
			$selected = ($selected == -1)?"":$selected;
			if($blocked_terms && !$selected){
				$blocked_terms = implode(",",$blocked_terms);
				$terms_sql = "( ".$wpdb->prefix."posts.ID NOT IN ( SELECT object_id FROM ".$wpdb->prefix."term_relationships WHERE term_taxonomy_id IN ($blocked_terms) ) ) AND ";
			}
			
			if($selected && $selected != -1){				
				$terms_sql = "( ".$wpdb->prefix."posts.ID IN ( SELECT object_id FROM ".$wpdb->prefix."term_relationships WHERE term_taxonomy_id IN ($selected) ) ) AND ";
			}
						
			if($ow_search != null){
				
				$title_content = " (((".$wpdb->prefix."posts.post_title LIKE '%".$ow_search."%') OR (".$wpdb->prefix."posts.post_excerpt LIKE '%".$ow_search."%') OR (".$wpdb->prefix."posts.post_content LIKE '%".$ow_search."%')) ";		
								
				$or_condition = $title_content. " OR " ;
				
				$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
				
				$i = 0; $count_custom = count($custom_fields); 
				foreach($custom_fields as $field){
					//Skip Contestant Title and Description
					if($field->system_name != 'contestant-title' && $field->system_name != 'contestant-desc'){
						$or_condition .= " (( ".$wpdb->prefix."postmeta.meta_key = '".$field->system_name."' AND ".$wpdb->prefix."postmeta.meta_value LIKE '%".$ow_search."%' ))  " ;
						$i++;
						
						//Add OR Condition for all custom fields except for END 
						if($i != $count_custom - 2){
							$or_condition .= " OR ";
						}
					}
				}				 
				$or_condition .= ") AND ";
			}
			
			//Listing the Query 
	 		$sql = "SELECT SQL_CALC_FOUND_ROWS ".$wpdb->prefix."posts.* FROM ".$wpdb->prefix."posts INNER JOIN ".$wpdb->prefix."postmeta ON ( ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id ) WHERE 1=1  AND ".$terms_sql.$or_condition . $wpdb->prefix."posts.post_type = '".OW_VOTES_TYPE."' AND ((".$wpdb->prefix."posts.post_status = 'publish')) GROUP BY ".$wpdb->prefix."posts.ID ORDER BY ".$orderby.$order;
					
			$total_record = count($wpdb->get_results($sql, ARRAY_A));
			
			$post_per_page  = $show_cont_args['postperpage'];
			$offset         = ($ajax ==1)?$_POST['offset']:($paged - 1)*$post_per_page; 
			$max_num_pages  = ceil($total_record/ $post_per_page);
		
			$wp_query->found_posts = $total_record;
			// number of pages 
			$wp_query->max_num_pages = $max_num_pages;
		
			$limit_query    =   " LIMIT ".$post_per_page." OFFSET ".$offset;    //echo $sql.$limit_query;
			
			$desc_rs =   $wpdb->get_results($sql.$limit_query,OBJECT);// return OBJECT 
			
			return $desc_rs;
		
		}
		
		public static function ow_votes_expiration_basedon_general($where){
			global $wpdb;
			return $where.' AND ( select option_id from '.$wpdb->prefix.'options where (`option_name` = "'.OW_VOTES_GENERALEXPIRATIONFIELD.'" AND `option_value` = 0 ) or (`option_name` = "'.OW_VOTES_GENERALEXPIRATIONFIELD.'" AND `option_value` > "'.date('Y-m-d H:i:s').'" ) ) ';
		}
		
		public static function ow_votes_expiration_basedon_taxid($where){
			global $wpdb,$taxid;
			return $where.' AND ( select option_id from '.$wpdb->prefix.'options where (`option_name` = "'.$taxid.'_'.OW_VOTES_TAXEXPIRATIONFIELD.'" AND `option_value` = 0 ) or (`option_name` = "'.$taxid.'_'.OW_VOTES_TAXEXPIRATIONFIELD.'" ) ) ';
		}
		
		public static function ow_vote_show_desc_prettyphoto(){
			global $wpdb;
			$sql = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " where system_name='contestant-desc' and pretty_view='Y' order by sequence";            
			$questions = $wpdb->get_results($sql);
			if(count($questions) > 0)
				return 1;
			else
				return 0;	   
		}
		
		public static function ow_get_top_contest_query($id){
			global $wpdb;
			unset($_SESSION['random_order']);
			$term_id[] = $id;
			$order_settings = 'DESC';
			$postargs = array(
				'post_type' => OW_VOTES_TYPE,
				'post_status' => 'publish',
				'posts_per_page' => 10,
				'tax_query' => array(
					array('taxonomy' => OW_VOTES_TAXONOMY,
						'field' => 'id',
						'terms' => $term_id,
						'include_children' => false)
				),
		
				'meta_key' => OW_VOTES_CUSTOMFIELD,
				'orderby' => 'meta_value_num',
				'order' => $order_settings            
			);
			$contest_post = new WP_Query($postargs);
			return $contest_post;
		}
		
		
	 
		public static function ow_get_total_vote_count_by_term_id($id){
			global $wpdb;
			$args = array (
				'post_type'    => OW_VOTES_TYPE,
				'post_status'  => 'publish',
				'posts_per_page' => -1,
				'tax_query'    => array(
						array(
							'taxonomy' => OW_VOTES_TAXONOMY,
							'field' => 'id',
							'terms' => $id
						)
					)
			);            
			
			// The Query
			$query = new WP_Query( $args );
			return $query;
		}
		
		public static function ow_voting_get_contestant_desc(){
		    global $wpdb;            
			$sql     = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE system_name = 'contestant-desc'";
			$desc_rs = $wpdb->get_results($sql);    
			return $desc_rs;
		}
		
		
		public static function ow_voting_get_contestant_title(){
		    global $wpdb;            
			$sql     = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE system_name = 'contestant-title'";
			$desc_rs = $wpdb->get_results($sql);    
			return $desc_rs;
		}
		
		public static function ow_voting_get_post_entry_track($where){
		    global $wpdb;            
			$sql     = "Select * from ".OW_VOTES_POST_ENTRY_TRACK.$where;
			$get_count_track = $wpdb->get_results($sql);    
			return $get_count_track;
		}
		
		public static function ow_voting_update_post_entry_track($new_count,$id,$term_id){
			global $wpdb;
			$save_sql = "UPDATE " . OW_VOTES_POST_ENTRY_TRACK . " SET count_post=" . $new_count . " WHERE id='" .$id. "' and ow_term_id='".$term_id."'";
			$wpdb->query($save_sql);
		}
		
		public static function ow_voting_insert_post_entry_track($user_ID,$ip,$term_id){
			global $wpdb;
			$save_sql = 'INSERT INTO `' . OW_VOTES_POST_ENTRY_TRACK . '` (`user_id_map`,`ip`,
									`count_post`,`ow_term_id`) VALUES ("' . $user_ID . '", "' . $ip . '", 1 , "'.$term_id.'") ';
			$wpdb->query($wpdb->prepare( $save_sql));
		}
		
		public static function ow_voting_user_get_custom_fields($admin=NULL){
			global $wpdb;
			
			if($admin)
			$sql = "SELECT * FROM " . OW_VOTES_USER_CUSTOM_TABLE . " WHERE admin_only  = 'Y' AND delete_time=0";
			else
			$sql = "SELECT * FROM " . OW_VOTES_USER_CUSTOM_TABLE . " WHERE delete_time=0";
			
			$question = $wpdb->get_results($sql);
			return $question;
	    }
		
		public static function ow_votes_user_entry_table($user_id){
			global $wpdb;
			$sql1 = "SELECT * FROM " . OW_VOTES_USER_ENTRY_TABLE. " WHERE user_id_map = '".$user_id."'";
			$user_entry = $wpdb->get_results($sql1);
			return $user_entry;
		}
		
		public static function ow_votes_delete_user_entry_table($user_id){
			global $wpdb;
			$wpdb->query("DELETE FROM " . OW_VOTES_USER_ENTRY_TABLE . " WHERE user_id_map = '" . $user_id . "'");
		}
		
		public static function ow_voting_delete_single_contestants($vote_id){
			global $current_user, $wp_roles;
			get_currentuserinfo();
			$post = get_post( $vote_id );
			if($post->post_author == $current_user->ID){
				wp_delete_post($post->ID, true);
				return '<div class="contestants-success vote-profile-status">
								<div class="success-rows">'
								.__("Contestant Deleted Successfully","voting-contest").
								'</div>'.
						'</div>';
			}
			else{
				return '<p class="ow_profile_required_mark">'.__("You do not have sufficient permission to delete","voting-contest").'</p>';
			}
		}
		
		public static function ow_votes_user_entry_update_table($val_serialized,$user_id){
			global $wpdb;
			$wpdb->query("UPDATE " . OW_VOTES_USER_ENTRY_TABLE . " SET field_values = '" . $val_serialized . "' WHERE user_id_map = '" . $user_id . "'");
		}
		
		public static function ow_votes_user_entry_insert_table($val_serialized,$user_id){
			global $wpdb;
			$wpdb->query("INSERT INTO " . OW_VOTES_USER_ENTRY_TABLE . " (user_id_map,field_values)". " VALUES ('".$user_id."', '".$val_serialized. "')");
		}
		
		public static function ow_voting_insert_contestants($contest_args,$vote_opt,$category_options,$custom_fields,$check_status){
			global $wpdb;
			$imgcontest = $category_options['imgcontest'];
			$vote_onlyloggedcansubmit = $vote_opt['vote_onlyloggedcansubmit'];
			extract( shortcode_atts( array(
			'id' => NULL,
			'showcontestants' => 1,
			'message' => 1,
			'contestshowfrm'=>1,
			'displayform'=>0,
			'loggeduser'=>$vote_onlyloggedcansubmit
			), $contest_args ));
			
			if($check_status && $displayform==0){
				return ;
			}			
			else{				
				$formProcessed = $formError  = FALSE;
				
				if(isset($_POST['contestantform'.$contest_args['id']])) {		
					$error = new WP_Error();
							
					//Validate the post values
					if(!get_term_by( 'id', $_POST['contest-id'], OW_VOTES_TAXONOMY)) {
						$error->add(__('Invalid Save','voting-contest'), '<strong>'.__('Error','voting-contest').'</strong>: '.__('Some problem in Saving. Please Try Later','voting-contest'));
					}
					
					$contestant_title = strip_tags($_POST['contestant-title']);
					$contestant_desc = $_POST['contestant-desc'.$contest_args['id']];
					if(!trim($contestant_title)) {
						$error->add(__('Invalid Title','voting-contest'), '<strong>'.__('Error','voting-contest').'</strong>: '.__('Enter the Contestants Title','voting-contest'));
					}
					
					$desc_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_desc();
					if($desc_rs[0]->admin_only == "Y"){         
						if($desc_rs[0]->required == "Y"){              
							if(!trim($contestant_desc)) {
								$error->add(__('Invalid Description','voting-contest'), '<strong>'.__('Error','voting-contest').'</strong>: '.__('Enter the Contestants Description','voting-contest'));
							}
						}                 
					}
					
					$supportedFormat = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
					$uploadedMeta = wp_check_filetype_and_ext('contestant-image', $_FILES['contestant-image']['name']);
					if($category_options['imgcontest']!='')
						$image_contest = $category_options['imgcontest'];
									
					if($image_contest=='photo'){
						if( !class_exists(wp_voting_photo_extension) ) {
							if(($_FILES['contestant-image']['error']) || ($_FILES['contestant-image']['size'] <=0 )) {
								$error->add('Invalid File', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Problem in File Upload','voting-contest'));
							}
							else if(!in_array($uploadedMeta['ext'], $supportedFormat)) {
								$error->add('Invalid File Format', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Invalid File Format. (Note: Supported File Formats ','voting-contest').implode($supportedFormat, ', ').')');
							}
						}
					}
					$ow_video_extension = get_option('_ow_video_extension');
					if(!empty($custom_fields)){
						$posted_val=array(); 
						foreach($custom_fields as $custom_field){
								
						   if($custom_field->system_name != 'contestant-desc'):
						   if($custom_field->question_type != "FILE"){
							$posted_val[$custom_field->system_name]=$_POST[$custom_field->system_name];
						   }
						   
						   
						   
						   if($custom_field->required=='Y' && $category_options['musicfileenable'] != 'on'){ 
								if($_POST[$custom_field->system_name]==''){
									
									if($custom_field->system_name == 'contestant-ow_video_url'){
										
										if($ow_video_extension != null && $image_contest != 'music'){
											continue;
										}
									
										//Skip Validation Video Url for Video & Music Contest Only 
										if($imgcontest != 'video' && $imgcontest != 'music'){
										     continue;
										}
										
									}																	
									
									//Skip Validation for Upload Video Extension
									if($custom_field->system_name == 'contestant-ow_video_upload_url'){										
										continue;								   
									}
									
									//Skip Validation for Upload Music 
									if($custom_field->system_name == 'contestant-ow_music_url'){										
										continue;								   
									}
									 
								//Check the Custom Field File Types
								if($custom_field->question_type == "FILE"){
									
									$supportedFormatfile = explode(',',$custom_field->response);
									$uploadedMeta_file = wp_check_filetype_and_ext($custom_field->system_name, $_FILES[$custom_field->system_name]['name']);
									if(($_FILES[$custom_field->system_name]['error']) || ($_FILES[$custom_field->system_name]['size'] <=0 )) {
										
										$error->add('Invalid File', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Problem in File Upload','voting-contest'));
									}
									else if(!in_array($uploadedMeta_file['ext'], $supportedFormatfile)) {										
										
											$error->add('Invalid File Format', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Invalid File Format. (Note: Supported File Formats ','voting-contest').implode($supportedFormatfile, ', ').')');
									}
									else{
										
										$custom_files[$custom_field->system_name]=$_FILES[$custom_field->system_name];  
									}
								}
								else{
								   $error->add('Invalid '.$custom_field->question, '<strong>'.__('Error','voting-contest').'</strong>: '.$custom_field->required_text);
								}
								}
						   }
						   
						   else{
							//Check the Custom Field File Types
								if($custom_field->question_type == "FILE"){
									
									$supportedFormatfile = explode(',',$custom_field->response);
									$uploadedMeta_file = wp_check_filetype_and_ext($custom_field->system_name, $_FILES[$custom_field->system_name]['name']);
									
									if($custom_fields->response != null) {										
										if(!in_array($uploadedMeta_file['ext'], $supportedFormatfile))
											$error->add('Invalid File Format', '<strong>'.__('Error','voting-contest').'</strong>: '.__('Invalid File Format. (Note: Supported File Formats ','voting-contest').implode($supportedFormatfile, ', ').')');
									}
									else{
										
										$custom_files[$custom_field->system_name]=$_FILES[$custom_field->system_name];  
									}
								}
						   }
						   endif;
						}
					}			
					
					if (count($error->get_error_codes())) {
						$formError = TRUE;
					?>
						<div class="ow_contestants-errors">
							<?php
							foreach ($error->get_error_codes() as $errcode) {
								echo '<div class="error-rows">'.$error->errors($errcode) . '</div>';
							}
							?>
						</div>
					<?php
					}else{ 
						$vote_publishing_type = ($vote_opt['vote_publishing_type'] == 'on')?'publish':OW_DEF_PUBLISHING;
						global $user_ID;
						if($contestant_desc == null){
							$contestant_desc = $contestant_title;
						}
						$args = array(			 
							'post_author' => $user_ID,
							'post_content' => $contestant_desc,
							'post_status' => $vote_publishing_type ,
							'post_type' => OW_VOTES_TYPE,
							'post_title' => $contestant_title
						);
						  
						$cont_details = array('contestant_title' => $contestant_title, 'contestant_desc' => $contestant_desc);
						if($category_options['vote_contest_entry_person']!=''){
							$user_ID = get_current_user_id();
							
							if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') {
								$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
							} else {
								$ip = $_SERVER['REMOTE_ADDR'];
							}
							
							if(is_user_logged_in()){
								$where_query =" where user_id_map='".$user_ID."' and ow_term_id='".$contest_args['id']."'";
								$contestant_author = $user_ID;
							}
							else{
								$where_query =" where ip='".$ip."' and ow_term_id='".$contest_args['id']."'";
								$contestant_author = $ip;
							}
							
							$get_count_track = Ow_Vote_Shortcode_Model::ow_voting_get_post_entry_track($where_query);
							$count_val = count($get_count_track);
							if($count_val>0){
								if($category_options['vote_contest_entry_person'] > $get_count_track[0]->count_post)
								{
									$post_id = wp_insert_post($args);
									$new_count = $get_count_track[0]->count_post+1;
									Ow_Vote_Shortcode_Model::ow_voting_update_post_entry_track($new_count,$get_count_track[0]->id,$contest_args['id']);
									do_action( 'mail_hook_add_contestant', $post_id,$cont_details);
								}
								else{
								  $formError = TRUE;
									?>
									<div class="ow_contestants-errors">
										<div class="error-rows">
											<strong><?php _e('Error:','voting-contest'); ?></strong>
											<?php _e('You Already Submitted ','voting-contest'); ?>
											<?php echo $get_count_track[0]->count_post;?> <?php _e('Entries.','voting-contest'); ?>
										</div>
									</div>
								<?php
								}
							}else{
								Ow_Vote_Shortcode_Model::ow_voting_insert_post_entry_track($user_ID,$ip,$contest_args['id']);
								$post_id = wp_insert_post($args);
								do_action( 'mail_hook_add_contestant', $post_id,$cont_details);
							}
							
							//Added for the Contestant Entered person
							update_post_meta($post_id, '_ow_contestant_author_'.$contest_args['id'], $contestant_author);
							
						}else{
							
							$post_id = wp_insert_post($args);
							do_action( 'mail_hook_add_contestant', $post_id,$cont_details);
							
							if(is_user_logged_in()){
								$user_ID = get_current_user_id();
								$contestant_author = $user_ID;
								//Added for the Contestant Entered person
								update_post_meta($post_id, '_ow_contestant_author_'.$contest_args['id'], $contestant_author);
							}
							
						}
						update_post_meta($post_id, OW_VOTES_CUSTOMFIELD, 0);
						do_action('ow_save_contestant',$post_id,$category_options);
						
						if(!empty($custom_files)){
							$i = 0;
							foreach($custom_files as $key => $files){
								$upload = wp_upload_bits($_FILES[$key]['name'], null, file_get_contents($_FILES[$key]['tmp_name']));
								update_post_meta($post_id, 'ow_custom_attachment_'.$i, $upload);
								$posted_val[$key] = $i;
								$i++;
							}
						}
						
						foreach($posted_val as $key => $values){
							//Updating the Custome Fields in POST META for search Fix in Future -- After Voting Upgrade Module Version
							if($key != 'contestant-title' && $key != 'contestant-desc'){
								update_post_meta($post_id, $key, $values);
							}
						}
						
						$val_serialized = base64_encode(maybe_serialize($posted_val));						
						Ow_Contestant_Model::ow_voting_insert_post_entry($post_id,$val_serialized);
						do_action('ow_save_custom_fields',$post_id,$category_options);
						
						$attach_id = FALSE;
						if($post_id && !is_wp_error( $post_id )) {
							
							if( class_exists(wp_voting_photo_extension) ) {
								do_action('owvoting_crop_image_media',$post_id); 
							} else {
								if($_FILES['contestant-image']['size']) {
									require_once (ABSPATH.'/wp-admin/includes/media.php');
									require_once (ABSPATH.'/wp-admin/includes/file.php');
									require_once (ABSPATH.'/wp-admin/includes/image.php');
									$attach_id = media_handle_upload('contestant-image', $post_id);
								}
								if($attach_id) {							
									set_post_thumbnail($post_id, $attach_id);
								}
							}
							
							wp_set_post_terms( $post_id, $_POST['contest-id'], OW_VOTES_TAXONOMY);
							unset($_POST);						
							
							$contesturl = get_permalink(get_the_ID());
							if(stripos($contesturl, '?')) {
								$contesturl .= '&success=1';
							}
							else {
								$contesturl .= '?success=1';
							}
							ob_end_flush();
							ob_start();
							$contesturl = get_permalink(get_the_ID());
							if(stripos($contesturl, '?')) {
								$contesturl .= '&success='.$id;
							}
							else
							{
								$contesturl .= '?success='.$id;
							}
							
							do_action('ow_voting_contestants_entry',$post_id,$id);
							if($_SESSION['ow_payment'] != 1){
								echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$contesturl.'">';
							}
							$formProcessed = $id;
						}
						else {
							if(!$formError){
							$formError = TRUE;
							?>
							<div class="ow_contestants-errors">
								<div class="error-rows"><strong><?php _e('Error:','voting-contest'); ?></strong><?php _e(' Problem in Saving. Please try it later.','voting-contest'); ?></div>
							</div>
							<?php
							}
						}
						
						
					}
				}
				
				
				if((isset($_GET['success']) && ($_GET['success'] == $id)) || $formProcessed == $id ){
				if($vote_opt['vote_publishing_type'] == 'on'){
				?>
					<div class="ow_contestants-success">
						<div class="success-rows">
							<?php _e(' Contestant Successfully Added.','voting-contest'); ?>
						</div>
					</div>	
				<?php
				}
				else{
				?>
					<div class="ow_contestants-success">
						<div class="success-rows">
							<?php _e(' Contestant Successfully Added. Waiting for Admin Approval.','voting-contest'); ?>
						</div>
					</div>	
				<?php
				}
				}
		
			}			
			
		}
		
		public static function ow_votes_get_adjacent_post_model($in_same_cat, $excluded_categories, $previous){
			global $wpdb;
			if ( ! $post = get_post() )
				return null;
			$current_post_date = $post->post_date;
			$join = '';
			$posts_in_ex_cats_sql = '';
			
			if ( $in_same_cat || ! empty( $excluded_categories ) ) {
			  $join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
			  if ( $in_same_cat ) {
				  $cat_array = $excluded_categories;
				  if ( ! $cat_array || is_wp_error( $cat_array ) )
					  return '';
				  $join .= " AND tt.taxonomy = 'contest_category' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
			  }
			  $posts_in_ex_cats_sql = "AND tt.taxonomy = 'contest_category'";
			  
			}
			$adjacent = $previous ? 'previous' : 'next';
			$op = $previous ? '<' : '>';
			$order = $previous ? 'DESC' : 'ASC';
			$join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
			$where = apply_filters( "get_{$adjacent}_post_where", "WHERE p.ID $op $post->ID AND p.post_type = '".OW_VOTES_TYPE."' AND p.post_status = 'publish' $posts_in_ex_cats_sql", $in_same_cat, $excluded_categories );
			$sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.ID $order LIMIT 1" );
			$query = "SELECT p.ID FROM $wpdb->posts AS p $join $where $sort";
			$query_key = 'adjacent_post_' . md5($query);
		
			$result = wp_cache_get($query_key, 'counts');
			if ( false !== $result ) {
				if ( $result )
					$result = get_post( $result );
				return $result;
			}
			$result = $wpdb->get_var( $query );
			return $result;
		}
		
	}
	
}else
die("<h2>".__('Failed to load Voting Shortcode model')."</h2>");
?>