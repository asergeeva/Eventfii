/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var FBCON = (function() {
	FB.init({
		appId  : EFGLOBAL.fbAppId,
		status : true, // check login status
		cookie : true, // enable cookies to allow the server to access the session
		xfbml  : true  // parse XFBML
	});
	
	return {		
		getLoginStatus: function() {
			FB.getLoginStatus(function(response) {
				if (response.session) {
					FBCON.onlogin();
				} else {
					
				}
			});
		},
		
		onlogin: function() {
			FB.api('/me', function(userInfo) {
				if (typeof userInfo.error == 'undefined') {
					LOGIN_FORM.fbUserLogin(userInfo);
				}
			});
		}
	}
})();