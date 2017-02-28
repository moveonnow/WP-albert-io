<?php
if(!class_exists('Ow_Installation_Model')){
	class Ow_Installation_Model {
	    static function create_tables_owvoting(){
		global $wpdb;
		/************* Create Tables if table not exists ****************/
		$vote_tbl_sql = 'CREATE TABLE IF NOT EXISTS ' . OW_VOTES_TBL . '(
							id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							ip VARCHAR( 255 ) NOT NULL,
							votes INT NOT NULL DEFAULT 0,
							post_id INT NOT NULL,
							termid VARCHAR( 255 ) NOT NULL DEFAULT "0",
							ip_always VARCHAR( 255 ) NOT NULL DEFAULT "0",
							email_always VARCHAR( 255 ) NOT NULL DEFAULT "0",							
							date DATETIME
						)';
		$contestant_custom_table = "CREATE TABLE IF NOT EXISTS ".OW_VOTES_ENTRY_CUSTOM_TABLE." (
									`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`sequence` int(11) NOT NULL DEFAULT '0',
									`question_type` enum('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN') NOT NULL DEFAULT 'TEXT',
									`question` text NOT NULL,`system_name` varchar(45) DEFAULT NULL,`response` text,
									`required` enum('Y','N') NOT NULL DEFAULT 'N',`required_text` text,
									`show_labels` enum('Y','N') NOT NULL DEFAULT 'N',
									`admin_only` enum('Y','N') NOT NULL DEFAULT 'N',`grid_only` enum('Y','N') NOT NULL DEFAULT 'N',`list_only` enum('Y','N') NOT NULL DEFAULT 'N',`delete_time` varchar(45) DEFAULT 0,
									`wp_user` int(22) DEFAULT '1', `admin_view` VARCHAR(5) NOT NULL DEFAULT 'N',`pretty_view` enum('Y','N') NOT NULL DEFAULT 'N', PRIMARY KEY (`id`),
									KEY `wp_user` (`wp_user`),KEY `system_name` (`system_name`),KEY `admin_only` (`admin_only`)
								)ENGINE=InnoDB"; 
		$contestant_custom_val = "CREATE TABLE IF NOT EXISTS ".OW_VOTES_POST_ENTRY_TABLE." (
									`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
									`post_id_map` int(11) NOT NULL,
									`field_values` longtext NOT NULL,
									 PRIMARY KEY (`id`)
								)ENGINE=InnoDB";
		$contestant_register_custom_table = "CREATE TABLE IF NOT EXISTS ".OW_VOTES_USER_CUSTOM_TABLE." (
											`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
											`sequence` int(11) NOT NULL DEFAULT '0',
											`question_type` enum('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN') NOT NULL DEFAULT 'TEXT',
											`question` text NOT NULL,
											`system_name` varchar(45) DEFAULT NULL,
											`response` text,
											`required` enum('Y','N') NOT NULL DEFAULT 'N',
											`required_text` text,
											`admin_only` enum('Y','N') NOT NULL DEFAULT 'N',
											`delete_time` varchar(45) DEFAULT 0,
											`wp_user` int(22) DEFAULT '1',PRIMARY KEY (`id`),
											 KEY `wp_user` (`wp_user`),KEY `system_name` (`system_name`),KEY `admin_only` (`admin_only`)
											)ENGINE=InnoDB";
		$contestant_register_custom_val = "CREATE TABLE IF NOT EXISTS ".OW_VOTES_USER_ENTRY_TABLE." (
										`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
										`user_id_map` int(11) NOT NULL,
										`field_values` longtext NOT NULL,
										 PRIMARY KEY (`id`)
										)ENGINE=InnoDB";
										
		$post_track = "CREATE TABLE IF NOT EXISTS ".OW_VOTES_POST_ENTRY_TRACK." (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`user_id_map` int(11) NOT NULL,
		`ip` VARCHAR( 255 ) NOT NULL,
		`count_post` INT NOT NULL,
		`ow_term_id` int(11) NOT NULL,		
		PRIMARY KEY (`id`)
		)ENGINE=InnoDB";
				
		
		$wpdb->query($vote_tbl_sql);
		$wpdb->query($contestant_custom_table);
		$wpdb->query($contestant_custom_val);
		$wpdb->query($contestant_register_custom_table);
		$wpdb->query($contestant_register_custom_val);
		$wpdb->query($post_track);
		
		$result = mysqli_query("SHOW COLUMNS FROM `".OW_VOTES_POST_ENTRY_TRACK."` LIKE 'ow_term_id'");
		$exists = (mysqli_num_rows($result))?TRUE:FALSE;
		if(!$exists) {
		   $post_track_alter = "ALTER TABLE ".OW_VOTES_POST_ENTRY_TRACK." ADD `ow_term_id` int(11) NOT NULL default '0'";
		   $wpdb->query($post_track_alter);
		}
						
		$result = mysqli_query("SHOW COLUMNS FROM `".OW_VOTES_TBL."` LIKE 'ip_always'");
		$exists = (mysqli_num_rows($result))?TRUE:FALSE;
		if(!$exists) {
		   $post_track_alter = "ALTER TABLE ".OW_VOTES_TBL." ADD `ip_always` varchar(255) NOT NULL default '0'";
		   $wpdb->query($post_track_alter);
		}
		
		$result = mysqli_query("SHOW COLUMNS FROM `".OW_VOTES_TBL."` LIKE 'email_always'");
		$exists = (mysqli_num_rows($result))?TRUE:FALSE;
		if(!$exists) {
		   $post_track_alter = "ALTER TABLE ".OW_VOTES_TBL." ADD `email_always` varchar(255) NOT NULL default '0'";
		   $wpdb->query($post_track_alter);
		}
		
		//Add ow_file_size coumn in votes_custom_field_contestant table
		$result = mysqli_query("SHOW COLUMNS FROM `".OW_VOTES_ENTRY_CUSTOM_TABLE."` LIKE 'ow_file_size'");
		$exists = (mysqli_num_rows($result))?TRUE:FALSE;
		if(!$exists) {
		   $post_track_alter = "ALTER TABLE ".OW_VOTES_ENTRY_CUSTOM_TABLE." ADD `ow_file_size` int(11) NOT NULL default '0'";
		   $wpdb->query($post_track_alter);
		}
						
		$result1 = mysqli_query("SHOW COLUMNS FROM ".OW_VOTES_ENTRY_CUSTOM_TABLE." LIKE 'grid_only'");
		$exists1 = (mysqli_num_rows($result1))?TRUE:FALSE;
		if(!$exists1) {
		  $grid_only_alter = "ALTER TABLE ".OW_VOTES_ENTRY_CUSTOM_TABLE." ADD `grid_only` enum('Y','N') NOT NULL DEFAULT 'N'";
		  $wpdb->query($grid_only_alter);
		}
		
		$result2 = mysqli_query("SHOW COLUMNS FROM ".OW_VOTES_ENTRY_CUSTOM_TABLE." LIKE 'list_only'");
		$exists2 = (mysqli_num_rows($result2))?TRUE:FALSE;
		if(!$exists2) {
		   $list_only_alter = "ALTER TABLE ".OW_VOTES_ENTRY_CUSTOM_TABLE." ADD `list_only` enum('Y','N') NOT NULL DEFAULT 'N'";
		   $wpdb->query($list_only_alter);
		}
		
		$result1 = mysqli_query("SHOW COLUMNS FROM ".OW_VOTES_ENTRY_CUSTOM_TABLE." LIKE 'show_labels'");
		$exists1 = (mysqli_num_rows($result1))?TRUE:FALSE;
		if(!$exists1) {
		  $grid_only_alter = "ALTER TABLE ".OW_VOTES_ENTRY_CUSTOM_TABLE." ADD `show_labels` enum('Y','N') NOT NULL DEFAULT 'N'";
		  $wpdb->query($grid_only_alter);
		}
			
		//File Type Length Add in ENUM
		$question_type = "ALTER TABLE ".OW_VOTES_ENTRY_CUSTOM_TABLE." CHANGE `question_type` `question_type`
				  ENUM('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN','FILE','DATE') CHARACTER SET utf8 COLLATE
				  utf8_general_ci NOT NULL DEFAULT 'TEXT'";
		$wpdb->query($question_type);
		
		//File Type Length Add in ENUM
		$question_type = "ALTER TABLE ".OW_VOTES_USER_CUSTOM_TABLE." CHANGE `question_type` `question_type`
				  ENUM('TEXT','TEXTAREA','MULTIPLE','SINGLE','DROPDOWN','FILE','DATE') CHARACTER SET utf8 COLLATE
				  utf8_general_ci NOT NULL DEFAULT 'TEXT'";
		$wpdb->query($question_type);
		
		
		/****** Check for the description and title field already there *******/
		$field_desc_check = Ow_Installation_Model::ow_voting_get_contestant_desc();
		$field_title_check = Ow_Installation_Model::ow_voting_get_title_desc();
		$field_video_check = Ow_Installation_Model::ow_voting_get_ow_video_url();
		if(count($field_desc_check[0]) == 0){
			//Add the Custom Field in the Table VOTES_ENTRY_CUSTOM_TABLE
			$wpdb->insert( 
				OW_VOTES_ENTRY_CUSTOM_TABLE, 
				array( 
					'question_type' => 'TEXTAREA', 
					'question'      => 'Description',
					'system_name' => 'contestant-desc',
					'required'    => 'Y',
					'admin_only'  => 'Y', 
					'admin_view'  => 'Y',  
				), 
				array( 
					'%s','%s','%s','%s' ,'%s','%s'
				) 
			);
		}
		
		if(count($field_title_check[0]) == 0){
			//Add the Custom Field in the Table VOTES_ENTRY_CUSTOM_TABLE
			$wpdb->insert( 
				OW_VOTES_ENTRY_CUSTOM_TABLE, 
				array(
					'sequence'	=> 0,
					'question_type' => 'TEXT', 
					'question'      => 'Title',
					'system_name' => 'contestant-title',
					'required'    => 'Y',
					'admin_only'  => 'Y', 
					'admin_view'  => 'Y',  
				), 
				array( 
					'%s','%s','%s','%s' ,'%s','%s'
				) 
			);
		}
		
		if(count($field_video_check[0]) == 0){
			//Add the Custom Field in the Table VOTES_ENTRY_CUSTOM_TABLE
			$wpdb->insert( 
				OW_VOTES_ENTRY_CUSTOM_TABLE, 
				array(
					'sequence'	=> 0,
					'question_type' => 'TEXT', 
					'question'      => 'Music/Video URL',
					'system_name' => 'contestant-ow_video_url',
					'required'    => 'Y',
					'admin_only'  => 'Y', 
					'admin_view'  => 'Y',  
				), 
				array( 
					'%s','%s','%s','%s' ,'%s','%s'
				) 
			);
		}	
		
	}
		
	    static function ow_voting_get_contestant_desc(){
		    global $wpdb;            
		    $sql     = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE system_name = 'contestant-desc'";
		    $desc_rs = $wpdb->get_results($sql);    
		    return $desc_rs;
	    }
	    
	    static function ow_voting_get_title_desc(){
		    global $wpdb;            
		    $sql     = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE system_name = 'contestant-title'";
		    $desc_rs = $wpdb->get_results($sql);    
		    return $desc_rs;
	    }
	    
	    static function ow_voting_get_ow_video_url(){
		    global $wpdb;            
		    $sql     = "SELECT * FROM " . OW_VOTES_ENTRY_CUSTOM_TABLE . " WHERE system_name = 'contestant-ow_video_url'";
		    $desc_rs = $wpdb->get_results($sql);    
		    return $desc_rs;
	    }
			    
	    static function ow_voting_delete_tables(){
		    global $wpdb;
		    //Delete all tables on deactivation 
		    $vote_table = 'DROP TABLE IF EXISTS ' . OW_VOTES_TBL;
		    $wpdb->query($vote_table);
		    
		    $contestant_cutom_tbl = 'DROP TABLE IF EXISTS ' . OW_VOTES_ENTRY_CUSTOM_TABLE;
		    $wpdb->query($contestant_cutom_tbl);
		    
		    $contest_val_tbl = 'DROP TABLE IF EXISTS ' . OW_VOTES_POST_ENTRY_TABLE;
		    $wpdb->query($contest_val_tbl);
		    
		    $contest_reg_tbl = 'DROP TABLE IF EXISTS ' . OW_VOTES_USER_CUSTOM_TABLE;
		    $wpdb->query($contest_reg_tbl);
		    
		    $contest_reg_val_tbl = 'DROP TABLE IF EXISTS ' . OW_VOTES_USER_ENTRY_TABLE;
		    $wpdb->query($contest_reg_val_tbl);
	    }
	}
}else
die("<h2>".__('Failed to load Voting installation model')."</h2>");
?>
