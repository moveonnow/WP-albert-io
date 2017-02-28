<?php
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script('wp-color-picker');
wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
wp_enqueue_style('OW_ADMIN_STYLES');



if(!function_exists('ow_common_settings_view')){
    function ow_common_settings_view($selected){
		
	wp_register_style('ow_tabs_setting', OW_ASSETS_CSS_PATH.'ow_tabs.css');
	wp_enqueue_style('ow_tabs_setting');
	
	wp_register_script('ow_admin_js', OW_ASSETS_JS_PATH . 'ow_admin_js.js');
	wp_enqueue_script('ow_admin_js',array('jquery'));
	
		
	wp_enqueue_style('qtip', OW_ASSETS_CSS_PATH.'jquery.qtip.min.css', null, false, false);
	wp_enqueue_script('qtip', OW_ASSETS_JS_PATH . 'jquery.qtip.min.js', array('jquery'), false, true);
	
	
	
	?>
	
	

	<div class="sidebar_ow_vote">
		<nav class="ow_settings_menu">
		    
			<ul>
				<?php apply_filters('ow_global_settings_top',''); ?>
				<li class="<?php echo ($selected=='common')?'current':'';?>">
					<a href="admin.php?page=votes_settings&vote_action=common" class="ow_set_links">
					<span class="owvotingicon owfa-list"></span>
					<span class="ow_vote_links"><?php _e('Common Settings','voting-contest'); ?></span>
					</a>
				</li>
				
				<li class="<?php echo ($selected=='contest')?'current':'';?>">
					<a href="admin.php?page=votes_settings&vote_action=contest" class="ow_set_links">
					<span class="owvotingicon owfa-camera"></span>
					<span class="ow_vote_links"><?php _e('Contest Settings','voting-contest'); ?></span>
					</a>
				</li>
				
				<li class="<?php echo ($selected=='color')?'current':'';?>">
					<a href="admin.php?page=votes_settings&vote_action=color" class="ow_set_links">
					<span class="owvotingicon owfa-color"></span>
					<span class="ow_vote_links"><?php _e('Style Settings','voting-contest'); ?></span>
					</a>
				</li>
					
				<li class="<?php echo ($selected=='share')?'current':'';?>" >
					<a href="admin.php?page=votes_settings&vote_action=share" class="ow_set_links">
					<span class="owvotingicon owfa-share-alt"></span>
					<span class="ow_vote_links"><?php _e('Share Settings','voting-contest'); ?></span>
					</a>
				</li>
				
				<li class="<?php echo ($selected=='script')?'current':'';?>">
					<a href="admin.php?page=votes_settings&vote_action=script" class="ow_set_links">
					<span class="owvotingicon owfa-strikethrough"></span>
					<span class="ow_vote_links"><?php _e('Script Settings','voting-contest'); ?></span>
					</a>
				</li>
				
				<li class="<?php echo ($selected=='expert')?'current':'';?>">
					<a href="admin.php?page=votes_settings&vote_action=expert" class="ow_set_links">
					<span class="owvotingicon owfa-eraser"></span>
					<span class="ow_vote_links"><?php _e('Excerpt Settings','voting-contest'); ?></span>
					</a>
				</li>
				
				<li class="<?php echo ($selected=='paginate')?'current':'';?>">
					<a href="admin.php?page=votes_setting_paginate" class="ow_set_links">
					<span class="owvotingicon owfa-sort-numeric-asc"></span>
					<span class="ow_vote_links"><?php _e('Pagination Settings','voting-contest'); ?></span>
					</a>
				</li>
				<?php apply_filters('ow_global_settings_bottom',''); ?>	
			</ul>
		</nav>
	</div>
	<?php
    }
}else{
    die("<h2>".__('Failed to load Voting common menu view','voting-contest')."</h2>");
}

if(!function_exists('ow_contest_settings_view')){
	function ow_contest_settings_view($option){
		?>
		<h2 class="color_h2"><?php _e('Contest Global Settings','voting-contest'); ?></h2>
		
		<div class="settings_content">
			<h4><?php _e('Voting Options','voting-contest'); ?></h4>
			<form action="" method="post">
			<table class="form-table">
			    
			    <tr valign="top">
					<th  scope="row"><label for="vote_onlyloggedinuser"><?php _e('Must be logged in to Vote?','voting-contest'); ?>
					<div class="hasTooltip"></div>
					<div class="hidden">
					<p class="description"><?php _e('Only logged in Users can register their Vote ','voting-contest'); ?></p>
					</div>
					</label>
					</th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_onlyloggedinuser" name="vote_onlyloggedinuser" <?php checked('on', $option['onlyloggedinuser']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				
				<tr valign="top">
					<th  scope="row"><label for="vote_onlyloggedinuser"><?php _e('Must be logged in to submit entries?','voting-contest'); ?>
					<div class="hasTooltip"></div>
					<div class="hidden">
					<p class="description"><?php _e('Only logged in Users can submit their entries for the contest ','voting-contest'); ?></p>
					</div>
					</label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_onlyloggedcansubmit" name="vote_onlyloggedcansubmit" <?php checked('on', $option['vote_onlyloggedcansubmit']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				
								
				<tr  valign="top">
                    <th  scope="row"><label for="vote_tracking_method"><?php _e('Vote Tracking','voting-contest'); ?> </label>
		    
						<div class="hasTooltip"></div>
									<div class="hidden">
									<p class="description"><?php _e('Select how Votes will be Tracked when a User is not required to log in. IP Traced is the most secure!','voting-contest'); ?></p>
									</div>
						</th>
		    
						<?php 
						$vote_tracking_method = array(
										    'ip_traced'=>'IP Traced',
										    'cookie_traced'=>'Cookie Traced',
										    'email_verify' => 'Email Verification'
									    ); 
						?>
                    <td colspan="2">					
						<select id="vote_tracking_method" name="vote_tracking_method"  <?php checked('on', $option['onlyloggedinuser']); ?>>
							<?php foreach($vote_tracking_method as $key => $method): ?>
								<?php $selected = ($key == $option['vote_tracking_method'])?'selected':''; ?>
								<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $method; ?></option>
							<?php endforeach; ?>
						</select>
						
					</td>                    
                </tr>
				
				<tr valign="top">
					<th  scope="row"><label for="vote_grab_email_address"><?php _e('Grab Email Addresss Before Voting','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('This option will only work for <br />IP & COOKIE vote tracking','voting-contest'); ?></p>
						</div>				
					</th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_grab_email_address" name="vote_grab_email_address" <?php checked('on', $option['vote_grab_email_address']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
                </tr>
				
				
				
		<tr  valign="top">
                    <th  scope="row"><label for="vote_frequency"><?php _e('Voting Frequency','voting-contest'); ?> </label>
		    
		    <div class="hasTooltip"></div>
			<div class="hidden">
			<p class="description"><?php _e('Allows to change the Voting Frequency.','voting-contest'); ?></p>
			</div>
		    </th>
                    <td colspan="2">
			<?php $vote_frequency_count = ($option['vote_frequency_count'] == null)?1:$option['vote_frequency_count']; ?>
			<input type="text" name="vote_frequency_count" id="vote_frequency_count" value="<?php echo $vote_frequency_count; ?>" />
			
			<?php
			    if($option['frequency'] != 0 && $option['frequency'] != 1 && $option['frequency'] != 11){
				if($option['frequency'] == 12 && $option['frequency'] == 24){
				    $vote_frequency_hours  = $option['frequency'];
				    $option_value = __('Every _____ Hours','voting-contest');
				}
				else{
				    $vote_frequency_hours  = $option['vote_frequency_hours'];
				    $option_value = __('Every ','voting_contest').$vote_frequency_hours. __(' Hours','voting-contest');
				}
				$display_class = "style='visibility:visible'";
			    }
			    else{
				$vote_frequency_hours  = 24;
				$display_class = "style='visibility:hidden'";
				$option_value = __('Every _____ Hours','voting-contest');
			    }
			?>
			
			<select id="vote_frequency" name="vote_frequency" >
                            <option value="0" <?php selected($option['frequency'], '0'); ?>><?php _e('No Limit','voting-contest'); ?></option>
			    <option value="2" <?php selected($option['frequency'], '2'); ?>><?php echo $option_value; ?></option>
                            <option value="1" <?php selected($option['frequency'], '1'); ?>><?php _e('per Calendar Day','voting-contest'); ?></option>
			    <option value="11" <?php selected($option['frequency'], '11'); ?>><?php _e('per Category','voting-contest'); ?></option>           
                        </select>
			
			<input type="text" required maxlength="3" name="vote_frequency_hours" id="vote_frequency_hours" value="<?php echo $vote_frequency_hours; ?>" <?php echo $display_class; ?> />
			
		    </td>
                </tr>
		
		
				 
				<tr valign="top">
                    <th  scope="row"><label for="vote_votingtype"><?php _e('User Can Vote For','voting-contest'); ?> </label></th>
                    <td colspan="2">
			
			
			<input type="radio" value="0" id="vote_votingtype_0" name="vote_votingtype"  <?php echo ($option['vote_votingtype'] == 'on' || $option['vote_votingtype'] == 0)?'checked':''; ?>/>
			<label for="vote_votingtype_0"><?php _e('Single','voting-contest'); ?></label>
			
			<input type="radio" value="1" id="vote_votingtype_1" name="vote_votingtype"  <?php echo ($option['vote_votingtype'] == 1)?'checked':''; ?>/>
			<label for="vote_votingtype_1"><?php _e('Multiple (Exclusive)','voting-contest'); ?></label>
			
			<input type="radio" value="2" id="vote_votingtype_2" name="vote_votingtype"  <?php echo ($option['vote_votingtype'] == '' || $option['vote_votingtype'] == 2)?'checked':''; ?>/>
			<label for="vote_votingtype_2"><?php _e('Multiple (Split)','voting-contest'); ?></label>
			
		   
					</td>
                </tr>
		</table>
			
		<h4><?php _e('Contest Options','voting-contest'); ?></h4>
		<table class="form-table">
		<tr  valign="top">
                    <th scope="row">
			    <label for="vote_truncation_grid"><?php _e('Title Truncation grid view','voting-contest'); ?> </label>
			    <div class="hasTooltip"></div>
			    <div class="hidden">
			    <p class="description"><?php _e('Limit the title characters show on contestant listing (grid view)','voting-contest'); ?></p>
			    </div>
		    </th>
                    <td colspan="2">
						<input type="text" id="vote_truncation_grid" onkeypress="return isnumber(event);" name="vote_truncation_grid"  value="<?php echo $option['vote_truncation_grid'] ?>"/>
						
					</td>
                </tr>
				
				<tr  valign="top">
                    <th  scope="row">
						<label for="vote_truncation_list"><?php _e('Title Truncation list view','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Limit the title characters show on contestant listing (List view)','voting-contest'); ?></p>
						</div>
					</th>
                    <td colspan="2">
						<input type="text" id="vote_truncation_list" onkeypress="return isnumber(event);" name="vote_truncation_list"  value="<?php echo $option['vote_truncation_list'] ?>"/>
						
					</td>
                </tr>
				
		<tr valign="top">
		    <th  scope="row"><label for="vote_publishing_type"><?php _e('Auto Approve Contestants','voting-contest'); ?> </label>
		    <div class="hasTooltip"></div>
		    <div class="hidden">
		    <p class="description"><?php _e('Select for Publishing Automatically<br />Unselect for Pending State','voting-contest'); ?></p>
		    </div>
		    
		    </th>
				    <td colspan="2">
					    <label class="switch switch-slide">
						    <input class="switch-input" type="checkbox" id="vote_publishing_type" name="vote_publishing_type" <?php checked('on', $option['vote_publishing_type']); ?>/>
						    <span class="switch-label" data-on="Yes" data-off="No"></span>
						    <span class="switch-handle"></span>
					    </label>
		    </td>
                </tr>
				 
				<tr valign="top">
                    <th  scope="row">
		    <label for="vote_tobestarteddesc"><?php _e('To Be Started Description','voting-contest'); ?> </label>
		    
		    <div class="hasTooltip"></div>
		     <div class="hidden">
					<p class="description"><?php _e('Start time Description.','voting-contest'); ?></p></td>
		     </div>
		    </th>
                    <td colspan="2"> <input type="text" id="vote_tobestarteddesc" name="vote_tobestarteddesc"  value="<?php echo $option['vote_tobestarteddesc'] ?>"/>
		    
                </tr>
				
				<tr valign="top">
                    <th  scope="row"><label for="vote_reachedenddesc"><?php _e('Closed Description','voting-contest'); ?> </label>
			
			 <div class="hasTooltip"></div>
		     <div class="hidden">
					<p class="description"><?php _e('Closed Description.','voting-contest'); ?></p></td>
		     </div>
		     
		    </th>
                    <td colspan="2"> <input type="text" id="vote_reachedenddesc" name="vote_reachedenddesc"  value="<?php echo $option['vote_reachedenddesc'] ?>"/>
		   
                </tr>
				 
				<tr valign="top">
                    <th  scope="row"><label for="vote_entriescloseddesc"><?php _e('Entries Closed Description','voting-contest'); ?> </label>
		    
		     <div class="hasTooltip"></div>
		    <div class="hidden">
					<p class="description"><?php _e('Entries Closed Description.','voting-contest'); ?></p></td>
		    </div>
		    </th>
                    <td colspan="2"> <input type="text" id="vote_entriescloseddesc" name="vote_entriescloseddesc"  value="<?php echo $option['vote_entriescloseddesc'] ?>"/>
		   
                </tr> 
				
				<tr valign="top">
                    <th  scope="row"><input type="submit" value="<?php _e('Update','voting-contest'); ?>" name="Submit" class="button-primary"></th>
                </tr>
				<input type="hidden" name="setting_action" value="contest_save" />	
			</table>
			
			</form>
			
		</div>
		<?php
	}
}else
die("<h2>".__('Failed to load Voting admin contest settings view','voting-contest')."</h2>");

if(!function_exists('ow_common_page_settings_view')){
	function ow_common_page_settings_view($option){
		 $all_sizes = Ow_Vote_Common_Controller::ow_vote_list_thumbnail_sizes();
	?>
		<h2 class="color_h2"><?php _e('Page Common Settings','voting-contest'); ?></h2>
		<div class="settings_content">
			<h4><?php _e('Image Settings','voting-contest'); ?></h4>
			<form action="" method="post">
				<table class="form-table"> 		
					<tr valign="top">
						<th scope="row"><label for="short_cont_image"><?php _e('Shortcode Contest Image','voting-contest'); ?> </label></th>
						<td>
						<select class="size" data-user-setting="imgsize" data-setting="size" name="short_cont_image" id="short_cont_image">
						<?php foreach($all_sizes as $key=>$val): ?>
						<?php $selected = ($key == $option['short_cont_image'])?'selected':''; ?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
						<?php endforeach;?>
						</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><label for="page_cont_image"><?php _e('Contestants Page Image','voting-contest'); ?> </label></th>
						<td>
						<select class="size" data-user-setting="imgsize" data-setting="size" name="page_cont_image" id="page_cont_image">
						<?php foreach($all_sizes as $key=>$val): ?>
						<?php $selected = ($key == $option['page_cont_image'])?'selected':''; ?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
						<?php endforeach;?>
						</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><label for="single_page_cont_image"><?php _e('Single Contestant Image','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Width of Image','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2" style="float: left">
						<input type="text" id="single_page_cont_image" name="single_page_cont_image"  value="<?php echo $option['single_page_cont_image'] ?>"/>
						</td>
						
						<td colspan="2" style="float: left">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="single_page_cont_image_px" name="single_page_cont_image_px" <?php checked('on', $option['single_page_cont_image_px']); ?>/>
							<span class="switch-label" data-on="px" data-off="%"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
					</tr>
				</table>
				
				
				<h4><?php _e('Video Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 		
					<tr  valign="top">
					<th  scope="row"><label for="vote_turn_related"><?php _e('Show related videos?','voting-contest'); ?> </label>
					<div class="hasTooltip"></div>
						<div class="hidden">
						<span class="description"><?php _e('Turn On/Off Related Videos at the end of play','voting-contest'); ?></span>
						</div>
					</th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_turn_related" name="vote_turn_related" <?php checked('on', $option['vote_turn_related']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
					</tr>
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_video_width"><?php _e('Video Width','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Width of Video in px','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
						<input type="text" id="vote_video_width" name="vote_video_width"  value="<?php echo $option['vote_video_width'] ?>"/>
						</td>
					</tr>
					
					<tr  valign="top">
						<th  scope="row"><label for="single_contestants_video_width"><?php _e('Single Contestant Video Width','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Width of Video','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2" style="float: left">
						<input type="text" id="single_contestants_video_width" name="single_contestants_video_width"  value="<?php echo $option['single_contestants_video_width'] ?>"/>
						</td>
						<td colspan="2" style="float: left">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="single_contestants_video_width_px" name="single_contestants_video_width_px" <?php checked('on', $option['single_contestants_video_width_px']); ?>/>
							<span class="switch-label" data-on="px" data-off="%"></span>
							<span class="switch-handle"></span>
						</label>
					</tr>
					
				</table>
				
				<h4><?php _e('Audio Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 		
					<tr  valign="top">
						<th  scope="row"><label for="vote_audio_width"><?php _e('Audio Width','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Grid Width of Audio in px','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
						<input type="text" id="vote_audio_width" name="vote_audio_width"  value="<?php echo $option['vote_audio_width'] ?>"/>
						</td>
					</tr>
					<tr  valign="top">
						<th  scope="row"><label for="vote_audio_width"><?php _e('Audio Height','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Grid Height of Audio in px','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
						<input type="text" id="vote_audio_height" name="vote_audio_height"  value="<?php echo $option['vote_audio_height'] ?>"/>
						</td>
					</tr>
					<tr  valign="top">
						<th  scope="row"><label for="vote_audio_skin"><?php _e('Audio Player Skin','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Select Skin for the audio player','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
							<select name="vote_audio_skin" id="vote_audio_skin">
								<option value="hu-css"<?php selected($option['vote_audio_skin'], 'hu-css'); ?>><?php _e('Hulu Skin','voting-contest'); ?></option>
								<option value="vim-css"<?php selected($option['vote_audio_skin'], 'vim-css'); ?>><?php _e('Vimeo Skin','voting-contest'); ?></option>
								<option value="tube-css"<?php selected($option['vote_audio_skin'], 'tube-css'); ?>><?php _e('YouTube Skin','voting-contest'); ?></option>
							</select>
						</td>
					</tr>
				</table>
				
				<h4><?php _e('Essay Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 		
					<tr  valign="top">
						<th  scope="row"><label for="vote_essay_width"><?php _e('Essay Width','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Grid Width of Essay in px','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
						<input type="text" id="vote_essay_width" name="vote_essay_width"  value="<?php echo $option['vote_essay_width'] ?>"/>
						</td>
					</tr>
				</table>
				
				<h4><?php _e('Single Contestant Page Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 
					<tr valign="top">
						<th scope="row"><label for="single_page_title"><?php _e('Title Position: ','voting-contest'); ?></label>
							<div class="hasTooltip"></div>
							<div class="hidden">
							<span class="description"><?php _e('Shows the title in the Top of Image/Video/Music grid view.','voting-contest'); ?></span>
							</div>
						</th>
						<td>
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="single_page_title" name="single_page_title" <?php if ($option['single_page_title'] == 'on')
							echo 'checked="checked"'; ?>/>
								<span class="switch-label" data-on="Top" data-off="Bottom"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_prettyphoto_disable_single"><?php _e('Disable PrettyPhoto','voting-contest'); ?> </label>
						
						<div class="hasTooltip"></div>
							<div class="hidden">
							<span class="description"><?php _e('Disables PrettyPhoto in Single Contestants Page','voting-contest'); ?></span>
							</div>
						
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_prettyphoto_disable_single" name="vote_prettyphoto_disable_single" <?php checked('on', $option['vote_prettyphoto_disable_single']); ?>/>
								<span class="switch-label" data-on="Yes" data-off="No"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
					
				</table>
				
				<h4><?php _e('Content Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 
					<tr valign="top">
						<th scope="row"><label for="show_description"><?php _e('Title/Category Position: ','voting-contest'); ?></label>
							<div class="hasTooltip"></div>
							<div class="hidden">
							<span class="description"><?php _e('Shows the title in the Top of Image/Video/Music grid view.','voting-contest'); ?></span>
							</div>
						</th>
						<td>
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_title_alocation" name="vote_title_alocation" <?php if ($option['vote_title_alocation'] == 'on')
							echo 'checked="checked"'; ?>/>
								<span class="switch-label" data-on="Top" data-off="Bottom"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_notify_mail"><?php _e('Admin Notification','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<span class="description"><?php _e('Admin notify on contestant entry submission.','voting-contest'); ?></span>
							</div>
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
									<input class="switch-input" type="checkbox" id="vote_notify_mail" name="vote_notify_mail" <?php checked('on', $option['vote_notify_mail']); ?>/>
									<span class="switch-label" data-on="Yes" data-off="No"></span>
									<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
        

				<tr  valign="top">
                    <th  scope="row"><label for="vote_admin_mail"><?php _e('Notification E-Mail Id','voting-contest'); ?> </label>
		    
					<div class="hasTooltip"></div>
								<div class="hidden">
								<p class="description"><?php _e('Admin notification E-mail Id','voting-contest'); ?></p>
								<p class="description"><?php _e('Note: If no Email Id is set. Mail will be sent to admin email (Settings->General)','voting-contest'); ?></p>
								</div>
					</th>
                    <td colspan="2">
						<input type="text" id="vote_admin_mail" name="vote_admin_mail"  value="<?php echo ($option['vote_admin_mail'])?$option['vote_admin_mail']:$_POST['vote_admin_mail']; ?>"/>
						
					</td>
                </tr>
				
				<tr  valign="top">
                    <th  scope="row"><label for="vote_admin_mail_content"><?php _e('Email Custom Content','voting-contest'); ?> </label>
		    
					<div class="hasTooltip"></div>
								<div class="hidden">
								<p class="description"><?php _e('Admin notification E-mail Content','voting-contest'); ?></p>
								<p class="description"><?php _e('Note: This text will be appended after the Link of Contestants','voting-contest'); ?></p>
								</div>
					</th>
                    <td colspan="2">
						<?php
						$settings = array( 'media_buttons' => false );
						wp_editor(html_entity_decode($option['vote_admin_mail_content']), 'vote_admin_mail_content', $settings);
						?>
					</td>
                </tr>
        
				<tr  valign="top">
                    <th scope="row"><label for="vote_title"><?php _e('Display Title','voting-contest'); ?> </label>
		    
		    <div class="hasTooltip"></div>
						<div class="hidden"><p class="description"><?php _e('Title.','voting-contest'); ?></p></div>
		    </th>
                    <td colspan="2">
						<input type="text" id="vote_title" name="vote_title"  value="<?php echo $option['title'] ?>"/>
						
					</td>
                </tr>
        
	
				<tr valign="top">
                    <th scope="row"><label for="vote_orderby"><?php _e('Order by','voting-contest'); ?></label> </th>
                    <td colspan="2"> 
                        <select id="vote_orderby" name="vote_orderby" >
                            <option value="author"<?php selected($option['orderby'], 'author'); ?>><?php _e('Author','voting-contest'); ?></option>
                            <option value="date"<?php selected($option['orderby'], 'date'); ?>><?php _e('Date','voting-contest'); ?></option>
                            <option value="title"<?php selected($option['orderby'], 'title'); ?>><?php _e('Title','voting-contest'); ?></option>
                            <option value="modified"<?php selected($option['orderby'], 'modified'); ?>><?php _e('Modified','voting-contest'); ?></option>
                            <option value="menu_order"<?php selected($option['orderby'], 'menu_order'); ?>><?php _e('Menu Order','voting-contest'); ?></option>
                            <option value="parent"<?php selected($option['orderby'], 'parent'); ?>><?php _e('Parent','voting-contest'); ?></option>
                            <option value="id"<?php selected($option['orderby'], 'id'); ?>><?php _e('ID','voting-contest'); ?> </option>
                            <option value="votes"<?php selected($option['orderby'], 'votes'); ?>><?php _e('Votes','voting-contest'); ?></option>
			    <option value="rand"<?php selected($option['orderby'], 'rand'); ?>><?php _e('Random','voting-contest'); ?></option>
                        </select>
                       
                    </td>
                </tr>
				
		    <tr>
		    <th scope="row"><label for="vote_order"><?php _e('','voting-contest'); ?></label> </th>
		    <td>
			
			<label class="switch switch-slide">
				<input class="switch-input" type="checkbox" id="vote_order" name="vote_order" <?php checked('on', $option['order']); ?>/>
				<span class="switch-label" data-on="Asc" data-off="Desc"></span>
				<span class="switch-handle"></span>
			</label>
				     
                   </td>
                   </tr>
                
				<tr  valign="top">
					<th  scope="row"><label for="vote_select_sidebar"><?php _e('Select Sidebar','voting-contest'); ?> </label>
					
					<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Selected Sidebar Will Be Displayed In Contestant Description Page.','voting-contest'); ?></p>
						</div>
					
					</th>
					<td colspan="2">
						<select name="vote_select_sidebar" id="vote_select_sidebar">
							<option value="">None</option>
						<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
							 <option value="<?php echo ucwords( $sidebar['id'] ); ?>"
							 <?php echo ($option['vote_select_sidebar']==ucwords($sidebar['id']))?'selected':'';?>>
									  <?php echo ucwords( $sidebar['name'] ); ?>
							 </option>
						<?php } ?>
						</select>
						
					</td>
				</tr>
			
				<tr  valign="top">
					<th  scope="row"><label for="vote_imgdisplay"><?php _e('Disable Sidebar','voting-contest'); ?> </label>
					
					<div class="hasTooltip"></div>
						<div class="hidden">
						<span class="description"><?php _e('Disable Sidebar In Contestant Description Page.','voting-contest'); ?></span>
						</div>
					
					</th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_sidebar" name="vote_sidebar" <?php checked('on', $option['vote_sidebar']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				
				<tr  valign="top">
					<th  scope="row"><label for="vote_imgdisplay"><?php _e('Disable Read More Button','voting-contest'); ?> </label>
					
					<div class="hasTooltip"></div>
						<div class="hidden">
						<span class="description"><?php _e('Disable Read More Button In Contestant Page.','voting-contest'); ?></span>
						</div>
					
					</th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_readmore" name="vote_readmore" <?php checked('on', $option['vote_readmore']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				
				<tr valign="top">
				<th  scope="row"><label for="vote_entry_form"><?php _e('Default State of Entry Form','voting-contest'); ?> </label></th>
				<td colspan="2">
				
			<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_entry_form" name="vote_entry_form" <?php checked('on', $option['vote_entry_form']); ?>/>
							<span class="switch-label" data-on="Closed" data-off="Open"></span>
							<span class="switch-handle"></span>
						</label>
			
			
				</td>
				</tr>
						
						
				
										
				</table>
				
				<h4><?php _e('PrettyPhoto Settings','voting-contest'); ?></h4>  
    			<table class="form-table">
			    
					<tr  valign="top">
						<th  scope="row"><label for="vote_prettyphoto_disable"><?php _e('Disable PrettyPhoto','voting-contest'); ?> </label>
						
						<div class="hasTooltip"></div>
							<div class="hidden">
							<span class="description"><?php _e('Disables PrettyPhoto in Grid/List View and Redirects to the Single Contestants Page','voting-contest'); ?></span>
							</div>
						
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_prettyphoto_disable" name="vote_prettyphoto_disable" <?php checked('on', $option['vote_prettyphoto_disable']); ?>/>
								<span class="switch-label" data-on="Yes" data-off="No"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
			    
				  	<tr  valign="top">
						<th  scope="row"><label for="vote_show_date_prettyphoto"><?php _e('Show Date','voting-contest'); ?> </label>
						
						<div class="hasTooltip"></div>
							<div class="hidden">
							<span class="description"><?php _e('Show Date in Pretty Photo','voting-contest'); ?></span>
							</div>
						
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_show_date_prettyphoto" name="vote_show_date_prettyphoto" <?php checked('on', $option['vote_show_date_prettyphoto']); ?>/>
								<span class="switch-label" data-on="Yes" data-off="No"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
					
					
				</table>
			
				<h4><?php _e('Permalink Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 		
					
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_custom_slug"><?php _e('Custom Slug','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify the Custom Slug','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
						<input type="text" id="vote_custom_slug" name="vote_custom_slug"  value="<?php echo $option['vote_custom_slug'] ?>"/>
						</td>
					</tr>
					

					
				</table>
				
				<h4><?php _e('All Contest Page Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 						
					<tr  valign="top">
						<th  scope="row"><label for="vote_enable_all_contest"><?php _e('Enable Grid Flow Design','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify Whether to Enable or Disable Grid Flow Design in All Contest Page','voting-contest'); ?></p>
							</div>
						</th>
						
						<?php 
						$vote_all_contest_grid_design = array(
							'normal-grid'	=>__('Normal Grid','voting-contest'),
							'flow-grid'		=>__('Flow Grid','voting-contest'),
							'masonry-grid'	=> __('Masonry Grid','voting-contest')
						); 
						?>
						
						<td colspan="2">					
							<select id="vote_enable_all_contest" name="vote_enable_all_contest">
								<?php foreach($vote_all_contest_grid_design as $key => $method): ?>
									<?php $selected = ($key == $option['vote_enable_all_contest'])?'selected':''; ?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $method; ?></option>
								<?php endforeach; ?>
							</select>
						</td>          
									
					</tr>
					<?php $display_class_all = ($option['vote_enable_all_contest'] != 'flow-grid')?"style='display:none'":"style='visibility:run-in'"; ?>
					<tr  valign="top" class="vote_all_contest_design" <?php echo $display_class_all; ?>>
						<th  scope="row"><label for="vote_all_contest_design"><?php _e('Grid Loading Effect','voting-contest'); ?> </label>
				
							<div class="hasTooltip"></div>
										<div class="hidden">
										<p class="description"><?php _e('Select Grid Loading Effect for All Contest Page','voting-contest'); ?></p>
										</div>
							</th>
				
							<?php 
							$vote_all_contest_design = array(
												'swipe-right'=>'Swipe Right',
												'swipe-down'=>'Swipe Down',
												'swipe-rotate' => 'Rotate'
											); 
							?>
						<td colspan="2">					
							<select id="vote_all_contest_design" name="vote_all_contest_design">
								<?php foreach($vote_all_contest_design as $key => $method): ?>
									<?php $selected = ($key == $option['vote_all_contest_design'])?'selected':''; ?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $method; ?></option>
								<?php endforeach; ?>
							</select>
							
						</td>                    
					</tr>
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_enable_ended"><?php _e('Enable Expired Contest','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify whether Enable or Disable the Ended and Expired Contest in Category Select','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_enable_ended" name="vote_enable_ended" <?php checked('on', $option['vote_enable_ended']); ?>/>
								<span class="switch-label" data-on="Enable" data-off="Disable"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_count_showhide"><?php _e('Show/Hide Vote Count','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify whether to Show/Hide Vote Count In All Contestant Page','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_count_showhide" name="vote_count_showhide" <?php checked('on', $option['vote_count_showhide']); ?>/>
								<span class="switch-label" data-on="Show" data-off="Hide"></span>
								<span class="switch-handle"></span>
							</label>
						</td>
					</tr>
					
				</table>
				
				<h4><?php _e('Login Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 		
					
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_hide_account"><?php _e('Hide Create Account','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify Whether to Hide or Show Create Account tab in Login Form','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_hide_account" name="vote_hide_account" <?php checked('on', $option['vote_hide_account']); ?>/>
								<span class="switch-label" data-on="Yes" data-off="No"></span>
								<span class="switch-handle"></span>
							</label>
							
						</td>
					</tr>
					
					
				</table>
				
				<h4><?php _e('Mobile Menu Settings','voting-contest'); ?></h4>
				
				<table class="form-table"> 
					
					
					<tr  valign="top">
						<th  scope="row"><label for="vote_openclose_menu"><?php _e('Default State of Mobile Menu','voting-contest'); ?> </label>
						<div class="hasTooltip"></div>
							<div class="hidden">
							<p class="description"><?php _e('Specify Whether to Open or Close Mobile Menu','voting-contest'); ?></p>
							</div>
						</th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_openclose_menu" name="vote_openclose_menu" <?php checked('on', $option['vote_openclose_menu']); ?>/>
								<span class="switch-label" data-on="Open" data-off="Close"></span>
								<span class="switch-handle"></span>
							</label>
							
						</td>
					</tr>
					
					<tr valign="top">
						<th  scope="row"><input type="submit" value="<?php _e('Update','voting-contest'); ?>" name="Submit" class="button-primary"></th>
					</tr>
					
				</table>
				
				
				
				<input type="hidden" name="setting_action" value="common_save" />
			</form>
		</div>
		
	<?php
	}
}else
die("<h2>".__('Failed to load Voting admin common settings view','voting-contest')."</h2>");

if(!function_exists('ow_color_page_settings_view')){
	function ow_color_page_settings_view($color_option,$active = null){		 
		 $option = $color_option[$active];
		 ?>
		
		<h2 class="color_h2"><?php _e('Style Settings','voting-contest'); ?></h2>
		<div class="settings_content">
			<input type="hidden" id="ow_default_theme" name="ow_default_theme" value="<?php echo OW_DEF_THEME; ?>" />
			<form action="" id="ow_color_styler" method="post">
			    
			    <label for="owt_color_select"><?php _e('Select Color Theme','voting-contest'); ?></label>
			    <select name="owt_color_select" id="owt_color_select">
				<?php foreach($color_option as $key => $opt): ?>
				    <?php $selected = ($key == $active)?'selected':''; ?>
				    <option value="<?php echo $key; ?>" <?php echo $selected; ?>>
					<?php echo str_replace("_"," ",$key); ?>
				    </option>
				<?php endforeach;?>				
			    </select>
			    
			   	    
			    <?php $style_class = ($active == OW_DEF_THEME)?'style="display:none;"':''; ?>
			    <button <?php echo $style_class; ?>id="ow_delete_current_theme" name="ow_delete_current_theme" class="button-primary"><?php _e('Delete Current Theme','voting-contest'); ?></button>
			    
			    <div id="ow_loader_image">
				<img src="<?php echo OW_LOADER_IMAGE; ?>"/>
			   </div>
			    
			    <div class="owt_accordion_load">
				<?php echo ow_render_accordion($option); ?>
			    </div>
			    
			    <tr valign="top">
					<th  scope="row">
						<td colspan="2" style="margin:0px;padding:0px;"> 
						<span style="font-size:12px;padding-left:8px;"><?php _e('Check if the permission is set to 777 for the file "wp-content/plugins/wp-voting-contest/assets/css/ow_votes_color.css"','voting-contest'); ?></span>
						</td>
					</th>
			    </tr>
			    
			    <table>
				
			    <tr valign="top" class="save_as_template">
				<th  scope="row">				    
				    <td colspan="4"><label for="save_as_template"><?php _e('Name of the Template','voting-contest'); ?> </label></td>
				    <td class="save_as_template_col"></td>
				</th>
			    </tr>
				
			    <tr valign="top">
				<th  scope="row">
				    <td><input type="submit" id="vi_color_save" value="<?php _e('Save','voting-contest'); ?>" name="Submit" class="button-primary"></td>
				    <td><button id="save_as" name="save_as" class="button-primary"><?php _e('Save As','voting-contest'); ?></button></td>				 </th>
			    </tr>
			    
			    
			    
			    </table>				    
			    <input type="hidden" name="setting_action" value="vi_color_save" />
			</form>
		</div>
		
	<?php
	}
}else
die("<h2>".__('Failed to load Voting admin color settings view','voting-contest')."</h2>");


if(!function_exists('ow_share_contestant_settings_view')){
	function ow_share_contestant_settings_view($option){
		$path =  wp_upload_dir();
	?>
		<h2 class="color_h2"><?php _e('Share Settings','voting-contest'); ?></h2>
		<div class="settings_content">
			<form action="" method="post" enctype="multipart/form-data">
			<h4><?php _e('Facebook Sharing','voting-contest'); ?></h4>
			<table class="form-table">
				<tr>
					<th  scope="row"><label for="vote_deactivation"><?php _e('Facebook App ID','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" value="<?php echo $option['vote_fb_appid'] ?>" name="vote_fb_appid" id="vote_fb_appid" />					
					</td>
				</tr>
				<tr valign="top">
					<th  scope="row"><label for="vote_deactivation"><?php _e('Enable Facebook Sharing?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_facebook" name="vote_facebook" <?php checked('on', $option['facebook']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="facebook_login"><?php _e('Enable Facebook Login?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="facebook_login" name="facebook_login" <?php checked('on', $option['facebook_login']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>				
				<tr>
					<th  scope="row"><label for="vote_deactivation"><?php _e('Use default Facebook image?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_facebook_default_img" name="vote_facebook_default_img" <?php checked('on', $option['file_fb_default']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
						<input type="hidden" name="fb_uploaded_image" value="<?php echo $option['file_facebook']; ?>" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2">
						<input type="file" name="facebook_image" />
						<?php if($option['file_facebook']!='' && $option['file_fb_default']==''){?>
						<span style="position: relative;top: 10px;"><img style="height:auto;width:auto;" src="<?php echo $path['url'].'/'.$option['file_facebook']?>"/></span>
						<?php } ?>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Suggested Image Size is max 105px width - max 36px height.','voting-contest'); ?></p>
						<p class="description"><?php _e('Upload image to change the default facebook image.','voting-contest'); ?></p>
						</div>
					</td> 
				</tr>   
            </table>
						
			 <!-- Pinterest Sharing -->
            <h4><?php _e('Pinterest Sharing','voting-contest'); ?></h4>
			<table class="form-table">
				<tr  valign="top">
					<th scope="row"><label for="vote_deactivation"><?php _e('Enable Pinterest Sharing?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_pinterest" name="vote_pinterest" <?php checked('on', $option['pinterest']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				<tr>
					<th  scope="row"><label for="vote_deactivation"><?php _e('Use default pinterest image?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_pinterest_default_img" name="vote_pinterest_default_img" <?php checked('on', $option['file_pinterest_default']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
						<input type="hidden" name="pinterest_uploaded_image" value="<?php echo $option['file_pinterest']; ?>" />
					</td>
				</tr> 
				<tr>
					<td></td>
					<td colspan="2">
						<input type="file" name="pinterest_image" />
						<?php if($option['file_pinterest']!='' && $option['file_pinterest_default']==''){?>
						<span style="position: relative;top: 10px;"><img style="height:auto;width:auto;" src="<?php echo $path['url'].'/'.$option['file_pinterest']?>"/></span>
						<?php } ?>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Suggested Image Size is max 105px width - max 36px height.','voting-contest'); ?></p>
						<p class="description"><?php _e('Upload image to change the default pinterest image.','voting-contest'); ?></p>
						</div>
					</td> 
				</tr>
				
            </table>
			
			<h4><?php _e('Google Plus Sharing','voting-contest'); ?></h4>
			<table class="form-table">
				<tr  valign="top">
					<th  scope="row"><label for="vote_deactivation"><?php _e('Enable Google Plus Sharing?','voting-contest'); ?> </label></th>
					<td  colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_gplus" name="vote_gplus" <?php checked('on', $option['gplus']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				<tr>
					<th  scope="row"><label for="vote_deactivation"><?php _e('Use default google plus image?','voting-contest'); ?> </label></th>
					<td  colspan="2"> 
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_gplus_default_img" name="vote_gplus_default_img" <?php checked('on', $option['file_gplus_default']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
						<input type="hidden" name="gplus_uploaded_image" value="<?php echo $option['file_gplus']; ?>" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td  colspan="2">
						<input type="file" name="gplus_image" />
						<?php if($option['file_pinterest']!='' && $option['file_gplus_default']==''){?>
						<span style="position: relative;top: 10px;"><img style="height:auto;width:auto;" src="<?php echo $path['url'].'/'.$option['file_gplus']?>"/></span>
						<?php } ?>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Suggested Image Size is max 105px width - max 36px height.','voting-contest'); ?></p>
						<p class="description"><?php _e('Upload image to change the default Google Plus image.','voting-contest'); ?></p>
						</div>
					</td> 
				</tr> 
            </table>
			
			<!-- Tumblr Sharing -->
            <h4><?php _e('Tumblr Sharing','voting-contest'); ?></h4>
			<table class="form-table">
				<tr  valign="top">
					<th  scope="row"><label for="vote_deactivation"><?php _e('Enable Tumblr Sharing?','voting-contest'); ?> </label></th>
					<td  colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_tumblr" name="vote_tumblr" <?php checked('on', $option['tumblr']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>				
				<tr>
					<th  scope="row"><label for="vote_deactivation"><?php _e('Use default tumblr image?','voting-contest'); ?> </label></th>
					<td  colspan="2"> 
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_tumblr_default_img" name="vote_tumblr_default_img" <?php checked('on', $option['file_tumblr_default']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
						<input type="hidden" name="tumblr_uploaded_image" value="<?php echo $option['file_tumblr']; ?>" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td  colspan="2">
						<input type="file" name="tumblr_image" />
						<?php if($option['file_tumblr']!='' && $option['file_tumblr_default']==''){?>
						<span style="position: relative;top: 10px;"><img style="height:auto;width:auto;" src="<?php echo $path['url'].'/'.$option['file_tumblr']?>"/></span>
						<?php } ?>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Suggested Image Size is max 105px width - max 36px height.','voting-contest'); ?></p>
						<p class="description"><?php _e('Upload image to change the default Tumblr image.','voting-contest'); ?></p>
						</div>
					</td> 
				</tr>
            </table>
			
			<h4><?php _e('Twitter Sharing','voting-contest'); ?></h4>
            <table class="form-table">
				
				<tr>
					<th  scope="row"><label for="vote_twitter"><?php _e('Twitter API key','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" value="<?php echo $option['vote_tw_appid'] ?>" name="vote_tw_appid" id="vote_tw_appid" />
					</td>
				</tr>  
            
				<tr>
					<th  scope="row"><label for="vote_twitter"><?php _e('Twitter API Secret','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" value="<?php echo $option['vote_tw_secret'] ?>" name="vote_tw_secret" id="vote_tw_secret" />
					</td>
				</tr>
				
				<tr  valign="top">
					<th  scope="row"><label for="vote_twitter"><?php _e('Enable Twitter Sharing?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_twitter" name="vote_twitter" <?php checked('on', $option['twitter']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
				<tr  valign="top">
					<th scope="row"><label for="twitter_login"><?php _e('Enable Twitter Login?','voting-contest'); ?> </label></th>
					<td colspan="2">
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="twitter_login" name="twitter_login" <?php checked('on', $option['twitter_login']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
					</td>
				</tr>  
				<tr>
					<th  scope="row"><label for="twitter_login"><?php _e('Use default Twitter image?','voting-contest'); ?> </label></th>
					<td colspan="2"> 
						<label class="switch switch-slide">
							<input class="switch-input" type="checkbox" id="vote_twitter_default_img" name="vote_twitter_default_img" <?php checked('on', $option['file_tw_default']); ?>/>
							<span class="switch-label" data-on="Yes" data-off="No"></span>
							<span class="switch-handle"></span>
						</label>
						<input type="hidden" name="tw_uploaded_image" value="<?php echo $option['file_twitter']; ?>" />
					</td>
			   </tr>
				<tr>
					<th></th>
					<td colspan="2">
						<input type="file" name="twitter_image" />
						<?php if($option['file_twitter']!='' && $option['file_tw_default']==''){  ?>
						<span style="position: relative;top: 10px;"><img style="height:auto;width:auto;" src="<?php echo $path['url'].'/'.$option['file_twitter']?>"/></span>
						<?php } ?>
						<div class="hasTooltip"></div>
						<div class="hidden">
						<p class="description"><?php _e('Upload image to change the default tweet image.','voting-contest'); ?></p>
						<p class="description"><?php _e('Suggested Image Size is max 105px width - max 36px height.','voting-contest'); ?></p>
						</div>
					</td>
				</tr> 
                 
				<tr valign="top">
					<th  scope="row"><input type="submit" value="<?php _e('Update','voting-contest'); ?>" name="Submit" class="button-primary"></th>
				</tr>
            </table>
            
           
				<input type="hidden" name="setting_action" value="share_save" />
			</form>
		</div>
	<?php
	}
}else
die("<h2>".__('Failed to load Voting admin Share settings view','voting-contest')."</h2>");

if(!function_exists('ow_script_contestant_settings_view')){
	function ow_script_contestant_settings_view($option){
	?>
		<h2 class="color_h2"><?php _e('Script Settings','voting-contest'); ?></h2>
		<div class="settings_content">
			<form action="" method="post">
			<h4><?php _e('Deactivation Settings','voting-contest'); ?></h4>
			<table class="form-table">
				<tr  valign="top">
					   <th  scope="row"><label for="vote_deactivation"><?php _e('Deactivation Settings','voting-contest'); ?> </label></th>
					   <td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="vote_deactivation" name="vote_deactivation" <?php checked('on', $option['deactivation']); ?>/>
								<span class="switch-label" data-on="Yes" data-off="No"></span>
								<span class="switch-handle"></span>
							</label>
						<span class="description"><?php _e('Data will get retained after Deactivation.','voting-contest'); ?></span>
					   </td>
				</tr>
            </table>
			
			<h4><?php _e('Turn Off the Loading Scripts','voting-contest'); ?></h4>  
	
			<table class="form-table">
				<tr  valign="top">
						<th  scope="row"><label for="disable_jquery"><?php _e('Jquery','voting-contest'); ?> </label></th>
						<td colspan="2">
							<label class="switch switch-slide">
								<input class="switch-input" type="checkbox" id="disable_jquery" name="disable_jquery" <?php checked('on', $option['vote_disable_jquery']); ?>/>
								<span class="switch-label" data-on="Yes" data-off="No"></span>
								<span class="switch-handle"></span>
							</label>
							<span class="description"><?php _e('Disable Jquery from Loading.','voting-contest'); ?></span>
						</td>
				</tr>
	     
				<!--		     
				<tr  valign="top">
                    <th  scope="row"><label for="disable_jquery_fancy"><?php _e('FancyBox','voting-contest'); ?> </label></th>
                    <td colspan="2">
						<input type="checkbox" id="disable_jquery_fancy" name="disable_jquery_fancy"  <?php checked('on', $option['vote_disable_jquery_fancy']); ?>/>
						<span class="description"><?php _e('Disable FancyBox from Loading.','voting-contest'); ?></span>
                    </td>
				</tr>
	    
				<tr  valign="top">
                    <th  scope="row"><label for="disable_jquery_pretty"><?php _e('Pretty Photo','voting-contest'); ?> </label></th>
                    <td colspan="2">
						<input type="checkbox" id="disable_jquery_pretty" name="disable_jquery_pretty"  <?php checked('on', $option['vote_disable_jquery_pretty']); ?>/>
						<span class="description"><?php _e('Disable Pretty Photo from Loading.','voting-contest'); ?></span>
                    </td>
				</tr>
	    
				<tr  valign="top">
                    <th  scope="row"><label for="disable_jquery_validate"><?php _e('Jquery Validate','voting-contest'); ?> </label></th>
                    <td colspan="2"> <input type="checkbox" id="disable_jquery_validate" name="disable_jquery_validate"  <?php checked('on', $option['vote_disable_jquery_validate']); ?>/>
                    <span class="description"><?php _e('Disable Validate jquery plugin from Loading.','voting-contest'); ?></span>
                    </td>
				</tr>
                 -->
				 
				<tr valign="top">
					<th  scope="row"><input type="submit" value="<?php _e('Update','voting-contest'); ?>" name="Submit" class="button-primary"></th>
				</tr>       
            
			</table>
			<input type="hidden" name="setting_action" value="script_save" />
			</form>
		</div>
	<?php	
	}
}else
die("<h2>".__('Failed to load Voting admin Script settings view','voting-contest')."</h2>");


if(!function_exists('ow_render_accordion')){
    function ow_render_accordion($option){	
	ob_start();
	?>
		<div id="owt-accordion" class="owt-accordion">
			    <ul>
				<li>
				   <a href="#"><i class="owvotingicon owfa-list"></i><?php _e('Counter Colors','voting-contest'); ?><span class="st-arrow"></span></a>			      <div class="st-content">
					<table class="form-table">				    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_counter_font_size"><?php _e('Counter Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_counter_font_size" id="votes_counter_font_size" value="<?php  echo $option['votes_counter_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    <tr  valign="top">
						<th  scope="row"><label for="votes_timertextcolor"><?php _e('Count Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_timertextcolor" id="votes_timertextcolor" value="<?php  echo $option['votes_timertextcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_timerbgcolor"><?php _e('Counter Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_timerbgcolor" id="votes_timerbgcolor" value="<?php echo $option['votes_timerbgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					</table>		
				    </div>
				</li>
				
				<li>
				    <a href="#"><i class="owvotingicon owfa-compass"></i><?php _e('Navigation Bar','voting-contest'); ?><span class="st-arrow"></span></a>
				    <div class="st-content">
						
				<table class="form-table"> 
				    <tr  valign="top">
					<th  scope="row"><label for="votes_navigation_font_size"><?php _e('Navigation Font Size','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="number" min="0" max="60" name="votes_navigation_font_size" id="votes_navigation_font_size" value="<?php  echo $option['votes_navigation_font_size']; ?>" class="votes-font-text"/>
					<span class="votes-font-text-px">px</span>
				        </td>
				    </tr>
				    
				    <tr  valign="top">
					<th  scope="row"><label for="votes_navigation_text_color"><?php _e('Navigation Font Color','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="votes_navigation_text_color" id="votes_navigation_text_color" value="<?php  echo $option['votes_navigation_text_color']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>			    
				   
				    
				    
				    <tr valign="top">
					<th  scope="row"><label for="votes_navigation_text_color_hover"><?php _e('Navigation Font Color:Hover','voting-contest'); ?> </label></th>
					<td colspan="2"> 
				        <input type="text" maxlength="7" name="votes_navigation_text_color_hover" id="votes_navigation_text_color_hover" value="<?php echo $option['votes_navigation_text_color_hover']; ?>" class="votes-color-field"/>
					</td>
				    </tr>
				    <tr  valign="top">
					<th  scope="row"><label for="votes_navigationbgcolor"><?php _e('Navigation Background Color','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="votes_navigationbgcolor" id="votes_navigationbgcolor" value="<?php  echo $option['votes_navigationbgcolor']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
				    
				    <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('List View & Grid View Settings','voting-contest'); ?></div>
				        </th>
				    </tr>
				    
				    
				     <tr  valign="top">
					<th  scope="row"><label for="votes_list_active"><?php _e('List View Active','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="votes_list_active" id="votes_list_active" value="<?php  echo $option['votes_list_active']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
				    <tr  valign="top">
					<th  scope="row"><label for="votes_list_inactive"><?php _e('List View Inactive','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="votes_list_inactive" id="votes_list_inactive" value="<?php  echo $option['votes_list_inactive']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
				      
				    <tr  valign="top">
					<th  scope="row"><label for="votes_grid_active"><?php _e('Grid View Active','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="votes_grid_active" id="votes_grid_active" value="<?php  echo $option['votes_grid_active']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
				    <tr  valign="top">
					<th  scope="row"><label for="votes_grid_inactive"><?php _e('Grid View Inactive','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="votes_grid_inactive" id="votes_grid_inactive" value="<?php  echo $option['votes_grid_inactive']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
					
					<tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Menu Button Style Settings','voting-contest'); ?></div>
				        </th>
				    </tr>
					
					<tr  valign="top">
					<th  scope="row"><label for="vote_navbar_button_background"><?php _e('Inactive Button Background','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="vote_navbar_button_background" id="vote_navbar_button_background" value="<?php  echo $option['vote_navbar_button_background']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
					
					<tr  valign="top">
					<th  scope="row"><label for="vote_navbar_active_button_background"><?php _e('Active Button Background','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="vote_navbar_active_button_background" id="vote_navbar_active_button_background" value="<?php  echo $option['vote_navbar_active_button_background']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
					
					<tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Mobile Menu Style Settings','voting-contest'); ?></div>
				        </th>
				    </tr>
					
					<tr  valign="top">
					<th  scope="row"><label for="vote_navbar_button_background"><?php _e('Menu Arrow & Font Color','voting-contest'); ?> </label></th>
					<td colspan="2"> 
					<input type="text" maxlength="7" name="vote_navbar_mobile_font" id="vote_navbar_mobile_font" value="<?php  echo $option['vote_navbar_mobile_font']; ?>" class="votes-color-field"/>
				        </td>
				    </tr>
					
				</table>
				
						
				    </div>
				</li>
				
				<li>
				    <a href="#"><i class="owvotingicon owfa-university"></i><?php _e('Contestant Title','voting-contest'); ?><span class="st-arrow"></span></a>
				    <div class="st-content">
					<table class="form-table">
					    
					    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_cont_title_font_size"><?php _e('Contestant Title Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_cont_title_font_size" id="votes_cont_title_font_size" value="<?php  echo $option['votes_cont_title_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_cont_title_bgcolor"><?php _e('Contestant Title Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_title_bgcolor" id="votes_cont_title_bgcolor" value="<?php echo $option['votes_cont_title_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					    <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('List View Settings','voting-contest'); ?></div>
						</th>
					    </tr>
					      
					    <tr  valign="top">
						<th  scope="row"><label for="votes_cont_title_color"><?php _e('Contestant Title Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_title_color" id="votes_cont_title_color" value="<?php echo $option['votes_cont_title_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_cont_title_color_hover"><?php _e('Contestant Title Font Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_title_color_hover" id="votes_cont_title_color_hover" value="<?php echo $option['votes_cont_title_color_hover']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					     <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Grid View Settings','voting-contest'); ?></div>
						</th>
					    </tr>
					      
					    <tr  valign="top">
						<th  scope="row"><label for="votes_cont_title_color_grid"><?php _e('Contestant Title Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_title_color_grid" id="votes_cont_title_color_grid" value="<?php echo $option['votes_cont_title_color_grid']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_cont_title_color_hover_grid"><?php _e('Contestant Title Font Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_title_color_hover_grid" id="votes_cont_title_color_hover_grid" value="<?php echo $option['votes_cont_title_color_hover_grid']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					</table>		
				    </div>
				</li>
				
				<li>
				    <a href="#"><i class="owvotingicon owfa-desc"></i><?php _e('Contestant Description','voting-contest'); ?><span class="st-arrow"></span></a>
				    <div class="st-content">
					<table class="form-table">				    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_cont_desc_font_size"><?php _e('Contestant Description Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_cont_desc_font_size" id="votes_cont_desc_font_size" value="<?php  echo $option['votes_cont_desc_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    <tr  valign="top">
						<th  scope="row"><label for="votes_cont_dese_color"><?php _e('Contestant Description Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_dese_color" id="votes_cont_dese_color" value="<?php echo $option['votes_cont_dese_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_cont_desc_bgcolor"><?php _e('Contestant Description Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_cont_desc_bgcolor" id="votes_cont_desc_bgcolor" value="<?php echo $option['votes_cont_desc_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Read More Settings','voting-contest'); ?></div>
						</th>
					    </tr>
					    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_readmore_font_size"><?php _e('Read More Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_readmore_font_size" id="votes_readmore_font_size" value="<?php  echo $option['votes_readmore_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					     <tr valign="top">
						<th  scope="row"><label for="votes_readmore_fontcolor"><?php _e('Read More Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_readmore_fontcolor" id="votes_readmore_fontcolor" value="<?php echo $option['votes_readmore_fontcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					     <tr valign="top">
						<th  scope="row"><label for="votes_readmore_fontcolor_hover"><?php _e('Read More Font Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_readmore_fontcolor_hover" id="votes_readmore_fontcolor_hover" value="<?php echo $option['votes_readmore_fontcolor_hover']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     <tr valign="top">
					        <th  scope="row"><label for="votes_readmore_bgcolor"><?php _e('Read More Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_readmore_bgcolor" id="votes_readmore_bgcolor" value="<?php echo $option['votes_readmore_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     <tr valign="top">
						<th  scope="row"><label for="votes_readmore_bgcolor_hover"><?php _e('Read More Background Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_readmore_bgcolor_hover" id="votes_readmore_bgcolor_hover" value="<?php echo $option['votes_readmore_bgcolor_hover']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					    <tr valign="top">
						<th  scope="row"><label for="votes_readmore_padding_top"><?php _e('Read More Padding Top','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text"  name="votes_readmore_padding_top" id="votes_readmore_padding_top" value="<?php  echo $option['votes_readmore_padding_top']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_readmore_padding_bottom"><?php _e('Read More Padding Bottom','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" name="votes_readmore_padding_bottom" id="votes_readmore_padding_bottom" value="<?php  echo $option['votes_readmore_padding_bottom']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_readmore_padding_left"><?php _e('Read More Padding Left','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" name="votes_readmore_padding_left" id="votes_readmore_padding_left" value="<?php  echo $option['votes_readmore_padding_left']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					     
					    <tr valign="top">
						<th  scope="row"><label for="votes_readmore_padding_right"><?php _e('Read More Padding Right','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" name="votes_readmore_padding_right" id="votes_readmore_padding_right" value="<?php  echo $option['votes_readmore_padding_right']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr> 
					     
					</table>		
				    </div>
				</li>
				
				<li>
				    <a href="#"><i class="owvotingicon owfa-voting"></i><?php _e('Voting and Sharing','voting-contest'); ?><span class="st-arrow"></span></a>
				    <div class="st-content">
					<table class="form-table">
					    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_bar_border_color"><?php _e('Vote Bar Border Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_bar_border_color" id="votes_bar_border_color" value="<?php echo $option['votes_bar_border_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>		    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_bar_border_size"><?php _e('Vote Bar Border Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_bar_border_size" id="votes_bar_border_size" value="<?php  echo $option['votes_bar_border_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    
					    <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Vote Count Settings','voting-contest'); ?></div>
						</th>
					    </tr>
					    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_count_font_size"><?php _e('Vote Count Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_count_font_size" id="votes_count_font_size" value="<?php  echo $option['votes_count_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_count_font_color"><?php _e('Vote Count Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_count_font_color" id="votes_count_font_color" value="<?php echo $option['votes_count_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     <tr valign="top">
						<th  scope="row"><label for="votes_count_bgcolor"><?php _e('Vote Count Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_count_bgcolor" id="votes_count_bgcolor" value="<?php echo $option['votes_count_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					    <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Vote Button Settings','voting-contest'); ?></div>
						</th>
					    </tr> 
					    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_button_font_size"><?php _e('Vote Button Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_button_font_size" id="votes_button_font_size" value="<?php  echo $option['votes_button_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					     <tr valign="top">
						<th  scope="row"><label for="votes_button_font_color"><?php _e('Vote Button Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_button_font_color" id="votes_button_font_color" value="<?php echo $option['votes_button_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_button_font_color_hover"><?php _e('Vote Button Font Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_button_font_color_hover" id="votes_button_font_color_hover" value="<?php echo $option['votes_button_font_color_hover']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_button_bgcolor"><?php _e('Vote Button Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_button_bgcolor" id="votes_button_bgcolor" value="<?php echo $option['votes_button_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    <tr valign="top">
						<th  scope="row"><label for="votes_button_bgcolor_hover"><?php _e('Vote Button Background Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_button_bgcolor_hover" id="votes_button_bgcolor_hover" value="<?php echo $option['votes_button_bgcolor_hover']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
						<tr valign="top">
						<th  scope="row"><label for="votes_highlight_button_bgcolor"><?php _e('Highlight Voted Button Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_highlight_button_bgcolor" id="votes_highlight_button_bgcolor" value="<?php echo $option['votes_highlight_button_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						<tr valign="top">
							<td colspan="2" style="margin:0px;padding:0px;"> 
							<span style="font-size:12px;"><?php _e('Highlight Voted button (vote registered) only works for voting frequency is set to "Per Category and Per Calendar Day"','voting-contest'); ?></span>
							</td>
						</tr>
						
						<tr valign="top">
						<th  scope="row"><label for="votes_already_button_bgcolor"><?php _e('Other Voted Button Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_already_button_bgcolor" id="votes_already_button_bgcolor" value="<?php echo $option['votes_already_button_bgcolor']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						<tr valign="top">
							<td colspan="2" style="margin:0px;padding:0px;"> 
							<span style="font-size:12px;"><?php _e('Other Voted button (Non votable) only works for voting frequency is set to "Per Category and Per Calendar Day"','voting-contest'); ?></span>
							</td>
						</tr>
					   
					    
					    <tr  valign="top">					
						<th colspan="10"> 
						<div class="ow_setting_info"><?php _e('Social Icons Settings','voting-contest'); ?></div>
						</th>
					    </tr>
					     <tr  valign="top">
						<th  scope="row"><label for="votes_social_font_size"><?php _e('Social Icon Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_social_font_size" id="votes_social_font_size" value="<?php  echo $option['votes_social_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					      <tr valign="top">
						<th  scope="row"><label for="votes_social_icon_color"><?php _e('Social Icon Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_social_icon_color" id="votes_social_icon_color" value="<?php echo $option['votes_social_icon_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					       <tr valign="top">
						<th  scope="row"><label for="votes_social_icon_color_hover"><?php _e('Social Icon Color:Hover','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_social_icon_color_hover" id="votes_social_icon_color_hover" value="<?php echo $option['votes_social_icon_color_hover']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					</table>		
				    </div>
				</li>
				
			    <li>
					<a href="#"><i class="owvotingicon owfa-single ow_padding_5"></i><?php _e('Single Contestants page','voting-contest'); ?><span class="st-arrow"></span></a>
					<div class="st-content">
					<table class="form-table">
						
						<tr valign="top">
							<th  scope="row"><label for="single_navigation_button"><?php _e('Navigation Button Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="single_navigation_button" id="single_navigation_button" value="<?php echo $option['single_navigation_button']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="single_navigation_button_hover"><?php _e('Navigation Button Hover Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="single_navigation_button_hover" id="single_navigation_button_hover" value="<?php echo $option['single_navigation_button_hover']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_cont_title_color_single"><?php _e('Contestant Title Text Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_cont_title_color_single" id="votes_cont_title_color_single" value="<?php echo $option['votes_cont_title_color_single']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_cont_content_color_single"><?php _e('Contestant Content Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_cont_content_color_single" id="votes_cont_content_color_single" value="<?php echo $option['votes_cont_content_color_single']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="single_contestant_content_bg"><?php _e('Contestant Content Background','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="single_contestant_content_bg" id="single_contestant_content_bg" value="<?php echo $option['single_contestant_content_bg']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_single_social_sharing"><?php _e('Social Sharing Bar','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_single_social_sharing" id="votes_single_social_sharing" value="<?php echo $option['votes_single_social_sharing']; ?>" class="votes-color-field"/>
							</td>
						</tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_single_social_sharing_url_color"><?php _e('Social Sharing Url Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_single_social_sharing_url_color" id="votes_single_social_sharing_url_color" value="<?php echo $option['votes_single_social_sharing_url_color']; ?>" class="votes-color-field"/>
							</td>
						</tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_single_social_sharing_bg"><?php _e('Social Sharing Box Background','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_single_social_sharing_bg" id="votes_single_social_sharing_bg" value="<?php echo $option['votes_single_social_sharing_bg']; ?>" class="votes-color-field"/>
							</td>
						</tr>
					</table>
					</div>
				</li>
				
				<li>
					<a href="#"><i class="owvotingicon owfa-popup ow_padding_5"></i><?php _e('Contestant Pop-up','voting-contest'); ?><span class="st-arrow"></span></a>
					<div class="st-content">
					<table class="form-table">				    
					   <!--YST-->
						<tr valign="top">
							<th  scope="row"><label for="votes_popup_content_bg"><?php _e('Contestant Pop-up Background','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_popup_content_bg" id="votes_popup_content_bg" value="<?php echo $option['votes_popup_content_bg']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_popup_additional_info_color"><?php _e('Additional Info Title Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_popup_additional_info_color" id="votes_popup_additional_info_color" value="<?php echo $option['votes_popup_additional_info_color']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_popup_additional_info_bg"><?php _e('Additional Info Background','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_popup_additional_info_bg" id="votes_popup_additional_info_bg" value="<?php echo $option['votes_popup_additional_info_bg']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
						
						<tr valign="top">
							<th  scope="row"><label for="votes_popup_content_color"><?php _e('Contestant Content Font Color','voting-contest'); ?> </label></th>
							<td colspan="2"> 
							<input type="text" maxlength="7" name="votes_popup_content_color" id="votes_popup_content_color" value="<?php echo $option['votes_popup_content_color']; ?>" class="votes-color-field"/>
							</td>
					    </tr>
					</table>
					</div>
				</li>
				
				<li>
				    <a href="#"><i class="owvotingicon owfa-sort-numeric-asc ow_padding_5"></i><?php _e('Pagination','voting-contest'); ?><span class="st-arrow"></span></a>
				    <div class="st-content">
					<table class="form-table">				    
					    <tr  valign="top">
						<th  scope="row"><label for="votes_pagination_font_size"><?php _e('Pagination Font Size','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="number" min="0" max="60" name="votes_pagination_font_size" id="votes_pagination_font_size" value="<?php  echo $option['votes_pagination_font_size']; ?>" class="votes-font-text"/>
						<span class="votes-font-text-px">px</span>
						</td>
					    </tr>
					    <tr  valign="top">
						<th  scope="row"><label for="votes_pagination_font_color"><?php _e('Pagination Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_pagination_font_color" id="votes_pagination_font_color" value="<?php echo $option['votes_pagination_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_pagination_active_font_color"><?php _e('Pagination Active Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_pagination_active_font_color" id="votes_pagination_active_font_color" value="<?php echo $option['votes_pagination_active_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_pagination_active_bg_color"><?php _e('Pagination Active Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_pagination_active_bg_color" id="votes_pagination_active_bg_color" value="<?php echo $option['votes_pagination_active_bg_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_pagination_hover_bg_color"><?php _e('Pagination Hover Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_pagination_hover_bg_color" id="votes_pagination_hover_bg_color" value="<?php echo $option['votes_pagination_hover_bg_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="votes_pagination_hover_font_color"><?php _e('Pagination Hover Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="votes_pagination_hover_font_color" id="votes_pagination_hover_font_color" value="<?php echo $option['votes_pagination_hover_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					     
					     
					     
					</table>		
				    </div>
				</li>
				
				<li>
				    <a href="#"><i class="owvotingicon owfa-login"></i><?php _e('Login/Register Pop-up','voting-contest'); ?><span class="st-arrow"></span></a>
				    <div class="st-content">
					<table class="form-table">
						
						<tr valign="top">
						<th  scope="row"><label for="login_tab_active_bg_color"><?php _e('Tab Active Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_tab_active_bg_color" id="login_tab_active_bg_color" value="<?php echo $option['login_tab_active_bg_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="login_tab_hover_bg_color"><?php _e('Tab Hover Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_tab_hover_bg_color" id="login_tab_hover_bg_color" value="<?php echo $option['login_tab_hover_bg_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr  valign="top">
						<th  scope="row"><label for="login_tab_font_color"><?php _e('Tab Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_tab_font_color" id="login_tab_font_color" value="<?php echo $option['login_tab_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
					    
					    <tr valign="top">
						<th  scope="row"><label for="login_tab_active_font_color"><?php _e('Tab Active Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_tab_active_font_color" id="login_tab_active_font_color" value="<?php echo $option['login_tab_active_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
						<tr valign="top">
						<th  scope="row"><label for="login_tab_hover_font_color"><?php _e('Tab Hover Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_tab_hover_font_color" id="login_tab_hover_font_color" value="<?php echo $option['login_tab_hover_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
					    <tr valign="top">
						<th  scope="row"><label for="login_body_background_color"><?php _e('Popup Body Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_body_background_color" id="login_body_background_color" value="<?php echo $option['login_body_background_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
						<tr valign="top">
						<th  scope="row"><label for="popup_body_text_color"><?php _e('Popup Body Text Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="popup_body_text_color" id="popup_body_text_color" value="<?php echo $option['popup_body_text_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
						<tr valign="top">
						<th  scope="row"><label for="login_button_background_color"><?php _e('Button Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_button_background_color" id="login_button_background_color" value="<?php echo $option['login_button_background_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
						<tr valign="top">
						<th  scope="row"><label for="login_button_hover_bg_color"><?php _e('Button Hover Background Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_button_hover_bg_color" id="login_button_hover_bg_color" value="<?php echo $option['login_button_hover_bg_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
						<tr valign="top">
						<th  scope="row"><label for="login_button_font_color"><?php _e('Button Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_button_font_color" id="login_button_font_color" value="<?php echo $option['login_button_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
						<tr valign="top">
						<th  scope="row"><label for="login_button_hover_font_color"><?php _e('Button Hover Font Color','voting-contest'); ?> </label></th>
						<td colspan="2"> 
						<input type="text" maxlength="7" name="login_button_hover_font_color" id="login_button_hover_font_color" value="<?php echo $option['login_button_hover_font_color']; ?>" class="votes-color-field"/>
						</td>
					    </tr>
						
					     
					     
					     
					</table>		
				    </div>
				</li>
			    
			    </ul>
			    </div>
			    
	<?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
    }
}

?>

<script type="text/javascript">
	jQuery(document).ready(function(){
	    
	    jQuery('.votes-color-field').wpColorPicker({
		change: function(event, ui)
		{
		    check_default_theme();
		}
	    });
	    
	    jQuery(".votes-font-text").change(function() {
		check_default_theme();  
	    });
	    
	    jQuery(function() {			
		jQuery('#owt-accordion').accordion({
			open : 0,
			oneOpenedItem	: true
		});				
	    });  
	    
	    
    jQuery('.hasTooltip').each(function() { // Notice the .each() loop, discussed below
    jQuery(this).qtip({
        content: {
            text: jQuery(this).next('div') // Use the "div" element next to this for the content
        }
    });
    });
		
	});
	
	function check_default_theme(){	   
	    var ow_default_theme = jQuery('#ow_default_theme').val();
	    var ow_current_theme = jQuery('#owt_color_select').val();
	    
	    if (ow_current_theme == ow_default_theme) {
		jQuery('#vi_color_save').hide();
	    }
	}
	
	function isnumber(evt){
	  var charCode = (evt.which) ? evt.which : evt.keyCode
	   //var charCode = evt.keyCode == 0 ? evt.charCode : evt.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57))
		  return false;	  
	  return true;
	}
</script>
