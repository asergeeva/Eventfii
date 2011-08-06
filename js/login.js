/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
var LOGIN_FORM = (function() {
	return {
		init: function() {
			FBCON.getLoginStatus();
		},
		
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
		
		fbUserLogin: function(userInfo) {
			$.post(EFGLOBAL.baseUrl + '/login', {
				isExist: false,
				fname: userInfo.first_name,
				lname: userInfo.last_name,
				email: userInfo.email,
				pic: 'http://graph.facebook.com/' + userInfo.id + '/picture',
				isFB: true,
				fbid: userInfo.id
			}, LOGIN_FORM.loginRedirect);
			$('#container').html(EFGLOBAL.ajaxLoader);
		},
		
		loginRedirect: function(status) {
			if( status == 1 ) {
				window.location = EFGLOBAL.baseUrl;
			} else if ( status == 0 ) {
				window.location = EFGLOBAL.baseUrl + '/login';
				$('#invalid_credentials').html("Please enter valid login credentials.");
			} else {
				window.location = EFGLOBAL.baseUrl + '/login' + status;
			}
		}
	}
})();