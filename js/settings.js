var EF_SETTINGS = (function() {
	function _saveSettings() {
		$('#save_loading_img').html(EFGLOBAL.ajaxLoader);
		$.post(EFGLOBAL.baseUrl + '/settings/save', {
			fname: $('#fname').val(),
			lname: $('#lname').val(),
			email: $('#email').val(),
			phone: $('#user-cell').val(),
			zip: $('#user-zip').val(),
			twitter: $('#twitter').val(),
			features: $('#features').attr('checked'),
			updates: $('#updates').attr('checked'),
			attend: $('#attend').attr('checked'),
			curpass: $('#password-current').val(),
			newpass: $('#password-new').val(),
			confpass: $('#password-confirm').val()
		}, function(respData) {
			$('#save_loading_img').html(EFGLOBAL.isSucceed);
		});
	}
	
	return {
		init: function() {
			USER_IMAGE_UPLOADER.init();
			$('#save_settings').live('click', _saveSettings);
		},
		
		fbconnect: function() {
			FB.api('/me', function(userInfo) {
				if (typeof userInfo.error == 'undefined') {
					$.post(EFGLOBAL.baseUrl + '/fb/user/update', {
						fbid: userInfo.id
					}, function() {
						$('#user_fbid').html(userInfo.id);
					});
				}
			});
		}
	}
})();

$(document).ready(function() {
	EF_SETTINGS.init();
});