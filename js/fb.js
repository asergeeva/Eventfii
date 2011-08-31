/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

var FBCON = (function() {
	return {
		fbInit: function() {
			FB.init({
				appId  : EFGLOBAL.fbAppId,
				status : true, // check login status
				cookie : true, // enable cookies to allow the server to access the session
				xfbml  : true  // parse XFBML
			});
		},
		
		onlogin: function() {
			FB.getLoginStatus(function(sessionInfo) {
				if (sessionInfo.session) {
					FB.api('/me', function(userInfo) {
						if (typeof userInfo.error == 'undefined') {
							LOGIN_FORM.fbUserLogin(userInfo, sessionInfo.session);
						}
					});
				} else {
					
				}
			});
		}
	}
	
	FBCON.fbInit();
})();