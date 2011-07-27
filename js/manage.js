var MANAGE_EVENT = ( function() {
	return {
		init: function() {
			$('#cp-nav ul li').live('click', function() {
				$('.section-current').removeAttr('class');
				$(this).parent().attr('class', 'section-current');
			});
			
		  $("img[rel]").overlay();
			$("a[rel]").overlay();
			
			// EMAIL SETTINGS
			// AUTO-SEND CHECKBOX
			$('#send-automatically').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/manage/email/autosend', {
					eventId: $('#manage_event_id').html(),
					autoSend: $('#reminder_auto_send_cb').attr('checked'),
					type: EFGLOBAL.emailReminderType
				});
			});
			
			// EMAIL
			$('#update_email_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/manage/email/save', {
					autoReminder: $('#automatic_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_email_send_time option:selected').val(),
					reminderRecipient: $('#email-to option:selected').val(),
					reminderSubject: $('#subject').val(),
					reminderContent: $('#message').val(),
					type: EFGLOBAL.emailReminderType
				}, function(retval) {
				if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
				else
					$('#reminder_status').html(retval);
				});
			});
			$('#send_email_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/manage/email/send', {
					autoReminder: $('#automatic_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_email_send_time option:selected').val(),
					reminderRecipient: $('#email-to option:selected').val(),
					reminderSubject: $('#subject').val(),
					reminderContent: $('#message').val()
				}, function(retval) {
					if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
					else
					$('#reminder_status').html(retval);
				});
			});
			
			// EMAIL
			$('#update_text_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/manage/text/save', {
					autoReminder: $('#automatic_text_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_text_send_time option:selected').val(),
					reminderRecipient: $('#text-to option:selected').val(),
					reminderContent: $('#text-message').val()
				}, function(retval) {
				if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
				else
					$('#reminder_status').html(retval);
				});
			});
			$('#send_text_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/manage/text/send', {
					autoReminder: $('#automatic_text_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_text_send_time option:selected').val(),
					reminderRecipient: $('#text-to option:selected').val(),
					reminderContent: $('#text-message').val()
				}, function(retval) {
					if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
					else
					$('#reminder_status').html(retval);
				});
			});
		}
	}
})();

$(document).ready(function() {
	MANAGE_EVENT.init();
});