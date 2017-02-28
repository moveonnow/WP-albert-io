<?php
if(!function_exists('ow_add_form_field')){
    function ow_add_form_field($field_name,$cat_id,$customfield){
       
        $system_name = $field_name.$cat_id;
        $option = get_option(OW_VIDEOEXTENSION_SETTINGS);
        
        $before_upload = ($option['ow_video_upload_msg'] != null)?$option['ow_video_upload_msg']:__('Video Uploading please Wait....','voting-contest');       
        $after_upload = ($option['ow_video_after_upload_msg'] != null)?$option['ow_video_after_upload_msg']:__('Upload Complete. Click submit to publish !','voting-contest');        
        ?>
       
        <div class="ow_add_contestants_row ow_file_extension">
            <!-- The global progress bar -->
             <div id="ow_progress<?php echo $cat_id; ?>" class="ow_progress">
                 <div class="progress-bar progress-bar-success"></div>
                 
             </div>
             <div class="progress_status ow_progress_percentage_<?php echo $system_name; ?>"></div>
             <a href="#" class="ow_cancel_video" id="cancel_button<?php echo $system_name; ?>">Cancel</a>
             <p class="ow_video_status" id="ow_vidstatus_<?php echo $cat_id; ?>"></p>
             
             <!-- The container for the uploaded files -->
             <div id="ow_files" class="ow_files"></div>
             <!-- The table listing the files available for upload/download -->
             <input type="hidden" name="action" value="ow_file_ajax_function" />
             <input type="hidden" name="<?php echo $field_name; ?>" id="<?php echo $system_name.'_hidden' ?>" value="" />
             <input type="hidden" name="<?php echo $field_name; ?>_hidden_attached" id="<?php echo $system_name.'_hidden_attachment_id' ?>" value="" />                
        </div>
        
        <script type="text/javascript">
            var $ = jQuery.noConflict();
            
            function ow_save_file_url(result){
                jQuery.ajax({         
                     url: vote_path_local.votesajaxurl,
                    type: 'POST',
                    data : {action: "ow_save_file_url",post_title:result.name,guid:result.url,mime:result.type},
                    success:function(response){
                       $('#'+result.system_name+'_hidden_attachment_id').val(response);
                    }
                });
            }
            
            $(function () {
                'use strict';
                
               var jqXHR;
               var result = new Array();
               var maxfile_size = '<?php echo $customfield->ow_file_size; ?>'  * 1000000;
               var accept_files = /(.|\/)(<?php echo str_replace(",","|",$customfield->response); ?>)$/i;  
            
               $('#<?php echo $system_name; ?>').fileupload({                         
                    url: vote_path_local.votesajaxurl,
                    maxChunkSize: 1000000, // 1 MB ,
                    maxFileSize: 9990,                   
                    downloadTemplateId:null,
                    dataType: 'json',
                    replaceFileInput:false,                    
                    add: function(e, data) {
                            $('#savecontestant'+'<?php echo $cat_id; ?>').attr("disabled","disabled");
                            var uploadErrors = [];
                            var acceptFileTypes = accept_files;
                            if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                                uploadErrors.push('File Type Not Accepted');
                            }
                            if(maxfile_size != ""){
                                if(data.originalFiles[0]['size'] > maxfile_size) {
                                    uploadErrors.push('Filesize is too Large');
                                }
                            }
                            if(uploadErrors.length > 0) {
                                alert(uploadErrors.join("\n"));
                            } else {
                                $('.ow_cancel_video').show();
                                jqXHR = data.submit();
                            }
                    },
                    done: function (e, data) {
                        $.each(data.result['contestant-ow_music_url'][0], function (index, file) {        
                            result[index] = file;
                            if(index == "url"){
                                $('#<?php echo $system_name ;?>_hidden').val(file);
                            }
                        });
                        if(result){
                            result['system_name'] = "<?php echo $system_name ;?>";
                            //console.log(result);
                            ow_save_file_url(result);                        
                        }
                    },
                    progressall: function (e, data) {
                        $('#savecontestant'+'<?php echo $cat_id; ?>').attr("disabled","disabled");
                        $('.ow_file_extension').show();
                        $('#ow_vidstatus_'+'<?php echo $cat_id; ?>').html('<?php echo $before_upload; ?>');
                        var progressid= 'ow_progress'+'<?php echo $cat_id; ?>';
                        $('#'+progressid).show();
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#'+progressid+' .progress-bar').css(
                            'width',
                            progress + '%'
                        );
                        $('.ow_progress_percentage_<?php echo $system_name; ?>').html(progress + '%');
                        if(progress === 100){
                            //On Completion Hide Cancel Video
                            $('.ow_cancel_video').hide();
                            $('.ow_progress_percentage_<?php echo $system_name; ?>').hide();
                            $('#<?php echo $system_name; ?>').attr("disabled","disabled");
                            $('#ow_vidstatus_'+'<?php echo $cat_id; ?>').html('<?php echo $after_upload; ?>');
                            
                            $('#savecontestant'+'<?php echo $cat_id; ?>').removeAttr("disabled");
                        }
                    }
                }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');
                    
                $('#cancel_button<?php echo $system_name; ?>').click(function (e) {
                    if (jqXHR) {
                        jqXHR.abort();  
                        jqXHR = null;
                        console.log("Canceled");
                    }
                    $('.ow_file_extension').hide();
                    $('#savecontestant'+'<?php echo $cat_id; ?>').removeAttr("disabled");
                    return false;
                });
                
            });    
            
        </script>   
        <?php
    }
}
?>