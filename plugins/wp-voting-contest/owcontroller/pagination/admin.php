<?php

class Voting_PageNavi_Options_Page extends scbAdminPage {

	function setup() {
		$this->textdomain = 'voting-contest';

		$this->args = array(
			'page_title' => __( 'Contest Pagination Settings', 'voting-contest' ),
			'menu_title' => __( 'Pagination Settings', 'voting-contest' ),
			'page_slug' => 'votes_setting_paginate',
		);
	}

	function validate( $new_data, $old_data ) {
		$options = wp_parse_args($new_data, $old_data);
		foreach ( array( 'style', 'num_pages', 'num_larger_page_numbers', 'larger_page_numbers_multiple' ) as $key )
			$options[$key] = absint( @$options[$key] );

		foreach ( array( 'use_pagenavi_css', 'always_show' ) as $key )
			$options[$key] = intval(@$options[$key]);

		return $options;
	}

	function page_content() {
		$rows = array(
			array(
				'title' => __( 'Text For Number Of Pages', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">
					%CURRENT_PAGE% - ' . __( 'The current page number.', 'voting-contest' ) . '<br />
					%TOTAL_PAGES% - ' . __( 'The total number of pages.', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'pages_text',
				'extra' => 'size="50"',
				'desc' => ''
			),

			array(
				'title' => __( 'Text For Current Page', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">
					%PAGE_NUMBER% - ' . __( 'The page number.', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'current_text',
				'desc' => ''
			),

			array(
				'title' => __( 'Text For Page', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">
					%PAGE_NUMBER% - ' . __( 'The page number.', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'page_text',
				'desc' => ''
			),

			array(
				'title' => __( 'Text For First Page', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">
					%TOTAL_PAGES% - ' . __( 'The total number of pages.', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'first_text',
				'desc' => ''
			),

			array(
				'title' => __( 'Text For Last Page', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">
					%TOTAL_PAGES% - ' . __( 'The total number of pages.', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'last_text',
				'desc' => ''
			),

			array(
				'title' => __( 'Text For Previous Page', 'voting-contest' ),
				'type' => 'text',
				'name' => 'prev_text',
			),

			array(
				'title' => __( 'Text For Next Page', 'voting-contest' ),
				'type' => 'text',
				'name' => 'next_text',
			),

			array(
				'title' => __( 'Text For Previous ...', 'voting-contest' ),
				'type' => 'text',
				'name' => 'dotleft_text',
			),

			array(
				'title' => __( 'Text For Next ...', 'voting-contest' ),
				'type' => 'text',
				'name' => 'dotright_text',
			),
			
			
			
			array(
				'title' => __( 'Text For LoadMore Button', 'voting-contest' ),
				'type' => 'text',
				'name' => 'load_more_button_text',
			),
			
			
		);

		$out =
		 html( 'h3', __( 'Page Navigation Text', 'voting-contest' ) )
		.html( 'p', __( 'Leaving a field blank will hide that part of the navigation.', 'voting-contest' ) )
		.$this->table( $rows );


		$rows = array(
			array(
				'title' => __( 'Use pagenavi-css.css', 'voting-contest' ),
				'type' => 'radio',
				'name' => 'use_pagenavi_css',
				'choices' => array( 1 => __( 'Yes', 'voting-contest' ), 0 => __( 'No', 'voting-contest' ) )
			),

			array(
				'title' => __( 'Page Navigation Style', 'voting-contest' ),
				'type' => 'select',
				'name' => 'style',
				'values' => array( 1 => __( 'Normal', 'voting-contest' ), 2 => __( 'Drop-down List', 'voting-contest' ) , 3 => __( 'Loadmore Option', 'voting-contest' ), 4 => __( 'Infinite Scroll', 'voting-contest' )),
				'text' => false
			),

			array(
				'title' => __( 'Always Show Page Navigation', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">'.__( "Show navigation even if there's only one page.", 'voting-contest' ).'</div>',
				'type' => 'radio',
				'name' => 'always_show',
				'choices' => array( 1 => __( 'Yes', 'voting-contest' ), 0 => __( 'No', 'voting-contest' ) ),
				'desc' => ''
			),

			array(
				'title' => __( 'Number Of Pages To Show', 'voting-contest' ),
				'type' => 'text',
				'name' => 'num_pages',
				'extra' => 'class="small-text"'
			),

			array(
				'title' => __( 'Number Of Larger Page Numbers To Show', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">' . __( 'Larger page numbers are in addition to the normal page numbers. They are useful when there are many pages of posts.', 'voting-contest' ) .
				'<br />' . __( 'For example, WP-PageNavi will display: Pages 1, 2, 3, 4, 5, 10, 20, 30, 40, 50.', 'voting-contest' ) .
				'<br />' . __( 'Enter 0 to disable.', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'num_larger_page_numbers',
				'extra' => 'class="small-text"',
				'desc' =>
				''
			),

			array(
				'title' => __( 'Show Larger Page Numbers In Multiples Of', 'voting-contest' ).' '.'<div class="hasTooltip"></div><div class="hidden">' . __( 'For example, if mutiple is 5, it will show: 5, 10, 15, 20, 25', 'voting-contest' ).'</div>',
				'type' => 'text',
				'name' => 'larger_page_numbers_multiple',
				'extra' => 'class="small-text"',
				'desc' =>
				''
			)
		);

		include(OW_VIEW_PATH.'ow_common_settings_view.php');
		$get_action ='paginate';
		ow_common_settings_view($get_action);
		
		$out .=
		 html( 'h3', __( 'Page Navigation Options', 'voting-contest' ) )
		.$this->table( $rows );

		
		echo '<div class="settings_content">'.html( 'h1', $this->args['page_title'] ).$this->form_wrap( $out ).'</div>';
        
	}
}

