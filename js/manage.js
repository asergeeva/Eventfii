var MANAGE_EVENT = ( function() {
	return {
		init: function() {
			$('#automatic_email_event_date').datepicker();
			$('#automatic_text_send_date').datepicker();
			
			AnyTime.picker(
				"#automatic_email_send_time",
							{ format: "%W, %M %D in the Year %z %E", firstDOW: 1 },
				 $("#automatic_email_send_time").AnyTime_picker(
							{ 
						format: "%I:%i %p", labelTitle: "What Time",
							labelHour: "Hour", labelMinute: "Minute"
						}
				));
				
			AnyTime.picker(
				"#automatic_text_send_time",
							{ format: "%W, %M %D in the Year %z %E", firstDOW: 1 },
				 $("#automatic_text_send_time").AnyTime_picker(
							{ 
						format: "%I:%i %p", labelTitle: "What Time",
							labelHour: "Hour", labelMinute: "Minute"
						}
				));
				
			AnyTime.picker(
				"#event_time_update",
							{ format: "%W, %M %D in the Year %z %E", firstDOW: 1 },
				 $("#event_time_update").AnyTime_picker(
							{ 
						format: "%I:%i %p", labelTitle: "What Time",
							labelHour: "Hour", labelMinute: "Minute"
						}
				));
			
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
					reminderDate: $('#automatic_email_event_date').val(),
					reminderTime: $('#automatic_email_send_time').val(),
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
					reminderDate: $('#automatic_email_event_date').val(),
					reminderTime: $('#automatic_email_send_time').val(),
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
					reminderDate: $('#automatic_text_send_date').val(),
					reminderTime: $('#automatic_text_send_time').val(),
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
					reminderDate: $('#automatic_email_event_date').val(),
					reminderTime: $('#automatic_text_send_time').val(),
					reminderRecipient: $('#text-to option:selected').val(),
					reminderContent: $('#text-message').val()
				}, function(retval) {
					if(retval=="Success")
					$('#reminder_status').html(EFGLOBAL.isSucceed);
					else
					$('#reminder_status').html(retval);
				});
			});
			
			$('.event_attendees').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/checkin', {
					'checkin': this.checked, 
					'guestId': this.value.split('_')[1],
					'eventId': this.value.split('_')[2]
				});
			});
		}
	}
})();

$(document).ready(function() {
	MANAGE_EVENT.init();
});