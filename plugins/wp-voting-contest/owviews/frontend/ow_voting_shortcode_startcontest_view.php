<?php
if(!function_exists('ow_voting_shortcode_startcontest_view')){
    function ow_voting_shortcode_startcontest_view($contest_args){
		extract( shortcode_atts( array(
			  'id' => NULL,
			  'showcontestants' => 1,
			  'message' => 1
			), $contest_args ) );

		$option = get_option(OW_VOTES_SETTINGS);
		$valid = FALSE;
		if(!is_wp_error($curterm = get_term( $id, OW_VOTES_TAXONOMY)) && isset($curterm) ) {
			$valid = TRUE;
				
			$idarr = explode(',', $id);
			$curterm = $time = NULL;
			if (count($idarr) > 1) {			
				   return FALSE;
			}
			else if( !is_wp_error($curterm = get_term( $id, OW_VOTES_TAXONOMY)) && isset($curterm) ) {
					$time = get_option($curterm->term_id . '_' . OW_VOTES_TAXSTARTTIME);
			}
			
			if($time != '0' && $time) {
				$timeentered = strtotime(str_replace("-", "/", $time));
				$currenttime = current_time( 'timestamp', 0 );
				$time = date('Y-m-d-H-i-s', strtotime(str_replace('-', '/', $time)));
				$currenttime1 = str_replace(' ','-',str_replace(':','-',current_time( 'mysql', 0 )));
				
				if($currenttime <= $timeentered) {
					if($showcontestants && $valid ){
						echo do_shortcode('[showcontestants id="'.$id.'" forcedisplay=1 showtimer=1 hidecontestants=1]'); 
						return;
					}
				?>
				<div class="ow_countdown_wrapper">
					<div class="ow_countdown_desc_wrapper countdown_enddesc_wrapper">
						<div class="ow_countdown_tag"><?php _e('Voting Starts In:','voting-contest'); ?></div>
					</div>
					<div class="countdown_end_timer ow_countdown_dashboard" id="countdown_end_dashboard<?php echo $id; ?>" data-datetimer="<?php echo $time; ?>"  data-currenttimer="<?php echo $currenttime1; ?>">
						<div class="dash weeks_dash">
							<div class="digit">0</div>
							<div class="digit">0</div>
							<span class="dash_title"><?php _e('weeks','voting-contest'); ?></span>
						</div>
						
						<div class="dash days_dash">
							<div class="digit">0</div>
							<div class="digit">0</div>
							<span class="dash_title"><?php _e('days','voting-contest'); ?></span>
						</div>
						
						<div class="dash hours_dash">
							<div class="digit">0</div>
							<div class="digit">0</div>
							<span class="dash_title"><?php _e('hours','voting-contest'); ?></span>
						</div>
						
						<div class="dash minutes_dash">
							<div class="digit">0</div>
							<div class="digit">0</div>
							<span class="dash_title"><?php _e('minutes','voting-contest'); ?></span>
						</div>

						<div class="dash seconds_dash">
							<div class="digit">0</div>
							<div class="digit">0</div>
							<span class="dash_title"><?php _e('seconds','voting-contest'); ?></span>
						</div>
					</div>
				</div>
				<?php
				}else {
					if($message) {
						?>
					   <div class="votes_error error"><?php _e('No Upcoming Contest','voting-contest'); ?></div>
					  <?php
					}
				}
			}
			else {
				if($message) {
				?>
				   <div class="votes_error error"><?php _e('Contest already Started.','voting-contest'); ?></div>
				  <?php
				}
			}
		}else {
			if($message) {
			  ?>
			   <div class="votes_error error"><?php _e('No Upcoming Contest','voting-contest'); ?></div>
			  <?php
			}
		} 
		
		if($showcontestants && $valid)
		   echo do_shortcode('[showcontestants id="'.$id.'" forcedisplay=1 showtimer=0]');
	}
}else{
    die("<h2>".__('Failed to load Voting Start Contest view','voting-contest')."</h2>");
}
?>