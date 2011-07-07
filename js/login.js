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
			$('#middle').html(EFGLOBAL.ajaxLoader);
		},
		
		newUserLogin: function() {
		var passval="";
		if($('#ef_login_pass_new').val().length>=6)
					 passval=hex_md5($('#ef_login_pass_new').val());
				else
					passval=$('#ef_login_pass_new').val();
			$.post(EFGLOBAL.baseUrl + '/user/create', {
				isExist: false,
				fname: $('#ef_fname_new').val(),
				lname: $('#ef_lname_new').val(),
				email: $('#ef_login_email_new').val(),
				phone: $('#ef_login_phone_new').val(),
				zipcode: $('#zipcode').val(),
				pass: passval
			}, LOGIN_FORM.userLogin);
			$('#middle').html(EFGLOBAL.ajaxLoader);
		},
		
		fbUserLogin: function(userInfo) {
			$.post(EFGLOBAL.baseUrl + '/user/fb/create', {
				isExist: false,
				fname: userInfo.first_name,
				lname: userInfo.last_name,
				email: userInfo.email,
				isFB: true
			}, LOGIN_FORM.loginRedirect);
			$('#middle').html(EFGLOBAL.ajaxLoader);
		},
		
		loginRedirect: function(status) {
	//	$('#container').html(status);
	window.location = EFGLOBAL.baseUrl;
		},
		
		userLogin: function(status) {
		$('body').html(status).ready(function() {
					CREATE_EVENT_FORM.init();
				});
	//window.location = EFGLOBAL.baseUrl;
		}
	}
})();