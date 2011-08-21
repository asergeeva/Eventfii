/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var TWITCON = (function() {
	return {
		init: function() {
			twttr.anywhere(function (T) {
    			T("#connect_twitter").connectButton();
  			});
		}
	}
})();

$(document).ready(function() {
	TWITCON.init();
});