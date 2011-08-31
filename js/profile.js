/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var PROFILE = (function() {
	return {
		init: function() {
			$('#follow_host').live('click', function() {
				var userId = $(this).attr('href').substring(1);
				$.post(EFGLOBAL.baseUrl + '/user/follow', {
					fid: userId
				}, PROFILE.isFollow);
			});
		}, 
		
		isFollow: function(response) {
			if (response == 0) {
				$('#follow_button').html('Follow');
			} else {
				$('#follow_button').html('Unfollow');
			}
		}
	}
})();

$(document).ready(function() {
	PROFILE.init();
});