/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var CP_EVENT = (function() {
	return {
		init: function() {
			$('#update_event_manage').live('click', this.manageEvent);
			$('#update_event_edit').live('click', this.editEvent);
			
			$('#update_event_before').live('click', this.beforeEvent);
			$('#update_event_on').live('click', this.onEvent);
			$('#update_event_after').live('click', this.afterEvent);
		
			$('#email_settings_top').live('click', this.emailSettings);
			$('#fb-logout').live('click', function() {
				FB.logout();
			});
		},
		
		openUpdateEvent: function(eid) {
			$('#manage_event_id').html(eid.split('-')[1]);
			this.manageEvent();
		},
		
		manageEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventPage) {
				$('#private').html(manageEventPage).ready(function() {
					$('#update_event_preview').attr('href', EFGLOBAL.baseUrl + '/event/' + $('#manage_event_id').html());
				});
			});
		},
		
		editEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/edit/save', {
				eventId: $('#manage_event_id').html()
			}, function(editEventPage) {
				$('#private').html(editEventPage).ready(function() {
					IMAGE_UPLOADER.init($('#manage_event_id').html(), 'update-file-uploader');
					$('#event_date_update').datepicker();
					$('#event_deadline_update').datepicker();
					$('#event_title_update').focus();
				});
			});
		},
		
		beforeEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/before', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventOnPage) {
				$('#private').html(manageEventOnPage);
			});
		},
		
		onEvent: function() {
			$('#email_settings_bottom').live('click', function() {
				$('#email_settings_top').trigger('click');
			});
			$.get(EFGLOBAL.baseUrl + '/event/manage/on', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventOnPage) {
				$('#private').html(manageEventOnPage);
			});
		},
		
		emailSettings: function() {
			$.get(EFGLOBAL.baseUrl + '/event/email', {
				eventId: $('#manage_event_id').html()
			}, function(emailSettingPage) {
				$('#private').html(emailSettingPage).ready(function() {
					$('#manage_event_email_tabs').tabs();
					$('#reminder_auto_send_date').datepicker();
					$('#followup_auto_send_date').datepicker();
					$('#attendance_auto_send_date').datepicker();
				});
			});
		},
		
		afterEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/after', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventAfterPage) {
				$('#private').html(manageEventAfterPage);
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
	
	// INITIALIZATIONS
	// SAVE BUTTON
	$('#invite_guest_update').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/edit/guest/save', {
			eventId: $('#manage_event_id').html(),
			guest_email: $('#guest_email').val()
		});
		if ($('#update_guest_prevpage').html() == 'manage') {
			this.manageEvent();
		} else if ($('#update_guest_prevpage').html() == 'update') {
			this.editEvent();
		}
	});
	
	// OPENINVITER EMAIL PROVIDER
	OPENINVITER.init();
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
		window.open(EFGLOBAL.baseUrl + '/event/print?eventId=' + $('#manage_event_id').html(), 'Print');
	});
	
	// EMAIL SETTINGS
	// AUTO-SEND CHECKBOX
	$('#reminder_auto_send_cb').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/manage/email/autosend', {
			eventId: $('#manage_event_id').html(),
			autoSend: $('#reminder_auto_send_cb').attr('checked'),
			type: EFGLOBAL.emailReminderType
		});
	});
	
	$('#followup_auto_send_cb').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/manage/email/autosend', {
			eventId: $('#manage_event_id').html(),
			autoSend: $('#followup_auto_send_cb').attr('checked'),
			type: EFGLOBAL.emailFollowupType
		});
	});
	
	// REMINDER
	$('#save_reminder').live('click', function() {
		$('#reminder_status').html(EFGLOBAL.ajaxLoader);
		$.post(EFGLOBAL.baseUrl + '/event/manage/email/save', {
			eventId: $('#manage_event_id').html(),
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
			eventId: $('#manage_event_id').html(),
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
			eventId: $('#manage_event_id').html(),
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
			eventId: $('#manage_event_id').html(),
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
})();

$(document).ready(function() {
	//IMAGE_UPLOADER.init($('#create_event_eventid').html());
	USER_IMAGE_UPLOADER.init();
	$("img[rel]").overlay();
	$("a[rel]").overlay();
	CREATE_EVENT_FORM.init();
	CP_EVENT.init();
	MANAGE_EVENT.init();
});