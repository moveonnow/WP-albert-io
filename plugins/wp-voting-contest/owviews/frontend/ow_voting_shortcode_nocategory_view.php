<?php
if(!function_exists('ow_voting_nocategory_form_view')){
    function ow_voting_nocategory_form_view($show_cont_args,$vote_opt,$custom_fields,$unblocked_terms){		
		
		$global_options = Ow_Vote_Common_Controller::ow_vote_get_all_global_settings($vote_opt); 
		if(!empty($global_options)){
			foreach($global_options as $variab => $glob_opt){
				$$variab = $glob_opt;
			}
		}
		
		if(!empty($show_cont_args)){
			foreach($show_cont_args as $args => $opt_glob){
				$$args = $opt_glob;
			}
		}
		$permalink = get_permalink( get_the_ID());
		
		if (false !== strpos($permalink,'?')){
			$url_prefix = '&amp;';
		}
		else{
			$url_prefix = '?';
		}
		
		if(isset($_GET['contest']))
		    $action_url  = $_GET['contest'];
		else
		    $action_url  = '';
		
		if(isset($_GET['contest_id']))
		    $contest_id = base64_decode($_GET['contest_id']);
			
		extract( shortcode_atts( array(
		   'id' => NULL,
		   'showcontestants' => 0,
		   'message' => 1,
		   'contestshowfrm'=>1,
		   'displayform' =>0,
		   'loggeduser'=>$vote_onlyloggedcansubmit,
		   'showrules' => 1,
		), $show_cont_args ));
		
		$show_form = 'showform=1';
		
		if($displayform == 0){	
		    if($check_status){
			    if($message) {
				    ?>
				    <div class="warning activation-warning constestants-warning"><?php echo $check_status; ?></div>
				    <?php
				    $show_form = 'showform=0';
				    if(!$showcontestants)
				    return;
			    }
		    }
		}	
		
		if($showcontestants){
			echo do_shortcode('[showcontestants id="'.$id.'" forcedisplay=1 showtimer=0 '.$show_form.' hideerrorcont=1 ]'); 
			return;  
		}

		$image_contest = $votes_start_time='';
		if($category_options['imgcontest']!='')
			$image_contest = $category_options['imgcontest'];
		
		if($id!='')
			$votes_start_time=get_option($id . '_' . OW_VOTES_TAXSTARTTIME);
		
		$votes_end_time  = get_option($id. '_' . OW_VOTES_TAXEXPIRATIONFIELD);
		$current_time = current_time( 'timestamp', 0 );
				
		    
		if($votes_start_time!='' && strtotime($votes_start_time) < $current_time && $displayform != 1 ){			
			return;
		}
		
		if(!isset($_SESSION['GET_VIEW_SHORTCODE'])){
			$_SESSION['GET_VIEW_SHORTCODE']=1;
		}
		else{
			$_SESSION['GET_VIEW_SHORTCODE']=$_SESSION['GET_VIEW_SHORTCODE']+1;
		}
		
		if($vote_onlyloggedcansubmit!='' && !is_user_logged_in()){
			$login_class="ow_logged_in_enabled";
			if($_SESSION['GET_VIEW_SHORTCODE']==1)
			Ow_Vote_Shortcode_Controller::ow_votes_custom_registration_fields_show();
		}
		else{$login_class="loggin_disabled";}
		
		?>
		<div class="ow_vote_add_contestants">
			
			<?php if($contestshowfrm !=0 && $global_options['vote_entry_form'] != 1){?>
				<div class="ow_vote_contest_top_bar">
					<ul class="ow_vote_menu_links">
						<?php $check_no_border = ($category_options['vote_contest_rules']!='')?'':'class="ow_vote_no_border"'; ?>
						<li <?php echo $check_no_border; ?>>
							<a class="ow_vote_navmenu_link <?php echo $login_class; ?> ow_vote_submit_entry" data-id="<?php echo $id; ?>">
								<span class="ow_vote_icons voteconestant-edit" aria-hidden="true"></span>
								<?php _e('Submit Entry','voting-contest'); ?>
							</a>
							<input type="hidden" name="open_button_text" class="open_btn_text<?php echo $id; ?>"
								value="<span class='ow_vote_icons voteconestant-edit' aria-hidden='true'></span>
									   <?php _e('Submit Entry','voting-contest'); ?>"/>
							<input type="hidden" name="close_button_text"  class="close_btn_text<?php echo $id; ?>"
															value="<?php _e('Close','voting-contest'); ?>"/>
						</li>						
					
					</ul>
				</div>
			<?php } ?>
			
			<?php
			    //Flag Variable if Option for Entry Form is set to Must Login & Open State
			      if($global_options['vote_entry_form'] == null && $login_class =='ow_logged_in_enabled' && !is_user_logged_in()){ ?>
				<input type="hidden" name="ow_open_login_form" class="ow_open_login_form" value="1" />				
			<?php } ?>
			
			<script>
			jQuery(document).ready(function(){
				add_contestant_validation("<?php echo $id; ?>","<?php _e('Enter the contestant title','voting-contest'); ?>"); 
				add_contestant_validation_method("ow_select_term","<?php _e('Select the Contest Category','voting-contest'); ?>");
			});
			</script>
			
			<?php
			//Global Settings OPen/close Option for Entry Form
			$displayform_css = ($global_options['vote_entry_form'] == 'on')?'display: none;':'display: block;';		
			?>			
				
						
			
			<form style="<?php echo $displayform_css; ?>" class="ow_form_add-contestants<?php echo $id; ?> add_form_contestant_ow_vote" name="add-contestants" action="<?php echo get_permalink(get_the_ID()); ?>" method="post" enctype="multipart/form-data">
				
				
				<div class="ow_add_contestants_row">
					<div class="ow_add_contestants_label">
						<label><?php _e('Select category','voting-contest'); ?>  <span class="required-mark">*</span></label>
					</div>
					<div class="ow_add_contestants_field">
						<select name="ow_select_term" id="ow_select_term">
							<option value=""><?php _e('Select category','voting-contest'); ?></option>
							<?php foreach($unblocked_terms as $id => $term): ?>
								<?php $category_options = get_option($id. '_' . OW_VOTES_SETTINGS); ?>
								<option value="<?php echo $id; ?>" data-val="<?php echo $category_options['imgcontest']; ?>"><?php echo $term; ?></option>
							<?php endforeach; ?>
						</select>						
					</div>
				</div>
				
				
				<?php $title_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_title(); ?>
				<div class="ow_add_contestants_row">
					<div class="ow_add_contestants_label">
						<label><?php echo $title_rs[0]->question; ?>  <span class="required-mark">*</span></label>
					</div>
					<div class="ow_add_contestants_field">
						<input type="text" name="contestant-title" value="<?php echo isset($_POST['contestant-title'])?$_POST['contestant-title']: ''; ?>"/>
					</div>
				</div>
				
				<?php                  
				$desc_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_desc();
				
				//Check if it is made visible in the admin end
				if($desc_rs[0]->admin_only == "Y"){            
					$required_desc = ($desc_rs[0]->required == "Y")?"*":'';
				?>
				
				<div class="ow_add_contestants_row">                
					<div class="ow_add_contestants_label">
						<label><?php echo $desc_rs[0]->question; ?>  <span class="required-mark"><?php echo $required_desc; ?></span></label>
					</div>
					<div class="ow_add_contestants_field_desc">
						<?php
						if(user_can_richedit()) {
							$desc_val = isset($_POST['contestant-desc'])?$_POST['contestant-desc']: '';
							$settings = array('media_buttons' => FALSE,'textarea_rows' => 2,'tinymce' => false);
							wp_editor($desc_val, 'contestant-desc'.$id, $settings);
						}
						else {
						?>
							<textarea style="width:100%;" id="contestant-desc<?php echo $id; ?>" name="contestant-desc" class="contestant-desc"><?php echo isset($_POST['contestant-desc'])?$_POST['contestant-desc']: ''; ?></textarea>
						<?php
						}
						?>
						<?php if($desc_rs[0]->required == "Y"){ ?>
						<script>
							jQuery(document).ready(function(){
							add_contestant_validation_method("<?php echo 'contestant-desc'.$id; ?>","<?php _e('Enter the contestant description','voting-contest'); ?>");
							});
						</script>
						<?php } ?>
					</div>
				</div>				
				<?php } ?>
				
				
				<?php
				$imgenable=$category_options['imgenable'];
				$imgrequired=$category_options['imgrequired']; ?>
					<?php 
						if($image_contest == 'photo')
							$imgrequired='on';
					?>
					<div class="ow_add_contestants_row" id="ow_img_id" style="display: none;">
						<div class="ow_add_contestants_label">
							<label><?php _e('Image  ','voting-contest'); ?><span class="required-mark img_required_span">*</span></label>
						</div>
						<div class="ow_add_contestants_field">
							<input type="file" id="contestant-image"  name="contestant-image" class="contestant-input" />
							<img style="display: none;" id="uploaded_img" src="" class="ow_uploaded_image"/>
						</div>
					</div>
					
										
				
				
				 <!-- Custom Fields -->
				  <?php
					if(!empty($custom_fields)){
						foreach($custom_fields as $custom_field){
							if($custom_field->system_name != 'contestant-desc' && $custom_field->system_name != 'contestant-title'){
							    
								if($custom_field->system_name == 'contestant-ow_video_url'){
								   //Show Video Url for Video Contest Only 
								  $display_none = 'style="display: none;"';
								  $class = "video_music_only";
								}else{
								  $display_none = 'style="display: block;"';
								  $class = "";
								}
							   	
								if($custom_field->admin_only == "Y"){  
								?>
								<div class="ow_add_contestants_row <?php echo $class; ?>" <?php echo $display_none; ?>>
                
									<div class="ow_add_contestants_label">
										<label>
										<?php 
										if($custom_field->question_type=='TEXT' || $custom_field->question_type=='TEXTAREA'){
											echo ''.$custom_field->question;
										}else{
										   echo ''.$custom_field->question; 
										}
										?>
										<?php if($custom_field->required=='Y'){?>
										    <?php if($custom_field->question_type=='FILE'){?>
											<span class="required-mark">*</span>
											  <script type="text/javascript">
												jQuery(document).ready(function($) {
													add_contestant_validation_method_file("<?php echo $custom_field->system_name.$id; ?>","<?php echo ($custom_field->required_text)?$custom_field->required_text:"Please upload the file"; ?>","<?php echo $custom_field->ow_file_size; ?>");
												});
											  </script>
										    <?php }else{ ?>
											  <span class="required-mark">*</span>
											  <script type="text/javascript">
												jQuery(document).ready(function($) {
													add_contestant_validation_method("<?php echo $custom_field->system_name.$id; ?>","<?php echo ($custom_field->required_text)?$custom_field->required_text:"This Field is required"; ?>");
												});
											  </script>
										    <?php } ?>
										<?php } ?>
										</label>
									</div>
									
									<div class="ow_add_contestants_field">
									<?php
									switch($custom_field->question_type){
										
										case 'TEXT':
											?>
											<input type="<?php echo $custom_field->question_type; ?>" id="<?php echo $custom_field->system_name.$id; ?>" name="<?php echo $custom_field->system_name; ?>" />
											<?php if($custom_field->required=='Y'){?>
											<?php
											}
										break;
									
										case 'TEXTAREA':
											?>
											<textarea rows="1" id="<?php echo $custom_field->system_name.$id; ?>"
													name="<?php echo $custom_field->system_name; ?>" ></textarea>
										<?php
										break;
									
										case 'SINGLE':
											$single_values = explode(',',$custom_field->response);
											if(!empty($single_values)){
											foreach($single_values as $single_val){ ?>
											 <span id="add_contestant_radio"> 
											 <input class="ow_stt_float" type="radio" name="<?php echo $custom_field->system_name; ?>[]"
													value="<?php echo $single_val; ?>" id="<?php echo $custom_field->system_name.$id; ?>" />
													<label><?php echo $single_val; ?></label>
											 </span>
											<?php }
											}
										break;
									
										case 'MULTIPLE':
											$multiple_values = explode(',',$custom_field->response);
											if(!empty($multiple_values)){
											foreach($multiple_values as $multi_val){ ?>
											 <span id="add_contestant_radio"> 
											 <input class="ow_stt_float" type="checkbox" name="<?php echo $custom_field->system_name; ?>[]"
													value="<?php echo $multi_val; ?>" id="<?php echo $custom_field->system_name.$id; ?>" />
													<span class="ow_stt_float"><?php echo $multi_val; ?></span>
											 </span>
											<?php
												}
											}
										break;
									
										case 'DROPDOWN':
											$drop_values = explode(',',$custom_field->response);
											?>
											<select name="<?php echo $custom_field->system_name; ?>" id="<?php echo $custom_field->system_name.$id; ?>">
											<option value="">Select</option>
											<?php foreach($drop_values as $val){ ?>
												  <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
											<?php } ?>
											</select>
									<?php
										break;
									    
										case 'FILE':
											$drop_values = explode(',',$custom_field->response);											    $allowed_filetypes = ($custom_field->response == null)?__("All","voting-contest"):$custom_field->response;	
											?>
											<input type="file" name="<?php echo $custom_field->system_name; ?>" id="<?php echo $custom_field->system_name.$id; ?>" />
											<span class='ow_allowed_file'><?php echo __('Allowed File Types : ','voting-contest').$allowed_filetypes; ?></span>
									<?php
										break;
									    
										case 'DATE':
											?>
											<input type="text"
											id="<?php echo $custom_field->system_name.$id; ?>" name="<?php echo $custom_field->system_name; ?>" class="date_picker" />
											<?php if($custom_field->required=='Y'){?>
											<?php
											}
										break;
									
									}
									?>
									</div>
								</div>
							<?php
								}
							}
						}
					}
				 ?>
				 
			<script>
			jQuery(document).ready(function(){
			    var valid = true;
			    
				jQuery('.date_picker').owvotedatetimepicker({
				    format:'m-d-Y',
				    step:10,
				    timepicker: false,
				});
			    		
				jQuery(".ow_form_add-contestants").submit(function (evt){
				    if (jQuery('.ow_open_login_form').val() == 1) {						    
					jQuery('.ow_form_add-contestants input').each(function (i) {
					    if(jQuery(this).hasClass('error')){
						valid = false;
						return false;
					    }
					    else{
						 valid = true;
					    }
					});				   
					if (valid == false) {		   			   
						return false;
					}
					else{
						valid = true;
						//Check the form is open or not logged in 
						if (valid != false) { 
						    window.contest_id = "<?php echo $id;?>";
						    ow_vote_ppOpen('#ow_vote_login_panel', '300',1);
						    //Tab in the Login Popup
						    jQuery('.ow_tabs_login_content').show();
						    jQuery('.ow_tabs_register_content').hide();
						    jQuery( '.ow_tabs_login' ).addClass('active');
						    evt.preventDefault();
						    return false;
						}
					}
				    }
				});			
		    
			});
			</script>
				 
			<div class="ow_add_contestants_row">
    				<div class="ow_contestants-label">
    					
    				</div>
    				<div class="ow_contestants-field">
    					<input type="hidden" id="contestantform"  name="contestantform" value=""/>
    					<input type="submit" id="savecontestant<?php echo $id;?>" name="savecontestant" class="savecontestant savecontest" value="<?php _e('Save','voting-contest'); ?>"/>
    				</div>
    			</div>
    
    			<input type="hidden" name="contest-id" id="contest-id" value="<?php echo $id; ?>"/>		
			
					
			</form>				
		</div>
		<?php
		switch($action_url){			    
			    case 'contestrules':
				    $contest_id = base64_decode($_GET['contest_id']);
				    if($contest_id==$id){        
					    $html_out = do_shortcode('[rulescontestants id='.$id.']');
					    wp_reset_postdata();
					    echo $html_out."</div>";
					    return;
				    }
			    break;
		}
		
		
    }
}else{
    die("<h2>".__('Failed to load Voting Add Form Without Category Shortcode view','voting-contest')."</h2>");
}
?>
