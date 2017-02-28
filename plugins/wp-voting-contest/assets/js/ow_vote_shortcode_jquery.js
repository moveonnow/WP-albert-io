jQuery(document).ready(function(){	  
	
	//Make the listing page title and right side content fix to the width
	ow_votes_list_page_show_contest();
	//Count down timer
	votes_countdown('.ow_countdown_dashboard');
	//Sorting filter
	ow_vote_sorting_filter();
	//Grid view function
	ow_vote_show_contest_grid_function();
	//List view function
	ow_vote_show_contest_list_function();
	//Pagination ajax
	ow_vote_pagination_click_function();
	ow_vote_pagination_change_function();
	//Add contestant validate the custom fields
	ow_vote_add_contestant_function();
	//Login/Registration form functionalities
	ow_vote_submit_user_form();
	//Gallery on show contestant
	ow_pretty_photo_gallery();
	//vote function
	ow_vote_click_function();
	//rules
	ow_rules_click_function();
	//Load More functionality
	ow_voting_load_more();
	
	//Load More functionality All Contestant Page
	ow_voting_load_more_all();
		
	//Single contestant page
	ow_single_contestant_function();
	ow_single_contestant_pretty();
	
	
	//Single page share url copy text function start
	function copyToClipboard(elem) {
		  // create hidden text element, if it doesn't already exist
		var targetId = "_hiddenCopyText_";
		var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
		var origSelectionStart, origSelectionEnd;
		if (isInput) {
			// can just use the original source element for the selection and copy
			target = elem;
			origSelectionStart = elem.selectionStart;
			origSelectionEnd = elem.selectionEnd;
		} else {
			// must use a temporary form element for the selection and copy
			target = document.getElementById(targetId);
			if (!target) {
				var target = document.createElement("textarea");
				target.style.position = "absolute";
				target.style.left = "-9999px";
				target.style.top = "0";
				target.id = targetId;
				document.body.appendChild(target);
			}
			target.textContent = elem.textContent;
		}
		// select the content
		var currentFocus = document.activeElement;
		target.focus();
		target.setSelectionRange(0, target.value.length);
		
		// copy the selection
		var succeed;
		try {
			  succeed = document.execCommand("copy");
		} catch(e) {
			succeed = false;
		}
		// restore original focus
		if (currentFocus && typeof currentFocus.focus === "function") {
			currentFocus.focus();
		}
		
		if (isInput) {
			// restore prior selection
			elem.setSelectionRange(origSelectionStart, origSelectionEnd);
		} else {
			// clear temporary content
			target.textContent = "";
		}
		return succeed;
	}
	
	var copiedurl = document.getElementById("ow_vote_share_url_copy");
	if(copiedurl) {
		document.getElementById("ow_vote_share_url_copy").addEventListener("focus", function() {
			copyToClipboard(document.getElementById("ow_vote_share_url_copy"));
			jQuery("#ow_vote_share_url_copy").select();
			jQuery(".copied_message span").slideDown("slow");
			setTimeout(function() {
				jQuery(".copied_message span").slideUp("slow");
			}, 3000);
		});
	}
	//Single page share url copy text function end
	
		
		
	var options = {
		url: vote_path_local.votesajaxurl + "?action=ow_render_search",
		getValue: "label",
		template: {
			type: "links",
			fields: {
				link: "link",
				iconSrc: "icon",			
			}
		},
		requestDelay: 500,
		list: {
			match: {
				enabled: true
			},
			showAnimation: {
				type: "fade", //normal|slide|fade
				time: 400,
				callback: function() {}
			},
	
			hideAnimation: {
				type: "slide", //normal|slide|fade
				time: 400,
				callback: function() {}
			}
		}
		
	};
	
	//jQuery("#ow_search_input").owAutocomplete(options);

	
	jQuery(document).on('submit','#ow_contestants_search',function(e){
		e.preventDefault();
		var contest_id = jQuery('#ow_search_input').val();		
		var insert_param = ow_insertParam('ow_search',contest_id);
		var url = document.URL;
		var shortUrl=url.substring(0,url.lastIndexOf("/page/"));
		window.location.href = shortUrl + '?' + insert_param;
	});	
	
	jQuery(document).on('change','#ow_tax_select',function(e){
		var contest_id = jQuery(this).val();		
		var insert_param = ow_insertParam('ow_cont',contest_id);
		var url = document.URL;
		var shortUrl=url.substring(0,url.lastIndexOf("/page/"));
		window.location.href = shortUrl + '?' + insert_param;
	});	
	
	
	jQuery(document).on('change','#ow_sort_select',function(e){
		var ow_sort = jQuery(this).val();		
		var insert_param = ow_insertParam('ow_sort',ow_sort);
		var url = document.URL;
		var shortUrl=url.substring(0,url.lastIndexOf("/page/"));
		window.location.href = shortUrl + '?' + insert_param;
		
	});
	
	function ow_insertParam(key, value)
	{
		key = encodeURI(key); value = encodeURI(value);	
		var kvp = document.location.search.substr(1).split('&');	
		var i=kvp.length; var x; while(i--) {
			x = kvp[i].split('=');			
			if (x[0]==key)
			{
				x[1] = value;
				kvp[i] = x.join('=');
				break;
			}
		}	
		if(i<0) {kvp[kvp.length] = [key,value].join('=');}	
		//this will reload the page, it's likely better to store this until finished
		return kvp.join('&');
		//document.location.search = kvp.join('&'); 
	}
	
	function ow_preview_img(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();						
			reader.onload = function (e) {
				jQuery('#uploaded_img').attr('src', e.target.result);
				jQuery('#uploaded_img').show();
			}						
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	jQuery(document).on('change','#ow_select_term',function(e){
		var contest_id = jQuery(this).val();		
		jQuery('#contest-id').val(contest_id);
		jQuery('#contestantform').val(contest_id);
		jQuery('#contestantform').attr('name', 'contestantform' + contest_id);
		jQuery('#contestant-desc4').attr('name', 'contestant-desc' + contest_id);
		
		var cat_term = jQuery('option:selected', this).attr('data-val');
		
		if(cat_term == "photo"){
			jQuery('#ow_img_id').show();
			jQuery('.img_required_span').show();
			add_contestant_validation_method("contestant-image","Please upload the file");								
			jQuery("#contestant-image").change(function(){
				ow_preview_img(this);
			});
			jQuery('.video_music_only').hide();
		}		
		else{
			
			if(cat_term == "music" || cat_term == "video"){
				jQuery('#ow_img_id').hide();
				jQuery('.video_music_only').show();
			}
			
			if(cat_term == "essay"){				
				jQuery('.video_music_only').hide();
			}
			
			//Send Ajax for Getting the Category options
			jQuery.ajax({
				url: vote_path_local.votesajaxurl,
				data:{
					action:'voting_getterm',			
					term_id:contest_id,				
				},
				type: 'POST',
				cache: false,
				success: function (resp) {
					var img_data = JSON.parse(resp);					
					if(img_data.imgenable == "on"){
						jQuery('#ow_img_id').show();
						if(img_data.imgrequired == "on"){
							jQuery('.img_required_span').show();
							add_contestant_validation_method("contestant-image","Please upload the file");								
							jQuery("#contestant-image").change(function(){
								ow_preview_img(this);
							});
						}
						else{
							jQuery('.img_required_span').hide();
						}
					}
					else{
						jQuery('#ow_img_id').hide();
					}
				},
				error: function (jqXHR , textStatus, errorThrown ) {
					jQuery('div.links', form).html(errorThrown);
				}
			});			
		}
	});
	
	jQuery(document).on('click','.ow_tog',function(e){
		e.preventDefault();
		if (jQuery(".ow_vote_show_contestants .ow_vote_menu_links").is(':hidden')) {
			jQuery(".ow_vote_show_contestants .ow_vote_menu_links").attr("style", "display: block !important");
			jQuery(".ow_tog a.togglehide span.ow_vote_icons").addClass('menu_open');
		}
		else{
			jQuery(".ow_vote_show_contestants .ow_vote_menu_links").attr("style", "display: none !important");
			jQuery(".ow_vote_show_contestants .ow_tog a.togglehide span.ow_vote_icons").removeClass('menu_open');
		}
		if (jQuery(".ow_vote_single_section .ow_vote_menu_links").is(':hidden')) {
			jQuery(".ow_vote_single_section .ow_vote_menu_links").attr("style", "display: block !important");
			jQuery(".ow_tog a.togglehide span.ow_vote_icons").addClass('menu_open');	
		}
		else{
			jQuery(".ow_vote_single_section .ow_vote_menu_links").attr("style", "display: none !important");
			jQuery(".ow_vote_single_section .ow_tog a.togglehide span.ow_vote_icons").removeClass('menu_open');
		}
	});
	
	
	jQuery(document).on('click','.ow_tabs_register',function (e){
		jQuery('.ow_tabs_login_content').hide();
		jQuery('.ow_tabs_register_content').show();
		jQuery('.forgot-panel').hide();
		
		jQuery( ".inner-container" ).removeClass('login-panel_add');
		jQuery( ".inner-container" ).removeClass('register-panel_add'); 
		jQuery( ".inner-container" ).removeClass('forgot-panel_add'); 
	        jQuery( ".inner-container" ).addClass('register-panel_add');
		
		
		jQuery( this ).addClass('active');
		jQuery( '.ow_tabs_login' ).removeClass('active');
		
	    
	});
	jQuery(document).on('click','.ow_tabs_login,.ow_tabs_already,.ow_votebutton',function (e){
		jQuery('.ow_tabs_login_content').show();
		jQuery('.ow_tabs_register_content').hide();
		jQuery( ".inner-container" ).removeClass('login-panel_add');
		jQuery( ".inner-container" ).removeClass('register-panel_add'); 
		jQuery( ".inner-container" ).removeClass('forgot-panel_add'); 
		jQuery( ".inner-container" ).addClass('login-panel_add');
		
		jQuery( '.ow_tabs_login' ).addClass('active');
		jQuery( '.ow_tabs_register' ).removeClass('active');
	});
	
	//On Page Load
	var window_width = jQuery(window).width();
	if (window_width < 600) {
		ow_change_grid_list(window_width);
	}
	
	jQuery(window).resize(function () {
		var window_width = jQuery(window).width();
		ow_change_grid_list(window_width);		
	});
	
	
	function ow_change_grid_list(window_width){
		if (window_width < 600) {
			jQuery(".ow_views_container").each(function(){
				//Show In List When It is Video Contest Else Show in GRID
				if (jQuery(this).hasClass('ow_video_contest')) {
					var ow_views_container = jQuery(this).attr('id');				
					var res = ow_views_container.split("ow_views_container_");
					var term_id = res[1];
					ow_show_contest_list(term_id);
				}
				else{
					var ow_views_container = jQuery(this).attr('id');				
					var res = ow_views_container.split("ow_views_container_");
					var term_id = res[1];
					ow_show_contest_grid(term_id);					
				}
				jQuery('li.ow_vote_float_right').hide();
			});			
		}
		else{
			jQuery('li.ow_vote_float_right').show();			
		}
	}
	
	jQuery(document).on({		
		mouseenter : function(){
			var cont_id = jQuery(this).attr('data-vote-id');
			
			var width = jQuery('#ow_image_responsive'+cont_id).width();
			var height = jQuery('#ow_image_responsive'+cont_id).height();		
			jQuery('.ow_overlay_bg').css("width",width);
			jQuery('.ow_overlay_bg').css("height",height);
			
			jQuery('.ow_overlay_'+cont_id).css('opacity','1');
			jQuery( '.ow_overlay_'+cont_id).children().addClass('ow_overlay_bg_bottom_0',{duration:500});
			
		},mouseleave : function(){
			var cont_id = jQuery(this).attr('data-vote-id');
			jQuery('.ow_overlay_'+cont_id).css('opacity','0');
			jQuery( '.ow_overlay_'+cont_id).children().removeClass('ow_overlay_bg_bottom_0',{duration:500});
		}
        }, 'a.ow_hover_image');
		
		AudioJS.setupAllWhenReady();
});

	function ow_single_contestant_function(){
		jQuery(document).on('click','.ow_share_click_expand',function(e){
			if(jQuery('.ow_total_share_single').is(':hidden')) {
				jQuery('.ow_vote_share_shrink').addClass('active');
				jQuery('.ow_total_share_single').show();
			}else{
				jQuery('.ow_vote_share_shrink').removeClass('active');
				jQuery('.ow_total_share_single').hide();
			}
		});
		
	}
	
	function ow_single_contestant_pretty() {
		jQuery('.single_contestant_pretty').ow_vote_prettyPhoto({
			hook:'data-vote-gallery',
			markup: ow_pretty_photo_theme_markupp(),
			social_tools: false,
			deeplinking: false,
			show_title: true,
			theme:'pp_kalypso',  
			changepicturecallback: function(ow_vote_id,ow_term_id)
			{                    
				
				var votes_counter = jQuery('.votes_count_single'+ow_vote_id).html();
				var get_html_count = "";var get_vote_btn = "";
				
				var ow_vote_button = jQuery('.ow_votes_btn_single'+ow_vote_id).html();
				
				if (ow_vote_button != undefined) {	
					var get_vote_btn = "<div class='ow_pp_vote_btn_single'>"+ow_vote_button+'</div>';
				}				
				
				if (votes_counter != undefined) {					
					var get_html_count = "<div class='ow_pp_vote_count'>"+votes_counter+'</div>';
				}				
				var get_html_pretty = jQuery('.ow_pretty_content_social'+ow_vote_id).html();
				
				jQuery('.pp_social').html(get_html_count+get_vote_btn+get_html_pretty);
				jQuery('.pp_pic_holder').css('margin-top','20px');
				jQuery('.pp_social').css('margin-right','20px');
				ow_voting_add_contents_pretty(ow_vote_id);
			}                   	                            		
		});
	}
	
	function ow_vote_add_contestant_function() {
		//jQuery('.add_form_contestant_ow_vote').hide();
		
		jQuery(document).on('click','.ow_vote_submit_entry',function(e){

			var voter_submitter = jQuery('.voter_submitter').val();

			//Check If User is voter or submitter
			if(voter_submitter == 0){
				jQuery.ow_vote_fancybox(
				'<h2 class="ow_vote_fancybox_result_header" style="margin:10px 0  0 10px;font-size:inherit;">Submitting Not Allowed</h2>'+
				'<div class="ow_vote_fancybox_result"><div class="owt_danger"><i class="ow_vote_icons voteconestant-warning"></i>Submitting Contestants Not Allowed for Voter</div></div>',
				{
					'padding':0,	
					'width':500,
					'height':280,
					'maxWidth': 500,
					'maxHeight': 280,
					'minWidth': 350,
					'minHeight': 220
				});
				return false;
			}

			var form_show_logged = jQuery(this).hasClass( "ow_logged_in_enabled" );
			if (!form_show_logged) {		
				var term_id = jQuery(this).attr('data-id');
				var close_button_text = jQuery('.close_btn_text'+term_id).val();
				var open_button_text = jQuery('.open_btn_text'+term_id).val();
				var _self = jQuery(this);
				
				if(jQuery('.ow_form_add-contestants'+term_id).is(':visible'))
					jQuery('.ow_form_add-contestants'+term_id).slideToggle('slow', function(){
						_self.html(open_button_text);	
					});
				else{
					
					jQuery('.ow_contestants-success').hide();
					jQuery('.ow_form_add-contestants'+term_id).slideToggle('slow', function(){
						_self.html('X '+close_button_text );	
					});
				}
			}else{
				ow_vote_ppOpen('#ow_vote_login_panel', '300',1);
				
				//Tab in the Login Popup
				jQuery('.ow_tabs_login_content').show();
				jQuery('.ow_tabs_register_content').hide();
				jQuery( '.ow_tabs_login' ).addClass('active');
			}
		});
		
	}
	
	//Sorting filter
	function ow_vote_sorting_filter(){
		jQuery('.ow_vote_filter_votes').change(function(){
			var term_id = jQuery(this).attr('id');
			var view = jQuery('.ow_vote_view_'+term_id).attr('data-view');
			jQuery('#ow_filter_view'+term_id).val(view);
			jQuery('#ow_vote_select_filter'+term_id).submit();
		});
	}
	
	//Grid view click function
	function ow_vote_show_contest_grid_function(){		
		jQuery(document).on('click','.ow_vote_grid_show_contest',function(e){
			var term_id = jQuery(this).attr('data-id');
			ow_show_contest_grid(term_id);
		});
	}
	
	function ow_show_contest_grid(term_id){
		var show_desc = jQuery('.ow_show_description'+term_id).val();
		jQuery('.ow_vote_view_'+term_id).attr('data-view','grid');
		jQuery('.ow_right_dynamic_content'+term_id).removeAttr('style');	
		//Remove list functionalities
		jQuery('#list_show'+term_id).removeClass('ow_list_active');
		jQuery('.ow_vote_view_'+term_id).removeClass('ow_vote_list');
		jQuery('.ow_list_title'+term_id).hide();
		jQuery('.ow_list_only').hide();
		
		//Add Grid to the html
		var width_img = jQuery('.ow_vote_img_width'+term_id).val();
		jQuery('.ow_right_dynamic_content'+term_id).css('width',width_img+'px');
		jQuery('#grid_show'+term_id).addClass('ow_grid_active');
		jQuery('.ow_vote_view_'+term_id).addClass('ow_vote_grid');
		jQuery('.ow_grid_title'+term_id).show();
		jQuery('.ow_grid_only').show();
		
		if(show_desc=='grid' || show_desc=='both')
			jQuery('.ow_show_desc_view_'+term_id).show();
		else
			jQuery('.ow_show_desc_view_'+term_id).hide();
			
		var title_alocation = jQuery('.ow_title_alocation_description'+term_id).val();
		if (title_alocation=='on') {
			var ow_image_contest = jQuery('.ow_image_contest'+term_id).val();
			if (ow_image_contest=='photo') {
				jQuery('.ow_vote_showcontent_'+term_id).each(function(){
					var check_content = jQuery(this).find('.ow_vote_title_content'+term_id).hasClass("ow_vote_title_added"+term_id);
					if (!check_content) {
						jQuery(this).find(".vote_left_sid"+term_id).before("<div class='title_trnc_grid"+term_id+"'>"+jQuery(this).find('.ow_vote_title_content'+term_id).html()+"</div>");
						jQuery(this).find('.ow_vote_title_content'+term_id).hide();
						jQuery(this).find('.ow_vote_title_content'+term_id).addClass('ow_vote_title_added'+term_id);
					}
				});
			}
		}else{
			var ow_image_contest = jQuery('.ow_image_contest'+term_id).val();
			if (ow_image_contest=='video') {
				jQuery('.ow_vote_showcontent_'+term_id).each(function(){
					var check_content = jQuery(this).find('.ow_vote_title_content'+term_id).hasClass("ow_vote_title_added"+term_id);
					if (!check_content) {
						jQuery(this).find(".video_contest_desc"+term_id).after("<div class='title_trnc_grid"+term_id+"'>"+jQuery(this).find('.ow_vote_title_content'+term_id).html()+"</div>");
						jQuery(this).find('.ow_vote_title_content'+term_id).hide();
						jQuery(this).find('.ow_vote_title_content'+term_id).addClass('ow_vote_title_added'+term_id);
					}
				});
			}else if (ow_image_contest=='music') {
				jQuery('.ow_vote_showcontent_'+term_id).each(function(){
					var check_content = jQuery(this).find('.ow_vote_title_content'+term_id).hasClass("ow_vote_title_added"+term_id);
					if (!check_content) {
						jQuery(this).find(".ow_msc_content"+term_id).after("<div class='title_trnc_grid"+term_id+"'>"+jQuery(this).find('.ow_vote_title_content'+term_id).html()+"</div>");
						jQuery(this).find('.ow_vote_title_content'+term_id).hide();
						jQuery(this).find('.ow_vote_title_content'+term_id).addClass('ow_vote_title_added'+term_id);
					}
				});
			}
		}	
		AudioJS.setupAllWhenReady();
	}
	
	//List view click function
	function ow_vote_show_contest_list_function(){		
		jQuery(document).on('click','.ow_vote_list_show_contest',function(e){
			var term_id = jQuery(this).attr('data-id');
			ow_show_contest_list(term_id);
			ow_votes_list_page_show_contest();
		});
	}
	
	function ow_show_contest_list(term_id) {
		var show_desc = jQuery('.ow_show_description'+term_id).val();
		jQuery('.ow_vote_view_'+term_id).attr('data-view','list');
		//Remove grid functionalities
		jQuery('#grid_show'+term_id).removeClass('ow_grid_active');
		jQuery('.ow_vote_view_'+term_id).removeClass('ow_vote_grid');
		jQuery('.ow_grid_title'+term_id).hide();
		jQuery('.ow_grid_only').hide();
		
		//Add List to the html
		jQuery('#list_show'+term_id).addClass('ow_list_active');
		jQuery('.ow_right_dynamic_content'+term_id).css('width','auto');
		jQuery('.ow_vote_view_'+term_id).addClass('ow_vote_list');
		jQuery('.ow_list_title'+term_id).show();
		jQuery('.ow_list_only').show();
		
		if((show_desc=='list') || (show_desc=='both'))
			jQuery('.ow_show_desc_view_'+term_id).show();
		else
			jQuery('.ow_show_desc_view_'+term_id).hide();
		
		var title_alocation = jQuery('.ow_title_alocation_description'+term_id).val();
		if (title_alocation=='on') {
			var ow_image_contest = jQuery('.ow_image_contest'+term_id).val();
			if (ow_image_contest=='photo') {
				jQuery('.ow_vote_showcontent_'+term_id).each(function(){
					jQuery(this).find('.title_trnc_grid'+term_id).remove();
					jQuery(this).find('.ow_vote_title_content'+term_id).show();
					jQuery(this).find('.ow_vote_title_content'+term_id).removeClass('ow_vote_title_added'+term_id);
				});
			}
		}else{
			var ow_image_contest = jQuery('.ow_image_contest'+term_id).val();
			if (ow_image_contest=='video') {
				jQuery('.ow_vote_showcontent_'+term_id).each(function(){
					jQuery(this).find('.title_trnc_grid'+term_id).remove();
					jQuery(this).find('.ow_vote_title_content'+term_id).show();
					jQuery(this).find('.ow_vote_title_content'+term_id).removeClass('ow_vote_title_added'+term_id);
				});
			}else if (ow_image_contest=='music') {
				jQuery('.ow_vote_showcontent_'+term_id).each(function(){
					jQuery(this).find('.title_trnc_grid'+term_id).remove();
					jQuery(this).find('.ow_vote_title_content'+term_id).show();
					jQuery(this).find('.ow_vote_title_content'+term_id).removeClass('ow_vote_title_added'+term_id);
				});
			}
		}
		AudioJS.setupAllWhenReady();
	}
	
	//Pagination ajax
	function ow_vote_pagination_click_function(){
		jQuery(document).on('click', '.ow_votes-pagination a',function(e){
			
			var all_contest_page = jQuery('#all_contest_page').val();
			if(all_contest_page == "1"){				
				return;
			}
			
			e.preventDefault();
			
			var term_id = jQuery(this).parent().attr('id');
			var view = jQuery('.ow_vote_view_'+term_id).attr('data-view');
			var link = jQuery(this).attr('href');
			
			//Profile pagination
			if(term_id == "profile"){
				votes_display_profilescreen(link);
				
				jQuery('.ow_voting-profile').ow_vote_block({
					message: '<img src="'+vote_path_local.vote_image_url+'wait_please.gif" />', 
					overlayCSS: { 
					backgroundColor: '#fff', 
					opacity:         0.6 
					}
				});
							
				jQuery('html,body').animate({
					 scrollTop: jQuery(".ow_voting-profile").offset().top},
				'slow');
				return false;
			}
			
			
			jQuery('.ow_vote_view_'+term_id).ow_vote_block({
				message: '<img src="'+vote_path_local.vote_image_url+'wait_please.gif" />', 
				overlayCSS: { 
				backgroundColor: '#fff', 
				opacity:         0.6 
				}
			});
			jQuery('.ow_vote_view_'+term_id).load(link+' .ow_contest-posts-container'+term_id,function(){
				if (view=='list') {
					ow_show_contest_list(term_id);
					ow_votes_list_page_show_contest();
				}else{
					ow_show_contest_grid(term_id);
				}
				
				//Gallery on show contestant
				ow_pretty_photo_gallery();
				
				jQuery('.ow_vote_view_'+term_id).ow_vote_unblock();
				jQuery('html,body').animate({
					scrollTop: jQuery('.ow_vote_view_'+term_id).offset().top},
					'slow');
				
			});
		});
	}
	
	function votes_display_profilescreen(link)
	{           
		jQuery('.ow_voting-profile').load(link+' .ow_voting-profile',function(){});
	}
	
	function ow_vote_pagination_change_function() {
		jQuery(document).on('change','.ow_votes-pagination select', function(e){
			
			var all_contest_page = jQuery('#all_contest_page').val();
			if(all_contest_page == "1"){
				var url      = jQuery(this).val();
				window.location.href = url;
				return;
			}
			
			e.preventDefault();
			
			var term_id = jQuery(this).attr('class');
			var view = jQuery('.ow_vote_view_'+term_id).attr('data-view');
			var link = jQuery(this).val();
			
			//Profile pagination
			if(term_id == "profile"){
				votes_display_profilescreen(link);
				
				jQuery('.ow_voting-profile').ow_vote_block({
					message: '<img src="'+vote_path_local.vote_image_url+'wait_please.gif" />', 
					overlayCSS: { 
					backgroundColor: '#fff', 
					opacity:         0.6 
					}
				});
							
				jQuery('html,body').animate({
					 scrollTop: jQuery(".ow_voting-profile").offset().top},
				'slow');
				return false;
			}
			
			jQuery('.ow_vote_view_'+term_id).ow_vote_block({
				message: '<img src="'+vote_path_local.vote_image_url+'wait_please.gif" />', 
				overlayCSS: { 
				backgroundColor: '#fff', 
				opacity:         0.6 
				}
			});
			jQuery('.ow_vote_view_'+term_id).load(link+' .ow_contest-posts-container'+term_id,function(){
				if (view=='list') {
					ow_show_contest_list(term_id);
					ow_votes_list_page_show_contest();
				}else{
					ow_show_contest_grid(term_id);
				}
				//Gallery on show contestant
				ow_pretty_photo_gallery();
				jQuery('.ow_vote_view_'+term_id).ow_vote_unblock();
				jQuery('html,body').animate({
					scrollTop: jQuery('.ow_vote_view_'+term_id).offset().top},
					'slow');
			});
		});
	}
	
	
	function ow_votes_list_page_show_contest(){
		jQuery('.ow_vote_showcontent_view').each(function(){
			var term_id = jQuery(this).attr('data-id');
			var view = jQuery('.ow_vote_view_'+term_id).attr('data-view');
			var show_desc = jQuery('.ow_show_description'+term_id).val();
			
			if (view=='list') {
				var main_div_width = parseInt(jQuery(this).width());
				var image_width = parseInt(jQuery('.ow_vote_img_width'+term_id).val());
				if (image_width=='') {
					image_width = parseInt(jQuery('.ow_vote_img_style'+term_id).width());
				}
				var pixels = ((image_width/main_div_width)*100)-100;
				pixels = Math.abs(pixels)-0.12;
				if (!isNaN(pixels)) {
					//jQuery('.ow_right_dynamic_content'+term_id).css('width',Math.abs(pixels)+'%');	
				}
			}else{
				var image_width = parseInt(jQuery('.ow_vote_img_width'+term_id).val());
				if (image_width=='') {
					image_width = parseInt(jQuery('.ow_vote_img_style'+term_id).width());
				}
				
				if(jQuery(this).hasClass( "ow_video_get" )){
					image_width=jQuery(this).find('iframe').width();
					if (jQuery.trim(image_width)=='') {
						image_width=jQuery('.ow_video_get').width();
					}
				}
				
				
				if (!isNaN(image_width)) {
					jQuery(this).find('.ow_right_dynamic_content'+term_id).css('width',Math.abs(image_width)+'px');	
				}			
				
			}
			
			if((show_desc==view) || (show_desc=='both'))
				jQuery('.ow_show_desc_view_'+term_id).show();
			else
				jQuery('.ow_show_desc_view_'+term_id).hide();
			
			
		});
	}
	
	//Contestant validation
	function add_contestant_validation(term_id,title_error_message) {
		jQuery('.ow_form_add-contestants'+term_id).ow_vote_validate({
			ow_vote_rules: {
			'contestant-title': "required",
			},
			ow_vote_messages: {
			'contestant-title': title_error_message,
			}
		});
		
	}
	
	function add_contestant_validation_method(id_validate,message) {
		jQuery(document).ready(function(){
			jQuery("#"+id_validate).ow_vote_rules( "add", {
				required:true,                                
				ow_vote_messages:{
					required:message
				}
			});
		});
	}
	
	function add_contestant_validation_method_file(id_validate,message,size) {
		jQuery(document).ready(function(){		
			if(size != 0){
			jQuery("#"+id_validate).ow_vote_rules( "add", {
				required:true,        
				maxFileSize: {
                        "unit": "MB",
                        "size": size
                    },
			});
			}
			else{
				add_contestant_validation_method(id_validate,message)
			}
		});
	}
	

	//Rules click function
	function ow_rules_click_function() {  
		jQuery(document).on('click','.ow_vote_navmenu_link_rules', function(event){
			event.preventDefault(); 
			ow_vote_ppOpen('#ow_vote_rules', '300',1);


		});
	}


	//Vote click function
	function ow_vote_click_function() { 
		jQuery(document).on('click','a.ow_votebutton', function(){

			var voter_submitter = jQuery('.voter_submitter').val();

			//Check If User is voter or submitter
			if(voter_submitter == 1){
				jQuery.ow_vote_fancybox(
				'<h2 class="ow_vote_fancybox_result_header" style="margin:10px 0  0 10px;font-size:inherit;">Voting Not Allowed</h2>'+
				'<div class="ow_vote_fancybox_result"><div class="owt_danger"><i class="ow_vote_icons voteconestant-warning"></i>Voting Not Allowed for Contestant Submitter</div></div>',
				{
					'padding':0,	
					'width':500,
					'height':280,
					'maxWidth': 500,
					'maxHeight': 280,
					'minWidth': 350,
					'minHeight': 220
				});
				return false;
			}

			var link_clicked  = this;
			var form_show_logged = jQuery(this).hasClass( "ow_logged_in_enabled" );
			var ow_voting_email = jQuery(this).hasClass( "ow_voting_email" );
			var ow_voting_grab = jQuery(this).hasClass( "ow_voting_grab" );
			
			var term_id = jQuery(link_clicked).attr('data-term-id');
			var ow_contest_closed = jQuery('#ow_contest_closed_'+term_id).val();
			
			if (ow_voting_email || ow_voting_grab) {
				if(form_show_logged && ow_contest_closed!='start'){
					ow_vote_ppOpen('#ow_vote_login_panel', '300',1);
				}
				else{
					var term_id = jQuery(link_clicked).attr('data-term-id');
					var vote_id = jQuery(link_clicked).attr('data-vote-id');
					var ow_contest_closed = jQuery('#ow_contest_closed_'+term_id).val();
					
					if (ow_contest_closed == 'start') {						
						jQuery.ow_vote_fancybox(
							'<h2 class="ow_vote_fancybox_result_header" style="margin:10px 0  0 10px;font-size:inherit;">Processing...</h2>'+
							'<div class="ow_vote_fancybox_result"></div>',
							{
								'padding':0,	
								'width':500,
								'height':280,
								'maxWidth': 500,
								'maxHeight': 280,
								'minWidth': 350,
								'minHeight': 220
							});
						
						jQuery('.ow_vote_view_'+term_id).ow_vote_unblock();
						jQuery('.ow_vote_fancybox_result').css("background","none");
						jQuery('.ow_vote_fancybox_result_header').html('Restricted');
						jQuery('.ow_vote_fancybox_result').html("<div class='owt_danger'><i class='ow_vote_icons voteconestant-warning'></i>"+jQuery('.ow_contest_closed_desc').val()+"</div>");
						var get_html_pretty = jQuery('.ow_fancy_content_social'+vote_id).html();
						jQuery('.ow_vote_fancybox_result').append("Share with your Friends");
						jQuery('.ow_vote_fancybox_result').append("<div class='ow_fancy_content_social'>"+get_html_pretty+"</div>");
				
						return false;
					}					
					else{
						ow_vote_ppOpen('#ow_vote_email_panel', '300',1);
						window.link_btn = link_clicked;
						return false;
					}
					
					
				}
			}			
			vote_button_function(link_clicked,form_show_logged);
			
		});
	}
	
	
	//Vote Button Function
	function vote_button_function(link_clicked,form_show_logged){		
		if (!form_show_logged) {				
			var term_id = jQuery(link_clicked).attr('data-term-id');
			var vote_id = jQuery(link_clicked).attr('data-vote-id');
			
			var enc_term_id = jQuery(link_clicked).attr('data-enc-id');
			var enc_vote_id = jQuery(link_clicked).attr('data-enc-pid');
			
			var votes_count = jQuery(link_clicked).attr('data-vote-count');
			
			var today= new Date();
			var current_time  = today.toUTCString();
			var gmt_offset = today.getTimezoneOffset();
			var cur_timeinfo = new Array();
			
			jQuery('.ow_vote_view_'+term_id).ow_vote_block({
				message: '<img src="'+vote_path_local.vote_image_url+'wait_please.gif" />', 
				overlayCSS: { 
				backgroundColor: '#fff', 
				opacity:         0.6 
				}
			});
			//console.log(link_clicked);
			jQuery.ajax({
			   url: vote_path_local.votesajaxurl,
			   data:{
				action:'ow_save_votes',			
				pid:enc_vote_id,
				termid: enc_term_id,
				current_time:current_time,
				gmt_offset:gmt_offset,
				votes_count:votes_count
			   },
			   type: 'GET',
			   cache: false,
			   dataType: 'jsonp',
			   beforeSend:function(){
				jQuery.ow_vote_fancybox(
				'<h2 class="ow_vote_fancybox_result_header" style="margin:10px 0  0 10px;font-size:inherit;">Processing...</h2>'+
				'<div class="ow_vote_fancybox_result"></div>',
				{
					'padding':0,	
					'width':500,
					'height':280,
					'maxWidth': 500,
					'maxHeight': 280,
					'minWidth': 350,
					'minHeight': 220
				});
			   },
			   success: function( result ) {
				jQuery('.ow_vote_view_'+term_id).ow_vote_unblock();
				
				if(result.success==1){
					jQuery('.ow_vote_count'+vote_id).text(result.votes);
					jQuery('.votes_count_single_count'+vote_id).text(result.votes);
					//Based on frequency
					if (result.button_flag==1) {
						jQuery('.voter_a_btn_term'+term_id).each(function(){
							var voting_type= jQuery(this).attr('data-voting-type');
							if (voting_type==0) {
								jQuery('.votr_btn_cont'+vote_id).text('Voted');
								jQuery('.voter_btn_term'+term_id).text('Voted');
								
								jQuery('.voter_a_btn_term'+term_id).each(function(){
									var new_vote_id = jQuery(this).attr('data-vote-id');
									if (new_vote_id==vote_id) {
										jQuery(this).addClass('ow_voting_green_button');	
									}else
									jQuery(this).addClass('ow_voting_grey_button');
								});
							}else if (voting_type==1 || voting_type==2) {
								if (voting_type==1)
								jQuery('.votr_btn_cont'+vote_id).text('Voted');
								var count_frequency = jQuery(this).attr('data-frequency-count');
								var total_count_voted = jQuery(this).attr('data-current-user-votecount');
								var total_count_votes = parseInt(total_count_voted) + parseInt(1);
								jQuery(this).attr('data-current-user-votecount',total_count_votes);
								var new_vote_id = jQuery(this).attr('data-vote-id');
								if (new_vote_id==vote_id) {
									if (voting_type==1)
									jQuery(this).addClass('ow_voting_green_button');	
								}/*else{
									if (count_frequency==total_count_votes) {
										jQuery(".voter_a_btn_term"+term_id+":not([class*='ow_voting_green_button'])").addClass("ow_voting_grey_button");
										jQuery('.voter_btn_term'+term_id).text('Voted');
									}
								}*/
								if (new_vote_id==vote_id){
									jQuery(this).removeClass('ow_voting_grey_button');
								}
							}
						});
						
					}else if(result.button_flag==2){
						
						if (result.frequency==1) {
							jQuery('.voter_a_btn_term'+term_id).each(function(){
								var voting_type= jQuery(this).attr('data-voting-type');
								if (voting_type==0) {
									jQuery('.votr_btn_cont'+vote_id).text('Voted');
									jQuery('.voter_btn_term'+term_id).text('Voted');
									
									jQuery('.voter_a_btn_term'+term_id).each(function(){
										var new_vote_id = jQuery(this).attr('data-vote-id');
										if (new_vote_id==vote_id) {
											jQuery(this).addClass('ow_voting_green_button');	
										}else
										jQuery(this).addClass('ow_voting_grey_button');
									});
								}else if (voting_type==1 || voting_type==2) {
									jQuery('.votr_btn_cont'+vote_id).text('Voted');
									var count_frequency = jQuery(this).attr('data-frequency-count');
									var total_count_voted = jQuery(this).attr('data-current-user-votecount');
									var total_count_votes = parseInt(total_count_voted) + parseInt(1);
									jQuery(this).attr('data-current-user-votecount',total_count_votes);
									var new_vote_id = jQuery(this).attr('data-vote-id');
									if (new_vote_id==vote_id) {
										if (voting_type==1)
										jQuery(this).addClass('ow_voting_green_button');
										if (voting_type==2)
										jQuery(this).addClass('ow_voting_green_button');
									}/*else{
										if (count_frequency==total_count_votes) {
											jQuery(".voter_a_btn_term"+term_id+":not([class*='ow_voting_green_button'])").addClass("ow_voting_grey_button");
											jQuery('.voter_btn_term'+term_id).text('Voted');
										}
									}*/
									if (new_vote_id==vote_id){
										jQuery(this).removeClass('ow_voting_grey_button');
									}
								}
							});
						}
						if (result.frequency==0) {
							jQuery('.voter_a_btn_term'+term_id).each(function(){
								var voting_type= jQuery(this).attr('data-voting-type');
								if (voting_type==0) {
									jQuery('.voter_a_btn_term'+term_id).each(function(){
										var new_vote_id = jQuery(this).attr('data-vote-id');
										if (new_vote_id==vote_id) {
											jQuery(this).addClass('ow_voting_green_button');	
										}else{
											jQuery('.voter_btn_term'+term_id).text('Voted');
											jQuery(this).addClass('ow_voting_grey_button');
										}
									});
								}
							});
						}
						if (result.frequency==2) {
							jQuery('.voter_a_btn_term'+term_id).each(function(){
								var voting_type= jQuery(this).attr('data-voting-type');
								if (voting_type==0) {
									jQuery('.voter_a_btn_term'+term_id).each(function(){
										var new_vote_id = jQuery(this).attr('data-vote-id');
										if (new_vote_id==vote_id) {
											jQuery(this).addClass('ow_voting_green_button');	
										}else{
											jQuery('.voter_btn_term'+term_id).text('Voted');
											jQuery(this).addClass('ow_voting_grey_button');
										}
									});
								}
								else if (voting_type==1 || voting_type==2) {
									jQuery('.votr_btn_cont'+vote_id).text('Voted');
									var count_frequency = jQuery(this).attr('data-frequency-count');
									var total_count_voted = jQuery(this).attr('data-current-user-votecount');
									var total_count_votes = parseInt(total_count_voted) + parseInt(1);
									jQuery(this).attr('data-current-user-votecount',total_count_votes);
									var new_vote_id = jQuery(this).attr('data-vote-id');
									if (new_vote_id==vote_id) {
										if (voting_type==1)
										jQuery(this).addClass('ow_voting_green_button');
										if (voting_type==2)
										jQuery(this).addClass('ow_voting_green_button');
									}
									if (new_vote_id==vote_id){
										jQuery(this).removeClass('ow_voting_grey_button');
									}
								}
							});
						}
					}
					
				}					
				
				jQuery('.ow_vote_fancybox_result').css("background","none");
				jQuery('.ow_vote_fancybox_result_header').html(result.msg);
				jQuery('.ow_vote_fancybox_result').html(result.msg_html);
				var get_html_pretty = jQuery('.ow_fancy_content_social'+vote_id).html();
				jQuery('.ow_vote_fancybox_result').append("Share with your Friends");
				jQuery('.ow_vote_fancybox_result').append("<div class='ow_fancy_content_social'>"+get_html_pretty+"</div>");
			   }	
			});
			return false;
		}else{
			ow_vote_ppOpen('#ow_vote_login_panel', '300',1);
		}
	}
	
	//Timer votes
	function votes_countdown(el) {
		var countdown = jQuery(el);
		if (!countdown.length) return false;
			countdown.each(function(i, e) {
				var timer 	= jQuery(this).data('datetimer').split("-");
				var currenttimer = jQuery(this).data('currenttimer').split("-");
				jQuery(this).ow_vote_countDown({
					omitWeeks: false, 
					targetDate: {					
						'year':     timer[0],
						'month':    timer[1],
						'day':      timer[2],
						'hour':     timer[3],
						'min':      timer[4],
						'sec':      timer[5]
					},
					currentDate: {					
						'year':     currenttimer[0],
						'month':    currenttimer[1],
						'day':      currenttimer[2],
						'hour':     currenttimer[3],
						'min':      currenttimer[4],
						'sec':      currenttimer[5]
					},
					onComplete: function() {
						//console.log('Completed');
					}
				});
				countdown.css('visibility','visible');
			});
	}
	
	//PrettyPhoto
	function ow_vote_ppOpen(panel, width,flag){
		
		jQuery(function() { 
			jQuery('.date_picker').live('click', function () {
				jQuery(this).owvotedatetimepicker('destroy').owvotedatetimepicker({format:'m-d-Y',
				    step:10,
				    timepicker: false,}).focus();
			});
		});
		
		 if(panel=='#ow_vote_forgot_panel'){
			jQuery('.forgot-panel').show();
            jQuery( ".inner-container" ).removeClass('login-panel_add');
            jQuery( ".inner-container" ).removeClass('register-panel_add'); 
            jQuery( ".inner-container" ).removeClass('forgot-panel_add');  
            jQuery( ".inner-container" ).addClass('forgot-panel_add');  
        }else if(panel=='#ow_vote_login_panel'){
            jQuery( ".inner-container" ).removeClass('login-panel_add');
            jQuery( ".inner-container" ).removeClass('register-panel_add'); 
            jQuery( ".inner-container" ).removeClass('forgot-panel_add'); 
            jQuery( ".inner-container" ).addClass('login-panel_add');  
        }else if(panel=='#ow_vote_register_panel'){
            jQuery( ".inner-container" ).removeClass('login-panel_add');
            jQuery( ".inner-container" ).removeClass('register-panel_add'); 
            jQuery( ".inner-container" ).removeClass('forgot-panel_add'); 
            jQuery( ".inner-container" ).addClass('register-panel_add');
        }
		
		jQuery( ".error_empty" ).remove();
        
        if(jQuery('.pp_pic_holder').size() > 0){
            jQuery.ow_vote_prettyPhoto.close();
        }

        setTimeout(function() {
			jQuery.fn.ow_vote_prettyPhoto({
				social_tools: false,
				deeplinking: false,
				show_title: false,
				default_width: width,
				theme:'pp_kalypso'
			});
			jQuery.ow_vote_prettyPhoto.open(panel);
		}, 300);
	}
	
	//User registration and login	
	function ow_vote_submit_user_form(){
		jQuery(document).on('submit','.zn_form_login',function(event){
			event.preventDefault();
			
			var form = jQuery(this),
			warning = false,
			button = jQuery('.zn_sub_button',this),
			values = form.serialize();
			var get_reg_or_login = '';
			
			if(jQuery( ".inner-container" ).hasClass( "register-panel_add" )){
				get_reg_or_login='register';
				//Validation for custom fields 	
                jQuery('.required_vote_custom').filter(':visible').each(function(){  
                    var type_bos = jQuery(this).attr('type'); 
                    var in_id = jQuery(this).attr('id');
                    if(type_bos=='checkbox'){    
                          var in_idc = jQuery(this).attr('id');
                          if (jQuery('.reg_check_'+in_idc+':checked').length > 0){
                            jQuery('.'+in_idc).attr('style','');
                          }
                          else{
                            jQuery('.'+in_idc).attr('style','color:red');
                            warning = true;
                          }
                    }else if(type_bos=='radio'){
                        var in_ids = jQuery(this).attr('id');
                           if (jQuery('.reg_radio_'+in_ids+':checked').length > 0){
                              jQuery('.'+in_ids).attr('style',''); 
                           }else{
                              jQuery('.'+in_ids).attr('style','color:red');
                              warning = true;
                           }
                    }
                    else{
        				if ( !jQuery(this).val() ) { 
        				    jQuery(this).attr('style','border:1px solid red;');
                            warning = true;
        				}else{
        				   jQuery(this).attr('style','border:none;'); 
        				}
                    }
    			});            
            }else{
			  get_reg_or_login='login';
              jQuery('input',form).each(function(){
				if ( !jQuery(this).val() ) {
					warning = true;
				}
              });   
            }
			
			if( warning ) {
			     jQuery(".error_empty").remove();
    			 
                 if(jQuery( ".inner-container" ).hasClass( "register-panel_add" )){
    			     jQuery( ".m_title" ).after( "<p class='error_empty'>Please Fill In The Required Fields Below. </p>" );    
    			 }
                 if(jQuery( ".inner-container" ).hasClass( "login-panel_add" )){
    			     jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Username and Password. </p>" );    
    			 }
				button.removeClass('zn_blocked');
				return false;
			}
			if( button.hasClass('zn_blocked')) {
				return;
			}
			button.addClass('zn_blocked');

			jQuery.post(vote_path_local.votesajaxurl, values, function(resp) { 
			    jQuery(".error_empty").remove();
				var res = resp.split("~~");
				var data = jQuery(document.createElement('div')).html(res[1]);				
				if (get_reg_or_login=='login') {
					if (res[0]==1) {
						data.find('a').attr('onClick','ow_vote_ppOpen(\'#ow_vote_forgot_panel\', \'300\');return false;');
						jQuery('div.links', form).html(data);
						button.removeClass('zn_blocked');
					}else{						
						if (jQuery('.zn_login_redirect', form).length > 0) {
							jQuery.ow_vote_prettyPhoto.close();
							redirect = jQuery('.zn_login_redirect', form);
							href = redirect.val();
							if (jQuery('.ow_open_login_form').val() == 1) {								
								jQuery('.ow_open_login_form').val('0');
								jQuery('.ow_form_add-contestants'+window.contest_id).submit(); 
								return true;
							}
							else{
								window.location = href;
							}
						}
					}
				}else{
					if(res[0]==0){
							button.addClass('zn_blocked');
							jQuery.ow_vote_fancybox('<h2 style="margin:10px 0  0 10px;font-size:inherit;">Your account has been created and you will be now logged in</h2>',
								{
								'width':300,
								'height':150,
								'maxWidth': 300,
								'maxHeight': 150,
								'minWidth': 200,
								'minHeight': 80
								}
							);
							if (jQuery('.ow_open_login_form').val() == 1) {
								jQuery('.ow_open_login_form').val('0');
								jQuery('.ow_form_add-contestants'+window.contest_id).submit(); 
								return false;
							}
							else{
								setTimeout(function() {
									jQuery.ow_vote_prettyPhoto.close();
									redirect = jQuery('.zn_login_redirect', form);
									href = redirect.val();
									window.location = href;
								}, 2000);
							}
						
					}
					else{
						button.removeClass('zn_blocked');
						jQuery.ow_vote_fancybox('<h2 style="margin:10px 0  0 10px;font-size:inherit;">'+res[1]+'</h2>',
								{
								'width':300,
								'height':150,
								'maxWidth': 300,
								'maxHeight': 150,
								'minWidth': 200,
								'minHeight': 80
								}
							);
					}
				}
			    return false;
				button.removeClass('zn_blocked');
			});
		});
		
		// LOST PASSWORD
		jQuery(document).on('submit','.zn_form_lost_pass',function(event){
			event.preventDefault();
			var form = jQuery(this),
			warning = false,
			button = jQuery('.zn_sub_button',this),
			values = form.serialize()+'&ajax_login=true';
			jQuery('input',form).each(function(){
				if ( !jQuery(this).val() ) {
					warning = true;
				}
			}); 
			if( warning ) {
			     jQuery(".error_empty").remove();
			     if(jQuery( ".inner-container" ).hasClass( "forgot-panel_add" )){
    			     jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Username / Email. </p>" );
    			 }
				button.removeClass('zn_blocked');
				return false;
			}
			if( button.hasClass('zn_blocked')) {
				return;
			}
			button.addClass('zn_blocked');
	        jQuery(".error_empty").remove();                        
			jQuery.ajax({
				url: form.attr('action'),
				data: values,
				type: 'POST',
				cache: false,
				success: function (resp) {
					var data = jQuery(document.createElement('div')).html(resp);
					
					jQuery('div.links', form).html('');
					
					if ( jQuery('#login_error', data).length ) {
					
						// We have an error
						var error = jQuery('#login_error', data);
						error.find('a').attr('onClick','ppOpen(\'#forgot_panel\', \'350\');return false;');
						jQuery('div.links', form).html(error);

					}
					else if ( jQuery('.message', data).length ) {
						var message = jQuery('.message', data);
						jQuery('div.links', form).html(message);
					}
					else {
					
						jQuery.prettyPhoto.close();
						redirect = jQuery('.zn_login_redirect', form);
						href = redirect.val();
						location.reload(true);
					}
					
					button.removeClass('zn_blocked');
				},
				error: function (jqXHR , textStatus, errorThrown ) {
					jQuery('div.links', form).html(errorThrown);

				}
			});
		});
		
		 // EMAIL - TWITTER LOGIN
		jQuery(document).on('submit','.zn_form_save_email',function(event){
			event.preventDefault();
		
			var form = jQuery(this),
				warning = false,
				button = jQuery('.zn_sub_button',this),
				values = form.serialize()+'&ajax_login=true&action=voting_save_twemail_session';   
                
			
			jQuery('input',form).each(function(){
				if ( !jQuery(this).val() ) {
					warning = true;
				}
			}); 
			
			if( warning ) {
			     jQuery(".error_empty").remove();
			     if(jQuery( ".inner-container" ).hasClass( "forgot-panel_add" )){
    			     jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Email. </p>" );
    			 }
				button.removeClass('zn_blocked');
				return false;
			}
			
			if( button.hasClass('zn_blocked')) {
				return;
			}
			
			button.addClass('zn_blocked');
	        jQuery(".error_empty").remove();                        
				jQuery.ajax({
				url: vote_path_local.votesajaxurl,
				data: values,
				type: 'POST',
				cache: false,
				success: function (resp) {
				    if(resp == 0){
				        jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Valid Email. </p>" );
                        button.removeClass('zn_blocked');
				    }
                    else{
                        jQuery(".error_empty").remove();
                        votes_twitter_authentication();
                    }
				},
				error: function (jqXHR , textStatus, errorThrown ) {
					jQuery('div.links', form).html(errorThrown);

				}
			});		 
			 
		}); 
		
		
		// EMAIL - Verification form
		jQuery(document).on('submit','.zn_email_verification',function(event){ 
			event.preventDefault();
	
			var form = jQuery(this),
				warning = false,
				sendy_email = '',
				warning_acceptance = false,
				button = jQuery('.zn_sub_button',this),
				values = form.serialize()+'&ajax_login=true&action=voting_email_verification';

           	
			jQuery('input',form).each(function(){
				if ( !jQuery(this).val() ) {
					warning = true;
				}
			}); 

			if( warning ) { 
			     jQuery(".error_empty").remove();
			     if(jQuery( ".inner-container" ).hasClass( "login-panel" )){
				jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Email. </p>" );
			     }
				button.removeClass('zn_blocked');
				return false;
			}
			


			/* mod_start */

			if(!jQuery('input[name="acceptance_checkbox"]:checked').val()) { 
				 
				jQuery(".error_empty").remove();
			    if(jQuery( ".inner-container" ).hasClass( "login-panel" )){
					jQuery( ".m_title" ).after( "<p class='error_empty'>Please Accept</p>" );
			    }
				button.removeClass('zn_blocked');
				return false;
		
			}


 			this.slist = '';
 			this.slist_warning = true; 
 			var self = this;

			jQuery('.slist_checkbox',form).each(function(){
				slist_name = jQuery(this).attr('id');
				if (jQuery('input[name="'+slist_name+'"]:checked').val()) {
					self.slist_warning = false;
					self.slist += '&ajax_'+slist_name+'='+slist_name;
				}
			}); 

			if (jQuery('input[name=slist_radio]:checked').val()){
				slist_name = jQuery('input[name=slist_radio]:checked').val();
				this.slist = self.slist += '&ajax_'+slist_name+'='+slist_name;
				self.slist_warning = false;
			}
				
			if( self.slist_warning ) { 
			     jQuery(".error_empty").remove();
			     if(jQuery( ".inner-container" ).hasClass( "login-panel" )){
				jQuery( ".m_title" ).after( "<p class='error_empty'>Please choose your status. </p>" );
			     }
				button.removeClass('zn_blocked');
				return false;
			}


			/* mod_end */





			if( button.hasClass('zn_blocked')) {
				return;
			}
			
			button.addClass('zn_blocked');
			jQuery(".error_empty").remove();                        
			jQuery.ajax({
				url: vote_path_local.votesajaxurl,
				data: values+self.slist,
				type: 'POST',
				cache: false,
				success: function (resp) { 
				    if(resp == 0){
				        jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Valid Email. </p>" );
					button.removeClass('zn_blocked');
				    }
				    else if(resp == 'captcha_fail'){
				        jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter The Captcha. </p>" );
					button.removeClass('zn_blocked');
				    }
				    else{

					jQuery(".error_empty").remove();
					jQuery(".ow_voting_verification_code_div").show();
					jQuery(".ow_voting_verification").hide();
					
				    }
				},
				error: function (jqXHR , textStatus, errorThrown ) {
					jQuery('div.links', form).html(errorThrown);

				}
			});		 
			 
		});
		
		// EMAIL - Verification Code
		jQuery(document).on('submit','.zn_email_verification_code',function(event){
			event.preventDefault();
		
			var form = jQuery(this),
				warning = false,
				button = jQuery('.zn_sub_button',this),
				values = form.serialize()+'&ajax_login=true&action=voting_email_code';   
               			
			jQuery('input',form).each(function(){
				if ( !jQuery(this).val() ) {
					warning = true;
				}
			}); 
			
			if( warning ) {
			     jQuery(".error_empty").remove();
			     if(jQuery( ".inner-container" ).hasClass( "login-panel" )){
				jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter Valid Code. </p>" );
			     }
				button.removeClass('zn_blocked');
				return false;
			}
			
			if( button.hasClass('zn_blocked')) {
				return;
			}
			
			button.addClass('zn_blocked');
			jQuery(".error_empty").remove();                        
			jQuery.ajax({
				url: vote_path_local.votesajaxurl,
				data: values,
				type: 'POST',
				cache: false,
				success: function (resp) {
				    if(resp == 0){
				        jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter Valid Code. </p>" );
					button.removeClass('zn_blocked');
				    }
				    else{
				    

				    	/*  mod_start  */

						url = vote_path_local.votesajaxurl + "?action=add_to_sendy";
						
						jQuery.post(url, {name:'user', email:'aei874@mail.ru'},
						  function(data) {
						      if(data)
						      {    		
						      	alert(data);  		
						      }
						      else
						      {
						      	alert("Sorry, unable to subscribe. Please try again later!");
						      }
						  }
						);

						/*  mod_end  */



					jQuery(".error_empty").remove();
					jQuery.ow_vote_prettyPhoto.close();
					vote_button_function(window.link_btn,"");
					jQuery(".ow_voting_verification_code_div").hide();
					jQuery(".ow_voting_verification").show();					
				    }
				},
				error: function (jqXHR , textStatus, errorThrown ) {
					jQuery('div.links', form).html(errorThrown);

				}
			});


				 
			 
		});
		
		// EMAIL Grab for IP and COOKIE
		jQuery(document).on('submit','.zn_email_grab',function(event){
			event.preventDefault();
		
			var form = jQuery(this),
				warning = false,
				button = jQuery('.zn_sub_button',this),
				values = form.serialize()+'&ajax_login=true&action=ow_voting_grab_email';   
               			
			jQuery('input',form).each(function(){
				if ( !jQuery(this).val() ) {
					warning = true;
				}
			}); 
			
			if( warning ) {
			     jQuery(".error_empty").remove();
			     if(jQuery( ".inner-container" ).hasClass( "login-panel" )){
				jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter Valid Email Address. </p>" );
			     }
				button.removeClass('zn_blocked');
				return false;
			}
			
			if( button.hasClass('zn_blocked')) {
				return;
			}
			
			button.addClass('zn_blocked');
			jQuery(".error_empty").remove();                        
			jQuery.ajax({
				url: vote_path_local.votesajaxurl,
				data: values,
				type: 'POST',
				cache: false,
				success: function (resp) {
				    if(resp == 0){
				        jQuery( ".m_title" ).after( "<p class='error_empty'>Please Enter Valid Email Address. </p>" );
						button.removeClass('zn_blocked');
				    }
				    else{
					jQuery(".error_empty").remove();
					jQuery.ow_vote_prettyPhoto.close();
					vote_button_function(window.link_btn,"");					
					jQuery(".ow_voting_verification").show();					
				    }
				},
				error: function (jqXHR , textStatus, errorThrown ) {
					jQuery('div.links', form).html(errorThrown);

				}
			});		 
			 
		});
		
	}
	
	
	function ow_pretty_photo_gallery(){
		var vote_prettyphoto_disable = jQuery('.vote_prettyphoto_disable').val();		
		if (vote_prettyphoto_disable == 1) {			
			jQuery('a[data-vote-gallery^=ow_vote_prettyPhoto]').ow_vote_prettyPhoto({
				hook:'data-vote-gallery',
				markup: ow_pretty_photo_theme_markupp(),
				social_tools: false,
				deeplinking: false,
				show_title: true,
				theme:'pp_kalypso',  
				changepicturecallback: function(ow_vote_id,ow_term_id)
				{
					//console.log(ow_vote_id);
					var get_html_pretty = jQuery('.ow_pretty_content_social'+ow_vote_id).html();
					jQuery('.pp_social').html(get_html_pretty);
					ow_voting_add_contents_pretty(ow_vote_id);
				}                   	                            		
			});
		}
	}
	
	function ow_voting_add_contents_pretty(votes_id){		
		jQuery.ajax({
			url: vote_path_local.votesajaxurl,
			data:{
				action:'voting_additional_fields_pretty',			
				pid:votes_id       		   
			},
			type: 'POST',
			//dataType: 'html',
			success: function( result ) {   
			   if(result != 0) 
				 jQuery('.pp_mult_desc').html(jQuery(result));
			}	
		});
		
	}
	
	function ow_pretty_photo_theme_markupp() {
		window.markupp = '<div class="pp_pic_holder"> \
                \     <div class="pp_social"></div> \
    						<div class="ppt">&nbsp;</div> \
    						<div class="pp_top"> \
    							<div class="pp_left"></div> \
    							<div class="pp_middle"></div> \
    							<div class="pp_right"></div> \
    						</div> \
    						<div class="pp_content_container"> \
    							<div class="pp_left"> \
    							<div class="pp_right"> \
    								<div class="pp_content"> \
    									<div class="pp_loaderIcon"></div> \
    									<div class="pp_fade pp_single"> \
    										<a href="#" class="pp_expand" title="Expand the image">Expand</a> \
    										<div class="pp_hoverContainer"> \
    											<a class="pp_next" href="#">next</a> \
    											<a class="pp_previous" href="#">previous</a> \
    										</div> \
    										<div id="pp_full_res" class=""></div> \
    										<div class="pp_details"> \
    											<div class="pp_nav"> \
    												<a href="#" class="pp_arrow_previous">Previous</a> \
    												<p class="currentTextHolder">0/0</p> \
    												<a href="#" class="pp_arrow_next">Next</a> \
    											</div> \
    											<p class="pp_description"></p> \
                                                <p class="pp_mult_desc"></p> \
    											\
    											<a class="pp_close" href="#">Close</a> \
    										</div> \
    									</div> \
    								</div> \
    							</div> \
    							</div> \
    						</div> \
    						<div class="pp_bottom"> \
    							<div class="pp_left"></div> \
    							<div class="pp_middle"></div> \
    							<div class="pp_right"></div> \
    						</div> \                                                                                                                                                      </div> \
    					<div class="pp_overlay"></div>'; 
		
		return window.markupp;
	}
	
	function confirm_delete_single(vote_id)
	{
		var r = confirm(jQuery('#confirm_delete_single').val());
		if (r == true) {
		   jQuery("#delete_contestants"+vote_id).submit();
		   return true;
		} else {
		   return false;
		}
	}
	
	// Ajax Scroll for Shortcode Contest Page Not for All Contestant Page
	function ow_voting_load_more(){
		jQuery('.ow_load_more').click(function (){			
			var cat_id = this.id.split("_")[2];
			var postperpage = jQuery('#ow_postperpage_'+cat_id).val();
			var offset = jQuery('#ow_offset_'+cat_id).val();
			var ow_category_options = jQuery('#ow_category_options_'+cat_id).val();
			var show_cont_args = jQuery('#ow_show_cont_args_'+cat_id).val();
			var global_options = jQuery('#ow_show_global_'+cat_id).val();
			jQuery.ajax({
			   url: vote_path_local.votesajaxurl,
			   data:{
				action:'voting_load_more',			
				cat_id:cat_id,
				postperpage : postperpage,
				category_option:ow_category_options,
				show_cont_args : show_cont_args,
				global_options : global_options,
				offset:offset
			   },
			   type: 'POST',
			   cache: false,
			   dataType: 'html',
			   beforeSend:function(){
					jQuery('.ow_jx_loader_'+cat_id).show();				
			   },
			   success: function( response ) {
				jQuery('.ow_jx_loader_'+cat_id).hide();
				if(cat_id == 0){
					jQuery('.ow_contest-posts-container0').append(response);
					new GridScrollFx( document.getElementById( 'grid' ), {
							viewportFactor : 0.4
					});
				}
				else{
					jQuery('.ow_jx_response_'+cat_id).append(response);
				}
				jQuery('#ow_offset_'+cat_id).val(parseInt(offset)+parseInt(postperpage));
				if (jQuery('#ow_load_stop_'+cat_id).val() == -1) {
					jQuery('#ow_load_'+cat_id).hide();
					jQuery('.ow_all_contestloaded').delay(5000).fadeOut('slow');
				}
				ow_pretty_photo_gallery();
				var view = jQuery('.ow_vote_view_'+cat_id).attr('data-view');
				if (view=='list') {
					ow_show_contest_list(cat_id);
					ow_votes_list_page_show_contest();
				}else{
					ow_show_contest_grid(cat_id);
				}
			   }	
			});
		});
		
		jQuery(function(){
			var ow_infinite = jQuery('#ow_infinite').val();
			if (ow_infinite == 1) {		
			var scrollFunction = function(){
				jQuery('.ow_all_contestloaded').delay(5000).fadeOut('slow');				
				var mostOfTheWayDown = (jQuery(document).height() - jQuery('.ow_views_container').height()) * 1 / 3;
				if (jQuery(window).scrollTop() >= mostOfTheWayDown) {
					jQuery(window).unbind("scroll"); 			    
					var obj  = jQuery('.ow_views_container');				
					var cat_id = jQuery(obj).attr('id').split("ow_views_container_")[1];
					var postperpage = jQuery('#ow_postperpage_'+cat_id).val();
					var offset = jQuery('#ow_offset_'+cat_id).val();
					var ow_category_options = jQuery('#ow_category_options_'+cat_id).val();
					var show_cont_args = jQuery('#ow_show_cont_args_'+cat_id).val();
					var global_options = jQuery('#ow_show_global_'+cat_id).val();
					if (jQuery('#ow_load_stop_'+cat_id).val() != -1) {
					jQuery.ajax({
					   url: vote_path_local.votesajaxurl,
					   data:{
						action:'voting_load_more',			
						cat_id:cat_id,
						postperpage : postperpage,
						category_option:ow_category_options,
						show_cont_args : show_cont_args,
						global_options:global_options,
						offset:offset
					   },
					   type: 'POST',
					   cache: false,
					   dataType: 'html',
					   beforeSend:function(){
						jQuery('.ow_jx_loader_'+cat_id).show();				
					   },
					   success: function( response ) {
						jQuery('.ow_jx_loader_'+cat_id).hide();
						
						jQuery('.ow_jx_response_'+cat_id).append(response);
						
						jQuery('#ow_offset_'+cat_id).val(parseInt(offset)+parseInt(postperpage));
						jQuery(window).scroll(scrollFunction);
						ow_pretty_photo_gallery();
						var view = jQuery('.ow_vote_view_'+cat_id).attr('data-view');
						if (view=='list') {
							ow_show_contest_list(cat_id);
							ow_votes_list_page_show_contest();
						}else{
							ow_show_contest_grid(cat_id);
						}
					   }	
					});
					}		    
				}		    
			};
			jQuery(window).scroll(scrollFunction);
			}
		});
	}
	
	
	// Ajax Scroll for Only All Contestant Page
	function ow_voting_load_more_all(){
		jQuery('.ow_load_more_all').click(function (){			
			var cat_id = this.id.split("_")[2];
			var postperpage = jQuery('#ow_postperpage_'+cat_id).val();
			var offset = jQuery('#ow_offset_'+cat_id).val();
			var ow_category_options = jQuery('#ow_category_options_'+cat_id).val();
			var show_cont_args = jQuery('#ow_show_cont_args_'+cat_id).val();
			var global_options = jQuery('#ow_show_global_'+cat_id).val();
			jQuery.ajax({
			   url: vote_path_local.votesajaxurl,
			   data:{
				action:'voting_load_more',			
				cat_id:cat_id,
				ow_ajax_flag:1,
				postperpage : postperpage,
				category_option:ow_category_options,
				show_cont_args : show_cont_args,
				global_options : global_options,
				offset:offset,
				ow_search : jQuery('#ow_search_input').val(),
			   },
			   type: 'POST',
			   cache: false,
			   dataType: 'html',
			   beforeSend:function(){
					jQuery('.ow_jx_loader_'+cat_id).show();				
			   },
			   success: function( response ) {
				
					jQuery('.ow_jx_loader_'+cat_id).hide();
					
					jQuery('.ow_contest-posts-container0').append(response);
					if(typeof GridScrollFx !== typeof undefined ){
						new GridScrollFx( document.getElementById( 'grid' ), {
								viewportFactor : 0.4
						});
					}
					
					jQuery('#ow_offset_'+cat_id).val(parseInt(offset)+parseInt(postperpage));
					if (jQuery('.ow_load_stop').val() == -1) {
						jQuery('.ow_load_more_all').hide();
						jQuery('.ow_all_contestloaded').delay(5000).fadeOut('slow');
					}
				
			   }	
			});
		});
		
		jQuery(function(){
			var ow_infinite = jQuery('#ow_infinite_all').val();
			if (ow_infinite == 1) {		
			var scrollFunction = function(){
				jQuery('.ow_all_contestloaded').delay(5000).fadeOut('slow');				
				var mostOfTheWayDown = (jQuery(document).height() - jQuery('.ow_views_container').height()) * 1 / 3;
				if (jQuery(window).scrollTop() >= mostOfTheWayDown) {
					jQuery(window).unbind("scroll"); 			    
					var obj  = jQuery('.ow_views_container');				
					var cat_id = jQuery('.ow_all_cat_id').val();
					var postperpage = jQuery('#ow_postperpage_'+cat_id).val();
					var offset = jQuery('#ow_offset_'+cat_id).val();
					var ow_category_options = jQuery('#ow_category_options_'+cat_id).val();
					var show_cont_args = jQuery('#ow_show_cont_args_'+cat_id).val();
					var global_options = jQuery('#ow_show_global_'+cat_id).val();
					if (jQuery('.ow_load_stop').val() != -1) {
					jQuery.ajax({
					   url: vote_path_local.votesajaxurl,
					   data:{
						action:'voting_load_more',			
						cat_id:cat_id,
						postperpage : postperpage,
						category_option:ow_category_options,
						show_cont_args : show_cont_args,
						global_options:global_options,
						offset:offset,
						ow_ajax_flag:1,
						ow_search : jQuery('#ow_search_input').val(),
					   },
					   type: 'POST',
					   cache: false,
					   dataType: 'html',
					   beforeSend:function(){
						jQuery('.ow_jx_loader_'+cat_id).show();				
					   },
					   success: function( response ) {
						jQuery('.ow_jx_loader_'+cat_id).hide();
						
							jQuery('.ow_contest-posts-container0').append(response);
							if(typeof GridScrollFx !== typeof undefined ){
								new GridScrollFx( document.getElementById( 'grid' ), {
										viewportFactor : 0.4
								});
							}
						
						jQuery('#ow_offset_'+cat_id).val(parseInt(offset)+parseInt(postperpage));
						setTimeout(function(){jQuery(window).scroll(scrollFunction)},1000);
						//jQuery(window).scroll(scrollFunction)
					   }	
					});
					}		    
				}		    
			};
			setTimeout(function(){jQuery(window).scroll(scrollFunction)},1000);
			//jQuery(window).scroll(scrollFunction)
			}
		});
	}

