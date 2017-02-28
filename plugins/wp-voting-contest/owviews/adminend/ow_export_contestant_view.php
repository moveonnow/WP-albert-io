<?php
if(!function_exists('ow_voting_export_contestants_view')){
    function ow_voting_export_contestants_view(){
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
    ?>
    <div class="wrap">
        <h2><?php _e('Export Contestant Details','voting-contest'); ?></h2>
        <div class="narrow">
            <form action="admin.php" method="get" name="votes_export_form" id="votes_export_form">
                <p> <?php _e('Please select the contest you want to export.','voting-contest'); ?></p>
                <table class="form-table"> 
            
                    <tr valign="top">
                        <th scope="row">  <?php _e('Select the Contest','voting-contest'); ?>  </th>
                        <td>   
                            <?php
                            wp_dropdown_categories(array('hide_empty' => false,
                                'name' => 'vote_contest_term',
                                'id' => 'vote_contest_term',
                                'hierarchical' => 1,
                                'show_count' => 0,
                                'taxonomy' => OW_VOTES_TAXONOMY,
                                'show_option_none' => __('Select the Category','voting-contest')));
                            ?>
							<div class="error_cat"></div>
                        </td>
                    </tr>
					
					<tr valign="top">
                        <th scope="row">  <?php _e('Export File','voting-contest'); ?>  </th>
                        <td>   
                            <select name="export" class="export_contestant">
								<option value="0"><?php _e('Select','voting-contest'); ?></option>
								<option value="CSV"><?php _e('CSV','voting-contest'); ?></option>
								<option value="html"><?php _e('HTML','voting-contest'); ?></option>
								<option value="excel_xlsx"><?php _e('Excel 2007 (XLSX)','voting-contest'); ?></option>
								<option value="excel_xls"><?php _e('Excel 2003 (XLS)','voting-contest'); ?></option>
								<option value="excel_ods"><?php _e('Open office (ODS)','voting-contest'); ?></option>
							</select>
							<div class="error_type"></div>
                        </td>
                    </tr>
										
                </table>
                <input type="hidden" name="votes_export" id="votes_export" value="Export" />
    
                <p class="submit"><input type="submit" value="<?php _e('Export','voting-contest'); ?>" class="button" id="votes_export_button" name="votes_export_button" /></p>
            </form>
	    <h5><?php _e('Please note: In order to properly export, commas will be stripped from the export','voting-contest'); ?></h5>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
		jQuery('#votes_export_form').submit(function(){
		   var dropval = jQuery('#vote_contest_term').val();
				var export_cotest = jQuery('.export_contestant').val();
				if(dropval=='-1'){
					jQuery('.error_category').hide();
					jQuery( ".error_cat" ).html( "<p style='position: relative;color:red;' class='error_category'><?php _e('Select the category to export','voting-contest'); ?></p>" );
					return false;
				}else if(export_cotest=='0'){
					jQuery('.error_category').hide();
					jQuery( ".error_type" ).html( "<p style='position: relative;color:red;' class='error_type_p'><?php _e('Select the export type','voting-contest'); ?></p>" );
					return false;
				}else{
					jQuery('.error_category').hide();
					jQuery('.error_type_p').hide();
					return true;
				}
		});
    });
    </script>
    <?php
    }
}else{
    die("<h2>".__('Failed to load Voting contestant Export view','voting-contest')."</h2>");
}
?>
