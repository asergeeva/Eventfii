/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
var UPDATE_FILE_UPLOADER = (function() {
	return {
		init: function() {
			var eventUrlDOM = $('#updateEvent_url').val();
			if (eventUrlDOM !== undefined) {
				var eventUrlToken = eventUrlDOM.split('/'),
						eid = eventUrlToken[eventUrlToken.length - 1];
				
				var uploader = new qq.FileUploader({
					// pass the dom node (ex. $(selector)[0] for jQuery users)
					element: $('#update-file-uploader')[0],
					// path to server-side upload script
					action: EFGLOBAL.baseUrl + '/event/image/upload',
					// additional data
					params: {eventId: eid}
				});
			}
		}
	}
})();

$(document).ready(function() {
	$("img[rel]").overlay();
	CREATE_EVENT_FORM.init();
});

var CP_EVENT = (function() {
	return {
		init: function() {
			$('#update_event_manage').live('click', this.manageEvent);
			$('#update_event_edit').live('click', this.editEvent);
		},
		
		openUpdateEvent: function(eid) {
			this.init();
			this.manageEvent(eid.split('-')[1]);
		},
		
		manageEvent: function(eid) {
			if ($('#event_title').find('span').attr('id') === undefined) {
				var eventUrl = $('#updateEvent_url').val().split('/');
				eid = eventUrl[eventUrl.length - 1];
			} else if (typeof eid === 'object') {
				eid = $('#event_title').find('span').attr('id').split('-')[1];
			}
			$.get(EFGLOBAL.baseUrl + '/event/manage', {
				eventId: eid
			}, function(manageEventPage) {
				$('#manage_event_container').html(manageEventPage);
			});
		},
		
		editEvent: function(eid) {
			if (typeof eid === 'object') {
				eid = $('#event_title').find('span').attr('id').split('-')[1];
			}
			
			$.get(EFGLOBAL.baseUrl + '/event/edit', {
				eventId: eid
			}, function(editEventPage) {
				$('#manage_event_container').html(editEventPage).ready(function() {
					$('#updateEvent_date').datepicker();
					$('#updateEvent_deadline').datepicker();
					$('#updateEvent_title').focus();
					$('#updateEvent_submit').live('click', CP_EVENT.updateEventSubmit);
					UPDATE_FILE_UPLOADER.init();
				});
			});
		},
		
		updateEventSubmit: function() {
			var urlToken = $('#updateEvent_url').val().split('/'),
					eid = urlToken[urlToken.length - 1];
					
			$.post(EFGLOBAL.baseUrl + '/event/update', {
				title: 				$('#updateEvent_title').val(),
				description:	$('#updateEvent_description').val(),
				address: 			$('#updateEvent_address').val(),
				date: 				$('#updateEvent_date').val(),
				time:					$('#updateEvent_time').val(),
				deadline: 		$('#updateEvent_deadline').val(),
				min_spot: 		$('#updateEvent_min_spots').val(),
				max_spot: 		$('#updateEvent_max_spots').val(),
				cost: 				$('#updateEvent_cost').val(),
				gets: 				$('#updateEvent_gets').val(),
				eo_bio: 			$('#updateEvent_bio').val(),
				is_public: 		$('input:radio[name=updateEvent_ispublic]:checked').val(),
				url:					$('#updateEvent_url').val(),
				eventId:      eid
			}, CP_EVENT.updateCP);
			$('#update_event_form').html(EFGLOBAL.ajaxLoader);
		}, 
		
		updateCP: function(updatedContainer) {
			$('#container').html(updatedContainer).ready(function() {
				$("img[rel]").overlay();
			});
		},
		
		collectPaymentEvent: function(eid) {
			$.post(EFGLOBAL.baseUrl + '/payment/collect', {
				eventId: eid.split('-')[1],
				receiver_email: $('#paypal_email').val()
			}, this.collectPaymentEventCB);
		},
		
		collectPaymentEventCB: function(createdEventContainer) {
			$('#created_events').html(createdEventContainer);
		},
		
		updateProfile: function(uid) {
			$.post(EFGLOBAL.baseUrl + '/user/profile/update', {
				paypal_email: $('#paypal_email').val()
			}, this.updateProfileCB);
			$('#user_profile').html(EFGLOBAL.ajaxLoader);
		},
		
		updateProfileCB: function(profileContainer) {
			$('#user_profile').html(profileContainer);
		}
	}
})();