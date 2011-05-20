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
			$('#updateEvent_date').datepicker();
			$('#updateEvent_deadline').datepicker();
			$('#updateEvent_title').focus();
		},
		
		openUpdateEvent: function(eid) {
			this.init();
			$('#updateEvent_submit').live('click', this.updateEventSubmit);
			
			$.get(EFGLOBAL.baseUrl + '/event/edit', {
				eventId: eid.split('-')[1]
			}, function(eventInfo) {
				eventInfo = eval('(' + eventInfo + ')');
				$('#updateEvent_title').val(eventInfo.title);
				$('#updateEvent_description').val(eventInfo.description);
				$('#updateEvent_address').val(eventInfo.location_address);
				$('#updateEvent_date').val(eventInfo.event_datetime.split(' ')[0]);
				$('#updateEvent_time').val(eventInfo.event_datetime.split(' ')[1]);
				$('#updateEvent_deadline').val(eventInfo.event_deadline);
				$('#updateEvent_min_spots').val(eventInfo.min_spot);
				$('#updateEvent_max_spots').val(eventInfo.max_spot);
				$('#updateEvent_cost').val(eventInfo.cost);
				$('#updateEvent_gets').val(eventInfo.gets);
				if (eventInfo.is_public === "1") {
					$('#updateEvent_ispublic_yes').attr('checked', 'checked');
				} else {
					$('#updateEvent_ispublic_no').attr('checked', 'checked');
				}
				$('#updateEvent_url').val(eventInfo.url);
				
				UPDATE_FILE_UPLOADER.init();
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