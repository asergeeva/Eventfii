/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var EF_CONTACTS = (function() {
	return {
		init: function() {
			CSV_UPLOADER.init();
			$('#contacts-list').find('input').attr('checked', 'checked');
		}
	}
})();

$(document).ready(function() {
	EF_CONTACTS.init();
});