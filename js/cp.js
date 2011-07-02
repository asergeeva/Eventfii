/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
$(document).ready(function() {
	$("img[rel]").overlay();
	CREATE_EVENT_FORM.init();
	CP_EVENT.init();
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
					$('#add_guest_right').html(contactListPage);
				});
			});
			$('#add_import_contact_list').live('click', function() {
				var selected_contacts = $('input:checkbox.selected_contact:checked'),
						guest_email = '',
						i = 0;
				for (i = 0; i < selected_contacts.length; ++i) {
					guest_email += selected_contacts[i].value;
					if (i < selected_contacts.length - 1) {
						guest_email += ',';
					}
				}
				$('#guest_email').val(guest_email);
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
			
			OPENINVITER.init();
			
			// EDIT EVENT
			$('#event_update').live('click', this.updateEventSubmit);
			
			// INVITE GUESTS
			// SAVE BUTTON
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
			
			// OPENINVITER EMAIL PROVIDER
			$('.event_invite_oi').live('click', function() {
				$('#update_event_form').html(EFGLOBAL.ajaxLoader);
				$.get(EFGLOBAL.baseUrl + '/event/edit/guest/inviter', {
					provider: this.href.split('#')[1]
				}, function(providerLoginPage) {
					$('#add_guest_right').html(providerLoginPage);
				});
			});
			
			
			// ON EVENT
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
			
			// EMAIL SETTINGS
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
				}, function(retval) {
				if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
				else
					$('#reminder_status').html(retval);
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
				}, function(retval) {
					if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
					else
					$('#reminder_status').html(retval);
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
				}, function(retval) {
					if(retval=="Success")
					$('#followup_status').html(EFGLOBAL.isSucceed);
					else
					$('#followup_status').html(retval);
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
				}, function(retval) {
					if(retval=="Success")
					$('#followup_status').html(EFGLOBAL.isSucceed);
					else
					$('#followup_status').html(retval);
				});
			});
		},
		
		openUpdateEvent: function(eid) {
			$('#update_event_overlay_eventid').html(eid.split('-')[1]);
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
					IMAGE_UPLOADER.init($('#update_event_overlay_eventid').html(), 'update-file-uploader');
					$('#event_date_update').datepicker();
					$('#event_deadline_update').datepicker();
					$('#event_title_update').focus();
				});
			});
		},
		
		addGuest: function() {
			$.get(EFGLOBAL.baseUrl + '/event/edit/guest', {
				eventId: $('#update_event_overlay_eventid').html(),
				prevPage: $('#update_event_guest_invite').parent().attr('href').substring(1)
			}, function(addGuestPage) {
				$('#manage_event_container').html(addGuestPage).ready(function() {
					CSV_UPLOADER.init($('#update_event_overlay_eventid').html(), 'guest-invite-file-uploader-update');
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
			$.get(EFGLOBAL.baseUrl + '/event/email', {
				eventId: $('#update_event_overlay_eventid').html()
			}, function(emailSettingPage) {
				$('#manage_event_container').html(emailSettingPage).ready(function() {
					$('#manage_event_email_tabs').tabs();
					$('#reminder_auto_send_date').datepicker();
					$('#followup_auto_send_date').datepicker();
					$('#attendance_auto_send_date').datepicker();
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
				if(eid.length<=0)
					eid=-1;
				//alert(eid);
					
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