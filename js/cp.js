/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var CP_EVENT = (function() {
	return {
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
	
	$('#uploadPic').live('click',function() {
		$("input[name=file]").click();
	});
	
	FBCON.harvestFriends();
});
