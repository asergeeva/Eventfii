/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var EF_SETTINGS = (function() {
	var _editEmail2 = $('#edit-email-2'),
		_editEmail3 = $('#edit-email-3'),
		_editEmail4 = $('#edit-email-4'),
		_editEmail5 = $('#edit-email-5'),
		
		_addEmail2  = $('#add-email-2'),
		_addEmail3  = $('#add-email-3'),
		_addEmail4  = $('#add-email-4'),
		_addEmail5  = $('#add-email-5');
	
	$('#save_settings').live('click', function() {
		$('#save_loading_img').html(EFGLOBAL.ajaxLoader);
		$.post(EFGLOBAL.baseUrl + '/settings/save', {
			fname    : $('#fname').val(),
			lname    : $('#lname').val(),
			email    : $('#email').val(),
			phone    : $('#user-cell').val(),
			zip      : $('#user-zip').val(),
			twitter  : $('#twitter').val(),
			features : $('#features').attr('checked'),
			updates  : $('#updates').attr('checked'),
			attend   : $('#attend').attr('checked'),
			curpass  : $('#password-current').val(),
			newpass  : $('#password-new').val(),
			confpass : $('#password-confirm').val()
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
					style     : 'border-style: inset; border-width: 2px',
					onblur    : 'submit',
					callback  : function(value, settings) {
						// Do nothing
					}
			});
			
			// Listening to the edit email
			_editEmail2.live('click', this.editEmail);
			_editEmail3.live('click', this.editEmail);
			_editEmail4.live('click', this.editEmail);
			_editEmail5.live('click', this.editEmail);
			
			_addEmail2.live('click', this.addEmail);
			_addEmail3.live('click', this.addEmail);
			_addEmail4.live('click', this.addEmail);
			_addEmail5.live('click', this.addEmail);
		},
		
		fbconnect: function() {
			FB.api('/me', function(userInfo) {
				if (typeof userInfo.error == 'undefined') {
					$.post(EFGLOBAL.baseUrl + '/fb/user/update', {
						fbid: userInfo.id
					}, function() {
						$('#user_pic').attr('src', EFGLOBAL.fbGraph + '/' + userInfo.id + '/picture?type=large');
						$('#user_fbid').html(userInfo.id);
						$('#notification-box p.message').html("Your Facebook account is now connected.");
						$('#notification-box').fadeIn('slow');
					});
				}
			});
			
			FBCON.harvestFriends();
		},
		
		addEmail: function() {
			var otherEmailComp = $(this).attr('id').split('-'),
				otherEmailId   = otherEmailComp[otherEmailComp.length - 1],
				emailContainer = $('#block-email-' + (parseInt(otherEmailId) + 1));
			$(this).hide();
			emailContainer.show();
		},
		
		editEmail: function() {
			var otherEmailComp = $(this).attr('id').split('-'),
				otherEmailId   = otherEmailComp[otherEmailComp.length - 1],
				otherEmailDOM  = $('#other-email-' + otherEmailId),
				saveEmailDOM   = $('#save-email-' + otherEmailId),
				editEmailDOM   = $(this);
			
			$(this).hide();
			
			otherEmailDOM.removeAttr('disabled');
			saveEmailDOM.show();
			saveEmailDOM.live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/settings/email/update', {
					email : otherEmailDOM.val(),
					id    : otherEmailId
				}, function(isSuccessful) {
					if (isSuccessful) {
						saveEmailDOM.hide();
						editEmailDOM.show();
						otherEmailDOM.attr('disabled', 'disabled');
					} else {
						alert('Invalid email address');
					}
				});
			});
		}
	}
})();

$(document).ready(function() {
	EF_SETTINGS.init();
});