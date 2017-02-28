<?php
if(!function_exists('ow_taxonomy_vote_view')){
    function ow_taxonomy_vote_view($values){
	
	wp_register_script('ow_votes_taxonomy', OW_ASSETS_JS_PATH . 'ow_vote_taxonomy.js');
	wp_enqueue_script('ow_votes_taxonomy',array('jquery'));
	
	wp_register_style('ow_datetimepicker_style', OW_ASSETS_CSS_PATH.'ow_datetimepicker.css');
	wp_enqueue_style('ow_datetimepicker_style');
	
	wp_register_script('ow_date_time_picker', OW_ASSETS_JS_PATH . 'ow_datetimepicker.js');
	wp_enqueue_script('ow_date_time_picker',array('jquery'));
	
	$show_desc_option = array(
			    '-1'        => 'None',
			    'grid' => 'Grid View',
			    'list' => 'List View',
			    'both'      => 'Both'
			    );
    ?>
	<table class="form-table">
		
	    <?php apply_filters('ow_category_settings_top',''); ?>
		
	     <tr valign="top">
			<th scope="row"><label for="imgcontest"><?php _e('Type of Contest: ','voting-contest'); ?></label></th>
			<td>
			    <select name="imgcontest" id="imgcontest">				   
				<?php $selecteds = (($values['options']['imgcontest'] == 'photo') || ($values['options']['imgcontest'] == ''))?'selected':''; ?>
				<option value="photo" <?php echo $selecteds; ?>><?php _e('Photo','voting-contest'); ?></option>
				
				<?php $selecteds = (($values['options']['imgcontest'] == 'video') || ($values['options']['imgcontest'] == 'on'))?'selected':''; ?>
				<option value="video" <?php echo $selecteds; ?>><?php _e('Video','voting-contest'); ?></option>
				
				<?php $selecteds = ($values['options']['imgcontest'] == 'music')?'selected':''; ?>
				<option value="music" <?php echo $selecteds; ?>><?php _e('Music','voting-contest'); ?></option>
				
				<?php $selecteds = ($values['options']['imgcontest'] == 'essay')?'selected':''; ?>
				<option value="essay" <?php echo $selecteds; ?>><?php _e('Essay','voting-contest'); ?></option>
				
			    </select>
			    <br/><span class="description"> <?php _e('Image Field will not be shown in Front end Add Contestant if it is Video Or Music Contest (Submit Entries).','voting-contest'); ?></span>
			</td>
        </tr>
	    
	    <?php $check_contest = ($values['options']['imgcontest'] == 'video' || $values['options']['imgcontest'] == 'on' || $values['options']['imgcontest'] == 'music')?'on':''; ?>
		
		
	<tr valign="top" class="show_music_man" id="<?php echo($values['options']['imgcontest'] == 'essay' || $values['options']['imgcontest'] == 'video' || $values['options']['imgcontest'] == 'music')?'edit_image_man':'';?>" style="<?php echo($values['options']['imgcontest'] != 'music')?'display:none;':'';?>">
		<th scope="row"><label for="musicfileenable"><?php _e('Enable Music Upload: ','voting-contest'); ?></label></th>
		<td>
			<label class="switch switch-slide">
				<input class="switch-input" type="checkbox" id="musicfileenable" name="musicfileenable" <?php checked('on', $values['options']['musicfileenable']); ?>/>
				<span class="switch-label" data-on="Yes" data-off="No"></span>
			</label>
			
			<span class="description"> <?php _e('Enable Music Upload In Submit Entry Form for Music Contest Category.','voting-contest'); ?></span>
		</td>
	</tr>
	
	
		
	<tr valign="top" class="show_image_man" id="<?php echo($values['options']['imgcontest'] == 'essay' || $values['options']['imgcontest'] == 'video' || $values['options']['imgcontest'] == 'music')?'edit_image_man':'';?>" style="<?php echo($values['options']['imgcontest'] == 'photo')?'display:none;':'';?>">
		<th scope="row"><label for="imgenable"><?php _e('Upload Image: ','voting-contest'); ?></label></th>
		<td>
			<label class="switch switch-slide">
				<input class="switch-input" type="checkbox" id="imgenable" name="imgenable" <?php checked('on', $values['options']['imgenable']); ?>/>
				<span class="switch-label" data-on="Yes" data-off="No"></span>
			</label>
			
			<span class="description"> <?php _e('Enable Image Upload In Submit Entry Form.','voting-contest'); ?></span>
		</td>
	</tr>
	<tr valign="top" class="show_image_man" id="<?php echo($values['options']['imgcontest'] == 'essay' || $values['options']['imgcontest'] == 'video' || $values['options']['imgcontest'] == 'music')?'edit_image_man':'';?>" style="<?php echo($values['options']['imgcontest'] == 'photo')?'display:none;':'';?>">
		<th scope="row"><label for="imgrequired"><?php _e('Image Mandatory: ','voting-contest'); ?></label></th>
		<td>
			<label class="switch switch-slide">
				<input class="switch-input" type="checkbox" id="imgrequired" name="imgrequired" <?php checked('on', $values['options']['imgrequired']); ?>/>
				<span class="switch-label" data-on="Yes" data-off="No"></span>
			</label>
			
			<span class="description"> <?php _e('Upload Image Field Required/Not Required.','voting-contest'); ?></span>
		</td>
	</tr>
	
	  <tr valign="top">
			<th scope="row"><label for="imgdisplay"><?php _e('Display Image: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
							<input class="switch-input" type="checkbox" id="imgdisplay" name="imgdisplay" <?php checked('on', $values['options']['imgdisplay']); ?>/>
							<input type="hidden" name="votes_category_settings" id="votes_category_settings" value="1"/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							
						</label>
			  
			   <span class="description"> <?php _e('Display Featured Image in Contestant Listing.','voting-contest'); ?></span>
			</td>
	    </tr>
	    
	
		
	    <tr valign="top">
			<th scope="row"><label for="votecount"><?php _e('Hide Vote Count: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="votecount" name="votecount" <?php checked('on', $values['options']['votecount']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
				
			
					<span class="description"> <?php _e('Hide Vote Count in Contestant Listing and Description Page.','voting-contest'); ?></span>
			
			</td>
	    </tr>
	    
		<tr valign="top">
			<th scope="row"><label for="termdisplay"><?php _e('Display Categories: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="termdisplay" name="termdisplay" <?php checked('on', $values['options']['termdisplay']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
				

			
					<span class="description"> <?php _e('Displays Categories in Contestant Listing.','voting-contest'); ?></span>
			
			</td>
	    </tr>
		
		<tr valign="top">
			<th scope="row"><label for="list_grid_hide"><?php _e('Hide Grid/List: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="list_grid_hide" name="list_grid_hide" <?php checked('on', $values['options']['list_grid_hide']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
				

			
					<span class="description"> <?php _e('Hide List/Grid Option in Contests.','voting-contest'); ?></span>
			
			</td>
	    </tr>
				
			
		<tr valign="top">
			<th scope="row"><label for="total_vote_count"><?php _e('Show Total Vote Count: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="total_vote_count" name="total_vote_count" <?php checked('on', $values['options']['total_vote_count']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
			
					<span class="description"> <?php _e('Displays Total Vote Count Of Category Below Contest Timer.','voting-contest'); ?></span>
			
			</td>
	        </tr>
		
		<tr valign="top">
			<th scope="row"><label for="top_ten_count"><?php _e('Show Top 10 Contestants: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="top_ten_count" name="top_ten_count" <?php checked('on', $values['options']['top_ten_count']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
			
					<span class="description"> <?php _e('Displays Top Ten Contestants.','voting-contest'); ?></span>
			
			</td>
	        </tr>
				
		<tr valign="top">
			<th scope="row"><label for="authordisplay"><?php _e('Show Author Name: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="authordisplay" name="authordisplay" <?php checked('on', $values['options']['authordisplay']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
			
					<span class="description"> <?php _e('Displays Author Name On Contestant .','voting-contest'); ?></span>
			
			</td>
	        </tr>
		
		<tr valign="top">
			<th scope="row"><label for="authornamedisplay"><?php _e('Show Author Email: ','voting-contest'); ?></label></th>
			<td>
			    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="authornamedisplay" name="authornamedisplay" <?php checked('on', $values['options']['authornamedisplay']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
			</td>
		</tr>
				
	    <tr valign="top">
			<th scope="row"><label for="votes_starttime"><?php _e('Select Start Time: ','voting-contest'); ?></label></th>
			<td>
				<input type="text" name="votes_starttime" id="votes_starttime" value="<?php  echo $values['start_time']; ?>" />
				<input class="button cleartime clearstarttime" type="button" value="Clear"/>
				<br/><span class="description"><p><i><?php _e('Default: No Start Time','voting-contest'); ?></i></p></span>
			</td>
		</tr>
	    <tr valign="top">
			<th scope="row"><label for="votes_expiration"><?php _e('Select End Time: ','voting-contest'); ?></label></th>
			<td>
				<input type="text" name="votes_expiration" id="votes_expiration" value="<?php  echo $values['expiration']; ?>" />
				<input class="button cleartime clearendtime" type="button" name="no_expiration" id="no_expiration" value="Clear"/>
				<br/><span class="description"><p><i><?php _e('Default: No Expiration','voting-contest'); ?></i></p></span>
			</td>
	    </tr>
	     <tr valign="top">
		<th scope="row"><label for="tax_hide_photos_live"><?php _e('Hide Photos Until Live: ','voting-contest'); ?></label></th>
		<td>
		    <label class="switch switch-slide">
				                     
						<input class="switch-input" type="checkbox" id="tax_hide_photos_live" name="tax_hide_photos_live" <?php checked('on', $values['options']['tax_hide_photos_live']); ?>/>
						<span class="switch-label" data-on="Yes" data-off="No"></span>
							
				    </label>
		</td>
	    </tr>
	    <tr valign="top">
			<th scope="row"><label for="tax_activationcount"><?php _e('Activation Count: ','voting-contest'); ?></label></th>
			<td>
			   <input type="text" name="tax_activationcount" id="tax_activationcount" value="<?php  echo $values['taxonomy_active_count']; ?>"/> 
				<br/><span class="description"><p><?php _e('Number of Contestants to be reached to make it Active.','voting-contest');?></p></span>
			</td>
	    </tr>
	    <tr valign="top">
			<th scope="row"><label for="vote_count_per_cat"><?php _e('Vote Count Per Click: ','voting-contest'); ?></label></th>
			<td>
						<!--<input type="text" name="vote_count_per_cat" id="vote_count_per_cat" value="<?php  echo $values['options']['vote_count_per_contest']; ?>"/> -->
				 <select name="vote_count_per_cat" id="vote_count_per_cat">
				 <?php for($i=1;$i<=5;$i++){
					 $selecteds = ($values['options']['vote_count_per_contest'] == $i)?'selected':'';    
				 ?>
					 <option value="<?php echo $i; ?>" <?php echo $selecteds;?>><?php echo $i; ?></option>
				 <?php }?>
				 </select>
				<br/><span class="description"><p><?php _e('Set the number of votes per click.(Default count 1)','voting-contest');?></p></span>
            </td>
	    </tr>
		<tr valign="top">
			<th scope="row"><label for="middle_custom_navigation"><?php _e('Set Gallery Button Link: ','voting-contest'); ?></label></th>
			<td>
			   <input type="text" name="middle_custom_navigation" id="middle_custom_navigation" value="<?php  echo $values['options']['middle_custom_navigation']; ?>"/>
				<span style="display: none;color:red;" id="erro_valid_url"><?php _e('Enter Valid URL','voting-contest'); ?></span>
				<br/><span class="description"><p><?php _e('Enter the URL of this contests main page','voting-contest');?></p></span>
			 </td>
		</tr>            
		<tr valign="top">
			<th scope="row"><label for="show_description"><?php _e('Show Contestant Description: ','voting-contest'); ?></label></th>
			<td>
			   <select name="show_description" id="show_description">
					<?php foreach($show_desc_option as $key => $desc): ?>
						<?php $selected = ($values['options']['show_description'] == $key)?'selected':''; ?>
						<option value="<?php echo $key; ?>" <?php echo $selected;?>>
							<?php echo $desc; ?>
						</option>
					<?php endforeach; ?>
			   </select>                  
				<span style="display: none;color:red;" id="erro_valid_url">Enter Valid URL</span>
				<br/><span class="description"><p><?php _e('Select options to show contestant descriptions in the Corresponding view','voting-contest');?></p></span>   
			</td>
		</tr>
            
		<tr valign="top">
			<th scope="row"><label for="vote_contest_entry_person"><?php _e('Entry Limit Per User:','voting-contest'); ?></label></th>
			<td>
			  <input type="text" name="vote_contest_entry_person" id="vote_contest_entry_person" value="<?php  echo $values['options']['vote_contest_entry_person']; ?>"/>
				<br/><span class="description"> <p><?php _e('Limit the number of entries a single contestant may submit. Leave blank for unlimited entries per contestant.','voting-contest');?></p></span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="vote_contest_entry_person"><?php _e('Rules & Prizes:','voting-contest'); ?></label></th>
			<td>
			<?php wp_editor(html_entity_decode($values['options']['vote_contest_rules']), 'contest-rules', $settings); ?>
			<br/><span class="description"> <p><?php _e('Description of Rules and prizes','voting-contest');?></p></span>
			</td>
        </tr>
		
	<?php apply_filters('ow_category_settings_bottom',''); ?>
	
    </table>

<?php
    }
}else
die("<h2>".__('Failed to load the Voting Taxonomy view','voting-contest')."</h2>");
?>