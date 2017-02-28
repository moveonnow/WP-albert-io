<?php
if(!function_exists('ow_voting_pretty_custom_values')){
    function ow_voting_pretty_custom_values($vote_id){
		$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
		$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries($vote_id);
		if(!empty($custom_entries)){
			$field_values = $custom_entries[0]->field_values;
			if(base64_decode($field_values, true))
				$field_val = maybe_unserialize(base64_decode($field_values));  
			else
				$field_val = maybe_unserialize($field_values);
				
		}
		if(!empty($custom_fields)){
		?>
		<div class="ow_contestant_custom_fields">
			<?php
			$votes_settings = get_option( OW_VOTES_SETTINGS );            
            if($votes_settings['vote_show_date_prettyphoto'] == 'on'){ 
			?>
                <span class="ow_pretty_date"><?php _e('Date : ','voting-contest');echo get_the_time( "Y-m-d", $vote_id ); ?></span>
			<?php
			}
			$i=0;
			foreach($custom_fields as $custom_field){
				if($custom_field->system_name != 'contestant-desc' && $custom_field->system_name != 'contestant-title'){
					if($custom_field->pretty_view=='Y'){
					    if($field_val[$custom_field->system_name] != ''){
						if($i==0)
						echo '<h2>'.__('Additional Information','voting-contest').'</h2>';
			?>
						<div class="ow_contestant_other_det">
							<span><strong><?php echo $custom_field->question.': ';?></strong></span>
						   <?php
						   if($field_val[$custom_field->system_name ]!='' || $field_val[$custom_field->system_name ] == 0){
							    if($custom_field->question_type == 'FILE'){										   
								$uploaded_file = get_post_meta($vote_id,'ow_custom_attachment_'.$field_val[$custom_field->system_name],true);
								?>
								<a class='ow_file_image <?php echo $custom_field->system_name; ?>' href="<?php echo $uploaded_file['url']; ?>">
								    <img src="<?php echo OW_SMALLFILE_IMAGE; ?>" />
								</a>
								<?php
							    }
							   else if(is_array($field_val[$custom_field->system_name ])){
								   $multiple = implode(', ',$field_val[$custom_field->system_name ]);
								   echo $multiple;
							   }else{
								   echo stripcslashes($field_val[$custom_field->system_name ]);
							   }
						   }
						   ?>
						</div>
						<?php
						$i++;
					    }
					}
				}
			}
			?>
			</div>
			<?php
		}
	}
}else{
    die("<h2>".__('Failed to load Pretty Custom Values view','voting-contest')."</h2>");
}
?>