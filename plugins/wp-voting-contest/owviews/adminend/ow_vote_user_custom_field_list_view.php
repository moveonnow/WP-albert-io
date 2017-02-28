<?php
if(!function_exists('ow_vote_user_custom_field_list_view')){
    function ow_vote_user_custom_field_list_view($custom_fields){
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
	
	wp_register_style('ow_vote_datatable_style', OW_ASSETS_CSS_PATH.'ow_data_tables.css');
	wp_enqueue_style('ow_vote_datatable_style');

	wp_register_script('ow_votes_data_tables', OW_ASSETS_JS_PATH . 'ow_vote_data_tables.js');
	wp_enqueue_script('ow_votes_data_tables',array('jquery'));
	
	wp_register_script('ow_votes_validate_js', OW_ASSETS_JS_PATH . 'ow_vote_validate.js');
	wp_enqueue_script('ow_votes_validate_js',array('jquery'));
	
	wp_register_script('ow_vote_jquery_ui_js', OW_ASSETS_JS_PATH . 'ow_vote_jquery_ui.js');
	wp_enqueue_script('ow_vote_jquery_ui_js',array('jquery'));
	
	wp_enqueue_script('postbox');
	wp_localize_script( 'ow_votes_data_tables', 'OW_VOTES_LINKS', array('ajaxurl' => admin_url('admin-ajax.php'), 'plugin_url' => OW_VOTES_PATH) );
	?>

	<div class="wrap">
	    
	    <h2><?php echo _e('Manage User Registration Fields','voting-contest'); ?>
		<?php
		if (!isset($_REQUEST['action']) || ($_REQUEST['action'] != 'edit_question' && $_REQUEST['action'] != 'new_question')) {
			echo '<a href="admin.php?page=fieldregistration&useraction=new_customfield" class="button add-new-h2 new_contestant_add" style="margin-left: 20px;">' . __('Add New Field','voting-contest') . '</a>';
		}
		?>
	    </h2>
	    <p class="user_field_regp"><?php echo _e('The fields that you create here will become part of your WordPress User Registration. 
		If you would like to add custom fields to the Contestant Entry Form, please use the Contestant Form Builder which is under
		<strong>Contest > Contestants > Contestant Form Builder</strong>.','voting-contest'); ?></p>
		
	    
	    <form id="form1" name="form1" method="post" action="">
		<table id="custom_table" class="widefat manage-questions">
		    <thead>
			<tr>
			    <th class="manage-column" id="cb" scope="col" style="width:5%;" ></th>
	
			    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort" style="width:25%;">
				    <?php _e('Field Name','voting-contest'); ?>
			    </th>
			    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort" style="width:15%;">
				    <?php _e('Values','voting-contest'); ?>
			    </th>
	
			    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort"  style="width:10%;">
				    <?php _e('Type','voting-contest'); ?>
			    </th>
			    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort" style="width:10%;">
				    <?php _e('Required','voting-contest'); ?>
			    </th>
	
			    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort" style="width:10%;">
				    <?php _e('Show in Form','voting-contest'); ?>
			    </th>

			    <th class="manage-column column-title" id="values" scope="col" title="Click to Sort" style="width:10%;">
				    <?php _e('Order Sequence','voting-contest'); ?>
			    </th>
			</tr>
		    </thead>
		    
		    <tbody>
			<?php
			if ( is_super_admin() ) {
			    if(!empty($custom_fields)){
				foreach ($custom_fields as $contestant_field) {
				    $custfield_id = $contestant_field->id;
				    $custfield_name = stripslashes($contestant_field->question);
				    $values = stripslashes($contestant_field->response);
				    $custfield_type = stripslashes($contestant_field->question_type);
				    $required = stripslashes($contestant_field->required);
				    $system_name = $contestant_field->system_name;
				    $sequence = $contestant_field->sequence;
				    $admin_only = $contestant_field->admin_only;
				    $wp_user = $contestant_field->wp_user == 0 ? 1 : $contestant_field->wp_user;
				    ?>
				    
				    <tr style="cursor: move" id="<?php echo $custfield_id ?>">
				    
					<td class="checkboxcol">
					    <input name="row_id" type="hidden" value="<?php echo $custfield_id ?>" />
					     <?php if($system_name != "contestant-desc"): ?>                            
						<input name="checkbox[<?php echo $custfield_id ?>]" type="checkbox" class="question_checkbox"  title="Delete <?php echo $custfield_name ?>" />
					     <?php endif; ?>
					</td>
					
					<td class="post-title page-title column-title">
					    <strong><a href="admin.php?page=fieldregistration&amp;useraction=edit_fields&amp;field_id=<?php echo $custfield_id ?>"><?php echo $custfield_name ?></a></strong>
						<div class="row-actions">
							<span class="edit"><a href="admin.php?page=fieldregistration&amp;useraction=edit_fields&amp;field_id=<?php echo $custfield_id ?>"><?php _e('Edit','voting-contest'); ?></a>  | </span>
							<span class="delete"><a onclick="return confirmDelete('single');" class="submitdelete"  href="admin.php?page=fieldregistration&amp;useraction=delete_fields&amp;field_id=<?php echo $custfield_id ?>"><?php _e('Delete','voting-contest'); ?></a></span>
						</div>
					</td>
					<td class="author column-author"><?php echo $values ?></td>
					
				    <?php 
				    if($custfield_type=='SINGLE')
					$custfield_type = 'RADIO';
				    else if($custfield_type=='MULTIPLE')
					$custfield_type = 'CHECKBOX';
				    ?>
					    <td class="author column-author"><?php echo $custfield_type ?></td>
					    <td class="author column-author"><?php echo $required ?></td>
					    <td class="author column-author"><?php echo $admin_only ?></td>
					    <td class="author column-author"><?php echo $sequence ?></td>
					    
				    </tr>
				    
				    <?php
				}
			    }
			}
			?>			
		    </tbody>
		</table>
	  
	    
	    <div>
		<p><input type="checkbox" name="sAll" onclick="selectAll(this)" class="select_checkbox" />
		    <strong>
			    <?php _e('Check All','voting-contest'); ?>
		    </strong>
		    <input type="hidden" name="useraction" value="delete_fields" />
		    <input name="delete_question" type="submit" class="button-secondary" id="delete_question" value="<?php _e('Delete Field','voting-contest'); ?>" onclick="return confirmDelete('multiple');">
		    <a class="button-primary add_cust_field" href="admin.php?page=fieldregistration&amp;useraction=new_customfield"><?php _e('Add New Field','voting-contest'); ?></a>
		</p>
	    </div>
	    </form>
	</div>	
	
	<script>
	    jQuery(document).ready(function($) {
			
			
			/* show the table data */
			var mytable = jQuery('#custom_table').ow_vote_dataTable({
				"bStateSave": true,
				"sPaginationType": "full_numbers",
			
				"fnDrawCallback": function( oSettings ) {
					jQuery('.question_checkbox').attr('checked',false);
					if(jQuery('.select_checkbox').is(':checked')){
					  jQuery('.select_checkbox').attr('checked',false);
					}
				},
				"oLanguage": {
					"sSearch": "<strong><?php _e('Live Search Filter', 'voting-contest'); ?>:</strong>",
					"sZeroRecords": "<?php _e('No Records Found!', 'voting-contest'); ?>"
				},
				"aoColumns": [
					{ "bSortable": false },
					null,null,null,null,null,null
				]    
			});
			
			jQuery('#new-question-form').ow_vote_validate({
				ow_vote_rules: {
					custfield: "required",
					values: "required",
					sequence:{number: true}
				},
				ow_vote_messages: {
					custfield: "<?php _e('Please add a field name','voting-contest'); ?>",
					values: "<?php _e('Please add a list of values for the field','voting-contest'); ?>",
					sequence:{number: "Please enter the numeric values"}
				}
			});
					
			var startPosition;
			var endPosition;
			jQuery("#custom_table tbody").ow_vote_sortable({
				cursor: "move",
				start:function(event, ui){    
					startPosition = ui.item.prevAll().length + 1;
				},
				update: function(event, ui) {
					endPosition = ui.item.prevAll().length + 1;
					var row_ids="";
					jQuery('#custom_table tbody input[name="row_id"]').each(function(i){
						row_ids= row_ids + ',' + jQuery(this).val();
					});
					jQuery.post(OW_VOTES_LINKS.ajaxurl, { action: "ow_vote_user_update_sequence", row_ids: row_ids} );
				}
			});
			postboxes.add_postbox_toggles('form_builder');    
			
			
	    });
		
		// Remove li parent for input 'values' from page if 'text' box or 'textarea' are selected
	    var selectValue = jQuery('select#custfield_type option:selected').val();
	    // hide values field on initial page view
	    if(selectValue == 'TEXT' || selectValue == 'TEXTAREA' || selectValue == 'DATE'){
		    jQuery('#add-question-values').hide();
		    // we don't want the values field trying to validate if not displayed, remove its name
		    jQuery('#add-question-values td input').attr("name","notrequired") 
	    }
		
	    jQuery('select#custfield_type').bind('change', function() {
			var selectValue = jQuery('select#custfield_type option:selected').val();
				
			if (selectValue == 'TEXT' || selectValue == 'TEXTAREA' || selectValue == 'DATE') {
				jQuery('#add-question-values').fadeOut('slow');
				// we don't want the values field trying to validate if not displayed, remove its name
				jQuery('#add-question-values td input').attr("name","notrequired") 
			} else{
				jQuery('#add-question-values').fadeIn('slow');
				// add the correct name value back in so we can run validation check.
				jQuery('#add-question-values td input').attr("name","values");			    
			}
	    });
		
		function selectAll(x) {
			if(x.checked){
				jQuery('.question_checkbox').attr('checked',true);
			}else{
				jQuery('.question_checkbox').attr('checked',false);
			}
		}
		
		function confirmDelete(seld){
			if(seld=='multiple'){
				if(jQuery('.question_checkbox').is(':checked')){
					if (confirm('<?php _e("Are you sure want to delete?","voting-contest"); ?>')){
					  return true;
					}
					}else{
						alert('<?php _e("Select atleast one field to delete!","voting-contest"); ?>');
					}
				return false;
			}
			else{
				return true;
			}
	    }
	</script>
	
    <?php
	}
}else{
    die("<h2>".__('Failed to load Voting cotestant custom field list view','voting-contest')."</h2>");
}
?>
