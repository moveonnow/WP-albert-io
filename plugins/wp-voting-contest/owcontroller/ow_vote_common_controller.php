<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if(!class_exists('Ow_Vote_Common_Controller')){
    class Ow_Vote_Common_Controller{
	
		public function __construct(){
			add_action('plugins_loaded',array($this,'ow_vote_export_contestants'));
			add_action('plugins_loaded',array($this,'ow_voting_log_contestants'));
			add_action('before_delete_post',array(&$this,'ow_voting_delete_post_entry_track'),1);						
		}
		
		
		
		public function ow_voting_log_contestants(){
			global $pagenow,$wpdb;
			if ($pagenow=='admin.php' && isset($_GET['page']) && $_GET['page']=='votinglogs' && $_POST['export_contestant'] == 1) {
				$get_type = $_POST['export'];
				if($get_type!=''){
					require(OW_CONTROLLER_XL_PATH.'PHPExcel.php');
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					
					$rowCount = 1; 
					$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'Contestant Title');
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,'Author');
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,'Voter');
					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'Voter Email');
					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,'Voter IP');
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,'Vote Date');
					
					$log_entries['orderby'] = ($_GET['orderby'] == null)?'log.date':$_GET['orderby'];   
					$log_entries['order'] = ($_GET['order'] == null)?'desc':$_GET['order'];
					$log_entries['rpp'] = 'all';
					$logvoteentries = Ow_Contestant_Model::ow_voting_log_entries($log_entries); 
					if(!empty($logvoteentries)){
						$string_colmn='A';
						foreach($logvoteentries as $logs){
							$rowCount++;
							
							$vote_author_id= $logs->post_author;							
							$vote_author   = ucfirst(get_the_author_meta( 'display_name', $vote_author_id ));
							$voter_name    = $logs->ip;
							$ip_always     = ($logs->ip_always == 0 || $logs->ip_always == null)?" - ":$logs->ip_always;
							$email_always  = $logs->email_always;
							
							if(filter_var($voter_name, FILTER_VALIDATE_IP) !== false)
								$voter_name = $logs->ip;
							else if(count(explode('@',$voter_name)) == 2){							
								$voter_name = $logs->ip;
							}
							else if(count(explode('@',$voter_name)) > 1){
								$browser = explode('@',$voter_name);
								$voter_name = $browser_array[$browser[0]];
							}						
							else{
								$voter_name = ucfirst(get_the_author_meta( 'display_name', $voter_name ));
								$voter_email_flag = 1;
							}
								
							if($voter_email_flag == 1){
								$voter_email = get_the_author_meta( 'user_email', $logs->ip );
								if($voter_email == null){
								    $voter_email = $voter_name;
								}
							}
							else{
								$voter_email = 0;
							}
							
							if($voter_email == 0){
								$voter_email = $email_always;
							}
							
							$voted_date    = $logs->date;
							
							$objPHPExcel->getActiveSheet()->SetCellValue($string_colmn.$rowCount, $logs->post_title);
							$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $vote_author);
							$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $voter_name);
							$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $voter_email);
							$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $ip_always);
							$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $voted_date);
							$string_colmn='A';
							
							set_time_limit(100);
							
						}
					}
							
					switch ($get_type) {
						
						case 'excel_xlsx':
							$filename = "Voting_logs_".date('d-m-Y-H-i-s').'.xlsx';
							// Redirect output to a client’s web browser (Excel2007)
							header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
							header('Content-Disposition: attachment;filename="'.$filename.'"');
							header('Cache-Control: max-age=0');
							// If you're serving to IE 9, then the following may be needed
							header('Cache-Control: max-age=1');
							
							
							set_time_limit ( 3000 );
							$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
							$cacheSettings = array( 'memoryCacheSize' => '512MB');
							PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
							
							// If you're serving to IE over SSL, then the following may be needed
							header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
							header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
							header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
							header ('Pragma: public'); // HTTP/1.0
							
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
							$objWriter->save('php://output');
						break;
					
						case 'excel_xls':
							$filename = "Voting_logs_".date('d-m-Y-H-i-s').'.xls';
							// Redirect output to a client’s web browser (Excel5)
							header('Content-Type: application/vnd.ms-excel');
							header('Content-Disposition: attachment;filename="'.$filename.'"');
							header('Cache-Control: max-age=0');
							// If you're serving to IE 9, then the following may be needed
							header('Cache-Control: max-age=1');
							
							set_time_limit ( 3000 );
							$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
							$cacheSettings = array( 'memoryCacheSize' => '512MB');
							PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
							
							
							// If you're serving to IE over SSL, then the following may be needed
							header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
							header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
							header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
							header ('Pragma: public'); // HTTP/1.0
							
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
							$objWriter->save('php://output');
						break;
						
						case 'excel_ods':
							$filename = "Voting_logs_".date('d-m-Y-H-i-s').'.ods';
							// Redirect output to a client’s web browser (OpenDocument)
							header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
							header('Content-Disposition: attachment;filename="'.$filename.'"');
							header('Cache-Control: max-age=0');
							// If you're serving to IE 9, then the following may be needed
							header('Cache-Control: max-age=1');
							
							set_time_limit ( 3000 );
							$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
							$cacheSettings = array( 'memoryCacheSize' => '512MB');
							PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
							
							// If you're serving to IE over SSL, then the following may be needed
							header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
							header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
							header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
							header ('Pragma: public'); // HTTP/1.0
							
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
							$objWriter->save('php://output');
						break;
						
						case 'CSV':
							$filename = "Voting_logs_".date('d-m-Y-H-i-s').'.csv';
							header('Content-Description: File Transfer');
							header('Content-Type: application/force-download');
							header('Content-Disposition: attachment; filename="'.$filename.'"');
							header('Cache-Control: max-age=0');
							// If you're serving to IE 9, then the following may be needed
							header('Cache-Control: max-age=1');
							
							set_time_limit ( 3000 );
							$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
							$cacheSettings = array( 'memoryCacheSize' => '512MB');
							PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
							
							// If you're serving to IE over SSL, then the following may be needed
							header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
							header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
							header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
							header ('Pragma: public'); // HTTP/1.0
							
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
							$objWriter->save('php://output');
						break;
					
						case 'html':
							$filename = "Voting_logs_".date('d-m-Y-H-i-s').'.html';
							header('Content-Description: File Transfer');
							header('Content-Type: application/force-download');
							header('Content-Disposition: attachment; filename="'.$filename.'"');
							header('Cache-Control: max-age=0');
							// If you're serving to IE 9, then the following may be needed
							header('Cache-Control: max-age=1');
							
							set_time_limit ( 3000 );
							$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
							$cacheSettings = array( 'memoryCacheSize' => '512MB');
							PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
							
							// If you're serving to IE over SSL, then the following may be needed
							header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
							header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
							header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
							header ('Pragma: public'); // HTTP/1.0
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
							$objWriter->save('php://output');
						break;	
					}
					exit;
				}
			}
		}
		
		public function ow_vote_export_contestants()
		{
			global $pagenow,$wpdb;
			if ($pagenow=='admin.php' && isset($_GET['votes_export'])  && $_GET['votes_export']=='Export') {
				
				$term_id = $_GET['vote_contest_term'];
				$get_type = $_GET['export'];
				
				require(OW_CONTROLLER_XL_PATH.'PHPExcel.php');
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);
				
				$rowCount = 1; 
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'Contestant Title');
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,'Status');
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,'Contest Category');
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'Votes');
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,'Created Date');

				$string_coulmn='F';
				//For Exporting contestants
				$post_entries = Ow_Contestant_Model::ow_voting_export_contestants($term_id);
				$custom_fields = Ow_Contestant_Model::ow_voting_get_all_custom_fields();
		
				if(!empty($custom_fields)){
					foreach($custom_fields as $custom_field){
						$objPHPExcel->getActiveSheet()->SetCellValue($string_coulmn.$rowCount,$custom_field->question);
						$string_coulmn++;
					}
				}
				$objPHPExcel->getActiveSheet()->SetCellValue($string_coulmn.$rowCount,'Author Email');
				$objPHPExcel->getActiveSheet()->SetCellValue(++$string_coulmn.$rowCount,'Author Name');

				if(!empty($post_entries)){
					$string_colmn='A';
					foreach($post_entries as $pos_val){
						$rowCount++;
						$posted_date = date('Y-m-d',strtotime($pos_val->post_date));
						
						$user_author = Ow_Contestant_Model::ow_voting_get_author_contestant($pos_val);
						$category = Ow_Contestant_Model::ow_voting_get_contest_name($pos_val);
						$votes_count = Ow_Contestant_Model::ow_voting_get_contest_meta($pos_val);
						
						$post_title = $pos_val->post_title;
						$post_content = preg_replace('/[\n\r]+/',' ',trim($pos_val->post_content));
						$post_status = $pos_val->post_status;
						$cat_name = $category->name;
						$votes_count_val = $votes_count->meta_value;
						
						$objPHPExcel->getActiveSheet()->SetCellValue($string_colmn.$rowCount, $post_title);
						
						$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $post_status);
						$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $cat_name);
						$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $votes_count_val);
						$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $posted_date);
							
						if(base64_decode($pos_val->field_values, true))	      
						$custom_values = maybe_unserialize(base64_decode($pos_val->field_values));
						else
						$custom_values = maybe_unserialize($pos_val->field_values);
						
						if(!empty($custom_fields)){		  
							foreach ($custom_fields as $ques_val){
								if(is_array($custom_values[$ques_val->system_name])){							  
									if($ques_val->system_name!="contestant-desc"){
										$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, implode(' - ',$custom_values[$ques_val->system_name]).',');
									}
									else{
										$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, implode(' - ',$post_content).',');
									}
								 
								}else{
									if($ques_val->system_name!="contestant-desc"){
										$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount, $custom_values[$ques_val->system_name]);
									}
									else{
										$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount,$post_content);
									}
								}
							}
						}
						
						$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount,$user_author->user_email);
						$objPHPExcel->getActiveSheet()->SetCellValue(++$string_colmn.$rowCount,$user_author->display_name);
						$string_colmn='A';					
					}
				}
						
				switch ($get_type) {
					
					case 'excel_xlsx':
						$filename = "contest_".date('d-m-Y-H-i-s').'.xlsx';
						// Redirect output to a client’s web browser (Excel2007)
						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						header('Content-Disposition: attachment;filename="'.$filename.'"');
						header('Cache-Control: max-age=0');
						// If you're serving to IE 9, then the following may be needed
						header('Cache-Control: max-age=1');
						
						// If you're serving to IE over SSL, then the following may be needed
						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
						header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
						header ('Pragma: public'); // HTTP/1.0
						
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
						$objWriter->save('php://output');
					break;
				
					case 'excel_xls':
						$filename = "contest_".date('d-m-Y-H-i-s').'.xls';
						// Redirect output to a client’s web browser (Excel5)
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="'.$filename.'"');
						header('Cache-Control: max-age=0');
						// If you're serving to IE 9, then the following may be needed
						header('Cache-Control: max-age=1');
						
						// If you're serving to IE over SSL, then the following may be needed
						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
						header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
						header ('Pragma: public'); // HTTP/1.0
						
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
						$objWriter->save('php://output');
					break;
					
					case 'excel_ods':
						$filename = "contest_".date('d-m-Y-H-i-s').'.ods';
						// Redirect output to a client’s web browser (OpenDocument)
						header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
						header('Content-Disposition: attachment;filename="'.$filename.'"');
						header('Cache-Control: max-age=0');
						// If you're serving to IE 9, then the following may be needed
						header('Cache-Control: max-age=1');
						
						// If you're serving to IE over SSL, then the following may be needed
						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
						header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
						header ('Pragma: public'); // HTTP/1.0
						
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
						$objWriter->save('php://output');
					break;
					
					case 'CSV':
						$filename = "contest_".date('d-m-Y-H-i-s').'.csv';
						header('Content-Description: File Transfer');
						header('Content-Type: application/force-download');
						header('Content-Disposition: attachment; filename="'.$filename.'"');
						header('Cache-Control: max-age=0');
						// If you're serving to IE 9, then the following may be needed
						header('Cache-Control: max-age=1');
						
						// If you're serving to IE over SSL, then the following may be needed
						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
						header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
						header ('Pragma: public'); // HTTP/1.0
						
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
						$objWriter->save('php://output');
					break;
				
					case 'html':
						$filename = "contest_".date('d-m-Y-H-i-s').'.html';
						header('Content-Description: File Transfer');
						header('Content-Type: application/force-download');
						header('Content-Disposition: attachment; filename="'.$filename.'"');
						header('Cache-Control: max-age=0');
						// If you're serving to IE 9, then the following may be needed
						header('Cache-Control: max-age=1');
						
						// If you're serving to IE over SSL, then the following may be needed
						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
						header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
						header ('Pragma: public'); // HTTP/1.0
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
						$objWriter->save('php://output');
					break;	
				}
			
			}
		}
		//Resize the image for the desired size to shown on the contestant listing admin page
		public static function ow_voting_resize_thumb($attach_id = null, $img_url = null, $width, $height, $crop = false){
			
			if ( $attach_id ) {
				$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
				$file_path = get_attached_file( $attach_id );
			}else if ( $img_url ) { // this is not an attachment, let's use the image url
				$file_path = parse_url( $img_url );
				$file_path = ltrim( $file_path['path'], '/' );		
				$orig_size = @getimagesize(realpath( $file_path ));
				$image_src[0] = $img_url;
				$image_src[1] = $orig_size[0];
				$image_src[2] = $orig_size[1];
			}
			
			$check_file_exists = getimagesize(realpath($file_path));
			if(!is_array($check_file_exists)){
				$vt_image = array (
					'url' =>OW_ASSETS_IMAGE_PATH.'img_not_available.png',
					'width' => $width,
					'height' => $height
				);
							
				return $vt_image;
			}
			
			$file_info = pathinfo( $file_path );
			$extension = '.'. $file_info['extension'];
			
			// the image path without the extension
			$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
			$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
			
			// checking if the file size is larger than the target size
			// if it is smaller or the same size, stop right here and return
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
			// the file is larger, check if the resized version already exists (for crop = true but will also work for crop = false if the sizes match)
			if ( file_exists( $cropped_img_path ) ) {
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
				$vt_image = array (
					'url' => $cropped_img_url,
					'width' => $width,
					'height' => $height
				);
				return $vt_image;
			}
	
			// crop = false
			if ( $crop == false ) {
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
				$vt_image = array (
					'url' => $resized_img_url,
					'width' => $new_img_size[0],
					'height' => $new_img_size[1]
				);
				return $vt_image;
				}
			}
	
			// no cached files - let's finally resize it
			$new_img_path = wp_get_image_editor( $file_path, $width, $height, $crop );
			$new_img_size = is_wp_error($new_img_path) ? array('height'=>0, 'width'=>0) : $new_img_path->get_size();
			$new_img = str_replace( basename( $image_src[0] ), basename($file_path), $image_src[0] );
	
			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $width,
				'height' => $height
			);
		
			return $vt_image;
			}
			// default output - without resizing
			$vt_image = array (
				'url' => $image_src[0],
				'width' => $image_src[1],
				'height' => $image_src[2]
			);
		
			return $vt_image;
		}
	
		public static function ow_voting_create_or_set_featured_image($url, $post_id) {			
			$file_name = basename($url);
			$upload = wp_upload_bits($file_name, null, file_get_contents($url));
			$wp_filetype = wp_check_filetype(basename($url), null);
			$wp_upload_dir = wp_upload_dir();
			$attachment = array(
				'guid' => _wp_relative_upload_path($upload['url']),
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment($attachment, false, $post_id);
			update_post_meta($post_id, '_thumbnail_id', $attach_id);
			$wp_attached_file = substr($wp_upload_dir['subdir'], 1) . '/' . $file_name;
			update_post_meta($attach_id, '_wp_attached_file', $wp_attached_file);
			$image_150 = Ow_Vote_Common_Controller::ow_voting_csv_resize($attach_id, '', 150, 150, true);
			$image_300 = Ow_Vote_Common_Controller::ow_voting_csv_resize($attach_id, '', 300, 0);
			$ds_image_ico = Ow_Vote_Common_Controller::ow_voting_csv_resize($attach_id, '', 80, 80, true);
			$ds_image_medium = Ow_Vote_Common_Controller::ow_voting_csv_resize($attach_id, '', 800, 0);
			$file_path = get_attached_file($attach_id);
			$orig_size = getimagesize(realpath($file_path));
			
			$wp_attachment_array = array(
			'width' => $orig_size[0],
			'height' => $orig_size[1],
			'hwstring_small' => "height='96' width='96'",
			'file' => $wp_attached_file,
			'sizes' => Array
				(
				'thumbnail' => Array
				(
				'file' => basename($image_150['url']),
				'width' => $image_150['width'],
				'height' => $image_150['height']
				),
				'medium' => Array
				(
				'file' => basename($image_300['url']),
				'width' => $image_300['width'],
				'height' => $image_300['height']
				),
				'post-thumbnail' => Array
				(
				'file' => basename($image_300['url']),
				'width' => $image_300['width'],
				'height' => $image_300['height']
				)
			),
			'image_meta' => Array
				(
				'aperture' => 0,
				'credit' => '',
				'camera' => '',
				'caption' => '',
				'created_timestamp' => 0,
				'copyright' => '',
				'focal_length' => 0,
				'iso' => 0,
				'shutter_speed' => 0,
				'title' => ''
			)
			);
			update_post_meta($attach_id, '_wp_attachment_metadata', $wp_attachment_array);
		
			if ($attach_id) {
			return true;
			} else {
			return false;
			}
		}
	
	
		public static function ow_voting_csv_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
		
			// this is an attachment, so we have the ID
			if ( $attach_id ) {
				$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
				$file_path = get_attached_file( $attach_id );     
				// this is not an attachment, let's use the image url
			} else if ( $img_url ) {
				$file_path = parse_url( $img_url );
				$file_path = ltrim( $file_path['path'], '/' );    		
				$orig_size = @getimagesize( ABSPATH.$file_path );
				$image_src[0] = $img_url;
				$image_src[1] = $orig_size[0];
				$image_src[2] = $orig_size[1];
			}
			if($image_src[1]==0){   
				$orig_size = @getimagesize( realpath($file_path ));  
				$image_src[1] = $orig_size[0];
				$image_src[2] = $orig_size[1];  
			}
			
			if($width == 0){
				$width = $image_src[1];            
			}
			if($height == 0){
				$height = $image_src[2];
			}
			$file_info = pathinfo( $file_path );
			$extension = '.'. $file_info['extension'];
		
			// the image path without the extension
			$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
			$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
		
			// checking if the file size is larger than the target size
			// if it is smaller or the same size, stop right here and return
			if ( $image_src[1] > $width || $image_src[2] > $height ) {
		
				// the file is larger, check if the resized version already exists (for crop = true but will also work for crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					return $vt_image;
				}
		
				// crop = false
				if ( $crop == false ) {
					// calculate the size proportionaly
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
			
					// checking if the file already exists
					if ( file_exists( $resized_img_path ) ) {
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $new_img_size[0],
						'height' => $new_img_size[1]
					);
					return $vt_image;
					}
				}
		
				// no cached files - let's finally resize it
				$new_img_path = wp_get_image_editor( $file_path, $width, $height, $crop );
				$new_img_size = is_wp_error($new_img_path) ? array('height'=>0, 'width'=>0) : $new_img_path->get_size();
				$new_img = str_replace( basename( $image_src[0] ), basename($file_path), $image_src[0] );
		
				// resized output
				$vt_image = array (
					'url' => $new_img,
					'width' => $new_img_size['width'],
					'height' => $new_img_size['height']
				);
					
				return $vt_image;
			}
		
			// default output - without resizing
			$vt_image = array (
				'url' => $image_src[0],
				'width' => $image_src[1],
				'height' => $image_src[2]
			);
			return $vt_image;
		}
		
		public static function ow_votes_form_select_input($name, $values, $default = '', $parameters = '') {
			$field = '<select name="' . Ow_Vote_Common_Controller::ow_vote_dropdown_output_string($name) . '"';
				if (Ow_Vote_Common_Controller::ow_vote_dropdown_not_null($parameters))
					$field .= ' ' . $parameters;
			$field .= '>';
		
			if (empty($default) && isset($GLOBALS[$name]))
				$default = stripslashes($GLOBALS[$name]);
		
			for ($i = 0, $n = sizeof($values); $i < $n; $i++) {
				$field .= '<option value="' . $values[$i]['id'] . '"';
				if ($default == $values[$i]['id']) {
					$field .= 'selected = "selected"';
				}
				$field .= '>' . $values[$i]['text'] . '</option>';
			}
			$field .= '</select>';
		
			return $field;
		}
		
		public static function ow_vote_dropdown_output_string($string, $translate = false, $protected = false) {
			if ($protected == true) {
				return htmlspecialchars($string);
			} else {
				if ($translate == false) {
					return Ow_Vote_Common_Controller::ow_votes_parse_input_field_data($string, array('"' => '&quot;'));
				} else {
					return Ow_Vote_Common_Controller::ow_votes_parse_input_field_data($string, $translate);
				}
			}
		}
		
		public static function ow_votes_parse_input_field_data($data, $parse) {
			return strtr(trim($data), $parse);
		}
		
		public static function ow_vote_dropdown_not_null($value) {
			if (is_array($value)) {
				if (sizeof($value) > 0) {
					return true;
				} else {
					return false;
				}
			} else {
				if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
					return true;
				} else {
					return false;
				}
			}
		}
		
		public static function ow_vote_hyphenize_string($string) {
			return 
				  preg_replace(
					array('#[\\s-]+#', '#[^A-Za-z0-9\., -]+#'),
					array('-', ''),
					  urldecode($string)
				);
		}
		
		public static function ow_vote_list_thumbnail_sizes(){
			global $_wp_additional_image_sizes;
			   $sizes = array();
			   foreach( get_intermediate_image_sizes() as $s ){
				   $sizes[ $s ] = array( 0, 0 );
				   if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
					   $sizes[ $s ][0] = get_option( $s . '_size_w' );
					   $sizes[ $s ][1] = get_option( $s . '_size_h' );
				   }else{
					   if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
						   $sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
				   }
			   }
			   $all_sizes = array();
			   foreach( $sizes as $size => $atts ){
					$all_sizes[$size] = $size . ' - ' .implode( 'x', $atts ); 			
			   }
			   return $all_sizes;
		}
		
		public static function ow_vote_get_thumbnail_sizes($get_seperate){
			global $_wp_additional_image_sizes;
			   $sizes = array();
			   foreach( get_intermediate_image_sizes() as $s ){
				   if( in_array( $s, array( $get_seperate) ) ){
					   $sizes[ $s ][0] = get_option( $s . '_size_w' );
					   $sizes[ $s ][1] = get_option( $s . '_size_h' );
				   }
			   }
			   $all_sizes = array();
			   foreach( $sizes as $size => $atts ){
					$all_sizes[$size] = implode( '--', $atts ); 			
			   }
			   return $all_sizes[$get_seperate];
		}
		
		public static function ow_vote_image_proportionate($imageFile,$size){			
			list($originalWidth, $originalHeight) = getimagesize(realpath($imageFile));
			$ratio = $originalWidth / $originalHeight;
			$targetWidth = $targetHeight = min($size, max($originalWidth, $originalHeight));

			if ($ratio < 1) {
			    $targetWidth = $targetHeight * $ratio;
			} else {
			    $targetHeight = $targetWidth / $ratio;
			}
			return(array($targetWidth,$targetHeight));
		}
		
		public static function ow_vote_seo_friendly_alternative_text($string){
			$string = str_replace(array('[\', \']'), '', $string);
			$string = preg_replace('/\[.*\]/U', '', $string);
			$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
			$string = htmlentities($string, ENT_COMPAT, 'utf-8');
			$string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
			$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
			$string = str_replace('-',' ',$string);
			return strtolower(trim($string, '-'));
		}
		
		public static function ow_vote_is_contest_started($id = FALSE) {	     
			$curterm = $time = NULL;
			if( !is_wp_error($curterm = get_term( $id, OW_VOTES_TAXONOMY)) && isset($curterm)) {	
				if( !Ow_Vote_Common_Controller::ow_votes_validateby_activation_limit($curterm->term_id) ){	
				   return FALSE;
				}
				$time = get_option($curterm->term_id . '_' . OW_VOTES_TAXSTARTTIME);
			}
			if($time != '0' && trim($time) && $time) {
				   $timeentered = strtotime(str_replace("-", "/", $time));
				   $currenttime = current_time( 'timestamp', 0 );
				   $time = date('Y-m-d-H-i-s', strtotime(str_replace('-', '/', $time)));
				   if($currenttime <= $timeentered) {
					   return FALSE;
				   }
			}else {
				   return TRUE;
			}
			return TRUE;
		}
		
		public static function ow_vote_is_contest_reachedend($id = FALSE) {
			$idarr = explode(',', $id);
			$curterm = $time = NULL;
	 
			if( !is_wp_error($curterm = get_term( $id, OW_VOTES_TAXONOMY)) && isset($curterm) ) {
				if(!Ow_Vote_Common_Controller::ow_votes_validateby_activation_limit($curterm->term_id)){
					return TRUE;
				}
				$time = get_option($curterm->term_id . '_' . OW_VOTES_TAXEXPIRATIONFIELD);
			}
			if($time != '0' && trim($time) && $time) {
			   $timeentered = strtotime(str_replace("-", "/", $time));
			   $currenttime = current_time( 'timestamp', 0 );
			   $time = date('Y-m-d-H-i-s', strtotime(str_replace('-', '/', $time)));
			   if($currenttime <= $timeentered) {
				   return FALSE;
			   }
			}else {
			   return FALSE;
			}
			return TRUE;
		}
		
		public static function ow_votes_validateby_activation_limit($id = NULL) {
			$limitcnt = (int)trim(get_option($id.'_'.OW_VOTES_TAXACTIVATIONLIMIT));
			$postcnt = Ow_Vote_Common_Controller::ow_get_term_post_count_by_type($id);
			if(!$limitcnt ) {
				return TRUE;
			}else if($limitcnt > $postcnt){
				return FALSE;
			}else if( $postcnt >= $limitcnt ){
				return TRUE;
			}else {
				return TRUE;
			}
		}
		
		public static function ow_get_term_post_count_by_type($term, $taxonomy = OW_VOTES_TAXONOMY, $type = OW_VOTES_TYPE) {
			$args = array(
				'fields' => 'ids',
				'posts_per_page' => -1,
				'post_type' => $type,
				'tax_query' => array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'id',
						'terms' => $term
					)
				),
				'post_status' => 'publish'
			);
	
			$posts = get_posts($args);
			
			if (count($posts) > 0) {
				return count($posts);
			} else {
				return 0;
			}
		}
		
		public static function ow_votes_is_addform_blocked($id = NULL) {
			$starttime = get_option($id . '_' . OW_VOTES_TAXSTARTTIME);
			$expirytime = get_option($id . '_' . OW_VOTES_TAXEXPIRATIONFIELD);
			$starttimetimestamp = strtotime(str_replace("-", "/", $starttime));
			$expirytimetimestamp = strtotime(str_replace("-", "/", $expirytime));
			$currenttimestamp = current_time( 'timestamp', 0 );
			$isstarted = TRUE;
			$blocked = FALSE;
			$msg = FALSE;
			if( !trim($starttimetimestamp)) {
				$isstarted = FALSE;
				$blocked = FALSE;
			}else if($currenttimestamp > $starttimetimestamp){
				$blocked = TRUE;
				$isstarted = TRUE;
				if($blocked){
					$option = get_option(OW_VOTES_SETTINGS);
					$msg = $option['vote_entriescloseddesc'];
				}
			}
			if(!$isstarted) {
				if(!trim($expirytimetimestamp)) {
					$blocked = FALSE;
				}else if($currenttimestamp > $expirytimetimestamp) {
					$blocked = TRUE;
					if($blocked){
						$option = get_option(OW_VOTES_SETTINGS);
						$msg = $option['vote_reachedenddesc'];
					}
				}
			}
			return $msg;
		}
		
		public static function ow_voting_profile_update($post,$error)
		{
			global $current_user, $wp_roles;
			/* Update user password. */
			if ( !empty($post['pass1'] ) && !empty( $post['pass2'] ) ) {
				if ( $post['pass1'] == $post['pass2'] )
					wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $post['pass1'] ) ) );
				else
					$error->add('Invalid Password','<strong>Error</strong> : '.__('The passwords you entered do not match.  Your password was not updated.', 'voting-contest'));                
			}
			if ( !empty( $post['email'] ) ){
				if (!is_email(esc_attr( $post['email'] )))
					$error->add('Invalid Email','<strong>Error</strong> : '.__('The Email you entered is not valid.  please try again.', 'voting-contest'));
				elseif(email_exists(esc_attr( $post['email'] )) != $current_user->ID )
					$error->add('Invalid Email Exists','<strong>Error</strong> : '.__('This email is already used by another user.  try a different one.', 'voting-contest'));
				else{
					wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $post['email'] )));
				}
			}
					
			if ( !empty( $post['first-name'] ) )
				update_user_meta( $current_user->ID, 'first_name', esc_attr( $post['first-name'] ) );
			if ( !empty( $post['last-name'] ) )
				update_user_meta($current_user->ID, 'last_name', esc_attr( $post['last-name'] ) );
			if ( !empty( $post['nickname'] ) )
				update_user_meta( $current_user->ID, 'nickname', esc_attr( $post['nickname'] ) );
							
			$error = Ow_Vote_Ajax_Controller::ow_votes_registration_errors_front_end($error);  
			if ( count($error->errors) == 0 ) {
				do_action('edit_user_profile_update', $current_user->ID);
			}
			else{
				return $error;
			}
		}
		
		public static function ow_votes_redirect_go_home(){ 
		  $previous_url = $_SERVER['HTTP_REFERER']; 
		  wp_redirect($previous_url);
		  exit();
		}
		
		public static function ow_vote_get_contestant_image($post_id,$short_cont_image){
			if (has_post_thumbnail($post_id)) {
				$ow_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $short_cont_image);
				$data['ow_original_img'] = wp_get_attachment_url(get_post_thumbnail_id($post_id)).'?'.uniqid();
				$data['ow_image_src'] = $ow_image_arr[0];

				
				$get_img_size=getimagesize(realpath($data['ow_image_src']));
				
			}else{
				$data['ow_image_src'] = OW_NO_IMAGE_CONTEST;
				$data['ow_original_img'] = OW_NO_IMAGE_CONTEST;
			}
			return $data;
		}
		
		public static function ow_vote_get_all_global_settings($vote_opt){
			$data=array();
			//Get all global options
			$data['short_cont_image'] = $vote_opt['short_cont_image'];
			$data['page_cont_image'] = $vote_opt['page_cont_image'];
			$data['vote_select_sidebar'] = $vote_opt['vote_select_sidebar'] ? $vote_opt['vote_select_sidebar'] : 'off';
			$data['vote_sidebar'] = $vote_opt['vote_sidebar'] ? $vote_opt['vote_sidebar'] : 'off';
			$data['vote_readmore'] = $vote_opt['vote_readmore'] ? $vote_opt['vote_readmore'] : 'off';
			$data['vote_entry_form'] = $vote_opt['vote_entry_form'] ? $vote_opt['vote_entry_form'] : '0';			
			$data['vote_turn_related'] = $vote_opt['vote_turn_related']?$vote_opt['vote_turn_related']:'off';
			$data['single_page_cont_image_px'] = $vote_opt['single_page_cont_image_px']?$vote_opt['single_page_cont_image_px']:'%';
			$data['vote_video_width'] = $vote_opt['vote_video_width']?$vote_opt['vote_video_width']:'off';
			$data['single_page_cont_image'] = $vote_opt['single_page_cont_image']?$vote_opt['single_page_cont_image']:'';
			$data['single_contestants_video_width'] = $vote_opt['single_contestants_video_width']?$vote_opt['single_contestants_video_width']:'';
			$data['single_contestants_video_width_px'] = $vote_opt['single_contestants_video_width_px']?$vote_opt['single_contestants_video_width_px']:'%';
			$data['vote_audio_width'] = $vote_opt['vote_audio_width']?$vote_opt['vote_audio_width']:'';
			$data['vote_audio_height'] = $vote_opt['vote_audio_height']?$vote_opt['vote_audio_height']:'';
			$data['vote_audio_skin'] = $vote_opt['vote_audio_skin']?$vote_opt['vote_audio_skin']:'hu-css';
			$data['vote_essay_width'] = $vote_opt['vote_essay_width']?$vote_opt['vote_essay_width']:'';
			$data['vote_title_alocation']=$vote_opt['vote_title_alocation']?$vote_opt['vote_title_alocation']:'off';
			$data['single_page_title']=$vote_opt['single_page_title']?$vote_opt['single_page_title']:'off';
			$data['vote_notify_mail'] = $vote_opt['vote_notify_mail']?$vote_opt['vote_notify_mail']:'off';
			$data['vote_admin_mail'] = $vote_opt['vote_admin_mail']?$vote_opt['vote_admin_mail']:'';
			$data['vote_admin_mail_content'] = $vote_opt['vote_admin_mail_content']?$vote_opt['vote_admin_mail_content']:'';
			$data['vote_from_name'] = $vote_opt['vote_from_name']?$vote_opt['vote_from_name']:'';
			$data['votes_timertextcolor'] = $vote_opt['votes_timertextcolor']?$vote_opt['votes_timertextcolor']:'';
			$data['votes_timerbgcolor'] = $vote_opt['votes_timerbgcolor']?$vote_opt['votes_timerbgcolor']:'';			
			$data['vote_show_date_prettyphoto'] = $vote_opt['vote_show_date_prettyphoto']?$vote_opt['vote_show_date_prettyphoto']:'off';
			$data['vote_prettyphoto_disable'] = $vote_opt['vote_prettyphoto_disable']?$vote_opt['vote_prettyphoto_disable']:'off';
			$data['vote_prettyphoto_disable_single'] = $vote_opt['vote_prettyphoto_disable_single']?$vote_opt['vote_prettyphoto_disable_single']:'off';
			$data['vote_hide_account'] = $vote_opt['vote_hide_account']?$vote_opt['vote_hide_account']:'off';
			$data['vote_openclose_menu'] = $vote_opt['vote_openclose_menu']?$vote_opt['vote_openclose_menu']:'off';
			$data['vote_enable_all_contest'] = $vote_opt['vote_enable_all_contest']?$vote_opt['vote_enable_all_contest']:'masonry-grid';
			$data['vote_all_contest_design'] = $vote_opt['vote_all_contest_design']?$vote_opt['vote_all_contest_design']:'swipe-down';
			$data['vote_enable_ended'] = $vote_opt['vote_enable_ended']?$vote_opt['vote_enable_ended']:'off';
			$data['vote_count_showhide'] = $vote_opt['vote_count_showhide']?$vote_opt['vote_count_showhide']:'off';
			$data['vote_onlyloggedcansubmit'] = $vote_opt['vote_onlyloggedcansubmit']?$vote_opt['vote_onlyloggedcansubmit']:FALSE;			
			
			$data['vote_tracking_method'] = $vote_opt['vote_tracking_method']?$vote_opt['vote_tracking_method']:'';
			$data['vote_truncation_grid'] = $vote_opt['vote_truncation_grid']?$vote_opt['vote_truncation_grid']:'';
			$data['vote_truncation_list'] = $vote_opt['vote_truncation_list']?$vote_opt['vote_truncation_list']:'';
			$data['frequency'] = $vote_opt['frequency']?$vote_opt['frequency']:'';
			
			$data['vote_frequency_count']=$vote_opt['vote_frequency_count']?$vote_opt['vote_frequency_count']:'';
			$data['vote_votingtype_val']=$vote_opt['vote_votingtype'];
			
			$data['vote_votingtype']  = $vote_opt['vote_votingtype']?$vote_opt['vote_votingtype']:'';
			$data['vote_publishing_type']  = $vote_opt['vote_publishing_type']?$vote_opt['vote_publishing_type']:'';
			$data['vote_grab_email_address']  = $vote_opt['vote_grab_email_address']?$vote_opt['vote_grab_email_address']:'';
			$data['vote_tobestarteddesc'] = $vote_opt['vote_tobestarteddesc']?$vote_opt['vote_tobestarteddesc']:'';
			$data['vote_reachedenddesc']  = $vote_opt['vote_reachedenddesc']?$vote_opt['vote_reachedenddesc']:'';
			$data['vote_entriescloseddesc'] = $vote_opt['vote_entriescloseddesc']?$vote_opt['vote_entriescloseddesc']:'';
					
			$data['facebook'] = $vote_opt['facebook'] ? $vote_opt['facebook'] : 'off';
			$data['file_facebook'] = $vote_opt['file_facebook'] ?$vote_opt['file_facebook']:'';
			$data['file_fb_default'] = $vote_opt['file_fb_default'] ?$vote_opt['file_fb_default']:'';
			$data['pinterest'] = $vote_opt['pinterest'] ? $vote_opt['pinterest'] : 'off';
			$data['file_pinterest'] = $vote_opt['file_pinterest']?$vote_opt['file_pinterest']:'';
			$data['file_pinterest_default'] = $vote_opt['file_pinterest_default']?$vote_opt['file_pinterest_default']:'';
			$data['gplus']= $vote_opt['gplus'] ? $vote_opt['gplus'] : 'off';
			$data['file_gplus']= $vote_opt['file_gplus'] ? $vote_opt['file_gplus'] : '';
			$data['file_gplus_default']= $vote_opt['file_gplus_default'] ? $vote_opt['file_gplus_default'] : '';
			$data['tumblr'] = $vote_opt['tumblr'] ? $vote_opt['tumblr'] : 'off';
			$data['file_tumblr'] = $vote_opt['file_tumblr'] ? $vote_opt['file_tumblr'] : '';
			$data['file_tumblr_default'] = $vote_opt['file_tumblr_default'] ? $vote_opt['file_tumblr_default'] : '';
			$data['facebook_login'] = $vote_opt['facebook_login']?$vote_opt['facebook_login']:'';
			$data['vote_fb_appid'] = $vote_opt['vote_fb_appid']?$vote_opt['vote_fb_appid']:"";
			$data['twitter'] = $vote_opt['twitter'] ? $vote_opt['twitter'] : 'off';
			$data['file_twitter'] = $vote_opt['file_twitter'] ?$vote_opt['file_twitter']:'';
			$data['file_tw_default'] = $vote_opt['file_tw_default'] ?$vote_opt['file_tw_default']:'';
			$data['twitter_login'] = $vote_opt['twitter_login']?$vote_opt['twitter_login']:'';
			$data['vote_tw_appid'] = $vote_opt['vote_tw_appid']?$vote_opt['vote_tw_appid']:'';
			
			$data['vote_tw_secret'] = $vote_opt['vote_tw_secret']?$vote_opt['vote_tw_secret']:'';
			$data['deactivation']= $vote_opt['deactivation']?$vote_opt['deactivation']:'';
			
			$data['vote_disable_jquery'] = $vote_opt['vote_disable_jquery']?$vote_opt['vote_disable_jquery']:'';
			$data['vote_disable_jquery_cookie'] = $vote_opt['vote_disable_jquery_cookie']?$vote_opt['vote_disable_jquery_cookie']:'';
			$data['vote_disable_jquery_fancy'] = $vote_opt['vote_disable_jquery_fancy']?$vote_opt['vote_disable_jquery_fancy']:'';
			$data['vote_disable_jquery_pretty'] = $vote_opt['vote_disable_jquery_pretty']?$vote_opt['vote_disable_jquery_pretty']:'';
			$data['vote_disable_jquery_validate'] =$vote_opt['vote_disable_jquery_validate']?$vote_opt['vote_disable_jquery_validate']:'';
			
			//Needed for shortcode page
			$data['onlyloggedinuser'] = $vote_opt['onlyloggedinuser']?$vote_opt['onlyloggedinuser']:'';
			
			return $data;
		}
		
		public static function ow_voting_encrypt($data, $size)
		{
		    $length = $size - fmod(strlen($data), $size);
		    $enc_data =  $data . str_repeat(chr($length), $length);		
		    return openssl_encrypt($enc_data,'AES-256-CBC',$encryption_key,0);
		}
		
		public static function ow_voting_decrypt($enc_name)
		{
		   	return $data1 =  openssl_decrypt($enc_name,'AES-256-CBC',$encryption_key,0);			  
		}
		
		
		public function ow_voting_delete_post_entry_track($postid)
		{			
		    global $post;
		    
		    if($post == null)
			$post = get_post( $postid ); 
		    
		    if($post->post_type == OW_VOTES_TYPE):
		    
			$term_list = get_the_terms($post->ID,OW_VOTES_TAXONOMY);						
			//$term_list = wp_get_post_terms($post->ID, OW_VOTES_TAXONOMY, array("fields" => "ids"));
			$post_author = $post->post_author;
			$term_id = $term_list[0]->term_id;			
			$ow_contestant_author = get_post_meta($postid,'_ow_contestant_author_'.$term_id,true);
				
			if($ow_contestant_author != null){
				Ow_Contestant_Model::ow_voting_delete_post_entry_track($ow_contestant_author,$term_id);
				delete_post_meta($postid, '_ow_contestant_author_'.$term_id, $ow_contestant_author);
			}
			return;			
		    endif;
		}
		
		public static function ow_votes_get_contestant_link($post_id){
			$custom_link = get_post_meta($post_id,'ow_contestant_link',true);
			if($custom_link == null)
				return get_permalink($post_id);
			else
				return $custom_link;
			
		}
		
		public static function ow_vote_contestant_thumbnail($post_id){
			//Check the Post Meta of contestant-ow_video_url is available
			$contestant_ow_video_url = get_post_meta($post_id,'contestant-ow_video_url',true);
			
			if($contestant_ow_video_url == null){
				$custom_entries = Ow_Contestant_Model::ow_voting_get_all_custom_entries($post_id);
				if(!empty($custom_entries)){
					$field_values = $custom_entries[0]->field_values;
					if(base64_decode($field_values, true))
						$field_val = maybe_unserialize(base64_decode($field_values));  
					else
						$field_val = maybe_unserialize($field_values);
						
				}				
				$contestant_ow_video_url = $field_val['contestant-ow_video_url'];
				return Ow_Vote_OW_Video::get_video_thumbnail($contestant_ow_video_url);
			}
			else{
				
				return Ow_Vote_OW_Video::get_video_thumbnail($contestant_ow_video_url);
			}
			
		}		
		
		
 
		
	
    }
}else
die("<h2>".__('Failed to load the Voting Common Controller','voting-contest')."</h2>");

return new Ow_Vote_Common_Controller();
