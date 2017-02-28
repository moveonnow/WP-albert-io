<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Single_Contestants')){
	class Ow_Vote_Single_Contestants{
		
		public function __construct(){
			add_filter('query_vars', array($this,'ow_votes_add_my_query_var'));
			add_action('parse_query',array($this,'ow_votes_parse_query_function'));
			
			add_action( 'voting_display_widget', array($this,'ow_voting_display_widget_contests'), 10, 1 );
			add_action( 'voting_display_recent_widget', array($this,'ow_voting_display_recent_contests'), 10, 1 );
			
			add_action('admin_bar_menu', array($this,'ow_voting_custom_toolbar_link'), 999);
			
		}
		
		public function ow_voting_custom_toolbar_link($wp_admin_bar){
			
			//In Front End for Edit Contestant
			if(is_singular(OW_VOTES_TYPE)){
				$args = array(
					'id' => 'edit_contestant',
					'title' => 'Edit Contestant', 
					'href' =>  get_edit_post_link() , 
					'meta' => array(
						'class' => 'edit_contestant', 
						'title' => __('Edit Contestant','voting-contest')
						)
				);
				$wp_admin_bar->add_node($args);
			}			
		}
		
		public function ow_votes_add_my_query_var($vars){
			$vars[] = 'contestants';
			$vars[] = 'ow_cont';
			$vars[] = 'ow_sort';
			$vars[] = 'ow_search';
			return $vars;
		}
		
		public function ow_votes_parse_query_function(){
			global $wp_query;
			if(isset($wp_query->query_vars['contestants'])){
				if($wp_query->query_vars['contestants']!=''){
					$_SESSION['votingoption'] = get_option(OW_VOTES_SETTINGS);
					add_filter('the_content', array($this,'ow_votes_contestant_content_update')); 
					add_filter('single_template', array($this,'ow_vote_contestant_body_content_class'));    
				} 
			}  
		}
		
		
		
		public function ow_votes_contestant_content_update(){
			$desc_rs = Ow_Vote_Shortcode_Model::ow_voting_get_contestant_desc();
			if($desc_rs[0]->admin_view == "Y"){        
				$post_id = get_the_ID();
				$post_content = get_post($post_id);
				$vote_content ='<div class="vote_content"> 
						 '.wpautop($post_content->post_content).'
						</div>';			  
				return $vote_content;
			}
		}
		
		public function ow_vote_contestant_body_content_class($single){
			$option = get_option(OW_VOTES_SETTINGS);
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_single_contestant_view.php');
			ob_start();
			ow_voting_single_contestant_view($option);
			return ob_get_clean();
		}
		
	
		public static function vote_previous_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '') {
			Ow_Vote_Single_Contestants::ow_votes_post_next_previous_link($format, $link, $in_same_cat, $excluded_categories, true);
		}
  
  
		public static function votes_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = ''){
			Ow_Vote_Single_Contestants::ow_votes_post_next_previous_link($format, $link, $in_same_cat, $excluded_categories, false);
		}


		public static function ow_votes_post_next_previous_link($format, $link, $in_same_cat, $excluded_categories = '', $previous){
			if ( $previous && is_attachment() ){
				$post = get_post( get_post()->post_parent );
			}else{
				$post = Ow_Vote_Single_Contestants::ow_votes_get_adjacent_post( $in_same_cat, $excluded_categories, $previous );
			}     
			if ( ! $post ) {
				$output = '';
			} else {
				$title = $post->post_title;
	
				if ( empty( $post->post_title ) )
					$title = $previous ? __( 'Previous Post','voting-contest' ) : __( 'Next Post','voting-contest' );
					
				$title = apply_filters( 'the_title', $title, $post->ID );
				$date = mysql2date( get_option( 'date_format' ), $post->post_date );
				$rel = $previous ? 'prev' : 'next';
				$rel_lr = $previous ? 'left' : 'right';
				$string = '<a href="' . get_permalink( $post ) . '" rel="'.$rel.'">';
				$inlink = str_replace( '%title', $title, $link );
				$inlink = str_replace( '%date', $date, $inlink );        
				$inlink = $string .'<span class="ow_vote_icons votecontestant-chevron-'.$rel_lr.' votecontestant-next-prev"></span></a>';
		   
				$output = str_replace( '%link', $inlink, $format );
			}
			$adjacent = $previous ? 'previous' : 'next';
			echo apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post );
		}
		
		public static function ow_votes_get_adjacent_post( $in_same_cat, $excluded_categories, $previous) {
			$result = Ow_Vote_Shortcode_Model::ow_votes_get_adjacent_post_model($in_same_cat, $excluded_categories, $previous );
			if ( null === $result )
				$result = '';
			if(isset($query_key))
			wp_cache_set($query_key, $result, 'counts');
    
			if ( $result )
				$result = get_post( $result );
			return $result;
		}
		
		public function ow_voting_display_widget_contests($param){
			$args = array(
				'post_type'   => OW_VOTES_TYPE,
				'post_status' => 'publish',            
				'meta_key'    => OW_VOTES_CUSTOMFIELD,
				'orderby'     => 'meta_value_num',
				'order' 	  => 'DESC',
				'posts_per_page'=> $param['no_of_conts'],
				'tax_query' => array(
								array(
								'taxonomy' => OW_VOTES_TAXONOMY,
								'field'    => 'term_id',
								'terms'    => $param['contest_tax'],
								),
							),
				);
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_widget_view.php');
			
			ow_voting_display_widget_view($args,$param);
			return;
		}
		
		public function ow_voting_display_recent_contests($param){
			$args = array(
				'post_type'   => OW_VOTES_TYPE,
				'post_status' => 'publish',            
				'meta_key'    => OW_VOTES_CUSTOMFIELD,
				'orderby'     => 'ID',
				'order' 	  => 'DESC',
				'posts_per_page'=> $param['no_of_conts'],
			);
			
			require_once(OW_VIEW_FRONT_PATH.'ow_voting_widget_view.php');
			ow_voting_display_recent_view($args,$param);
			return;
		}
		
	}
}else
die("<h2>".__('Failed to load the Voting Single Contestant Controller','voting-contest')."</h2>");

return new Ow_Vote_Single_Contestants();
