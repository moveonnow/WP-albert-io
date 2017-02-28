
jQuery(document).ready(function(){
    
    jQuery('#existing_contest_term').change(function(){
        var selected_term = jQuery('#existing_contest_term').val();       
        jQuery.ajax({
            cache: true,
            url: ajaxurl,
            data:{
                action: 'ow_votes_contestant_bulk_move',
                term_id: selected_term
            },
            type: 'GET',
            success: function( result ) {
                jQuery('#selected_term_post_listing').html(result);
            }	
            
        });
    });
        
});


