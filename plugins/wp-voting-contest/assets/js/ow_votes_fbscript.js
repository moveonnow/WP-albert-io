jQuery(document).ready(function(){
	var vote_fb_appid = jQuery('#vote_fb_appid').val();
	if(vote_fb_appid != null)
	{
		  window.fbAsyncInit = function() {
		  FB.init({
			appId      : vote_fb_appid,
			status : true,
			cookie     : false,  // enable cookies to allow the server to access 								
			xfbml      : true,  // parse social plugins on this page
			version    : 'v2.5', // use version 2.5
			oauth : true,
		  });
		    
		};
		
		// Load the SDK asynchronously
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	  
		//whenever the user logs in, we refresh the page
	}
	//jQuery('.ow_voting_facebook_login').append('<fb:login-button data-size="icon" data-colorscheme="dark" scope="public_profile,email" onlogin="checkLoginState();" class="fb_login_button"></fb:login-button>');
	var vote_fb_image = jQuery('#vote_fb_image').val();
	jQuery('.ow_voting_facebook_login').append('<a href="javascript:" title="Sign in with Facebook" onclick="fb_login();"><img src="'+vote_fb_image+'" border="0" alt=""></a>');
	
	jQuery('.FB_login_button').find('img').attr('src', vote_fb_image);

	
	
});
function statusChangeCallback(response,flag) {    
    
    if(flag == 1){
        
     FB.api('/me', {fields: 'name,email'}, function(response) {
		//console.log(response);
      jQuery.ajax({
		  type: 'POST',
		  url: vote_path_local.votesajaxurl,		 
		  data: {
		  action : 'ow_facebook_login',
		  responses: response,	
          	  email: response.email,		 
		  },
		  success: function(response, textStatus, XMLHttpRequest){	
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
			setTimeout(function() {
             location.reload();
			}, 2000);
			
		  },
		  error: function(MLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		  }
	   });    
    }); 
      
    }    
   
}

function fb_login(){
    FB.login(function(response) {

        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID

            FB.api('/me', {fields: 'name,email'}, function(response) {
		console.log(response);
      jQuery.ajax({
		  type: 'POST',
		  url: vote_path_local.votesajaxurl,		 
		  data: {
		  action : 'ow_facebook_login',
		  responses: response,	
          	  email: response.email,		 
		  },
		  success: function(response, textStatus, XMLHttpRequest){	
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
			setTimeout(function() {
             location.reload();
			}, 2000);
			
		  },
		  error: function(MLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		  }
	   });    
    }); 

        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'publish_stream,email'
    });
}

function checkLoginState() {
    FB.getLoginStatus(function(response) {
		if (response.status === 'connected' || response.status === 'not_authorized') {
			statusChangeCallback(response,1);
		}else {
			FB.login(function(response) {
				if (response.authResponse) {
					statusChangeCallback(response,1);
				} 
			});
		}
        //statusChangeCallback(response,1);      
    });
}


//Twitter login
function voting_save_twemail_session()
{
    jQuery.ow_vote_prettyPhoto.close();  
    jQuery(".error_empty").remove();  
    setTimeout(function() {
        jQuery.fn.ow_vote_prettyPhoto({social_tools: false, deeplinking: false, show_title: false, default_width: '350', theme:'pp_kalypso'});
    	jQuery.ow_vote_prettyPhoto.open('#twitter_register_panel');
	jQuery('.ow_tabs_register_content').show();
	jQuery('.forgot-panel').show();
     }, 300);
   
    jQuery( ".inner-container" ).addClass('forgot-panel_add');
    
}

var ow_myWindow;
var timer = setInterval(checktwitter_auth, 1000);

function votes_twitter_authentication()
{
    jQuery.ajax({
	  type: 'POST',
	  url: vote_path_local.votesajaxurl,		 
	  data: {
		action : 'ow_twitter_login',
		vote_tw_appid: jQuery('#vote_tw_appid').val(),	
		vote_tw_secret: jQuery('#vote_tw_secret').val(), 
		current_callback_url : jQuery('#current_callback_url').val(),    	 
	  },
	  success: function(response, textStatus, XMLHttpRequest){
		if (response==1) {
			alert('Error connecting to Twitter! Try again later!');
		}else{
			ow_myWindow = window.open(response,null,"height=200,width=400,status=yes,toolbar=no,menubar=no,location=no"); 
		  //window.location.href = response;         			
		}
	  },
	  error: function(MLHttpRequest, textStatus, errorThrown){
		alert(errorThrown);
	  }
   });
   
}

function checktwitter_auth() {
	try {
		var url_opened = ow_myWindow.location.href;
		if (url_opened.indexOf('oauth_verifier') != -1){
			clearInterval(timer);
			ow_myWindow.close();
			window.location.href = url_opened;
		}
	}
	catch (e){

	}
}

var getCookies = function(){
  var pairs = document.cookie.split(";");
  var cookies = {};
  for (var i=0; i<pairs.length; i++){
    var pair = pairs[i].split("=");
    cookies[pair[0]] = unescape(pair[1]);
  }
  return cookies;
}
var myCookies = getCookies();


