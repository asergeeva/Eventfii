/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var MANAGE_EVENT = ( function() {
	return {
		init: function() {
			$('#automatic_email_event_date').datepicker();
			$('#automatic_text_send_date').datepicker();
			
			// EMAIL
			$('#send_email_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/email/send', {
					autoReminder: $('#automatic_email_send_cb').attr('checked'),
					reminderDate: $('#automatic_email_event_date').val(),
					reminderTime: $('#automatic_email_send_time option:selected').val(),
					reminderRecipient: $('#email-to option:selected').val(),
					reminderSubject: $('#subject').html().trim(),
					reminderContent: $('#message').val(),
					isFollowup: $('#is_followup').val(),
					eid: $('#event_id').html()
				}, function(retval) {
					if(retval=="Success") {
						$('#reminder_status').html(EFGLOBAL.isSucceed);
					} else {
 						$('#reminder_status').html(retval);
 					}
				});
			});
			
			// TEXT
			$('#send_text_reminder').live('click', function() {
				$('#reminder_status').html(EFGLOBAL.ajaxLoader);
				$.post(EFGLOBAL.baseUrl + '/event/text/send', {
					autoReminder: $('#automatic_text_send_cb').attr('checked'),
					reminderDate: $('#automatic_text_event_date').val(),
					reminderTime: $('#automatic_text_send_time').val(),
					reminderRecipient: $('#text-to option:selected').val(),
					reminderContent: $('#text-message').val(),
					eid: $('#event_id').html()
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