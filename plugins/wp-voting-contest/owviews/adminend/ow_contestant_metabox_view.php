<?php
if(!function_exists('ow_votes_count_metabox_view')){
    function ow_votes_count_metabox_view($cnt)
    {
	?>
	<h1> <?php echo  $cnt ? $cnt.' ' : '0'.' '; _e('Votes','voting-contest'); ?> </h1> 
	<?php $cnt = ($cnt == null)?0:$cnt; ?>
	<input type="hidden" value="<?php echo $cnt; ?>" name="votes_counter" />
	<?php  
    }
}else
die("<h2>".__('Failed to load Voting contestant count box view','voting-contest')."</h2>");

if(!function_exists('ow_votes_custom_field_metabox_view')){
    function ow_votes_custom_field_metabox_view($custom_fields,$custom_entries,$category)
    {
	wp_register_style('ow_datetimepicker_style', OW_ASSETS_CSS_PATH.'ow_datetimepicker.css');
	wp_enqueue_style('ow_datetimepicker_style');
	
	wp_register_script('ow_date_time_picker', OW_ASSETS_JS_PATH . 'ow_datetimepicker.js');
	wp_enqueue_script('ow_date_time_picker',array('jquery'));
	
	$category_options = get_option($category[0]->term_id. '_' . OW_VOTES_SETTINGS); 
	$imgcontest = $category_options['imgcontest'];
		wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
		wp_enqueue_style('OW_ADMIN_STYLES');
	
	    if(!empty($custom_entries)){
            $field_values = $custom_entries[0]->field_values;
            if(base64_decode($field_values, true))
                $field_val = maybe_unserialize(base64_decode($field_values));  
            else
                $field_val = maybe_unserialize($field_values);
                
	    }
		
		if(!empty($custom_fields)){
			foreach($custom_fields as $custom_field){
				
				if($custom_field->system_name != "contestant-desc" && $custom_field->system_name != "contestant-title" ){
				    
				    if($custom_field->system_name == 'contestant-ow_video_url'){
						//Show Video Url for Video Contest Only 
						if($imgcontest != 'video' && $imgcontest != 'music'){
							 continue;
						}								   
				    }					
					
					
								
				?>
				
				<div class="ow_contestants-row">
					
					<?php apply_filters('ow_display_form_field',$custom_field,$imgcontest,$category_options);	 ?>
					
					<?php
						//Video Extension plugin - Do not show to any category in admin end
						if($custom_field->system_name == 'contestant-ow_video_upload_url'){						  
							continue;						   								   
						}					
					?>
					
					<div class="ow_contestants-label">
						<label>
						<?php 
							if($custom_field->question_type=='TEXT' || $custom_field->question_type=='TEXTAREA'){
								echo 'Enter the '.$custom_field->question;
							}else{
								   echo 'Select the '.$custom_field->question; 
							}
						?>
						<?php if($custom_field->required=='Y'){
						   $class = "required_post_entries";     
						?>
						<span class="required-mark">*</span></label>
						<?php
						}else{$class='';}
						?>
					</div>
				
					<div class="ow_contestants-field">
						<?php
						if($custom_field->question_type=='TEXT'){
						?>
							<input style="width: 35%;" class="<?php echo $class; ?>" type="<?php echo $custom_field->question_type; ?>" id="<?php echo $custom_field->system_name; ?>" value="<?php echo esc_attr(stripcslashes($field_val[$custom_field->system_name])); ?>" name="<?php echo $custom_field->system_name; ?>" />
							<input type="hidden" value="<?php echo ($custom_field->required_text!='')?$custom_field->required_text:$custom_field->question.' Field Required' ?>" class="val_<?php echo $custom_field->system_name; ?>" />   
						<?php
						}
						elseif($custom_field->question_type=='TEXTAREA')
						{
						?>
							<textarea class="<?php echo $class; ?>" style="width: 35%;" rows="1" id="<?php echo $custom_field->system_name; ?>" name="<?php echo $custom_field->system_name; ?>" ><?php echo esc_attr(stripcslashes($field_val[$custom_field->system_name])); ?></textarea> 
							<input type="hidden" value="<?php echo ($custom_field->required_text!='')?$custom_field->required_text:$custom_field->question.' Field Required' ?>" class="val_<?php echo $custom_field->system_name; ?>" /> 
						<?php
						}elseif($custom_field->question_type=='SINGLE')
						{
							$values = explode(',',$custom_field->response); 
							foreach($values as $val){
						?>
							<span id="add_contestant_radio"> 
							<input  class="reg_radio_<?php echo $custom_field->system_name; ?>  <?php echo $class; ?>" class="stt_float"  type="radio" <?php if(is_array($field_val[$custom_field->system_name]) || $field_val[$custom_field->system_name]==$val){if(in_array($val,$field_val[$custom_field->system_name])||$field_val[$custom_field->system_name]==$val){echo "checked";}} ?> name="<?php echo $custom_field->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_field->system_name; ?>" /> <span class="question_radio <?php echo $custom_field->system_name; ?>" ><?php echo $val; ?></span>
							</span>
							<input type="hidden" value="<?php echo ($custom_field->required_text!='')?$custom_field->required_text:$custom_field->question.' Field Required' ?>" class="val_<?php echo $custom_field->system_name; ?>" /> 
						<?php } ?>
						<?php
						}
						elseif($custom_field->question_type=='MULTIPLE')
						{
							$values = explode(',',$custom_field->response); 
							foreach($values as $val){
						?>
							<span id="add_contestant_radio"> 
							<input type="checkbox" class="<?php echo $class; ?> reg_check_<?php echo $custom_field->system_name; ?>" <?php if(is_array($field_val[$custom_field->system_name]) || $field_val[$custom_field->system_name]==$val){if(in_array($val,$field_val[$custom_field->system_name]) || $field_val[$custom_field->system_name]==$val){echo "checked";}} ?> name="<?php echo $custom_field->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_field->system_name; ?>" />
							<span class="question_check <?php echo $custom_field->system_name; ?>" ><?php echo $val; ?></span>  </span>
							<input type="hidden" value="<?php echo ($custom_field->required_text!='')?$custom_field->required_text:$custom_field->question.' Field Required' ?>" class="val_<?php echo $custom_field->system_name; ?>" /> 
						<?php } ?>
						<?php
						}
						elseif($custom_field->question_type=='FILE')
						{
						    
							$uploaded_file = get_post_meta(get_the_ID(),'ow_custom_attachment_'.$field_val[$custom_field->system_name],true);
							if(!empty($uploaded_file)){
							?>
							<div class="ow_contestants-file div_file_<?php echo $custom_field->system_name; ?>">
							
							<a href="javascript:" class="ow_file_class <?php echo $custom_field->system_name; ?>">
							    <img src="<?php echo OW_CANCEL_IMAGE; ?>" />
							</a>
							
							<a class='ow_file_image <?php echo $custom_field->system_name; ?>' href="<?php echo $uploaded_file['url']; ?>">
							    <img src="<?php echo OW_FILE_IMAGE; ?>" />
							</a>
							
							
							
							</div>
							
							<?php
							}
							else{
							    ?>
							    <input type="file" id="<?php echo $custom_field->system_name; ?>" name="<?php echo $custom_field->system_name; ?>"  />
							    <?php
							}
						
						}
						elseif($custom_field->question_type=='DROPDOWN'){
							$values = explode(',',$custom_field->response); ?>
							<select style="width: 35%;padding:1px;" name="<?php echo $custom_field->system_name; ?>" class="<?php echo $class; ?>" id="<?php echo $custom_field->system_name; ?>">
							<option value="">Select</option>
							<?php foreach($values as $val){ ?>
								  <option value="<?php echo $val; ?>" <?php echo ($field_val[$custom_field->system_name]==$val)?'Selected':''; ?> ><?php echo $val; ?></option>
							<?php } ?>
							</select> 
							<input type="hidden" value="<?php echo ($custom_field->required_text!='')?$custom_field->required_text:$custom_field->question.' Field Required' ?>" class="val_<?php echo $custom_field->system_name; ?>" />
						<?php
						}elseif($custom_field->question_type=='DATE'){
						    ?>
						    <input type="text"
						    id="<?php echo $custom_field->system_name.$id; ?>" name="<?php echo $custom_field->system_name; ?>" value="<?php echo esc_attr(stripcslashes($field_val[$custom_field->system_name])); ?>" class="date_picker"/>
						<?php
						}
						?>
					</div>
				</div>
				
				<?php
				}
			}
		}
		?>
		<script type="text/javascript">
		    jQuery(document).ready(function (){
			
			var valid = true;
			    
				jQuery('.date_picker').owvotedatetimepicker({
				    format:'m-d-Y',
				    step:10,
				    timepicker: false,
				});
				
			jQuery('.ow_file_class').click(function (){
			    var file_id = jQuery(this).attr("class").split(" ")[1];			    
			    jQuery('.div_file_'+file_id).html('<input type="file" id="'+file_id+'" name="'+file_id+'" />');
			    console.log(jQuery(this).attr("class").split(" ")[1]);
			});
		    });
		</script>
		    <?php
	}
}else
die("<h2>".__('Failed to load Voting contestant custom field view','voting-contest')."</h2>");

if(!function_exists('ow_votes_custom_link_metabox_view')){
    function ow_votes_custom_link_metabox_view($custom_link)
    {
	?>
	<div class="ow_contestants-row">
		<div class="ow_contestants-label">
			<label for="ow_contestant_link"><?php _e('Custom Link for Redirection','voting-contest'); ?></label>
		</div>
		<div class="ow_contestants-field">
			<input type="text" name="ow_contestant_link" id="ow_contestant_link" value="<?php echo $custom_link; ?>" style="width: 35%;" /> 
		</div>
			
	</div>
	<?php  
    }
}else
die("<h2>".__('Failed to load Voting contestant custom link view','voting-contest')."</h2>");

?>

