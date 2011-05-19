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
		
		userLogin: function(cpContainer) {
			$('body').attr('id', 'cp_body');
			$('#container').html(cpContainer);
			$("img[rel]").overlay();
			CREATE_EVENT_FORM.init();
		}
	}
})();