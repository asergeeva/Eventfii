 /*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var CP_EVENT = (function() {
	return {
		init: function() {
			$('#fb-logout').live('click', function() {
				FB.logout();
			});
		},
		
		updateProfile: function(uid) {
			$.post(EFGLOBAL.baseUrl + '/user/profile/update', {
				paypal_email: $('#paypal_email').val()
			}, this.updateProfileCB);
			$('#user_profile').html(EFGLOBAL.ajaxLoader);
		},
		
		updateProfileCB: function(profileContainer) {
			$('#user_profile').html(profileContainer);
		}
	}
})();

$(document).ready(function() {
	USER_IMAGE_UPLOADER.init();
	$("img[rel]").overlay();
	$("a[rel]").overlay();
	CP_EVENT.init();
	
	$('#editBtn').click(function(){
		$('.edit').click();
		$('#editBtn').hide();
	});
	
	$('.edit').click(function(){$('#editBtn').hide();});
	$('.edit').editable('user/status/update', {
			type:'textarea',
			indicator : 'Saving...',
			tooltip   : 'Click to edit...',
			style: 'border-style: inset; border-width: 2px',
			onblur: 'submit',
			callback : function(value, settings) {
				$('#editBtn').show();
				$('#div_2').html(value);
				$('#div_2').css('left','25px');
			}
	});
    
	$('#dtls_update').live('click', function() {
			$.post(EFGLOBAL.baseUrl + '/user/profile-dtls/update',{
			email: $('#email').val(),
			cell: $('#cell').val(),
			zip: $('#zip').val()
	}, function(returnCodes) {
			var errCodes = returnCodes.split(',');
			if(errCodes[0]=='1')
				$('#email_err').html('Please enter a valid email address');
			else
				$('#email_err').html('');
			if(errCodes[2]=='1')
				$('#cell_err').html('Please enter a valid cell phone number');
			else
				$('#cell_err').html('');
			if(errCodes[1]=='1')
				$('#zip_err').html('Please enter a valid zip code');
			else
				$('#zip_err').html('');
			if(errCodes[1]=='0' && errCodes[0]=='0' && errCodes[2]=='0')
				$('#update_success').html('Updated!');
		});
	});	
	
	$('#uploadPic').live('click',function() {
		$("input[name=file]").click();
	});
});
