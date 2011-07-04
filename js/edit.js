var EDIT_FORM = (function() {
	return {
		init: function() {
			$('#event_update').live('click', this.updateEventSubmit);
		},
		
		updateEventSubmit: function() {
			var urlToken = $('#event_url_update').val().split('/'),
					eid = urlToken[urlToken.length - 1];
			if (eid.length<=0) {
					eid=-1;
			}
					
			$.post(EFGLOBAL.baseUrl + '/event/update', {
				title: 				$('#event_title_update').val(),
				description:	$('#event_description_update').val(),
				address: 			$('#event_address_update').val(),
				date: 				$('#event_date_update').val(),
				time:					$('#event_time_update').val(),
				deadline: 		$('#event_deadline_update').val(),
				goal:     		$('#event_goal_update').val(),
				gets: 				$('#event_gets_update').val(),
				type:					$('#event_type_update option:selected').val(),
				is_public: 		$('input:radio[name=event_ispublic_update]:checked').val(),
				url:					$('#event_url_update').val(),
				eventId:      eid
			}, CP_EVENT.updateCP);
			$('#update_event_form').html(EFGLOBAL.ajaxLoader);
		}, 
	}
	
	IMAGE_UPLOADER.init($('#manage_event_id').html(), 'update-file-uploader');
	$('#event_date_update').datepicker();
	$('#event_deadline_update').datepicker();
	$('#event_title_update').focus();
	$("img[rel]").overlay();
	$("a[rel]").overlay();
})();

$(document).ready(function() {
	EDIT_FORM.init();
});