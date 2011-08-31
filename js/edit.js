/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var EDIT_FORM = (function() {
	return {
		init: function() {
			$('#event_update').live('click', this.updateEventSubmit);
			$('#update_event_guest_invite').live('click', this.onInviteClick);
			$('#event_date_update').datepicker();
			$('#event_deadline_update').datepicker();
			$('#event_title_update').focus();
			$("img[rel]").overlay();
			$("a[rel]").overlay();
		},
		
		updateCP: function(updatedContainer) {
			$('#container').html(updatedContainer).ready(function() {
				$("a[rel]").overlay();
			});
		},
		
		onInviteClick: function() {
			$('#invite_guest_click_counter').val('1');
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
			}, EDIT_FORM.updateCP);
			$('#update_event_form').html(EFGLOBAL.ajaxLoader);
		}, 
	}
})();

$(document).ready(function() {
	EDIT_FORM.init();
});