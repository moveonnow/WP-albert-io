<?php
if(!function_exists('ow_settings_excerpt_view')){
	function ow_settings_excerpt_view($thisname,$tag_list,$options_all_tags,$default){
		wp_enqueue_script($thisname . '_script', OW_ASSETS_JS_PATH. 'ow_votes-advanced-excerpt.js');
		$tag_cols = 5;
		extract($default, EXTR_SKIP);
		?>
		<h2 class="color_h2"><?php _e('Contest Excerpt Settings','voting-contest'); ?></h2>
		
		<div class="settings_content">
			<form method="post" action="">
                <?php
                if (function_exists('wp_nonce_field'))
                    wp_nonce_field($thisname . '_update_options');
                ?>

                <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                    <label for="<?php echo $thisname; ?>_length"><?php _e("Excerpt Length:",'voting-contest'); ?></label>
                            </th>
                            <td>
                                <input name="<?php echo $thisname; ?>_length" type="text"
                                       id="<?php echo $thisname; ?>_length"
                                       value="<?php echo $length; ?>" size="2"/>
                                <input name="<?php echo $thisname; ?>_use_words" type="checkbox"
                                       id="<?php echo $thisname; ?>_use_words" value="on"<?php echo (1 == $use_words) ? ' checked="checked"' : ''; ?>/>
                                <?php _e("Use words?",'voting-contest'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo $thisname; ?>_ellipsis"><?php _e("Ellipsis:",'voting-contest'); ?></label>
			    <div class="hasTooltip"></div>
				<div class="hidden">
				<?php _e("Will substitute the part of the post that is omitted in the excerpt.",'voting-contest'); ?>
				</div>
			    </th>
                            <td>
                                <input name="<?php echo $thisname; ?>_ellipsis" type="text"
                                       id="<?php echo $thisname; ?>_ellipsis"
                                       value="<?php echo $ellipsis; ?>" size="5"/>                    
                                
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="<?php echo $thisname; ?>_length">
                                    <?php _e("Finish:",'voting-contest'); ?></label>
			    <div class="hasTooltip"></div>
				<div class="hidden">
							<?php _e("Prevents cutting a word or sentence at the end of an excerpt. This option can result in (slightly) longer excerpts.",'voting-contest'); ?>
				</div>
			    
			    </th>
                            <td>
                                <input name="<?php echo $thisname; ?>_finish_word" type="checkbox"
                                       id="<?php echo $thisname; ?>_finish_word" value="on"<?php echo (1 == $finish_word) ? ' checked="checked"' : ''; ?>/>
								<?php _e("Word",'voting-contest'); ?><br/>
                                <input name="<?php echo $thisname; ?>_finish_sentence" type="checkbox"
                                       id="<?php echo $thisname; ?>_finish_sentence" value="on"<?php echo (1 == $finish_sentence) ? ' checked="checked"' : ''; ?>/>
                                       <?php _e("Sentence",'voting-contest'); ?>
                                <br /><br/>
				
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="<?php echo $thisname; ?>_read_more">
							<?php _e("&lsquo;Read-more&rsquo; Text:",'voting-contest'); ?></label></th>
                            <td>
                                <input name="<?php echo $thisname; ?>_read_more" type="text"
                                       id="<?php echo $thisname; ?>_read_more" value="<?php echo $read_more; ?>" />
                                <input name="<?php echo $thisname; ?>_add_link" type="checkbox"
                                       id="<?php echo $thisname; ?>_add_link" value="on" <?php echo (1 == $add_link) ? 'checked="checked" ' : ''; ?>/>
                                <?php _e("Add link to excerpt",'voting-contest'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="<?php echo $thisname; ?>_no_custom">
                                    <?php _e("No Custom Excerpts:",'voting-contest'); ?></label></th>
                            <td>
                                <input name="<?php echo $thisname; ?>_no_custom" type="checkbox"
                                       id="<?php echo $thisname; ?>_no_custom" value="on" <?php echo (1 == $no_custom) ? 'checked="checked" ' : ''; ?>/>
                                       <?php _e("Generate excerpts even if a post has a custom excerpt attached.",'voting-contest'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="<?php echo $thisname; ?>_no_shortcode">
                                       <?php _e("Strip Shortcodes:",'voting-contest'); ?></label></th>
                            <td>
                                <input name="<?php echo $thisname; ?>_no_shortcode" type="checkbox"
                                       id="<?php echo $thisname; ?>_no_shortcode" value="on" <?php echo (1 == $no_shortcode) ? 'checked="checked" ' : ''; ?>/>
							<?php _e("Remove shortcodes from the excerpt. <em>(recommended)</em>",'voting-contest'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e("Keep Markup:",'voting-contest'); ?></th>
                            <td>
                                <table id="<?php echo $thisname; ?>_tags_table">
                                    <tr>
                                        <td colspan="<?php echo $tag_cols; ?>">
                                            <input name="<?php echo $thisname; ?>_allowed_tags[]" type="checkbox"
                                                   value="_all" <?php echo (in_array('_all', $allowed_tags)) ? 'checked="checked" ' : ''; ?>/>
										<?php _e("Don't remove any markup",'voting-contest'); ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $i = 0;
                                    foreach ($tag_list as $tag):
                                        if ($tag == '_all')
                                            continue;
                                        if (0 == $i % $tag_cols):
                                            ?>
                                            <tr>
                                            <?php
                                            endif;
                                            $i++;
                                            ?>
                                            <td>
                                                <input name="<?php echo $thisname; ?>_allowed_tags[]" type="checkbox"
                                                       value="<?php echo $tag; ?>" <?php echo (in_array($tag, $allowed_tags)) ? 'checked="checked" ' : ''; ?>/>
                                                <code><?php echo $tag; ?></code>
                                            </td>
                                            <?php
                                            if (0 == $i % $tag_cols):
                                                $i = 0;
                                                echo '</tr>';
                                            endif;
                                            endforeach;
                                            if (0 != $i % $tag_cols):
                                            ?>
                                            <td colspan="<?php echo ($tag_cols - $i); ?>">&nbsp;</td>
                                        </tr>
                                        <?php
                                        endif;
                                        ?>
                                </table>
                                <a href="" id="<?php echo $thisname; ?>_select_all"><?php _e('Select all','voting-contest'); ?></a>
                                / <a href="" id="<?php echo $thisname; ?>_select_none"><?php _e('Select none','voting-contest'); ?></a><br />
                                <?php _e('More tags:','voting-contest'); ?>
                                <select name="<?php echo $thisname; ?>_more_tags" id="<?php echo $thisname; ?>_more_tags">
                                    <?php
                                    foreach ($options_all_tags as $tag):
                                        ?>
                                        <option value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                </select>
                                <input type="button" name="<?php echo $thisname; ?>_add_tag" id="<?php echo $thisname; ?>_add_tag" class="button" value="<?php _e('Add tag','voting-contest'); ?>" />
                            </td>
                        </tr>
                    </table>
                    <p class="submit"><input type="submit" name="Submit" class="button-primary"
                                             value="<?php _e("Save Changes",'voting-contest'); ?>" /></p>
                </form>
			
		</div>
		<?php
	}
}else
die("<h2>".__('Failed to load Voting admin Expert settings view','voting-contest')."</h2>");

