var EF_CONTACTS = (function() {
	return {
		init: function() {
			CONTACTS_UPLOADER.init();
		}
	}
})();

$(document).ready(function() {
	EF_CONTACTS.init();
});