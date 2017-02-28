<?php
if(!function_exists('ow_voting_add_custom_field_contestant')){
    function ow_voting_add_custom_field_contestant($custom_fields_id=NULL,$field_values=NULL){
	
	wp_enqueue_style('qtip', OW_ASSETS_CSS_PATH.'jquery.qtip.min.css', null, false, false);
	wp_enqueue_script('qtip', OW_ASSETS_JS_PATH . 'jquery.qtip.min.js', array('jquery'), false, true);
	
		$values=array(
			array('id'=>'Y','text'=> __('Yes','voting-contest')),
			array('id'=>'N','text'=> __('No','voting-contest'))
		);
		
		$date_values = array(
							array('id'=>'F d, Y','text'=> __('M d Y (Eg : March 20, 2016)','voting-contest')),
							array('id'=>'d m, Y','text'=> __('d M Y (Eg : 20 March, 2016)','voting-contest')),
							array('id'=>'d M, Y','text'=> __('d m Y (Eg : 20 Mar, 2016)','voting-contest')),
							array('id'=>'M d, Y','text'=> __('m d Y (Eg : Mar 20, 2016)','voting-contest')),
						);
	?>
	<div class="metabox-holder">
		<div class="postbox">
			<div title="<?php _e('Click to toggle','voting-contest'); ?>" class="handlediv"><br /></div>
			
			<?php if($custom_fields_id==''){ ?>		
			<h3 class="custom_field_h3"><?php _e('Add New Contestant Custom Fields','voting-contest'); ?></h3>
			<?php } else{ ?>
			<h3 class="custom_field_h3"><?php _e('Edit Contestant Custom Fields','voting-contest'); ?></h3>
			<?php } ?>
			
			<div class="inside">
							
				<form name="newquestion" method="post" action="" id="new-question-form">
					<table class="form-table new_contest_cust_fld">
						<tbody>
							<?php
							if($field_values->system_name == "contestant-title"){ ?>
							<tr>
								<td colspan="2"><?php _e('Title Notes: This is not a custom question. This field generates the Title for the custom post type and is required by WordPress. You can rename the title label for your contest but you cannot remove this from the entry form.','voting-contest'); ?></td>
							</tr>
							<?php } ?>
							<?php if($field_values->system_name == "contestant-ow_video_url"){ ?>
							<tr>
								<td colspan="2"><?php _e('Video Notes: This is not a custom question. This field is the URL field for Video Contests and will only be shown on the entry form for the Video Contest Category. It supports URLS from YouTube, Vimeo and Vine. You can rename the label of this field so it reads differently on your entry form but you cannot remove it from the Video Contest Category entry form.','voting-contest'); ?></td>
							</tr>
							<?php } ?>
							<?php if($field_values->system_name == "contestant-desc"){ ?>
							<tr>
								<td colspan="2"><?php _e('Description Notes: This is not a custom question. This generates the custom post description. It is optional. You can choose to display or hide this field from your entry form. You may rename the label so it reads differently on the contest entry form.','voting-contest'); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<th>
									<label for="custfield"><?php _e('Field Name'); ?><em title="<?php _e('This field is required','voting-contest') ?>"> *</em></label>
								</th>
								<td>
									<input class="custfield-name"  name="custfield" id="custfield" size="50" value="<?php echo $field_values->question;?>" type="text" />
									<div class="hasTooltip"></div>
									<div class="hidden">
									    <span class="description"><?php _e('Custom Field name','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<?php if($field_values->system_name != "contestant-title" && $field_values->system_name != "contestant-ow_video_url"): ?>
							<tr>
								<th id="custfield-type-select">
									<label for="custfield_type"><?php _e('Type','voting-contest'); ?></label>
								</th>
								<td>
								<?php
										$q_values	=	array(
											array('id'=>'TEXT','text'=> __('Text')),
											array('id'=>'TEXTAREA','text'=> __('Text Area')),
											array('id'=>'SINGLE','text'=> __('Radio Button')),
											array('id'=>'DROPDOWN','text'=> __('Drop Down')),
											array('id'=>'MULTIPLE','text'=> __('Checkbox')),
											array('id'=>'FILE','text'=> __('File')),
											array('id'=>'DATE','text'=> __('DatePicker'))
											);
										echo Ow_Vote_Common_Controller::ow_votes_form_select_input( 'custfield_type', $q_values, $field_values->question_type, 'id="custfield_type"');
										
								?>
								<div class="hasTooltip"></div>
								<div class="hidden">
								<span class="description"><?php _e('Type of the Custom Field','voting-contest'); ?></span>
								</div>
								</td>
							</tr>
							
							
							<tr id="add-date-values">
								<th>
									<label class="inline" for="datepicker_only"><?php _e('Display Format ','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$date_only = get_option($field_values->system_name);
									$date_only = ($date_only)?$date_only:'F d, Y';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('datepicker_only', $date_values, $date_only);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('Select Date Display format inorder to show on front end','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							
							
							<tr id="add-question-values">
								<th>
									<label for="values"><?php _e('Values','voting-contest'); ?></label>
								</th>
								<td>
									<input name="values" id="values" size="50" value="<?php echo str_replace("-"," ",$field_values->response);?>" type="text" />
									<div class="hasTooltip"></div>
									<div class="hidden">
									<span class="description"><?php _e('A comma seperated list of values. Eg. black, blue, red','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							<?php if($field_values->question_type == 'FILE'): ?>
							<tr class="add-extension-values">
								<th>
									<label for="file_types"><?php _e('Allowed File Types','voting-contest'); ?></label>
								</th>
								<td>
									<input name="file_types" id="file_types" size="50" value="<?php echo $field_values->response;?>" type="text" />
									<div class="hasTooltip"></div>
									<div class="hidden">
									<span class="description"><?php _e('A comma separated list of extensions. Eg. docx, pdf, doc','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<tr class="add-extension-values1">
								<th>
									<label for="file_types_size"><?php _e('Allowed File Size Limit','voting-contest'); ?></label>
								</th>
								<td>
									<input name="file_types_size" id="file_types_size" size="50" value="<?php echo $field_values->ow_file_size;?>" type="text" />
									<div class="hasTooltip"></div>
									<div class="hidden">
									<span class="description"><?php _e('Mention size upload limit in MB. Eg. 3 ','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							<?php endif; ?>
							<?php else: ?>
							<input type="hidden" name="custfield_type" value="TEXT" />
							<?php endif; ?>
							
							<?php if($field_values->system_name != "contestant-title"):
							//&& $field_values->system_name != "contestant-ow_video_url"
							?>
							<tr>
								<th>
									<label class="inline" for="required"><?php _e('Required:','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$req_drop = ($field_values->required)?$field_values->required:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('required', $values, $req_drop);
								?>
								<div class="hasTooltip"></div>
								<div class="hidden">
								<span class="description"><?php _e('Mark this question as required (Mandatory).','voting-contest'); ?></span>
								</div>
								</td>
							</tr>
							<?php else: ?>
							<input type="hidden" name="required" value="Y" />
							<?php endif; ?>
							
							<?php if($field_values->system_name != "contestant-title" && $field_values->system_name != "contestant-ow_video_url"): ?>
							<tr>
								<th>
									<label class="inline" for="admin_only"><?php _e('Show in contestant form','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$admin_only = ($field_values->admin_only)?$field_values->admin_only:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('admin_only', $values, $admin_only);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('YES: Shows custom field in contestant form.  NO: Shows custom field in admin only','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<?php else: ?>
							<input type="hidden" name="admin_only" value="Y" />
							<?php endif; ?>
							
							<?php if($field_values->system_name != "contestant-title" && $field_values->system_name != "contestant-ow_video_url"): ?>
							
							<tr>
								<th>
									<label class="inline" for="show_labels"><?php _e('Show Labels In Grid/List view','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$show_labels = ($field_values->show_labels)?$field_values->show_labels:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('show_labels', $values, $show_labels);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('YES: Show label in Grid/List View.  NO: Hide label in Grid/List only','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<tr>
								<th>
									<label class="inline" for="grid_only"><?php _e('Show in Grid View','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$grid_only = ($field_values->grid_only)?$field_values->grid_only:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('grid_only', $values, $grid_only);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('YES: Shows custom field in Grid View.  NO: Shows custom field in admin only','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<?php else: ?>
							<input type="hidden" name="grid_only" value="Y" />
							<input type="hidden" name="grid_only" value="Y" />
							<?php endif; ?>
							
							<?php if($field_values->system_name != "contestant-title" && $field_values->system_name != "contestant-ow_video_url"): ?>
							<tr>
								<th>
									<label class="inline" for="list_only"><?php _e('Show in List View','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$list_only = ($field_values->list_only)?$field_values->list_only:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('list_only', $values, $list_only);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('YES: Shows custom field in List View.  NO: Shows custom field in admin only','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<?php else: ?>
							<input type="hidden" name="list_only" value="Y" />
							<?php endif; ?>
                    
							<?php if($field_values->system_name != "contestant-ow_video_url"): ?>
							<tr>
								<th>
									<label class="inline" for="admin_view"><?php _e('Show in Contest description page','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$admin_view = ($field_values->admin_view)?$field_values->admin_view:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('admin_view', $values, $admin_view);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('YES: Shows custom field details in Contestant description page.','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
                   
							<tr>
								<th>
									<label class="inline" for="admin_view"><?php _e('Show in PrettyPhoto Slideshow','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$pretty_view= ($field_values->pretty_view)?$field_values->pretty_view:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('pretty_view', $values, $pretty_view);?>
									<div class="hasTooltip"></div>
									<div class="hidden">
								   <span class="description"><?php _e('YES: Shows custom field details in PrettyPhoto Slideshow.','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
		    <?php else: ?>
							<input type="hidden" name="admin_view" value="Y" />
                     <?php endif; ?>
							<tr>
								<th>
									<label for="required_text"><?php _e('Required Text','voting-contest'); ?></label>
								</th>
								<td>
									<input name="required_text" id="required_text" size="50" type="text" value="<?php echo $field_values->required_text;?>" />
									<div class="hasTooltip"></div>
									<div class="hidden">
									<span class="description"><?php _e('Text to display if field is empty. (Validation Error Message)','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							<tr>
								<th>
									<label for="sequence"><?php   _e('Order/Sequence','voting-contest'); ?></label>
								</th>
								<td>
									<?php $sequence = ($field_values->sequence)?$field_values->sequence:'0'; ?>
								<input name="sequence" id="sequence" size="50" value="<?php if(isset($sequence)) echo $sequence; ?>" type="text" />           	
								  
								  <div class="hasTooltip"></div>
								  <div class="hidden">
								    <span class="description"><?php _e('Order the view of the field by numeric values Ex:(Entering 1- will show first, 2- will be shown second.. etc)','voting-contest'); ?></span>
								    </div>
								</td>
							</tr>
						</tbody>
					</table>
					
					<p class="submit-footer">
						<?php if($custom_fields_id==''){ ?>	
							<input name="vote_action" value="insert_customfield" type="hidden" />
						<?php }else{ ?>
							<input type="hidden" name="vote_action" value="edit_customfield">
							<input name="custom_fields_id" value="<?php echo $custom_fields_id; ?>" type="hidden">
						<?php } ?>
						<?php if($custom_fields_id==''){ ?>	
							<input class="button-primary" name="Submit" value="<?php _e('Add Custom Fields','voting-contest'); ?>" type="submit" />
						<?php }else{ ?>
							<input type="hidden" name="system_name" value="<?php echo $field_values->system_name; ?>" />
							<input class="button-primary" name="Submit" value="<?php _e('Update Custom Fields','voting-contest'); ?>" type="submit" />
						<?php } ?>
					</p>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	jQuery(document).ready(function(){    
	    jQuery('.hasTooltip').each(function() { // Notice the .each() loop, discussed below
	    jQuery(this).qtip({
		content: {
		    text: jQuery(this).next('div') // Use the "div" element next to this for the content
		}
	});
	    });
	});
	</script>
	<?php
	}
}else{
    die("<h2>".__('Failed to load Voting cotestant custom field add view','voting-contest')."</h2>");
}

