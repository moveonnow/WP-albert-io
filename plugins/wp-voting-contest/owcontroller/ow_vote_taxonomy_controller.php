<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Taxonomy_Controller')){
    class Ow_Vote_Taxonomy_Controller{
	
	public function __construct(){
	    add_filter("manage_edit-". OW_VOTES_TAXONOMY ."_columns", array($this,'ow_add_new_votestax_column'));
	    add_action('manage_' . OW_VOTES_TAXONOMY . '_custom_column', array($this,'ow_custom_new_votestax_column'), 10, 3);
	    
	    //Taxonomy Custom fields
	    add_action(OW_VOTES_TAXONOMY . '_add_form_fields', array($this,'ow_taxonomy_voting_fields'));
	    add_action(OW_VOTES_TAXONOMY . '_edit_form', array($this,'ow_taxonomy_voting_fields'));
	    
	    //Save values of taxonomy
	    add_action('created_term',  array($this,'ow_votes_taxonomy_custom_save'));
	    add_action('edit_term', array($this,'ow_votes_taxonomy_custom_save'));
	    add_action('delete_term',array($this,'ow_votes_taxonomy_custom_delete'));
	}
	
	public function ow_add_new_votestax_column(){
	    wp_register_style('OW_ADMIN_STYLES', OW_ASSETS_ADMIN_CSS_PATH);
	    wp_enqueue_style('OW_ADMIN_STYLES');
	    
	    $add_columns['cb'] = '<input type="checkbox" />';
	    $add_columns['id'] = __('ID', 'voting-contest');
		$add_columns['shortcode'] = __('Shortcode', 'voting-contest');	
	    $add_columns['starttime'] = __('Start Time', 'voting-contest');
	    $add_columns['expiry'] = __('End Time', 'voting-contest');
	    $add_columns['name'] = __('Name', 'voting-contest');
	    $add_columns['description'] = __('Description', 'voting-contest');
	    $add_columns['slug'] = __('Slug', 'voting-contest');
	    $add_columns['posts'] = __('Voting Contest', 'voting-contest');
	    return $add_columns;
	}
	
	public function ow_custom_new_votestax_column($out, $column_name, $theme_id){
	    $theme = get_term($theme_id, 'votes');
	    switch ($column_name) {
		case 'id':				
			$out .= $theme_id;
		break;
		case 'shortcode':				
			$out .= "[showcontestants id=".$theme_id."]";
		break;
		case 'expiry':
			$expoption  = get_option($theme_id . '_' . OW_VOTES_TAXEXPIRATIONFIELD);		
			if(isset($expoption) && $expoption != '0' && $expoption){
				$votes_expiration = date('m-d-Y H:i:s', strtotime(str_replace('-', '/', $expoption )));
			}else{
				$votes_expiration = 'No Expiration';
			}
			$out .= $votes_expiration;
		break;
		case 'starttime':
			$startoption  = get_option($theme_id . '_' . OW_VOTES_TAXSTARTTIME);		
			if(isset($startoption) && $startoption != '0' && $startoption){
				$starttime = date('m-d-Y H:i:s', strtotime(str_replace('-', '/', $startoption )));
			}else{
				$starttime = 'Not Set';
			}
			$out .= $starttime;
		break;
		default:
		break;
	    }
	    return $out; 
	}
	
	public function ow_taxonomy_voting_fields(){
	    require_once(OW_VIEW_PATH.'ow_taxonomy_view.php');
	    
	     
	    $votes_expiration = $tax_activationcount = $votes_starttime =  ''; 
	    $option = array('imgdisplay' => NULL,
			    'termdisplay' => NULL,
			    'middle_custom_navigation'=>NULL,
			    'votecount'=>NULL,
			    'show_description'=>OW_VOTES_SHOW_DESC,
			    'vote_count_per_contest'=>1
			    );
	    
	    //Edit form values 
	    if(isset($_REQUEST['tag_ID'])) {
		$curterm = $_REQUEST['tag_ID'];
		$option = get_option($curterm . '_' . OW_VOTES_SETTINGS);
		$expoption  = get_option($curterm . '_' . OW_VOTES_TAXEXPIRATIONFIELD);
		$tax_activationcount = get_option($curterm . '_' . OW_VOTES_TAXACTIVATIONLIMIT);
		$votes_starttime = get_option($curterm . '_' . OW_VOTES_TAXSTARTTIME);
		if(isset($votes_starttime) && $votes_starttime != '0' && $votes_starttime){
		    $votes_starttime = date('m-d-Y H:i', strtotime(str_replace('-', '/', $votes_starttime )));
		}
	    }
	    if(isset($expoption) && $expoption != '0' && $expoption){
			$votes_expiration = date('m-d-Y H:i', strtotime(str_replace('-', '/', $expoption )));
	    }
	    
	    $values = array('expiration'=>$votes_expiration,'taxonomy_active_count'=>$tax_activationcount,
			    'start_time'=>$votes_starttime,'options'=>$option
			);
	    
	    ow_taxonomy_vote_view($values);
	}
	
	public function ow_votes_taxonomy_custom_save($ID){
	    
	    if(isset($_POST['votes_category_settings'])){
			$curterm = $ID;
			$musicfileenable = isset($_POST['musicfileenable']) ? $_POST['musicfileenable'] : NULL;
			$imgenable = isset($_POST['imgenable']) ? $_POST['imgenable'] : NULL;
			$imgrequired = isset($_POST['imgrequired']) ? $_POST['imgrequired'] : NULL;
			$imgdisplay = isset($_POST['imgdisplay']) ? $_POST['imgdisplay'] : NULL;
			$termdisplay = isset($_POST['termdisplay']) ? $_POST['termdisplay'] : NULL;
			$total_vote_count = isset($_POST['total_vote_count']) ? $_POST['total_vote_count'] : NULL;
			$imgcontest = isset($_POST['imgcontest'])?$_POST['imgcontest']:NULL;
			$middle_custom_navigation = isset($_POST['middle_custom_navigation'])?$_POST['middle_custom_navigation']:'';
			$votecount = isset($_POST['votecount']) ? $_POST['votecount'] : NULL;
			$hide_grid_list = isset($_POST['list_grid_hide']) ? $_POST['list_grid_hide'] : '';
			$show_description = isset($_POST['show_description'])?$_POST['show_description']:OW_VOTES_SHOW_DESC;
			$vote_contest_entry_person = isset($_POST['vote_contest_entry_person'])?$_POST['vote_contest_entry_person']:OW_VOTES_ENTRY_LIMIT_FORM;
			$vote_count_per_cat = isset($_POST['vote_count_per_cat'])?$_POST['vote_count_per_cat']:1;
			$contest_rules = isset($_POST['contest-rules'])?htmlentities(stripslashes($_POST['contest-rules'])):'';
			$top_ten_count =  isset($_POST['top_ten_count']) ? $_POST['top_ten_count'] : NULL;
			$authordisplay = isset($_POST['authordisplay']) ? $_POST['authordisplay'] : NULL;
			$authornamedisplay = isset($_POST['authornamedisplay']) ? $_POST['authornamedisplay'] : NULL;
			$tax_hide_photos_live =  isset($_POST['tax_hide_photos_live']) ? $_POST['tax_hide_photos_live'] : NULL;
	
			$args = array(
				'musicfileenable'=>$musicfileenable,
				'imgenable'=>$imgenable,
				'imgrequired'=>$imgrequired,
				'imgdisplay' => $imgdisplay,
				'termdisplay' => $termdisplay,
				'total_vote_count' => $total_vote_count,
				'top_ten_count'=>$top_ten_count,
				'authordisplay'=>$authordisplay,
				'authornamedisplay'=>$authornamedisplay,
				'imgcontest' => $imgcontest,
				'middle_custom_navigation'=>$middle_custom_navigation,
				'votecount'=>$votecount,
				'list_grid_hide'=>$hide_grid_list,
				'show_description'=>$show_description,
				'vote_contest_entry_person' => $vote_contest_entry_person,
				'vote_count_per_contest' => $vote_count_per_cat,
				'vote_contest_rules'=>$contest_rules,
				'tax_hide_photos_live' => $tax_hide_photos_live
			);
		
			update_option($curterm . '_' . OW_VOTES_SETTINGS, $args);
			$votes_expiration = $votes_starttime  = NULL;
			if(isset($_POST['votes_expiration']) && $_POST['votes_expiration'] != '0' && trim($_POST['votes_expiration'])){
				$votes_expiration = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['votes_expiration'] ))); 
			}
			if(isset($_POST['votes_starttime']) && $_POST['votes_starttime'] != '0' && trim($_POST['votes_starttime'])){
				$votes_starttime = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['votes_starttime'] )));
			}
			$tax_activationcount = isset($_POST['tax_activationcount']) ? $_POST['tax_activationcount'] : NULL;
			update_option($curterm . '_' . OW_VOTES_TAXEXPIRATIONFIELD, $votes_expiration);
			update_option($curterm . '_' . OW_VOTES_TAXACTIVATIONLIMIT, $tax_activationcount);
			update_option($curterm . '_' . OW_VOTES_TAXSTARTTIME, $votes_starttime);			
	    }
	}
	
	public function ow_votes_taxonomy_custom_delete()
	{
	    if(isset($_REQUEST['tag_ID'])) {
		    $curterm = $_REQUEST['tag_ID'];
		    if(get_option($curterm . '_' . OW_VOTES_SETTINGS)){
			    delete_option($curterm . '_' . OW_VOTES_SETTINGS);
		    }
		    delete_option($curterm . '_' . OW_VOTES_TAXEXPIRATIONFIELD);
		    delete_option($curterm . '_' . OW_VOTES_TAXACTIVATIONLIMIT);
		    delete_option($curterm . '_' . OW_VOTES_TAXSTARTTIME);
	    }
	}
	
    }
}else
die("<h2>".__('Failed to load Voting Taxonomy Controller','voting-contest')."</h2>");

return new Ow_Vote_Taxonomy_Controller();
