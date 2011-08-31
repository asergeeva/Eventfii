/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var EF_CONTACTS = (function() {
	return {
		init: function() {
			CONTACTS_UPLOADER.init();
			OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
		}
	}
})();

$(document).ready(function() {
	EF_CONTACTS.init();
});