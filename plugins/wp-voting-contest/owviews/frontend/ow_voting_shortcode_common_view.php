<?php
if(!function_exists('ow_voting_shortcode_total_count_view')){
   function ow_voting_shortcode_total_count_view($id,$total_count_query){
		$votes_count = 0;		   
		if ( $total_count_query->have_posts() ) {
			while ( $total_count_query->have_posts() ) {
				$total_count_query->the_post();
				$votes_count += get_post_meta(get_the_id(),'votes_count',true);          
			}
		 wp_reset_postdata();
		} 
		
		
		if($votes_count){
			$term_name = get_term($id,OW_VOTES_TAXONOMY);
			?>
			<div>
				<span class="ow_total_result_count">
				<?php echo __('Total votes for the Contest','voting-contest').' "'.$term_name->name.'" : ';?>
				<span class="total_cnt_vote_res<?php echo $id;?>"> <?php echo $votes_count; ?> </span></span>
			</div>		
		<?php
		}
   }
}else{
    die("<h2>".__('Failed to load Total Count Contest view','voting-contest')."</h2>");
}
?>