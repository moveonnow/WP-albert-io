<?php
if(!function_exists('ow_voting_import_contestants_view')){
    function ow_voting_import_contestants_view(){
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
    ?>
	<div class="wrap">
        <h2><?php _e('Import Contestants','voting-contest'); ?></h2>
            <div class="narrow">
                <p><?php _e('Steps to Import Contestants','voting-contest'); ?></p>

                <ul style="list-style: disc inside none;">
                    <li><?php _e('Prepare your Contestants CSV/XLS/XLSX/ODS file in the format given below.','voting-contest'); ?></li>
                    <li><?php _e('Choose Category.','voting-contest');
		    echo sprintf('(&nbsp;'.__("Note: New Category can be created in this page","voting-contest").' <a href="%1$s"> '.__("Add Category","voting-contest").' </a> )', 'edit-tags.php?taxonomy=' . OW_VOTES_TAXONOMY . '&post_type=' . OW_VOTES_TYPE); ?> </li>
                    <li><?php _e('Upload the CSV/XLS/XLSX/ODS file using the form and click upload','voting-contest'); ?></li>
                </ul>

                <div class="sampledata">
                    <div class="titledata"><?php _e('Sample file Data','voting-contest'); ?></div>
                    <div class="titledata" style="height:25px;"></div>
                    <div class="titledata"><?php _e('"contest_title","contest_content","featured_image_url"'); ?></div>
                    <div class="rowdata"><?php _e('"pagetitle1","pagecontent","http://i0.kym-cdn.com/entries/icons/original/000/007/263/photo_cat2.jpg"'); ?></div>
                    <div class="rowdata"><?php _e('"pagetitle2","pagecontent",""'); ?></div>
                    <div class="rowdata"><?php _e('.'); ?></div>
                    <div class="rowdata"><?php _e('.'); ?></div>
                    <div class="requireddata description"><p style="font-weight: bold;"><?php _e('<u>Required Fields</u>: contest_title','voting-contest'); ?></p>
                        <p><?php _e('<b>Note:</b> column values should be seperated by comma.','voting-contest'); ?><br/> 
			<?php _e('First line of the CSV file should be <b>"contest_title","contest_content","featured_image_url"'); ?></b><br/>
			<?php _e('By Default <b>votes</b> will be 0','voting-contest'); ?><br/>
			<?php _e('By Default <b>Expiration Date</b> will be 0','voting-contest'); ?>
                        </p>
                    </div>
                </div>

                <form method="post" enctype="multipart/form-data" name="contest_csv_form" id="contest_csv_form">
                    <p>
                        <label for="contest_csv_term"><?php _e('Choose a Category for the Contestants','voting-contest'); ?></label>
		    <?php
		    wp_dropdown_categories(array('hide_empty' => false,
			'name' => 'contest_csv_term',
			'id' => 'contest_csv_term',
			'hierarchical' => 1,
			'show_count' => 1,
			'taxonomy' => OW_VOTES_TAXONOMY,
			'show_option_none' => __('Select the Category','voting-contest')));
		    ?>
                    </p>
                    <p>
                        <label for="contest_csv_file"><?php _e('Choose a file from your computer','voting-contest'); ?></label>
                        <input name="contest_csv_file" id="contest_csv_file" type="file" />
                    </p>

                    <p class="submit"><input type="submit" value="<?php _e('Upload file and import','voting-contest'); ?>" class="button" id="contest_csv_submit" name="contest_csv_submit"></p>
                </form>
            </div>
        </div>
    <?php
    }
}else{
    die("<h2>".__('Failed to load Voting contestant import view','voting-contest')."</h2>");
}
?>
