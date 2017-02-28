<?php
if(!function_exists('ow_voting_shortcode_endcontest_view')){
    function ow_voting_shortcode_endcontest_view($contest_args){		
		extract( shortcode_atts( array(
			  'id' => NULL,
			  'message'=>1
		), $contest_args ) );

		$option = get_option(OW_VOTES_SETTINGS);
		//Check if they used one more id one id is allowed
		$idarr = explode(',', $id);
		$curterm = $time = NULL;
		if (count($idarr) > 1) {			
			   return FALSE;
		} 
		else if( !is_wp_error($curterm = get_term( $id, OW_VOTES_TAXONOMY)) && isset($curterm)) {
			   $time = get_option($curterm->term_id . '_' . OW_VOTES_TAXEXPIRATIONFIELD);
		}
		
		if($time != '0' && $time) {
			$timeentered = strtotime(str_replace("-", "/", $time));
			$currenttime = current_time( 'timestamp', 0 );
			$currenttime1 = str_replace(' ','-',str_replace(':','-',current_time( 'mysql', 0 )));
			$time = date('Y-m-d-H-i-s', strtotime(str_replace('-', '/', $time)));
			
			if($currenttime <= $timeentered) {
			?>
				<div class="ow_countdown_wrapper">
					<div class="ow_countdown_desc_wrapper countdown_enddesc_wrapper">
						<div class="ow_countdown_tag"><?php _e('Contest Ends In:','voting-contest'); ?></div>
					</div>
					<div class="countdown_end_timer ow_countdown_dashboard" id="countdown_end_dashboard<?php echo $id; ?>" data-datetimer="<?php echo $time; ?>"  data-currenttimer="<?php echo $currenttime1; ?>">
						
						<div class="dash weeks_dash">
							<div class="dash_title"><?php _e('weeks','voting-contest'); ?></div>
							<div class="dash_inner">
								<div class="digit digit_first">0</div>
								<div class="digit digit_second">0</div>
							</div>	
						</div>
						
						<div class="dash days_dash">
							<div class="dash_title"><?php _e('days','voting-contest'); ?></div>
							<div class="dash_inner">
								<div class="digit digit_first">0</div>
								<div class="digit digit_second">0</div>
							</div>	
						</div>
						
						<div class="dash hours_dash">
							<div class="dash_title"><?php _e('hours','voting-contest'); ?></div>
							<div class="dash_inner">
								<div class="digit digit_first">0</div>
								<div class="digit digit_second">0</div>
							</div>	
						</div>
						
						<div class="dash minutes_dash">
							<div class="dash_title"><?php _e('minutes','voting-contest'); ?></div>
							<div class="dash_inner">
								<div class="digit digit_first">0</div>
								<div class="digit digit_second">0</div>
							</div>	
						</div>

						<div class="dash seconds_dash">
							<div class="dash_title"><?php _e('seconds','voting-contest'); ?></div>
							<div class="dash_inner">
								<div class="digit digit_first">0</div>
								<div class="digit digit_second">0</div>
							</div>	
						</div>

					</div>
				</div>
			<?php
			}
			else{
				if($message){
			?>
			<div class="ow_countdown_wrapper">
				<div class="countdown_title"><?php _e('Contest Ended','voting-contest'); ?></div>
			</div>
			<?php
				}
			}
		}
	}
}else{
    die("<h2>".__('Failed to load Voting End Contest view','voting-contest')."</h2>");
}
?>