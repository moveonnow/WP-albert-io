<?php
if(!function_exists('ow_voting_shortcode_addcontestant_view')){
    function ow_voting_shortcode_addcontestant_view($show_cont_args,$vote_opt,$category_options,$check_status,$custom_fields){
		
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
		   'showcontestants' => 1,
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
						
						<?php 
						if($category_options['vote_contest_rules']!='' && $showrules !=0){ ?>
							<li class="ow_vote_navmenu_link <?php echo ((isset($action_url) && $action_url=='contestrules') && $contest_id==$id)?'ow_active_contest_rules active':''; ?>">
								<a href="<?php echo $permalink.$url_prefix.'contest=contestrules&amp;contest_id='.base64_encode($id); ?>">
									<span class="ow_vote_icons voteconestant-gift"></span><?php _e('Rules and Prizes','voting-contest'); ?>
								</a>
							</li>
						<?php } ?>
					
					</ul>
				</div>
			<?php } ?>
			
			<?php			
			//Flag Variable if Option for Entry Form is set to Must Login & Open State
			if($global_options['vote_entry_form'] != 'on' && $login_class =='ow_logged_in_enabled' && !is_user_logged_in()){ ?>
				<input type="hidden" name="ow_open_login_form" class="ow_open_login_form" value="1" />				
			<?php } ?>
			
			<script>
			jQuery(document).ready(function(){
				add_contestant_validation("<?php echo $id; ?>","<?php _e('Enter the contestant title','voting-contest'); ?>");
			});
			</script>
			
			
			
			<?php
			//Global Settings OPen/close Option for Entry Form
			$displayform_css = ($global_options['vote_entry_form'] == 'on')?'display: none;':'display: block;';		
			?>			
				
						
			
			<form style="<?php echo $displayform_css; ?>" class="ow_form_add-contestants<?php echo $id; ?> add_form_contestant_ow_vote" name="add-contestants" action="<?php echo get_permalink(get_the_ID()); ?>" method="post" enctype="multipart/form-data">
				
				<?php $title_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_title(); ?>
				<div class="ow_add_contestants_row contestant_title">
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
				
				<div class="ow_add_contestants_row contestant_desc">                
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
				
				<?php if($image_contest != 'on'){ ?>
				<?php
				$imgenable=$category_options['imgenable'];
				$imgrequired=$category_options['imgrequired']; ?>
					<?php if($image_contest == 'photo' || $imgenable== 'on') {
						if($image_contest == 'photo')
							$imgrequired='on';
					?>
					<div class="ow_add_contestants_row contestant_image">
						<div class="ow_add_contestants_label">
							<label><?php _e('Image  ','voting-contest'); ?><?php echo ($imgrequired=='on')?'<span class="required-mark">*</span>':''?></label>
						</div>
						<div class="ow_add_contestants_field">
							<input type="file" id="contestant-image<?php echo $id; ?>"  name="contestant-image" class="contestant-input slim" <?php echo apply_filters('ow_photo_extesnsion_filters'); ?>  />
							<?php if(!class_exists('wp_voting_photo_extension')){ ?>
							<img style="display: none;" id="uploaded_img<?php echo $id; ?>" src="" class="ow_uploaded_image"/>
							<?php } ?>
						</div>
					</div>
					
					<?php if($imgrequired=='on'){ ?>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
						add_contestant_validation_method("<?php echo 'contestant-image'.$id; ?>","<?php _e('Please upload the file','voting-contest'); ?>");
						
						function ow_preview_img(input) {
							if (input.files && input.files[0]) {
								var reader = new FileReader();						
								reader.onload = function (e) {
									$('#<?php echo 'uploaded_img'.$id; ?>').attr('src', e.target.result);
									$('#<?php echo 'uploaded_img'.$id; ?>').show();
								}						
								reader.readAsDataURL(input.files[0]);
							}
						}						
						$("#<?php echo 'contestant-image'.$id; ?>").change(function(){
							ow_preview_img(this);
						});
					});
					</script>
					<?php } ?>
					<?php } ?>		
				<?php } ?>
				
				 <!-- Custom Fields -->
				  <?php
					$ow_video_extension = get_option('_ow_video_extension');
					
					if(!empty($custom_fields)){ $field_count = 0;
						foreach($custom_fields as $custom_field){
							if($custom_field->system_name != 'contestant-desc' && $custom_field->system_name != 'contestant-title'){										
								//Video Extension plugin 
								if($custom_field->system_name == 'contestant-ow_video_upload_url'){
									
									if($ow_video_extension == null){
										continue;
									}
									
								   //Show Video Url for Video Contest Only 
								   if($image_contest != 'video'){
									continue;
								   }
								   
								}				
								
							    
								if($custom_field->system_name == 'contestant-ow_video_url'){
									
									if($ow_video_extension != null && $image_contest != 'music'){
										continue;
									}
									
								   //Show Video Url for Video Contest Only 
								   if($image_contest != 'video' && $image_contest != 'music'){										
										continue;
								   }					   
								}
								
								
							   	
								if($custom_field->admin_only == "Y"){  
								?>
								<div class="ow_add_contestants_row contestant_field<?php echo $field_count; ?>">
                
									<div class="ow_add_contestants_label">
										<label for="<?php echo $custom_field->system_name.$id; ?>" id="<?php echo $custom_field->system_name; ?>" data-val="<?php echo $custom_field->question; ?>">
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
											<input type="<?php echo $custom_field->question_type; ?>"
											id="<?php echo $custom_field->system_name.$id; ?>" name="<?php echo $custom_field->system_name; ?>"  <?php apply_filters('ow_add_frontend_fields',$custom_field); ?> />
											<?php if($custom_field->required=='Y'){?>
											<?php
											}
										break;
									
										case 'TEXTAREA':
											?>
											<textarea rows="1" id="<?php echo $custom_field->system_name.$id; ?>"
													name="<?php echo $custom_field->system_name; ?>" <?php apply_filters('ow_add_frontend_fields',$custom_field); ?> ></textarea>
										<?php
										break;
									
										case 'SINGLE':
											$single_values = explode(',',$custom_field->response);
											if(!empty($single_values)){
											foreach($single_values as $single_val){ ?>
											 <span id="add_contestant_radio"> 
											 <input class="ow_stt_float" type="radio" name="<?php echo $custom_field->system_name; ?>[]"
													value="<?php echo $single_val; ?>" id="<?php echo $custom_field->system_name.$id; ?>" />
													<label><?php echo str_replace("-"," ",$single_val); ?></label>
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
													<span class="ow_stt_float"><?php echo str_replace("-"," ",$multi_val); ?></span>
											 </span>
											<?php
												}
											}
										break;
									
										case 'DROPDOWN':
											$drop_values = explode(',',$custom_field->response);
											?>
											<select name="<?php echo $custom_field->system_name; ?>" id="<?php echo $custom_field->system_name.$id; ?>" <?php apply_filters('ow_add_frontend_fields_select',$custom_field); ?>>
											<option value="">Select</option>
											<?php foreach($drop_values as $val){ ?>
												  <option value="<?php echo $val; ?>"><?php echo str_replace("-"," ",$val); ?></option>
											<?php } ?>
											</select>
									<?php
										break;
									    
										case 'FILE':
											$drop_values = explode(',',$custom_field->response); 
											$allowed_filetypes = ($custom_field->response == null)?__("All","voting-contest"):$custom_field->response;
											$allowed_filesizes = ($custom_field->ow_file_size == 0)?__("Any Size","voting-contest"):$custom_field->ow_file_size;		
											?>
											<input type="file" name="<?php echo $custom_field->system_name; ?>" id="<?php echo $custom_field->system_name.$id; ?>" />
											<span class='ow_allowed_file'><?php echo __('Allowed File Types : ','voting-contest').$allowed_filetypes; ?></span>
											<span class='ow_allowed_file'><?php echo __('Allowed File Size Limit : ','voting-contest').$allowed_filesizes; ?>MB</span>
									<?php
										break;
									    
										case 'DATE':
											?>
											<input type="text"
											id="<?php echo $custom_field->system_name.$id; ?>" name="<?php echo $custom_field->system_name; ?>" class="date_picker" <?php apply_filters('ow_add_frontend_fields',$custom_field); ?> />
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
								
								apply_filters('ow_add_form_field',$custom_field->system_name,$image_contest,$id);								
								
							}
							$field_count++;
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
			    		
				jQuery(".ow_form_add-contestants<?php echo $id; ?>").submit(function (evt){
					
									
				    if (jQuery('.ow_open_login_form').val() == 1) {						    
					jQuery('.ow_form_add-contestants<?php echo $id; ?> input').each(function (i) {
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
			
  



			<div class="ow_add_contestants_row contestant_submit">
    				<div class="ow_contestants-label">
    					
    				</div>
    				<div class="ow_contestants-field">
    					<input type="hidden" id="contestantform<?php echo $id;?>"  name="contestantform<?php echo $id;?>" value="contestantform<?php echo $id;?>"/>
    					<input type="submit" id="savecontestant<?php echo $id;?>" name="savecontestant" class="savecontestant savecontest" value="<?php _e('Save','voting-contest'); ?>"/>
						<?php do_action('owvoting_preview_button'); ?>
    				</div>
    			</div>
    
    			<input type="hidden" name="contest-id" value="<?php echo $id; ?>"/>		
			
					
			</form>				
		</div>
		<?php
		do_action('owvoting_extension_image_preview');
		
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
    die("<h2>".__('Failed to load Voting Shortcode view','voting-contest')."</h2>");
}
?>
