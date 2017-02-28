(function($) { 'use strict';

	var w=window,d=document,
    e=d.documentElement,
    g=d.getElementsByTagName('body')[0];

    $(document).ready(function($){

    	var type = window.location.hash.substr(1);

    	if ( '' != type ) {
    		var comment_id = $( '#comments' ).find( '#'+type );

    		if ( comment_id.length ) {
    			$( '#comments' ).toggleClass('expanded');
    		}

    	}

    	var x=w.innerWidth||e.clientWidth||g.clientWidth, // Viewport Width
        y=w.innerHeight||e.clientHeight||g.clientHeight; // Viewport Height

        // Global Vars

        var $window = $(window);
        var body = $('body');

    	// Header Search

    	var headerSearchField = $('header input.search-field');
    	var headerSearchIcon = $('header i.fa-search');

    	$(document).on('click', function(){
    		var mainHeader = $('#floating_menu header');
            mainHeader.removeClass('search-opened');
        });

    	headerSearchIcon.click(function(e){
    		e.stopPropagation();
    		$(this).closest('header').toggleClass('search-opened');
    	});

    	headerSearchField.click(function(e){
    		e.stopPropagation();
    	});

    	// Accordion

		var accordionEl = $('#accordion');
		if(accordionEl.length){
			accordionEl.accordion({
				active: false,
				collapsible: true,
				heightStyle: 'content'
			});
		}

		// Comments

		var comments = $('#comments');

		if(comments.length){
			var commentsTitle = comments.find('.ctb');
			var commentForm = $('#commentform');
			var commentFormSubmit = commentForm.find('input[type="submit"]');

			commentsTitle.click(function(){
				comments.toggleClass('expanded');
			});

			commentFormSubmit.click(function(){
				setTimeout(function(){
					commentForm.find('textarea').next('.error').text('Please fill out all required fields.').css({opacity: 1});
				}, 200);
			});

		}

		// Search form

		var searchForm = $('.wrp.cnt form.srh');

		if(searchForm.length){
			var searchSubmit = searchForm.find('.sBn, .search-button, .submit_btn');
			if(searchSubmit.is('input')) {
				searchSubmit.prop('value', 'Search');
			}

			if(searchSubmit.is('button')) {
				searchSubmit.html('Search');
			}
		}

		// dropdown button

		if(x > 774 && x < 1025){

	        var mainMenuDropdownLink = $('.menu > .menu-item-has-children > a, .menu > .page_item_has_children > a');
	        var dropDownArrow = $('<span class="dropdown-toggle"><i class="fa fa-chevron-down"></i></span>');

	        mainMenuDropdownLink.after(dropDownArrow);

	        // dropdown open on click

	        var dropDownButton = mainMenuDropdownLink.next('span.dropdown-toggle');

	        dropDownButton.on('click', function(){
	            var $this = $(this);
	            $this.parent('li').toggleClass('toggle-on').find('.toggle-on').removeClass('toggle-on');
	            $this.parent('li').siblings().removeClass('toggle-on');
	        });

	    }

	    // Mega menu on mobile

	    if(x < 775){
	    	var mainMenuDropdownLink = $('.menu-item-has-children > a, .page_item_has_children > a');
	        var dropDownArrow = $('<span class="dropdown-toggle"><i class="fa fa-chevron-right"></i></span>');

	        mainMenuDropdownLink.after(dropDownArrow);

	        $('header .menu > .menu-item-has-children').addClass('has-extended has-heading')

	        // dropdown open on click

	        var dropDownButton = $('span.dropdown-toggle, a.colch');

	        mainMenuDropdownLink.on('click', function(e){
	        	// Ovde Kuks ako budu hteli da im svi clanovi koji imaju podmenu otvaraju taj podmenu na klik, samo umesto dropDownButton stavi mainMenuDropdownLink i odkomentarisi e.preventDefault();

	        	e.preventDefault();
	            var $this = $(this);
	            $this.parent('li').toggleClass('toggle-on').find('.toggle-on').removeClass('toggle-on').find('.hide').removeClass('hide');
	            $this.parent('li').find('.hide').removeClass('hide');
	            $this.parent('li').siblings().removeClass('toggle-on');
	            if($this.parent('li').hasClass('toggle-on')){
	            	$this.parent('li').siblings().addClass('hide');
	            }
	            else{
	            	$this.parent('li').siblings().removeClass('hide');
	            }
	        });

	        var menuTrigger = $('header .hmn');

	        menuTrigger.on('click', function(){
	            body.toggleClass('menu-opened');
	        });

	    }

	    // Share on single

	    if(body.hasClass('single')){
	    	setTimeout(function(){
		    	var topShare = $('.awr .sumome-share-client-wrapper');
		    	var singleMeta = topShare.next();
		    	singleMeta.after(topShare);
	    	}, 2000);
	    }

	    // Set placeholder name for input
	    $( ".tve_lg_input input[name='email']" ).attr( 'placeholder', 'Email' );

	    $( '.search-no-results .scn .search-field' ).attr( 'placeholder', 'Search again' );


	    setTimeout(function(){
		    	var topShare = $('#accordion .sumome-share-client-wrapper');
		    	topShare.remove();
		    	$( '.home .sumome-stylebufferbottom-shim' ).remove();
		    	$( '.thrv-leads-screen-filler' ).attr( 'style', 'padding-top: 250px !important; padding-bottom: 100px;' );
	    	}, 2000);

	    $( '.tve-leads-in-content button' ).attr( 'style', 'margin-right: 5px !important;' );

	    $( '.tve-leads-slide-intve-leads-slide-in.tve-tl-anim.tve-leads-track-slide_in-8.tl-anim-slide_right.tl_bot_right.tve-leads-triggered' ).attr( 'style', 'bottom: 0 !important;' );

	    var menu_top_height = $( '.top-header' ).outerHeight();

	    $( '#nav_right' ).attr( 'style', 'height:calc(100vh - '+menu_top_height+') !important;' );


	    var submit_in_content = $( 'input[type=email].two-inputs' ).parent().next( '.tve_lg_input_container' ).find( 'button[type=Submit]' );

	    submit_in_content.css( 'margin-left', '0px !important' );

	}); // End Document Ready

})(jQuery);

