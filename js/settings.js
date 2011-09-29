/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var EF_SETTINGS = (function() {
	$('#save_settings').live('click', function() {
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
			$('#save_settings_status').html(EFGLOBAL.isSucceed);
		});
	});
	
	return {
		init: function() {
			USER_IMAGE_UPLOADER.init();
			$('#user-bio').editable('user/status/update', {
					type:'textarea',
					indicator : 'Saving...',
					tooltip   : 'Click to edit...',
					style: 'border-style: inset; border-width: 2px',
					onblur: 'submit',
					callback : function(value, settings) {
						// Do nothing
					}
			});
		},
		
		fbconnect: function() {
			FB.api('/me', function(userInfo) {
				if (typeof userInfo.error == 'undefined') {
					$.post(EFGLOBAL.baseUrl + '/fb/user/update', {
						fbid: userInfo.id
					}, function() {
						$('#user_fbid').html(userInfo.id);
						$('#notification-box p.message').html("Your Facebook account is now connected.");
						$('#notification-box').fadeIn('slow');
					});
				}
			});
		}
	}
})();

$(document).ready(function() {
	EF_SETTINGS.init();
});