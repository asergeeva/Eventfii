/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
var LOGIN_FORM = (function() {
	return {
		existingUserLogin: function() {
			$.post(EFGLOBAL.baseUrl + '/login', {
					isExist: true,
					email: $('#ef_login_email_exist').val(),
					pass: hex_md5($('#ef_login_pass_exist').val())
			}, LOGIN_FORM.userLogin);
			$('#middle').html(EFGLOBAL.ajaxLoader);
		},
		
		newUserLogin: function() {
			$.post(EFGLOBAL.baseUrl + '/user/create', {
				isExist: false,
				fname: $('#ef_fname_new').val(),
				lname: $('#ef_lname_new').val(),
				email: $('#ef_login_email_new').val(),
				pass: hex_md5($('#ef_login_pass_new').val())
			}, LOGIN_FORM.userLogin);
			$('#middle').html(EFGLOBAL.ajaxLoader);
		},
		
		fbUserLogin: function(userInfo) {
			$.post(EFGLOBAL.baseUrl + '/user/create', {
				isExist: false,
				fname: userInfo.first_name,
				lname: userInfo.last_name,
				email: userInfo.email,
				isFB: true
			}, LOGIN_FORM.userLogin);
			$('#middle').html(EFGLOBAL.ajaxLoader);
		},
		
		userLogin: function(status) {
			window.location = EFGLOBAL.baseUrl;
		}
	}
})();