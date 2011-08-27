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