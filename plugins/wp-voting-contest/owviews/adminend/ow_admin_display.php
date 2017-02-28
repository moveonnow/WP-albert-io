<?php
if(!function_exists('ow_admin_display')){
    function ow_admin_display($custom_field,$category,$category_option){
        
        global $post;
        $_ow_music_upload_url = get_post_meta($post->ID,'_ow_music_upload_url',true);
        
        $style_none = ($_ow_music_upload_url != null)?'style="display:none"':'style="display:block"';
        
        ?>            
            <div class="ow_contestants-row">					
                <div class="ow_contestants-label">
                    <label><?php echo $custom_field->question; ?></label>
                </div>				
                <div class="ow_contestants-field">                    
                     
                     <a <?php echo $style_none; ?> href="#" id="<?php echo $custom_field->system_name; ?>" name="<?php echo $custom_field->system_name; ?>" class="music_select_file contest_music_upload"><?php _e('Select File','coting-contest'); ?></a>
                     
                     <div class="ow_contestants-field display_none">
                        <audio controls id="audio_id">
                            <source id="mp3Source" src="<?php echo $_ow_music_upload_url; ?>" type="audio/mp3">
                            <source id="oggSource" src="<?php echo $_ow_music_upload_url; ?>" type="audio/ogg">
                            Your browser does not support the audio tag.
                        </audio>
                        <a href="javascript:" class="removal ow_file_class_remove <?php echo $custom_field->system_name; ?>">
                            <?php _e('Remove','voting-contest'); ?>
                        </a>
                    </div>                
                    <input type="hidden" name="_ow_music_upload_url" id="_ow_music_upload_url" value="<?php echo $_ow_music_upload_url; ?>" />
                     
                </div>
            </div>
            
            <script>
                jQuery(document).ready(function($) {
                    $('.contest_music_upload').click(function(e) {
                        e.preventDefault();            
                        var custom_uploader = wp.media({
                            title: '<?php echo $custom_field->question; ?>',
                            button: {
                                text: 'Select File'
                            },
                            multiple: false,  // Set this to true to allow multiple files to be selected                            
                            library: { type : 'audio'},
                        })
                        .on('select', function() {
                            
                            var attachment = custom_uploader.state().get('selection').first().toJSON();
                            $('#contestant-ow_video_url').val(attachment.url);
                            $('.contest_music_upload').hide();
                            $('.display_none').show();
                            
                            //Update Audio Player 
                            var audio = document.getElementById('audio_id');                                                  
                            $('#oggSource').attr("src",attachment.url);
                            $('#mp3Source').attr("src",attachment.url);
                            audio.load();
                            
                            $('#_ow_music_upload_url').val(attachment.url);
                            
                        })
                        .open();
                    });
                    
                    $('.removal').click(function(e){                        
                         $('.display_none').hide();
                         $('.music_select_file').show();
                    });
                });
            </script>
        <?php        
    }
}
?>