<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Ajax_Controller')){
    class Ow_Vote_Ajax_Controller{
	
		public function __construct(){
			//Ajax 
			add_action('wp_ajax_ow_votes_contestant_bulk_move', array($this,'ow_votes_contestant_bulk_move'));
			add_action('wp_ajax_ow_vote_update_sequence', array($this,'ow_votes_contestant_update_seq_custom_field'));
			add_action('wp_ajax_ow_vote_user_update_sequence', array($this,'ow_votes_user_update_seq_custom_field'));
			
			add_action("wp_ajax_nopriv_ow_vote_pretty_login", array($this,'ow_votes_user_login'));
			add_action("wp_ajax_ow_vote_pretty_login", array($this,'ow_votes_user_login'));
			
			add_action('wp_ajax_voting_additional_fields_pretty',  array($this,'ow_voting_additional_fields_pretty'));
			add_action('wp_ajax_nopriv_voting_additional_fields_pretty',  array($this,'ow_voting_additional_fields_pretty'));
			
			add_action("wp_ajax_nopriv_ow_facebook_login", array($this,'ow_votes_user_facebook_login'));
			add_action("wp_ajax_ow_facebook_login", array($this,'ow_votes_user_facebook_login'));
			
			add_action("wp_ajax_nopriv_ow_twitter_login", array($this,'ow_votes_user_twitter_login'));
			add_action("wp_ajax_ow_twitter_login", array($this,'ow_votes_user_twitter_login'));
			
			add_action('wp_ajax_nopriv_voting_save_twemail_session', array($this,'ow_voting_save_twitter_in_session'));
			add_action('wp_ajax_voting_save_twemail_session', array($this,'ow_voting_save_twitter_in_session'));
			
			add_action('wp_ajax_nopriv_voting_email_verification', array($this,'ow_voting_voting_email_verification'));
			add_action('wp_ajax_voting_email_verification', array($this,'ow_voting_voting_email_verification'));			
						
			add_action('wp_ajax_nopriv_voting_email_code', array($this,'ow_voting_email_verification_code'));
			add_action('wp_ajax_voting_email_code', array($this,'ow_voting_email_verification_code'));						
			
			add_action('wp_ajax_nopriv_ow_voting_grab_email', array($this,'ow_voting_grab_email'));
			add_action('wp_ajax_ow_voting_grab_email', array($this,'ow_voting_grab_email'));			
			
			add_action('wp_ajax_nopriv_voting_load_more', array($this,'ow_voting_load_more'));
			add_action('wp_ajax_voting_load_more', array($this,'ow_voting_load_more'));			
			
			add_action('wp_ajax_nopriv_voting_getterm', array($this,'ow_voting_getterm'));
			add_action('wp_ajax_voting_getterm', array($this,'ow_voting_getterm'));
			
			add_action( 'ow_vote_twitter_auth_hook', array($this,'ow_vote_twitter_auth_hook'), 10, 1 );
			
			add_action( 'wp_ajax_ow_render_accordion',array($this,'ow_render_accordion_ajax')  );
			add_action( 'wp_ajax_nopriv_ow_render_accordion', array($this,'ow_render_accordion_ajax') );			
			
			add_action( 'wp_ajax_ow_render_search',array($this,'ow_render_search_ajax')  );
			add_action( 'wp_ajax_nopriv_ow_render_search', array($this,'ow_render_search_ajax') );			
			
			add_action( 'wp_ajax_owtotalvotes',array($this,'ow_voting_total_votes')  );
			add_action( 'wp_ajax_nopriv_owtotalvotes', array($this,'ow_voting_total_votes') );			
			
			//Save the File URL using the Hidden - ow_save_file_url
			add_action('wp_ajax_ow_save_file_url', array($this,'ow_save_file_url'));
			add_action('wp_ajax_nopriv_ow_save_file_url', array($this,'ow_save_file_url'));
			
			add_action('wp_ajax_ow_file_ajax_function', array($this,'ow_load_Fileajax_Uploader'));
			add_action('wp_ajax_nopriv_ow_file_ajax_function', array($this,'ow_load_Fileajax_Uploader'));
			
		}
		
		public function ow_save_file_url(){		
			if($_POST['action'] == 'ow_save_file_url'){
				$upload_path = wp_upload_dir();
				
				$post_id = wp_insert_post(array (
							'post_type' => 'attachment',
							'post_title' => $_POST['post_title'],						
							'post_status' => 'inherit',
							'comment_status' => 'closed',   
							'ping_status' => 'closed',
							'guid' => $_POST['guid'],
							'post_mime_type' => $_POST['mime']
						));
				update_post_meta($post_id,'_wp_attached_file',$upload_path['subdir'].'/'.$_POST['post_title']);
				
				echo Ow_Vote_Common_Controller::ow_voting_encrypt($post_id);
			}
			exit;
		}
		
		public function ow_load_Fileajax_Uploader(){		
			require_once(OW_CONTROLLER_PATH.'ow_uploader.php');		
			$upload_handler = new ow_UploadHandler();
			die(); 		
		}
				
		public function ow_voting_save_twitter_in_session(){
			$email = sanitize_email( $_POST['user_login'] );
			if(!is_email($email)){
			   echo 0;
			}
			else{
			   $_SESSION['twitter_saved_email'] = $email;
			   echo 1;
			}
			exit;
		}
		
		public function ow_voting_voting_email_verification(){
			$email = sanitize_email( $_POST['ow_voting_email'] );
			if(!is_email($email)){
			   echo 0;
			}
			else{
			   $_SESSION['votes_current_email'] = $email;			   
			   $_SESSION['votes_random_string'] = mt_rand(100000,999999);
			   require_once(OW_CONTROLLER_PATH.'ow_vote_shortcode_controller.php');
			   Ow_Vote_Shortcode_Controller::ow_votes_verification_mail_function($_SESSION['votes_random_string'],$email);
			   echo $_SESSION['votes_random_string'];
			}
			exit;
		}
		
		public function ow_voting_email_verification_code(){
			$ver_code = htmlentities( $_POST['ow_voting_verifcation_code'] );
			if($ver_code == $_SESSION['votes_random_string']){
			   $_SESSION['verified_votes_random_string'] = $_SESSION['votes_random_string'];
			   echo 1;
			}
			else{
				$_SESSION['verified_votes_random_string'] = '';
			   echo 0;		   
			}
			exit;	
		}
		
		//Grab Email for IP and COOKIE
		public function ow_voting_grab_email(){
			$email = sanitize_email( $_POST['ow_voting_email_grab'] );
			if(!is_email($email)){
			   echo 0;
			}
			else{
			   $_SESSION['ow_voting_email_grab'] = $email;
			   echo 1;
			}
			exit;
		} 
		
		public function ow_votes_user_twitter_login(){
			if(isset($_POST)){
				$votes_settings = $_POST;        
			}
			include_once OW_CONTROLLER_PATH.'ow_twitter_controller.php';
			$connection = new Ow_Twitter_Controller($votes_settings['vote_tw_appid'] , $votes_settings['vote_tw_secret'] );
			$request_token = $connection->getRequestToken($votes_settings['current_callback_url']);        
			//received token info from twitter
			$_SESSION['token'] 			= $request_token['oauth_token'];
			$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
			$_SESSION['current_callback_url'] 	= $votes_settings['current_callback_url'];
			
			if($connection->http_code=='200'){
			   //redirect user to twitter
			   $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
			   echo $twitter_url;    	   
			   exit;
			}else{
				echo 1;
				exit;
			}
		}
		
		public function ow_vote_twitter_auth_hook(){
			if(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) 
			{
				// everything looks good, request access token
				//successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
				include_once OW_CONTROLLER_PATH.'ow_twitter_controller.php';
				$connection = new Ow_Twitter_Controller($votes_settings['vote_tw_appid'] , $votes_settings['vote_tw_secret'],$_SESSION['token'] , $_SESSION['token_secret'] );
				$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);           
				
				if($connection->http_code=='200'){
					//redirect user to twitter
					$_SESSION['status'] = 'verified';
					$_SESSION['request_vars'] = $access_token;
					  
				   // unset no longer needed request tokens
					unset($_SESSION['token']);
					unset($_SESSION['token_secret']);                          
					
					$screenname 	= $_SESSION['request_vars']['screen_name'];            
					$content = $connection->get('users/show', array('screen_name' => $screenname));        
					
					if(!empty($screenname)) {
					  $username   = $screenname;
					  $user_login = sanitize_user($username, true);
					}   
					$twitter_saved_email = $_SESSION['twitter_saved_email'];
					if (($user_id_tmp = email_exists ($twitter_saved_email)) !== false) {              
						$user_data = get_userdata ($user_id_tmp); 
									 
						if ($user_data !== false) {  
						  $user_id = $user_data->ID;
						  $user_login = $user_data->user_login;                  
						  wp_clear_auth_cookie ();
						  wp_set_auth_cookie ($user_data->ID, true);
						  do_action ('wp_login', $user_data->user_login, $user_data);
						  wp_redirect($_SESSION['current_callback_url']);
						  exit;
						}
						
						
					}else{
						$new_user = true;
						$user_login_cus = Ow_Vote_Ajax_Controller::ow_voting_usernameexists($user_login);
						$user_password = wp_generate_password ();
						$user_role = get_option('default_role');
						$user_data = array (
										  'user_login' => $user_login_cus,
										  'display_name' => $user_login_cus,	
										  'user_email' => $twitter_saved_email,					
										  'user_pass' => $user_password,
										  'first_name' => $content->name,							
										  'role' => $user_role,
										  'description' => $content->description,
									  );                            
									  
						$user_id = wp_insert_user ($user_data); 
					}
				}else{
					//_e('Error, Try again later!','voting-contest');
				}
			}
		}
		
		public function ow_votes_user_facebook_login(){
			$fbdata = $_POST['responses'];
           
			if(!empty($fbdata['name'])) {
			  $username = $fbdata['name'];
			}
			else if (!empty($fbdata['first_name']) && !empty($fbdata['last_name'])) {
			  $username = $fbdata['first_name'].$fbdata['last_name'];
			}
			else {
			  $user_emailname = explode('@', $fbdata['email']);
			  $username = $user_emailname[0];
			}
			
			$user_login = sanitize_user($username, true);
          
			if (($user_id_tmp = email_exists ($fbdata['email'])) !== false) {
				$user_data = get_userdata ($user_id_tmp);
				if ($user_data !== false) {
				  $user_id = $user_data->ID;
				  $user_login = $user_data->user_login;                             
				  wp_clear_auth_cookie ();
				  wp_set_auth_cookie ($user_data->ID, true);
				  do_action ('wp_login', $user_data->user_login, $user_data);
				}
			}
			else 
			{
    		  $new_user = true;
              $user_login = Ow_Vote_Ajax_Controller::ow_voting_usernameexists($user_login);
              $user_password = wp_generate_password ();
    		  $user_role = get_option('default_role');
              $user_data = array (
    							'user_login' => $user_login,
    							'display_name' => (!empty ($fbdata['name']) ? $fbdata['name'] : $user_login),
    							'user_email' => $fbdata['email'],
    							'first_name' => $fbdata['first_name'],
    							'last_name' => $fbdata['last_name'],
    							'user_url' => $fbdata['website'],
    							'user_pass' => $user_password,
    							'description' => $fbdata['aboutme'],
    			                'role' => $user_role
    						);                            
                            
               $user_id = wp_insert_user ($user_data);           
            }
			exit;
		}
		
		
		public static function ow_voting_usernameexists($username) {
			$nameexists = true;
			$index = 0;
			$userName = $username;
			while($nameexists == true){
			  if (username_exists($userName) != 0) {
				$index++;
				$userName = $username.$index;
			  }
			  else {
				$nameexists = false;
			  }
			}
			return $userName;
		}

		public function ow_votes_contestant_bulk_move(){
			$out = '';
			$term_id = $_GET['term_id'];
			$postargs = array(
				'post_type' => OW_VOTES_TYPE,
				'post_status' => 'publish',
				'tax_query' => array(
					array('taxonomy' => OW_VOTES_TAXONOMY,
					'field' => 'id',
					'terms' => $term_id,
					'include_children' => false)
				),
				'nopaging' => true
			);
		
			$contest_post = new WP_Query($postargs);
			if ($contest_post->post_count > 0) {
				$out.='<input type="checkbox" value="0" class="select_all_post" id="select_all_post">&nbsp;&nbsp;<b> '.__('Select All','voting-contest').'</b><br/>';
				while ($contest_post->have_posts()) {
					$contest_post->the_post();
					$out .= '<input type="checkbox" class="selected-post" name="selected_post[]" value="' . get_the_ID() . '"/>&nbsp;&nbsp;' . get_the_title() . '<br/>';
				}
			} else {
				$out .= 'No Post Found';
			}
			?>
			<script>
			jQuery('.select_all_post').on('click', function(){
				if(jQuery('.select_all_post').attr('checked'))
				jQuery('.selected-post').attr('checked',' checked');
				else
				jQuery('.selected-post').removeAttr('checked');
			});
			</script>
			<?php
			echo $out;
			exit;
		}
	
		public function ow_votes_user_update_seq_custom_field()
		{
			$rows = explode(",", $_POST['row_ids']);
			for ($i = 0; $i < count($rows); $i++) {
				Ow_Custom_Field_Model::ow_custom_field_user_update_sequence($i,$rows[$i]);
			}
			die();
		}
		
		public function ow_votes_contestant_update_seq_custom_field()
		{
			$rows = explode(",", $_POST['row_ids']);
			for ($i = 1; $i < count($rows); $i++) {
				Ow_Custom_Field_Model::ow_custom_field_update_sequence($i,$rows[$i]);
			}
			die();
		}
		
		//Custom Fields on registration
		public static function ow_votes_register_extra_fields($user_id){
			if ( ! is_super_admin() ) {
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields(1);
			}else{
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields();
			}
			
			$error = new WP_Error();
			if(!empty($custom_field)){
				$posted_val=array();
				foreach($custom_field as $custom_fields){
				   $posted_val[$custom_fields->system_name]=$_POST[$custom_fields->system_name];  
				}
			}
			
			$val_serialized = serialize($posted_val);
			$registered_entries = Ow_Vote_Shortcode_Model::ow_votes_user_entry_table($user_id);
			
			if(!empty($registered_entries))
				Ow_Vote_Shortcode_Model::ow_votes_user_entry_update_table($val_serialized,$user_id);
			else{
				Ow_Vote_Shortcode_Model::ow_votes_user_entry_insert_table($val_serialized,$user_id);
				if (!is_user_logged_in()) {
					//determine WordPress user account to impersonate
					$user_login = 'guest';
				   //get user's ID
					$user = get_user_by($user_login);
					//login
					wp_set_current_user($user_id, $user_login);
					wp_set_auth_cookie($user_id);
					do_action('wp_login', $user_login);
				}
			}
			
		}
		
		//Validate the custom fields
		public static function ow_votes_registration_errors($errors, $sanitized_user_login=NULL){
			if ( ! is_super_admin() ) {
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields(1);
			}else{
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields();
			}
			
			$error = new WP_Error();
			if(!empty($custom_field)){
				   $posted_val=array();
				   foreach($custom_field as $custom_fields){
						  $posted_val[$custom_fields->system_name]=$_POST[$custom_fields->system_name];  
						  if($custom_fields->required=='Y'){ 
							   if($_POST[$custom_fields->system_name]==''){
								   $req_text = ($custom_fields->required_text!='')?$custom_fields->required_text:$custom_fields->question." Field required";
								  $errors->add('Invalid '.$custom_fields->question, '<strong>Error</strong> : '.$req_text);                                                 
							   }
						  }					  
				   }
			   }            
		   return $errors;
		}
		
		public static function ow_votes_registration_errors_front_end($errors, $sanitized_user_login=NULL){
			if ( ! is_super_admin() ) {
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields(1);
			}else{
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields();
			}
			
			$error = new WP_Error();
			if(!empty($custom_field)){
				   $posted_val=array();
				   foreach($custom_field as $custom_fields){
						if($custom_fields->admin_only == "Y"){ 
						  $posted_val[$custom_fields->system_name]=$_POST[$custom_fields->system_name];  
						  if($custom_fields->required=='Y'){ 
							   if($_POST[$custom_fields->system_name]==''){
								   $req_text = ($custom_fields->required_text!='')?$custom_fields->required_text:$custom_fields->question." Field required";
								  $errors->add('Invalid '.$custom_fields->question, '<strong>Error</strong> : '.$req_text);                                                 
							   }
						  }
						}
					  
				   }
			   }            
		   return $errors;
		}
		
		//User metaboxs add to profiles
		public static function ow_voting_modify_contact_methods($user){
			if ( ! is_super_admin() ) {
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields(1);
			}else{
				$custom_field = Ow_Vote_Shortcode_Model::ow_voting_user_get_custom_fields();
			}
			
			$registered_entries = Ow_Vote_Shortcode_Model::ow_votes_user_entry_table($user->ID);
			if(!empty($registered_entries)){
				if(base64_decode($registered_entries[0]->field_values, true))
				   $registration = unserialize(base64_decode($registered_entries[0]->field_values));  
				else
				   $registration = unserialize($registered_entries[0]->field_values);  
			}else{
				$registration=array();
			}
			
			require_once(OW_VIEW_PATH.'ow_user_metabox_view.php');
			ow_user_metabox_view($custom_field,$registration);
	
		}
		
		public static function ow_votes_delete_user_custom_entry($user_id){
			Ow_Vote_Shortcode_Model::ow_votes_delete_user_entry_table($user_id);
		}
		
		public function ow_votes_user_login(){
			if($_SERVER[ 'HTTP_X_REQUESTED_WITH' ]=='XMLHttpRequest'){
				if ( $_POST['zn_form_action'] == 'login' ) {
					$user = wp_signon();
					if ( is_wp_error($user) ) {
					   echo '1'.'~~'.'<div class="login_error">'.$user->get_error_message().'</div>';
					   die();
					}
					else{
						echo '0'.'~~'.'success';
						die();
					}
				}
				elseif( $_POST['zn_form_action'] == 'register' ){
					$zn_error = false;
					$zn_error_message = array();
					
					if ( !empty( $_POST['user_login'] ) ) {
						if ( username_exists( $_POST['user_login'] ) ){	
							$zn_error = true;
							$zn_error_message[] = __('The username already exists','voting-contest');
						}
						else {
							$username = $_POST['user_login'];
						}
					}
					else {
						$zn_error = true;
						$zn_error_message[] = __('Please enter an username','voting-contest');
					}
		
					if ( !empty( $_POST['user_password'] ) ) {
						$password = $_POST['user_password'];
					}
					else {
						$zn_error = true;
						$zn_error_message[] = __('Please enter a password','voting-contest');
					}
		
					if ( ( empty( $_POST['user_password'] ) && empty( $_POST['user_password2'] ) ) || $_POST['user_password'] != $_POST['user_password2'] ) {
						$zn_error = true;
						$zn_error_message[] = __('Passwords do not match','voting-contest');
					}
					
					if ( !empty( $_POST['user_email'] ) ) {
						if( !email_exists( $_POST['user_email'] )) {
							if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
								$zn_error = true;
								$zn_error_message[] = __('Please enter a valid EMAIL address','voting-contest');
							}
							else{
								$email = $_POST['user_email'];
							}
						}
						else {
							$zn_error = true;
							$zn_error_message[] = __('This email address has already been used','voting-contest');
						}
					}
					else {
						$zn_error = true;
						$zn_error_message[] = __('Please enter an email address','voting-contest');
					}
					
					
					if ( $zn_error ){
						echo '1'.'~~'.'<div class="login_error">';
						foreach ( $zn_error_message as $error) {
							echo $error.'<br />';
						}
						echo '</div>';
						die();
					}
					else {
						
						$user_data = array(
							'ID' => '',
							'user_pass' => $password,
							'user_login' => $username,
							'display_name' => $username,
							'user_email' => $email,
							'role' => get_option('default_role') // Use default role or another role, e.g. 'editor'
							);
						
						
						
						$user_id = wp_insert_user( $user_data );
						
						do_action('owt_register_form_hook',$user_id);
						
						wp_new_user_notification( $user_id, $password );
						echo '0'.'~~'.'<div class="login_error">'.__('Your account has been created.','voting-contest').'</div>';
						die();
					}
				}
				elseif( $_POST['zn_form_action'] == 'reset_pass' ){
					echo do_action('login_form', 'resetpass');
				}
			}else{
				wp_redirect( home_url() );
				die();	
			}
		}
		
		public function ow_voting_additional_fields_pretty($post_id = null){
			$vote_id = ($post_id == null)?$_POST['pid']:$post_id;
			ob_start();
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_pretty_custom_field_values_view.php');
			ow_voting_pretty_custom_values($vote_id);
			$out = ob_get_clean();
			if(isset($_POST['pid']))
				die($out);
			else
				return $out;
		}
		
		public function ow_render_accordion_ajax(){			
			if(isset($_POST['color'])){
			    $coloroption = get_option(OW_VOTES_COLORSETTINGS);			    
			    $option = $coloroption[$_POST['color']];			    
			}
			require_once(OW_VIEW_PATH.'ow_common_settings_view.php');
			echo ow_render_accordion($option);
			exit;
		}
		
		
		public function ow_voting_load_more(){
			ob_start();			
			$category_options = unserialize(base64_decode($_POST['category_option']));
			$show_cont_args = unserialize(base64_decode($_POST['show_cont_args']));
			$global_options = unserialize(base64_decode($_POST['global_options']));
			
			if($_POST['ow_ajax_flag'] == 1){		
				require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_all_showcontest_view.php'); 
				echo ow_voting_shortcode_all_showcontest_view($show_cont_args,$global_options,1,$_POST['cat_id'],$_POST['ow_search']);
				exit;				
			}
			else{
				$remaining = Ow_Vote_Shortcode_Model::ow_get_show_contest_query($show_cont_args,1);				
				require_once(OW_VIEW_FRONT_PATH.'ow_voting_shortcode_showcontest_ajax_view.php');	
				echo ow_voting_shortcode_showcontest_ajax_view($remaining,$category_options,$_POST['cat_id'],$show_cont_args,$global_options);			
			}		
			exit;
		}
		
		//Get the Contest category Options such as imgenable and imgrequired
		public function ow_voting_getterm(){
			ob_start();
			$category_options = get_option($_POST['term_id']. '_' . OW_VOTES_SETTINGS);
			$imgrequired = $category_options['imgrequired'];
			$imgenable   = $category_options['imgenable'];
			echo json_encode(array('imgrequired' => $imgrequired,'imgenable'=>$imgenable));
			exit;
		}
		
		public function ow_sanitize_output($buffer) {

			$search = array(
			    '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			    '/[^\S ]+\</s',  // strip whitespaces before tags, except space
			    '/(\s)+/s'       // shorten multiple whitespace sequences
			);
		    
			$replace = array(
			    '>',
			    '<',
			    '\\1'
			);
		    
			$buffer = preg_replace($search, $replace, $buffer);			
			return $buffer;
		}
		
		public function ow_render_search_ajax(){
			$term = strtolower( $_GET['term'] );
			$suggestions = array();
			$postargs = array(
				'post_type' => OW_VOTES_TYPE,
				'post_status' => 'publish',
				's' => $term,
			);
			$loop = new WP_Query( $postargs );
			
			while( $loop->have_posts() ) {
				$loop->the_post();
				$post_id = get_the_ID();
				$suggestion = array();
				$suggestion['label'] = get_the_title();
				$suggestion['link']  = Ow_Vote_Common_Controller::ow_votes_get_contestant_link($post_id);
				if (has_post_thumbnail($post_id)) {
					$image 				 = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );
					$suggestion['icon']  = 	$image[0];
				}
				else{
					$suggestion['icon']  = 	OW_NO_IMAGE_CONTEST;
				}
				$suggestions[] = $suggestion;
			}
			
			wp_reset_query();		
			
			$response = json_encode( $suggestions );
			echo $response;
			exit();
		}
		
		public function ow_votes_update_contestant_field($post_id,$system_name,$value){
			
			$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries($post_id);
			if(!empty($custom_entries)){ 
				$field_values = $custom_entries[0]->field_values;
				if(base64_decode($field_values, true))
					$field_val = maybe_unserialize(base64_decode($field_values));  
				else
					$field_val = maybe_unserialize($field_values);
					
				foreach($field_val as $key => $fields)	{
					
					if($key == $system_name){
						$posted_val[$key] = $value;
					}else{
						$posted_val[$key] = $fields;
					}					
					
				}
				
				$val_serialized = base64_encode(maybe_serialize($posted_val)); 
				Ow_Contestant_Model::ow_voting_contestant_update_field($val_serialized,$post_id);
			
			}
						
			//Update in the Post meta table
			update_post_meta($post_id,$system_name,$value);
			
		}
		
		
		public static function ow_voting_total_votes(){			
			$total_votes = Ow_Contestant_Model::ow_total_votes();
			echo "<span class='ow_total_counter'>".$total_votes."</span>";
			exit;
		}
		
    }
}else
die("<h2>".__('Failed to load Voting Ajax Controller','voting-contest')."</h2>");


return new Ow_Vote_Ajax_Controller();
