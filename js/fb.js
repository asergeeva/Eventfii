/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var FBCON = (function() {
	return {
		init: function() {
			FB.init({
				appId  : '122732554481304',
				status : true, // check login status
				cookie : true, // enable cookies to allow the server to access the session
				xfbml  : true  // parse XFBML
			});
		},
		
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
				LOGIN_FORM.fbUserLogin(userInfo);
			});
		}
	}
})();

FBCON.init();