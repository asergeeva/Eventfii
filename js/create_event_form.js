/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var CREATE_FILE_UPLOADER = (function() {
	return {
		init: function() {
			var eventUrlDOM = $('#event_url_create').val();
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

var GUEST_INVITE_FILE_UPLOADER_CREATE = (function() {
	return {
		init: function() {
			var eventUrlDOM = $('#event_url_create').val();
			if (eventUrlDOM !== undefined) {
				var eventUrlToken = eventUrlDOM.split('/'),
						eid = eventUrlToken[eventUrlToken.length - 1];
				
				var uploader = new qq.FileUploader({
					// pass the dom node (ex. $(selector)[0] for jQuery users)
					element: $('#guest-invite-file-uploader-create')[0],
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
	$('#event_create').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/submit', {
			title: 				$('#event_title_create').val(),
			description:	$('#event_description_create').val(),
			address: 			$('#event_address_create').val(),
			date: 				$('#event_date_create').val(),
			time:					$('#event_time_create').val(),
			deadline: 		$('#event_deadline_create').val(),
			goal:     		$('#event_goal_create').val(),
			gets: 				$('#event_gets_create').val(),
			is_public: 		$('input:radio[name=event_ispublic_create]:checked').val(),
			url:					$('#event_url_create').val(),
			guest_email:  $('#guest_email').val()
		}, CREATE_EVENT_FORM.createEventSubmit);
		$('#middle').html(EFGLOBAL.ajaxLoader);
 });
 
 return {
	 init: function() {
			$('#event_date_create').datepicker();
			$('#event_deadline_create').datepicker();
			$('#event_title_create').focus();
			CREATE_FILE_UPLOADER.init();
			GUEST_INVITE_FILE_UPLOADER_CREATE.init();
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