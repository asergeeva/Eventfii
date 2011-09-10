/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 *
 * Documentation:
 *		- tinySort: http://tinysort.sjeiti.com/#usage
 */
var MANAGE_EVENT = ( function() {
	var _isAsc = true,
		_maxTextChar = 135,
		_curTextMessage = '';
	
	return {
		init: function() {
			$('#automatic_email_event_date').datepicker();
			$('#automatic_text_send_date').datepicker();
			
			// Sort by RSVP
			$('#head-rsvp').live('click', function() {
				if (!_isAsc) {
					$('ul#attendee-list>li').tsort('em[title]',{attr:'title'});
					_isAsc = true;
				} else {
					$('ul#attendee-list>li').tsort('em[title]',{attr:'title', order: 'desc'});
					_isAsc = false;
				}
			});
			
			// Sort by name
			$('#head-name').live('click', function() {
				if (!_isAsc) {
					$('ul#attendee-list>li').tsort('strong[title]',{attr:'title'});
					_isAsc = true;
				} else {
					$('ul#attendee-list>li').tsort('strong[title]',{attr:'title', order: 'desc'});
					_isAsc = false;
				}
			});
			
			// EMAIL
			$('#send_email_reminder').live('click', function() {
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
						$('#notification-message').html(EFGLOBAL.isSucceed);
					} else {
 						$('#notification-message').html(retval);
 					}
 					$('#notification-box').fadeIn('slow');
				});
			});
			
			// TEXT
			$('#send_text_reminder').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/text/send', {
					autoReminder: $('#automatic_text_send_cb').attr('checked'),
					reminderDate: $('#automatic_text_event_date').val(),
					reminderTime: $('#automatic_text_send_time').val(),
					reminderRecipient: $('#text-to option:selected').val(),
					reminderContent: $('#text-message').val(),
					eid: $('#event_id').html()
				}, function(retval) {
					if(retval=="Success") {
						$('#notification-message').html(EFGLOBAL.isSucceed);
					} else {
						$('#notification-message').html(retval);
					}
					$('#notification-box').fadeIn('slow');
				});
			});
			
			$('.event_attendees').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/checkin', {
					'checkin': this.checked, 
					'guestId': this.value.split('_')[1],
					'eventId': this.value.split('_')[2]
				});
			});
			
			
			$('#text-message').keyup(function() {
				if (_curTextMessage.length <= _maxTextChar) {
					$('#character-count').html(_maxTextChar - _curTextMessage.length);
					_curTextMessage = $('#text-message').val();
				} else {
					$('#text-message').val(_curTextMessage);
				}
			});
		}
	}
})();

$(document).ready(function() {
	MANAGE_EVENT.init();
	CSV_UPLOADER.init();
});