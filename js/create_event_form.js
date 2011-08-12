/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 TrueRSVP Inc. 
 * All rights reserved
 */
var CREATE_EVENT_FORM = (function() {
	$('#invite_guest_submit').live('click', function() {
		if( $('.btn-update').length == 0 ) {
			$('#create_new_event').trigger('click');
		} else if ( $('.btn-update').length > 0 && $('#invite_guest_click_counter').val() == '1' ) {
			$('#invite_guest_click_counter').val('0');
		} else {
			$('#create_new_event').trigger('click');
		}
		$('#event_guest_invite_overlay').find('a').trigger('click');
	});
 
	return {
		init: function() {
			$('#event_date_create').datepicker();
			$('#event_deadline_create').datepicker();
			$('#event_title_create').focus();
			if ($('#csv_upload') !== undefined && $('#eventid').length > 0) {
				CSV_UPLOADER.init('csv_upload');
			}
	 },
	 
	 createEventSubmit: function(loginForm) {
			$errArr=loginForm.split(",");
				if ( $errArr[5] == 2 )
					$('#titleErr').html("Title can only contain spaces, characters A-Z or numbers 0-9 and can be of length between 5 and 100.");
				else if ( $errArr[5] == 3 )
					$('#titleErr').html("Please enter an event title");
				else
					$('#titleErr').html("");
					
				if ( $errArr[0] == 1 )
					$('#pubErr').html("Please select event permission.");
				else
					$('#pubErr').html("");
					
				if ( $errArr[6] == 1 )
					$('#descErr').html("Description can only contain spaces, characters A-Z or numbers 0-9 and can be of length between 25 and 500.");
				else
					$('#descErr').html("");
					
				if ( $errArr[4] == 1 )
					$('#addrErr').html("Please enter a valid address.An address can only contain spaces, characters A-Z, numbers 0-9 and symbols -*,@&");
				else
					$('#addrErr').html("");
					
				if ( $errArr[3] == 2 )
					$('#dtErr').html("Please enter a valid date in mm/dd/yyyy format.");
				else if ( $errArr[3] == 3 )
					$('#dtErr').html("Event date should be a date in the future.");
				else
					$('#dtErr').html("");
					
				if ( $errArr[1] == 1 )
					$('#timeErr').html("Please enter a time in 12 hour clock format");
				else
					$('#timeErr').html("");
					
				if ( $errArr[8] == 1 )
					$('#eventErr').html("Please select an event type.");
				else
					$('#eventErr').html("");	
					
					
				if ( $errArr[7] == 1 )
					$('#goalErr').html("Please enter an attendance goal between 1 and 1000000.");
				else
					$('#goalErr').html("");
					
				if ( $errArr[2] == 2 )
					$('#deadlineErr').html("Please enter a valid date in mm/dd/yyyy format.");
				else if ( $errArr[2] == 3 )
					$('#deadlineErr').html("Deadline date cannot be greater than the event date.");
				else
					$('#deadlineErr').html("");
					
				if ( loginForm.length > 18 ) {
					//window.location = EFGLOBAL.baseUrl;
				}
			}
		}
	}
)();

$(document).ready( function() {
	CREATE_EVENT_FORM.init();
	AnyTime.picker(
	"#event_time_create",
				{ format: "%W, %M %D in the Year %z %E", firstDOW: 1 },
	 $("#event_time_create").AnyTime_picker(
				{ 
			format: "%I:%i %p", labelTitle: "What Time",
				labelHour: "Hour", labelMinute: "Minute"
			}
	));
	$( "#event_address_create" ).addresspicker();
});
