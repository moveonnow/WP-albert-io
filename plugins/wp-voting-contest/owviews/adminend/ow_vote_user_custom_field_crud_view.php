<?php
if(!function_exists('ow_voting_add_custom_field_user')){
    function ow_voting_add_custom_field_user($custom_fields_id=NULL,$field_values=NULL){
	
	wp_register_style('ow_tabs_setting', OW_ASSETS_CSS_PATH.'ow_tabs.css');
	wp_enqueue_style('ow_tabs_setting');	
	wp_enqueue_style('qtip', OW_ASSETS_CSS_PATH.'jquery.qtip.min.css', null, false, false);
	wp_enqueue_script('qtip', OW_ASSETS_JS_PATH . 'jquery.qtip.min.js', array('jquery'), false, true);
	
		$values=array(
			array('id'=>'Y','text'=> __('Yes','voting-contest')),
			array('id'=>'N','text'=> __('No','voting-contest'))
		); 
	?>
	<div class="metabox-holder">
		<div class="postbox">
			<div title="<?php _e('Click to toggle','voting-contest'); ?>" class="handlediv"><br /></div>
			
			<?php if($custom_fields_id==''){ ?>		
			<h3 class="custom_field_h3"><?php _e('Add New Registration Custom Fields','voting-contest'); ?></h3>
			<?php } else{ ?>
			<h3 class="custom_field_h3"><?php _e('Edit Registration Custom Fields','voting-contest'); ?></h3>
			<?php } ?>
			
			<div class="inside">
							
				<form name="newquestion" method="post" action="" id="new-question-form">
					<table class="form-table new_contest_cust_fld">
						<tbody>
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
							<tr id="add-question-values">
								<th>
									<label for="values"><?php _e('Values','voting-contest'); ?></label>
								</th>
								<td>
									<input name="values" id="values" size="50" value="<?php echo $field_values->response;?>" type="text" />
									<div class="hasTooltip"></div>
									<div class="hidden">
									<span class="description"><?php _e('A comma seperated list of values. Eg. black, blue, red','voting-contest'); ?></span>
									</div>
								</td>
							</tr>
							
							
							
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
							<tr>
								<th>
									<label class="inline" for="admin_only"><?php _e('Show in registration form','voting-contest'); ?></label>
								</th>
								<td>
									<?php
									$admin_only = ($field_values->admin_only)?$field_values->admin_only:'N';
									echo Ow_Vote_Common_Controller::ow_votes_form_select_input('admin_only', $values, $admin_only);?>
								    <div class="hasTooltip"></div>
								    <div class="hidden">
								   <span class="description"><?php _e('YES: Shows custom field in registration form.','voting-contest'); ?></span>
								    </div>
								</td>
							</tr>
                                        
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
							<input name="useraction" value="insert_customfield" type="hidden" />
						<?php }else{ ?>
							<input type="hidden" name="useraction" value="edit_customfield">
							<input name="custom_fields_id" value="<?php echo $custom_fields_id; ?>" type="hidden">
						<?php } ?>
						<?php if($custom_fields_id==''){ ?>	
							<input class="button-primary" name="Submit" value="<?php _e('Add Custom Fields','voting-contest'); ?>" type="submit" />
						<?php }else{ ?>
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

