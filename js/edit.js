var EDIT_FORM = (function() {
	return {
		init: function() {
			IMAGE_UPLOADER.init($('#manage_event_id').html(), 'update-file-uploader');
			$('#event_date_update').datepicker();
			$('#event_deadline_update').datepicker();
			$('#event_title_update').focus();
		}
	}
})();

$(document).ready(function() {
	EDIT_FORM.init();
});