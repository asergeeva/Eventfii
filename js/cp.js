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
		init: function() {
			var uploader = new qq.FileUploader({
				// pass the dom node (ex. $(selector)[0] for jQuery users)
				element: $('#guest-invite-file-uploader-update')[0],
				// path to server-side upload script
				action: EFGLOBAL.baseUrl + '/event/image/upload',
				// additional data
				params: {eventId: 'csv-'+ $('#update_event_overlay_eventid').html()}
			});
		}
	}
})();

$(document).ready(function() {
	$("img[rel]").overlay();
	CREATE_EVENT_FORM.init();
});

var OPENINVITER = (function() {
	return {
		init: function() {
			$('#oi_import').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/edit/guest/inviter', {
					oi_provider: $('#oi_provider').val(),
					oi_email: $('#oi_email').val(),
					oi_pass: $('#oi_pass').val()
				}, function(contactListPage) {
					$('#add_guest_right').html(contactListPage).ready(function() {
						$('.selected_contact').live('click', function() {
							if ($(this).attr('checked') === true) {
								$('#guest_email').val($('#guest_email').val() + ', ' + $(this).val());
							}
						});
					});
				});
			});
		}
	}
}());

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
				$('#manage_event_container').html(manageEventPage).ready(function() {
					$('#update_event_preview').attr('href', EFGLOBAL.baseUrl + '/event/' + $('#update_event_overlay_eventid').html());
				});
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
						$.post(EFGLOBAL.baseUrl + '/event/edit/guest/save', {
							eventId: $('#update_event_overlay_eventid').html(),
							guest_email: $('#guest_email').val()
						});
						if ($('#update_guest_prevpage').html() == 'manage') {
							CP_EVENT.manageEvent();
						} else if ($('#update_guest_prevpage').html() == 'update') {
							CP_EVENT.editEvent();
						}
					});
					
					// EMAIL INVITER PROVIDER
					$('.event_invite_oi').live('click', function() {
						$('#update_event_form').html(EFGLOBAL.ajaxLoader);
						$.get(EFGLOBAL.baseUrl + '/event/edit/guest/inviter', {
							provider: this.href.split('#')[1]
						}, function(providerLoginPage) {
							$('#add_guest_right').html(providerLoginPage).ready(function() {
								OPENINVITER.init();
							});
						});
					});
				});
			});
		},
		
		onEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/on', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(manageEventOnPage) {
				$('#manage_event_container').html(manageEventOnPage).ready(function() {
					$('.event_attendee_cb').live('click', function() {
						$.post(EFGLOBAL.baseUrl + '/event/checkin', {
							checkin: this.checked,
							guestId: $(this).val().split('_')[1],
							eventId: $(this).val().split('_')[2]
						});
					});
					$('#print_guest').live('click', function() {
						window.open(EFGLOBAL.baseUrl + '/event/print?eventId=' + $('#update_event_overlay_eventid').html(), 'Print');
					});
				});
			});
		},
		
		emailSettings: function() {
			$.get(EFGLOBAL.baseUrl + '/event/email', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(emailSettingPage) {
				$('#manage_event_container').html(emailSettingPage).ready(function() {
					$('#manage_event_email_tabs').tabs();
					$('#reminder_auto_send_date').datepicker();
					$('#followup_auto_send_date').datepicker();
					$('#attendance_auto_send_date').datepicker();
					
					// AUTO-SEND CHECKBOX
					$('#reminder_auto_send_cb').live('click', function() {
						$.post(EFGLOBAL.baseUrl + '/event/manage/email/autosend', {
							eventId: $('#update_event_overlay_eventid').html(),
							autoSend: $('#reminder_auto_send_cb').attr('checked'),
							type: EFGLOBAL.emailReminderType
						});
					});
					
					$('#followup_auto_send_cb').live('click', function() {
						$.post(EFGLOBAL.baseUrl + '/event/manage/email/autosend', {
							eventId: $('#update_event_overlay_eventid').html(),
							autoSend: $('#followup_auto_send_cb').attr('checked'),
							type: EFGLOBAL.emailFollowupType
						});
					});
					
					// REMINDER
					$('#save_reminder').live('click', function() {
						$('#reminder_status').html(EFGLOBAL.ajaxLoader);
						$.post(EFGLOBAL.baseUrl + '/event/manage/email/save', {
							eventId: $('#update_event_overlay_eventid').html(),
							autoReminder: $('#reminder_auto_send_cb').attr('checked'),
							reminderDate: $('#reminder_auto_send_date').val(),
							reminderTime: $('#reminder_auto_send_time option:selected').val(),
							reminderRecipient: $('#reminder_recipient option:selected').val(),
							reminderSubject: $('#reminder_message_subject').val(),
							reminderContent: $('#reminder_message_text').val(),
							type: EFGLOBAL.emailReminderType
						}, function() {
							$('#reminder_status').html(EFGLOBAL.isSucceed);
						});
					});
					$('#send_reminder').live('click', function() {
						$('#reminder_status').html(EFGLOBAL.ajaxLoader);
						$.post(EFGLOBAL.baseUrl + '/event/manage/email/send', {
							eventId: $('#update_event_overlay_eventid').html(),
							autoReminder: $('#reminder_auto_send_cb').attr('checked'),
							reminderDate: $('#reminder_auto_send_date').val(),
							reminderTime: $('#reminder_auto_send_time option:selected').val(),
							reminderRecipient: $('#reminder_recipient option:selected').val(),
							reminderSubject: $('#reminder_message_subject').val(),
							reminderContent: $('#reminder_message_text').val()
						}, function() {
							$('#reminder_status').html(EFGLOBAL.isSucceed);
						});
					});
					
					// FOLLOW-UP
					$('#save_followup').live('click', function() {
						$('#followup_status').html(EFGLOBAL.ajaxLoader);
						$.post(EFGLOBAL.baseUrl + '/event/manage/email/save', {
							eventId: $('#update_event_overlay_eventid').html(),
							autoReminder: $('#followup_auto_send_cb').attr('checked'),
							reminderDate: $('#followup_auto_send_date').val(),
							reminderTime: $('#followup_auto_send_time option:selected').val(),
							reminderRecipient: $('#followup_recipient option:selected').val(),
							reminderSubject: $('#followup_message_subject').val(),
							reminderContent: $('#followup_message_text').val(),
							type: EFGLOBAL.emailFollowupType
						}, function() {
							$('#followup_status').html(EFGLOBAL.isSucceed);
						});
					});
					$('#send_followup').live('click', function() {
						$('#followup_status').html(EFGLOBAL.ajaxLoader);
						$.post(EFGLOBAL.baseUrl + '/event/manage/email/send', {
							eventId: $('#update_event_overlay_eventid').html(),
							autoReminder: $('#followup_auto_send_cb').attr('checked'),
							reminderDate: $('#followup_auto_send_date').val(),
							reminderTime: $('#followup_auto_send_time option:selected').val(),
							reminderRecipient: $('#followup_recipient option:selected').val(),
							reminderSubject: $('#followup_message_subject').val(),
							reminderContent: $('#followup_message_text').val()
						}, function() {
							$('#followup_status').html(EFGLOBAL.isSucceed);
						});
					});
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