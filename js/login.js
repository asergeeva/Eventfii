/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
 
var LOGIN_FORM = (function() {
	return {
		existingUserLogin: function() {
			$.post(EFGLOBAL.baseUrl + '/login', {
					isExist: true,
					email: $('#ef_login_email_exist').val(),
					pass: hex_md5($('#ef_login_pass_exist').val())
			}, LOGIN_FORM.loginRedirect);
			$('#container').html(EFGLOBAL.ajaxLoader);
		},
		
		newUserLogin: function() {
			var passval="";
			if ($('#ef_login_pass_new').val().length>=6) {
				passval=hex_md5($('#ef_login_pass_new').val());
			} else {
				passval=$('#ef_login_pass_new').val();
			}
			
			$.post(EFGLOBAL.baseUrl + '/user/create', {
				isExist: false,
				fname: $('#ef_fname_new').val(),
				lname: $('#ef_lname_new').val(),
				email: $('#ef_login_email_new').val(),
				phone: $('#ef_login_phone_new').val(),
				zipcode: $('#zipcode').val(),
				pass: passval
			}, LOGIN_FORM.userLogin);
			$('#container').html(EFGLOBAL.ajaxLoader);
		},
		
		/**
		 * userInfo - the user object from Facebook
		 * sessionInfo - the session from Facebook
		 */
		fbUserLogin: function(userInfo, sessionInfo, req_uri) {
			$.post(EFGLOBAL.baseUrl + '/login', {
				isExist: false,
				req_uri: req_uri,
				fname: userInfo.first_name,
				lname: userInfo.last_name,
				email: userInfo.email,
				pic: 'http://graph.facebook.com/' + userInfo.id + '/picture?type=large',
				isFB: true,
				fbid: userInfo.id,
				curPage: window.location.href,
				fb_access_token: sessionInfo.authResponse.accessToken,
				fb_session_key: sessionInfo.authResponse.signedRequest
			}, LOGIN_FORM.loginRedirect);
		},
		
		/**
		 * Redirect user when they are logged in
		 */
		loginRedirect: function(status) {
			// Regular login
			if( status == 1 ) {
				window.location = EFGLOBAL.baseUrl + '/home?loggedIn=true';
			// Facebook login
			} else if ( status == 3 ) {
				window.location = EFGLOBAL.baseUrl + '/register?step=create';
			// Use the current page as redirect
			} else if ( status == 4 ) {
				window.location = window.location.href;
			// Invalid login
			} else if ( status == 0 ) {
				window.location = EFGLOBAL.baseUrl + '/login';
				$('#invalid_credentials').html("Please enter valid login credentials.");
				//If Facebook session exists, and we send request from manage guest page.
			} else if ( status == 8 ) {
				window.location.reload();
			}else if ( status == 9 ) {
				//do nothing
			} else {
				window.location = EFGLOBAL.baseUrl + '/login' + status;
			}
		}
	}
})();