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
				xfbml  : true,  // parse XFBML
				frictionlessRequests : true,
				oauth: true
			});
		},
		
		loginUser: function(sessionInfo) {
			if (sessionInfo.authResponse) {
				FB.api('/me', function(userInfo) {
					if (typeof userInfo.error == 'undefined') {
						// If Facebook session exists, and we send request from manage guest page.
						var req_uri = '';
						if(typeof($("#req_uri").val()) != 'undefined')
						{
							req_uri = 'fbimport';
						}
						// pass extra param 
						LOGIN_FORM.fbUserLogin(userInfo, sessionInfo, req_uri);
					}
				});
			} else {
				console.log('FB session is unavailable');
			}
		},
		
		onlogin: function() {
			FB.getLoginStatus(this.loginUser);
		},
		
		harvestFriends: function() {
			FB.getLoginStatus(function(sessionInfo) {
				if (sessionInfo.authResponse) {
					FB.api('/me/friends?access_token=' + sessionInfo.authResponse.accessToken, function(userFriends) {
						if (typeof userFriends.error == 'undefined') {
							$.post(EFGLOBAL.baseUrl + '/fb/friends', {
								fbFriends: JSON.stringify(userFriends)
							});
						}
					});
				} else {
					
				}
			});
		}
	}
	
	FBCON.fbInit();
})();