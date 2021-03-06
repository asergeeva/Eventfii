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
		
	$('#cancel-event').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/manage/cancel', {
			eventId: $('#event-id').html()
		}, function(responseText) {
			$('#notification-message').html(responseText);
			$('#notification-box').fadeIn('slow');
		});
	});
	
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
					reminderSubject: $('#subject').val(),
					reminderContent: $('#message').val(),
					isFollowup: $('#is_followup').val(),
					eid: $('#event-id').html()
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
					eid: $('#event-id').html()
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
			//$('#contacts-list').find('input').attr('checked', 'checked');
			
			
			$('#text-message').keyup(function() {
				if (_curTextMessage.length <= _maxTextChar) {
					$('#character-count').html(_maxTextChar - _curTextMessage.length);
					_curTextMessage = $('#text-message').val();
				} else {
					$('#text-message').val(_curTextMessage);
				}
			});
			
			/* SELECT CONTACTS ALL or NONE */
			$('#select_contact_all').live('click', function() {
				$('#contacts-list').find('input').attr('checked', 'checked');
			});
			
			$('#select_contact_none').live('click', function() {
				$('#contacts-list').find('input').removeAttr('checked');
			});
		}
	}
})();

$(document).ready(function() {
	MANAGE_EVENT.init();
	CSV_UPLOADER.init();
	
	// Accordian Function
	$(".responses dt a").click( function() {
		var link = $(this);
		
		if ( ! link.hasClass("manage-accord") )
			return false;
			
		var list = link.parent("dt").next("dd").next("dd");

		link.addClass('active');		
		link.parent("dt").siblings().find("a").removeClass("active");
		
		if ( ! list.is("animated") ) {
			list.slideToggle('slow', function() {
			  if ( ! list.is(":visible") ) {
				link.removeClass('active');
			  }
			}).siblings(".responses-extra").slideUp("slow");;
		}
		return false;
	} );
	$("#show-popup-addguest").click(function(){
		$("#popup-addguest").fadeIn(500)
		return false;	
	});
	$("#popup-addguest .popup-close a").click(function(){
		$("#popup-addguest").fadeOut(500);
		return false;
	});
});

/*Function for Marking checked In*/
function markCheckIn(eid, uid, hid, sid)
{
	$.ajax({
		type: 'POST',
		data: 'eid='+eid+'&uid='+uid,
		url: EFGLOBAL.baseUrl + '/event/markChecked',
		cache: false,
		success: function(response)
		{
			if(response)
			{
				$("#"+sid).show();
				$("#"+hid).hide();
			}	
		}
	});	
}

/*Function for UnMarking checked In*/
function unMarkCheckIn(eid, uid, hid, sid)
{
	$.ajax({
		type: 'POST',
		data: 'eid='+eid+'&uid='+uid,
		url: EFGLOBAL.baseUrl + '/event/unmarkChecked',
		cache: false,
		success: function(response)
		{
			if(response)
			{
				$("#"+sid).show();
				$("#"+hid).hide();
			}	
		}
	});		
}
var t = '';
/*Function to save Guest on the day of Event*/
function saveGuest()
{
	$("#error").html("");
	var error = true;
	var emailRegex  = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	var fname = $("#guest_fname").val();
	var lname = $("#guest_lname").val();
	var email = $("#guest_email").val();
	if(email == '' || email == 'Email')
	{
		error = false;
	}else if(email != '' && email != 'Email' && !email.match(emailRegex))
	{
		error = false;	
	}
	if(error == true)
	{
		var data = $("#guest_form").serialize();
		$.ajax({
			type: 'POST',
			data: data,
			url: EFGLOBAL.baseUrl+'/event/addGuest',
			cache: false,
			success: function(response)
			{
				$("#success").html('Guest successfully invited.');
				t = setInterval("reloadPage()", 3000);
			}
		});
	}else
	{
		$("#error").html("Please enter correct value for email.");	
	}
}

/*Function for reloading the page*/
function reloadPage()
{
	clearInterval(t);	
	window.location.reload();
}