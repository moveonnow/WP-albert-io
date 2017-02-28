<?php
if(!function_exists('ow_user_metabox_view')){
    function ow_user_metabox_view($questions,$registration){	
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
	
	wp_register_style('ow_datetimepicker_style', OW_ASSETS_CSS_PATH.'ow_datetimepicker.css');
	wp_enqueue_style('ow_datetimepicker_style');
	
	wp_register_script('ow_date_time_picker', OW_ASSETS_JS_PATH . 'ow_datetimepicker.js');
	wp_enqueue_script('ow_date_time_picker',array('jquery'));
	
		if(!empty($questions)){
			if(current_filter()=='user_new_form_tag') echo ">";  
	?>
			<h3><?php _e('Custom Registration Fields','voting-contest'); ?></h3>
			<table class="form-table">
			<?php
				foreach($questions as $custom_fields){            
					if($custom_fields->required=='Y'){$class="required_vote_custom";$span_man="<span style='color:red;'>*</span>";}
					else{$class="";$span_man="";} 
				?>
				<tr>
					<th>
						<label for="<?php echo $custom_fields->question; ?>">
						<?php echo $custom_fields->question; ?><?php echo $span_man; ?>
						</label>
					</th>
					<td>
                    
					<?php 
						switch($custom_fields->question_type){
							case 'TEXT':
							?>
							<input style="width: 20%;" id="<?php echo $custom_fields->system_name; ?>" type="<?php echo strtolower($custom_fields->question_type); ?>" class="inputbox <?php echo $class; ?>" name="<?php echo $custom_fields->system_name; ?>" placeholder="<?php _e($custom_fields->question);?>" value="<?php echo $registration[$custom_fields->system_name]; ?>" />
							<?php
							break;
							
							case 'TEXTAREA':
							?>
							<textarea style="width: 20%;" rows="2" id="<?php echo $custom_fields->system_name; ?>" placeholder="<?php _e($custom_fields->question);?>" name="<?php echo $custom_fields->system_name; ?>" class="<?php echo $class; ?>" ><?php echo $registration[$custom_fields->system_name]; ?></textarea>
							<?php
							break;
						
							case 'SINGLE':
								$values = explode(',',$custom_fields->response); 
								foreach($values as $val){
								?>   
									<span id="add_contestant_radio"> 
									<input class="<?php echo $class; ?> custom_reg_radio" type="radio" name="<?php echo $custom_fields->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_fields->system_name; ?>" <?php if(is_array($registration[$custom_fields->system_name])){if(in_array($val,$registration[$custom_fields->system_name])){echo "checked";}} ?> /> 
									<span class="question_radio" style="margin-right:10px;" ><?php echo $val; ?></span>
									</span>  
							<?php
								}
							break;
						
							case 'MULTIPLE':
								$values = explode(',',$custom_fields->response); 
								foreach($values as $val){
								?>
								<span id="add_contestant_radio"> 
								<input class="<?php echo $class; ?> custom_reg_check" type="checkbox"  name="<?php echo $custom_fields->system_name; ?>[]" value="<?php echo $val; ?>" id="<?php echo $custom_fields->system_name; ?>" <?php if(is_array($registration[$custom_fields->system_name])){if(in_array($val,$registration[$custom_fields->system_name])){echo "checked";}} ?> />
								<span style="margin-right:10px;" class="question_check" ><?php echo $val; ?></span>
								</span> 
								<?php
								}
							break;
						
							case 'DROPDOWN':
								$values = explode(',',$custom_fields->response);
								?>
								<select class="<?php echo $class; ?>" style="width: 20%;" name="<?php echo $custom_fields->system_name; ?>" id="<?php echo $custom_fields->system_name; ?>">
								<option value="">Select</option>
								<?php foreach($values as $val){ ?>
									  <option value="<?php echo $val; ?>" 
									  <?php echo($registration[$custom_fields->system_name]==$val)?'selected="selected"':'';?> > 
									  <?php echo $val; ?></option>
								<?php } ?>
								</select> 
								<?php
							break;
						         
							  case 'DATE':
								?>
								 <input type="text" id="<?php echo $custom_fields->system_name.$id; ?>" name="<?php echo $custom_fields->system_name; ?>" value="<?php echo $registration[$custom_fields->system_name]; ?>" class="date_picker"/>
								<?php if($custom_fields->required=='Y'){?>
								<?php
								}
							break;  
							
						}
					?>                             			
					</td> 
				</tr>
				<?php            
				} 
				if(current_filter()=='user_new_form_tag') echo "</table>";
				else{ 
				?>      
				</table>
				<?php
				}
		}
		?>
		<script type="text/javascript">
			    jQuery(document).ready(function(){
				var valid = true;			    
				jQuery('.date_picker').owvotedatetimepicker({
				    format:'m-d-Y',
				    step:10,
				    timepicker: false,
				});
			    });
		</script>
		<?php
    }
}else{
    die("<h2>".__('Failed to load Voting User meta view','voting-contest')."</h2>");
}

