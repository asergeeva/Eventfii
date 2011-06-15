/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var CREATE_FILE_UPLOADER = (function() {
	return {
		init: function() {
			var eventUrlDOM = $('#createEvent_url').val();
			if (eventUrlDOM !== undefined) {
				var eventUrlToken = eventUrlDOM.split('/'),
						eid = eventUrlToken[eventUrlToken.length - 1];
				
				var uploader = new qq.FileUploader({
					// pass the dom node (ex. $(selector)[0] for jQuery users)
					element: $('#create-file-uploader')[0],
					// path to server-side upload script
					action: EFGLOBAL.baseUrl + '/event/image/upload',
					// additional data
					params: {eventId: eid}
				});
			}
		}
	}
})();

var GUEST_INVITE_FILE_UPLOADER = (function() {
	return {
		init: function() {
			var eventUrlDOM = $('#createEvent_url').val();
			if (eventUrlDOM !== undefined) {
				var eventUrlToken = eventUrlDOM.split('/'),
						eid = eventUrlToken[eventUrlToken.length - 1];
				
				var uploader = new qq.FileUploader({
					// pass the dom node (ex. $(selector)[0] for jQuery users)
					element: $('#guest-invite-file-uploader')[0],
					// path to server-side upload script
					action: EFGLOBAL.baseUrl + '/event/image/upload',
					// additional data
					params: {eventId: 'csv-'+ eid}
				});
			}
		}
	}
})();
 
var CREATE_EVENT_FORM = (function() {
	$('#createEvent_submit').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/submit', {
			title: 				$('#createEvent_title').val(),
			description:	$('#createEvent_description').val(),
			address: 			$('#createEvent_address').val(),
			date: 				$('#createEvent_date').val(),
			time:					$('#createEvent_time').val(),
			deadline: 		$('#createEvent_deadline').val(),
			goal:     		$('#createEvent_goal').val(),
			cost: 				$('#createEvent_cost').val(),
			gets: 				$('#createEvent_gets').val(),
			is_public: 		$('input:radio[name=createEvent_ispublic]:checked').val(),
			image:        $('#createEvent_picture').val(),
			url:					$('#createEvent_url').val(),
			guest_email:  $('#guest_email').val()
		}, CREATE_EVENT_FORM.createEventSubmit);
		$('#middle').html(EFGLOBAL.ajaxLoader);
 });
 
 return {
	 init: function() {
			$('#createEvent_date').datepicker();
			$('#createEvent_deadline').datepicker();
			$('#createEvent_title').focus();
			CREATE_FILE_UPLOADER.init();
			GUEST_INVITE_FILE_UPLOADER.init();
			$("img[rel]").overlay();
			
			$('#invite_guest_submit').live('click', function() {
				$('#event_guest_invite_overlay').find('a').trigger('click');
				$('#create_new_event').trigger('click');
			});
	 },
	 
	 createEventSubmit: function(loginForm) {
			$('#container').html(loginForm).ready(function() {
				$("img[rel]").overlay();
			});
	 }
 }
})();

$(document).ready(function() {
	CREATE_FILE_UPLOADER.init();
	CREATE_EVENT_FORM.init();
});