<?php
if(!function_exists('ow_contestants_search')){
    function ow_contestants_search(){
		$ow_search 	  = get_query_var('ow_search');
		?>
		<form name="ow_contestants_search" id="ow_contestants_search" method="GET">
			<input type="text" id="ow_search_input" name="ow_search_input" placeholder="Search" autocomplete="off" value="<?php echo $ow_search; ?>" />
			<div id="ow_search_result"></div>
		</form>
		<?php
    }
}else{
    die("<h2>".__('Failed to load Contestant Search Form','voting-contest')."</h2>");
}
?>
