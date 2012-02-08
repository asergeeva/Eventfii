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
			/* SELECT CONTACTS ALL or NONE */
			$('#select_contact_all').live('click', function() {
				$('#contacts-list').find('input').attr('checked', 'checked');
			});
			
			$('#select_contact_none').live('click', function() {
				$('#contacts-list').find('input').removeAttr('checked');
			});
		}
	}
})();

$(document).ready(function() {
	EF_CONTACTS.init();
});