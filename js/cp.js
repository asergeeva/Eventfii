/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
var UPDATE_FILE_UPLOADER = (function() {
	return {
		init: function() {
			var eventUrlDOM = $('#event_url_update').val();
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

var GUEST_INVITE_FILE_UPLOADER_UPDATE = (function() {
	return {
		init: function(curPage) {
			var eventUrlDOM = $('#event_url_update').val();
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

$(document).ready(function() {
	$("img[rel]").overlay();
	CREATE_EVENT_FORM.init();
});

var CP_EVENT = (function() {
	return {
		init: function() {
			$('#update_event_manage').live('click', this.manageEvent);
			$('#update_event_edit').live('click', this.editEvent);
			
			$('#update_event_before').live('click', this.manageEvent);
			$('#update_event_on').live('click', this.onEvent);
			$('#update_event_after').live('click', this.afterEvent);
			
			$('#update_event_guest_invite').live('click', this.addGuest);
			$('#email_settings').live('click', this.emailSettings);
		},
		
		openUpdateEvent: function(eid) {
			$('#update_event_overlay_eventid').html(eid.split('-')[1]);
			this.init();
			this.manageEvent();
		},
		
		manageEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(manageEventPage) {
				$('#manage_event_container').html(manageEventPage);
			});
		},
		
		editEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/edit', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(editEventPage) {
				$('#manage_event_container').html(editEventPage).ready(function() {
					UPDATE_FILE_UPLOADER.init();
					$('#event_date_update').datepicker();
					$('#event_deadline_update').datepicker();
					$('#event_title_update').focus();
					$('#event_update').live('click', CP_EVENT.updateEventSubmit);
				});
			});
		},
		
		addGuest: function() {
			$.get(EFGLOBAL.baseUrl + '/event/edit/guest', {
				eventId: $('#update_event_overlay_eventid').html(),
				prevPage: $('#update_event_guest_invite').parent().attr('href').substring(1)
			}, function(addGuestPage) {
				$('#manage_event_container').html(addGuestPage).ready(function() {
					GUEST_INVITE_FILE_UPLOADER_UPDATE.init();
					$('#invite_guest_update').live('click', function() {
						if ($('#update_guest_prevpage').html() == 'manage') {
							CP_EVENT.manageEvent();
						} else if ($('#update_guest_prevpage').html() == 'update') {
							CP_EVENT.editEvent();
						}
					});
				});
			});
		},
		
		onEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/on', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(manageEventOnPage) {
				$('#manage_event_container').html(manageEventOnPage);
			});
		},
		
		emailSettings: function() {
			$.get(EFGLOBAL.baseUrl + '/event/email', function(emailSettingPage) {
				$('#manage_event_container').html(emailSettingPage).ready(function() {
					$('#manage_event_email_tabs').tabs();
				});
			});
		},
		
		afterEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/after', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(manageEventAfterPage) {
				$('#manage_event_container').html(manageEventAfterPage);
			});
		},
		
		updateEventSubmit: function() {
			var urlToken = $('#event_url_update').val().split('/'),
					eid = urlToken[urlToken.length - 1];
					
			$.post(EFGLOBAL.baseUrl + '/event/update', {
				title: 				$('#event_title_update').val(),
				description:	$('#event_description_update').val(),
				address: 			$('#event_address_update').val(),
				date: 				$('#event_date_update').val(),
				time:					$('#event_time_update').val(),
				deadline: 		$('#event_deadline_update').val(),
				goal:     		$('#event_goal_update').val(),
				gets: 				$('#event_gets_update').val(),
				is_public: 		$('input:radio[name=event_ispublic_update]:checked').val(),
				url:					$('#event_url_update').val(),
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