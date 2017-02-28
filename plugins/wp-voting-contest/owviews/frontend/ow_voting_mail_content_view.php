<?php
if(!function_exists('ow_voting_mail_addcontestant_view')){
    function ow_voting_mail_addcontestant_view($option_setting,$post_id,$cont_details,$email_description = null){
		
		$contestant_title = $cont_details['contestant_title'];
		$contestant_desc = $cont_details['contestant_desc'];
				
		$admin_url1 = get_bloginfo('url');
		$admin_url = $admin_url1.'&#47;wp-admin&#47;post.php?post='.$post_id.'&action=edit';
		
		$message ='
            <html lang="en">
             <head>
              <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
              <title>'. get_bloginfo('name').'</title>
              <style type="text/css">
              a:hover { text-decoration: none !important; }
              .header h1 {color: #47c8db !important; font: bold 32px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 40px;}
              .header p {color: #c6c6c6; font: normal 12px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 18px;}
          
              .content h2 {color:#646464 !important; font-weight: bold; margin: 0; padding: 0; line-height: 26px; font-size: 18px; font-family: Helvetica, Arial, sans-serif;  }
              .content p {color:#767676; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;}
              .content a {color: #0eb6ce; text-decoration: none;}
              .footer p {font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;}
              .footer a {color: #0eb6ce; text-decoration: none;}
              </style>
           </head>
           
          <body style="margin: 0; padding: 0; background: #4b4b4b;" bgcolor="#4b4b4b">
               
                <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding: 35px 0; background: #4b4b4b;">
                  <tr>
                    <td align="center" style="margin: 0; padding: 0; background:#4b4b4b;" >
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; background:#2a2a2a;" class="header">
                            <tr>
                            <td width="20"style="font-size: 0px;">&nbsp;</td>
                            <td width="580" align="left" style="padding: 18px 0 10px;">
                                <h1 style="color: #47c8db; font: bold 32px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 40px;">'.get_bloginfo('name').'</h1>
                                <p style="color: #c6c6c6; font: normal 12px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 18px;">'.get_bloginfo('description').'</p>
                            </td>
                          </tr>
                        </table><!-- header-->
                
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; background: #fff;" bgcolor="#fff">
                            
                            <tr>
                            <td width="600" valign="top" align="left" style="font-family: Helvetica, Arial, sans-serif; padding: 20px 0 0;" class="content">
                                <table cellpadding="0" cellspacing="0" border="0"  style="color: #717171; font: normal 11px Helvetica, Arial, sans-serif; margin: 0; padding: 0;" width="600">
        
                                <tr>
                                    <td width="21" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                                    <td style="padding: 20px 0 0;" align="left">            
                                        <h2 style="color:#646464; font-weight: bold; margin: 0; padding: 0; line-height: 26px; font-size: 18px; font-family: Helvetica, Arial, sans-serif; ">New Contestant Entry has been submitted</h2>
                                    </td>
                                    <td width="21" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                                </tr>
                    
                                <tr>
                                    <td width="21" style="font-size: 1px; line-height: 1px;"><p>&nbsp;</p></td>
                                    <td style="padding: 15px 0 15px;"  valign="top">
                                        <p>&nbsp;</p>
                                        <p style="color:#767676; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;"> <b>Contestant Title: </b>'.$contestant_title.'</p><br>
                        
                                        <p style="color:#767676; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;"> <b>Contestant Description:</b> '.$contestant_desc.'</p><br>
										
										
                                        <p style="color:#767676; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;"><a href="'.$admin_url.'">Click here</a> to view the entry</p><br/>
										
										
										'.$email_description.'
             
                               </td>
                                </tr>
                                </table>    
                            </td>
                            
                          </tr>
                            <tr>
                                <td width="600" align="left" style="padding: font-size: 0; line-height: 0; height: 3px;" height="3" colspan="2">&nbsp;</td>
                              </tr> 
                        </table><!-- body -->
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; line-height: 10px;" class="footer"> 
                        <tr>
                            <td align="center" style="padding: 5px 0 10px; font-size: 11px; color:#7d7a7a; margin: 0; line-height: 1.2;font-family: Helvetica, Arial, sans-serif;" valign="top">
                                <br><p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">This is an Automated Email</p>
                                <p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">Sent From <webversion style="color: #0eb6ce; text-decoration: none;"><a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a></webversion>. Please Do not respond </p>
                            </td>
                          </tr>
                        </table><!-- footer-->
                    </td>
                    </td>
                </tr>
            </table>
          </body>
        </html>';
		
		return $message;
		
    }
}else{
    die("<h2>".__('Failed to load Voting Add contest mail view','voting-contest')."</h2>");
}


if(!function_exists('ow_voting_mail_verification_code')){
    function ow_voting_mail_verification_code($verificationcode){
				
		$message ='
            <html lang="en">
             <head>
              <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
              <title>'. get_bloginfo('name').'</title>
              <style type="text/css">
              a:hover { text-decoration: none !important; }
              .header h1 {color: #47c8db !important; font: bold 32px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 40px;}
              .header p {color: #c6c6c6; font: normal 12px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 18px;}
          
              .content h2 {color:#646464 !important; font-weight: bold; margin: 0; padding: 0; line-height: 26px; font-size: 18px; font-family: Helvetica, Arial, sans-serif;  }
              .content p {color:#767676; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;}
              .content a {color: #0eb6ce; text-decoration: none;}
              .footer p {font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;}
              .footer a {color: #0eb6ce; text-decoration: none;}
              </style>
           </head>
           
          <body style="margin: 0; padding: 0; background: #4b4b4b;" bgcolor="#4b4b4b">
               
                <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding: 35px 0; background: #4b4b4b;">
                  <tr>
                    <td align="center" style="margin: 0; padding: 0; background:#4b4b4b;" >
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; background:#2a2a2a;" class="header">
                            <tr>
                            <td width="20"style="font-size: 0px;">&nbsp;</td>
                            <td width="580" align="left" style="padding: 18px 0 10px;">
                                <h1 style="color: #47c8db; font: bold 32px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 40px;">'.get_bloginfo('name').'</h1>
                                <p style="color: #c6c6c6; font: normal 12px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 18px;">'.get_bloginfo('description').'</p>
                            </td>
                          </tr>
                        </table><!-- header-->
                
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; background: #fff;" bgcolor="#fff">
                            
                            <tr>
                            <td width="600" valign="top" align="left" style="font-family: Helvetica, Arial, sans-serif; padding: 20px 0 0;" class="content">
                                <table cellpadding="0" cellspacing="0" border="0"  style="color: #717171; font: normal 11px Helvetica, Arial, sans-serif; margin: 0; padding: 0;" width="600">
        
                                <tr>
                                    <td width="21" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                                    <td style="padding: 20px 0 0;" align="left">            
                                        <h2 style="color:#646464; font-weight: bold; margin: 0; padding: 0; line-height: 26px; font-size: 18px; font-family: Helvetica, Arial, sans-serif; ">Email Verification Code: '.$verificationcode.'</h2>
                                    </td>
                                    <td width="21" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                                </tr>
                    
                                <tr>
                                    <td width="21" style="font-size: 1px; line-height: 1px;"><p>&nbsp;</p></td>
                                    <td style="padding: 15px 0 15px;"  valign="top">
                                        <p>&nbsp;</p>                                       
             
                               </td>
                                </tr>
                                </table>    
                            </td>
                            
                          </tr>
                            <tr>
                                <td width="600" align="left" style="padding: font-size: 0; line-height: 0; height: 3px;" height="3" colspan="2">&nbsp;</td>
                              </tr> 
                        </table><!-- body -->
                        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; line-height: 10px;" class="footer"> 
                        <tr>
                            <td align="center" style="padding: 5px 0 10px; font-size: 11px; color:#7d7a7a; margin: 0; line-height: 1.2;font-family: Helvetica, Arial, sans-serif;" valign="top">
                                <br><p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">This is an Automated Email</p>
                                <p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">Sent From <webversion style="color: #0eb6ce; text-decoration: none;"><a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a></webversion>. Please Do not respond </p>
                            </td>
                          </tr>
                        </table><!-- footer-->
                    </td>
                    </td>
                </tr>
            </table>
          </body>
        </html>';
		
	return $message;
		
    }
}else{
    die("<h2>".__('Failed to load Voting Add Verification Code mail view','voting-contest')."</h2>");
}


?>
