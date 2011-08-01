var MANAGE_EVENT = ( function() {
	return {
		init: function() {
			$('#send-automatically').datepicker();
			
			// EMAIL SETTINGS
			// AUTO-SEND CHECKBOX
			$('#automatic_email_send_cb').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/email/autosend', {
					autoSend: $('#automatic_email_send_cb').attr('checked')
				});
			});
			$('#automatic_text_send_cb').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/text/autosend', {
					autoSend: $('#automatic_text_send_cb').attr('checked')
				});
			});
			
			// EMAIL
			$('#update_email_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/email/save', {
					autoReminder: $('#automatic_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_email_send_time option:selected').val(),
					reminderTimeMid: $('#automatic_email_send_timeframe option:selected').val(),
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
			$('#send_email_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/email/send', {
					autoReminder: $('#automatic_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_email_send_time option:selected').val(),
					reminderTimeMid: $('#automatic_email_send_timeframe option:selected').val(),
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
				$.post(EFGLOBAL.baseUrl + '/event/text/save', {
					autoReminder: $('#automatic_text_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_text_send_time option:selected').val(),
					reminderTimeMid: $('#automatic_text_send_timeframe option:selected').val(),
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
				$.post(EFGLOBAL.baseUrl + '/event/text/send', {
					autoReminder: $('#automatic_text_send_cb').attr('checked'),
					reminderDate: $('#send-automatically').val(),
					reminderTime: $('#automatic_text_send_time option:selected').val(),
					reminderTimeMid: $('#automatic_text_send_timeframe option:selected').val(),
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