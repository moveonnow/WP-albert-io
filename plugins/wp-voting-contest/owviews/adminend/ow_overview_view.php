    <?php
	wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	wp_enqueue_style('OW_ADMIN_STYLES');
    ?>

    <div class="wrap">
        <h2><?php __('Overview','voting-contest'); ?></h2>
        
		<div class="narrow overview-page ow_voting_index" id="accordion">
			<div class="version_block"><div class="version_info"><?php _e('Version','voting-contest'); ?> <?php echo OW_VOTE_VERSION; ?></div>
				<a href="http://plugins.ohiowebtech.com/documentation/setup-contest-category/" target="_blank"><?php _e('Online Documentation','voting-contest'); ?></a>
				<a href="http://demo.ohiowebtech.com/" target="_blank"><?php _e('Demo Contests','voting-contest'); ?></a>
			</div>
			<h3 class="info"><?php _e('Example of basic shortcode usage:','voting-contest'); ?> [showcontestants id=42] <br />(Note: You must replace the ID # with the ID # of the contest category you want to display.</h3>		
			<h3 class="info"><?php _e('Example of all shortcode usage:','voting-contest'); ?><br />[showcontestants id=42 postperpage=20 thumb=1 height=200 width=200 title=Sample termdisplay=1 order=ASC orderby=date showtimer=1 showform=1 view=grid pagination=0]</h3>
			<ul class="overview-listing">
				<li ><b>showcontestants</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					<ul>
						<?php
						echo html('li', '<u>id</u> &nbsp '.__('(IMPORTANT: You must enter the correct Contest Category ID in your shortcode for your contest to be displayed properly. The ID is listed on the Contest Category page.)','voting-contest'));
						echo html('li', '<u>orderby</u> &nbsp'.__('(Note: Specify how your contestants are ordered by, by adding orderby=votes to your shortcode. Options are listed <a href="http://plugins.ohiowebtech.com/documentation/shortcode-usage/" target="_blank">HERE</a> )','voting-contest'));
						echo html('li', '<u>order</u> &nbsp '.__('(Note: Specify the Order the Contestants are listed in by adding order=ASC or order=DESC to your shortcode.)','voting-contest'));
						echo html('li', '<u>postperpage</u> &nbsp '.__('(Note: Specify the Number of Contestants to display per page in the Contest Listing by adding postperpage=10 to your shortcode. Change # to desired amount.)','voting-contest'));
						echo html('li', '<u>thumb</u> &nbsp '.__('(Note: Specify whether the thumbnail will be displayed in the Contest Listing)','voting-contest'));
						echo html('li', '<u>height</u> &nbsp '.__('(Note: Specify the Height of the Thumbnail in the Contest Listing)','voting-contest'));
						echo html('li', '<u>width</u> &nbsp '.__('(Note: Specify the Width of the Thumbnail in the Contest Listing)','voting-contest'));
						echo html('li', '<u>title</u> &nbsp '.__('(Note: Add a title above your contest by adding title=Your Title Here   to your shortcode. This is rarely used or needed.)','voting-contest'));
						echo html('li', '<u>termdisplay</u> &nbsp '.__('(Note: Specify whether the Category is displayed in the Contest Listing)','voting-contest'));
						echo html('li', '<u>exclude</u> &nbsp '.__('(Note: Exclude contestants from your listing by adding exclude=1,2,3 to your shortcode. You must separate multiple contestants post ids by a comma.)','voting-contest'));
						echo html('li', '<u>pagination</u> &nbsp '.__('(Note: The contestants will be displayed without pagination if it is set to 0)','voting-contest'));
						echo html('li', '<u>onlyloggedinuser</u> &nbsp '.__('(Note: To specify Whether the non-loggedin Users able to cast the vote or not.)','voting-contest'));
						echo html('li', '<u>showtimer</u> &nbsp '.__('(Note: By default, the Start or End timer is not shown. Added showtimer=1 to display the Start or End timer.)','voting-contest'));
						echo html('li', '<u>showform</u> &nbsp '.__('(Note: By default, the entry form is displayed based upon your start timer settings. If you want to hide the form add showform=0 to your shortcode.)','voting-contest'));
						echo html('li', '<u>view</u> &nbsp '.__('(Note: By default, the grid and list view changer is shown. By adding view=grid or view=list you can specify the default view which removes the view changer.)','voting-contest'));
						?>
					</ul>
				</li>
					
				<li ><b><?php _e('Multiple Contestants In Single Page','voting-contest')?></b><br/><p><i><?php _e('Create a page and use showcontestant shortcode multiple times. It will list multiple contestants in a single page.','voting-contest'); ?></i></p>
					<ul>
						<?php
						echo html('li', '<u>Example</u> &nbsp (Note: [showcontestants id=3 showform=1][showcontestants id=2 showform=1] etc)');
						echo html('li', '<u>Use</u> &nbsp '.__('(Note: you can use the attributes available for show contestant shortcode.)','voting-contest'));
						?>
					</ul>
				</li>
					
					
				<li><b>upcomingcontestants</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					 <ul>
						<?php
						echo html('li', '<u>id</u> &nbsp '.__('(Note: To specify the Category of the post for which the Start timer get displayed.)','voting-contest'));
						echo html('li', '<u>showcontestants</u> &nbsp '.__('(Note: To specify Whether the Contestants get displayed or not.)','voting-contest'));
						?>
					</ul>
				</li>
				<li><b>endcontestants</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					 <ul>
						<?php
						echo html('li', '<u>id</u> &nbsp '.__('(Note: To specify the Category of the post for which the End timer get displayed.)','voting-contest'));
						?>
					</ul>
				</li>
				<li><b>addcontestants</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					 <ul>
						<?php
						echo html('li', '<u>id</u> &nbsp '.__('(Note: Specify the Contest Category which the contestants are added to.)','voting-contest'));
						echo html('li', '<u>showcontestants</u> &nbsp '.__('(Note: Adding the shortcode attribute showcontestants=1 or showcontestants=0 will show or hide the contestants. Very useful when you just want to display the entry form.)','voting-contest'));
						echo html('li', '<u>displayform</u> &nbsp '.__('(Note: Adding the shortcode attribute diplayform=1 or diplayform=0 will override your default form settings that are based off the start time. )','voting-contest'));
						echo html('li', '<u>showrules</u> &nbsp '.__('(Note: Adding the shortcode attribute showrules=1 or showrules=0 will show or hide the Rules and Prizes link. Very useful when you just want to display the entry form.)','voting-contest'));
						?>
					</ul>
				</li>
				<li><b>profilescreen</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					 <ul>
						<?php
						echo html('li', '<u>contests</u> &nbsp '.__('(Note: To specify Whether the Contestants get displayed or not.)','voting-contest'));
						echo html('li', '<u>postperpage</u> &nbsp '.__('(Note: To specify the Number of Contestants displayed per page in the Contest Listing)','voting-contest'));
						echo html('li', '<u>form</u> &nbsp '.__('(Note: To specify Whether the Profile Edit form get displayed or not.)','voting-contest'));
						?>
					</ul>
				</li>
				
				<li ><b>showallcontestants</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					<ul>
						<?php
						echo html('li', '<u>orderby</u> &nbsp'.__('(Note: Specify how your contestants are ordered by, by adding orderby=votes to your shortcode. Options are listed <a href="http://plugins.ohiowebtech.com/documentation/shortcode-usage/" target="_blank">HERE</a> )','voting-contest'));
						echo html('li', '<u>order</u> &nbsp '.__('(Note: Specify the Order the Contestants are listed in by adding order=ASC or order=DESC to your shortcode.)','voting-contest'));
						echo html('li', '<u>postperpage</u> &nbsp '.__('(Note: Specify the Number of Contestants to display per page in the Contest Listing by adding postperpage=10 to your shortcode. Change # to desired amount.)','voting-contest'));
						?>
					</ul>
				</li>
				<li><b>addcontest</b><p><i><?php _e('Attributes that can be passed to this shortcode are as follows:','voting-contest'); ?></i></p>
					 <ul>
						<?php
						echo html('li', __('(No Attributes available for this shortcode.)','voting-contest'));
						?>
					</ul>
				</li>
				
			</ul>
		</div>
    </div>