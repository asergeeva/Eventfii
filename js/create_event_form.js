/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var CREATE_EVENT_FORM = (function() {
	/*
	$('#event_create_submit').live('click', function() {
		$('#event_create').html(EFGLOBAL.ajaxLoader);
		$.post(EFGLOBAL.baseUrl + '/event/create', {
			title:          $('#event_title_create').val(),
			description:	  $('#event_description_create').val(),
			address: 			  $('#addresspicker').val(),
			date: 				  $('#event_date_create').val(),
			time:				    $('#event_time_create').val(),
			deadline: 			$('#event_deadline_create').val(),
			goal:     			$('#event_goal_create').val(),
			type:				    $('#event_type_create option:selected').val(),
			is_public: 			$('input:radio[name=event_ispublic_create]:checked').val(),
			url:					  $('#event_url_create').val()
		}, CREATE_EVENT_FORM.createEventSubmit);
		$('#container').html(EFGLOBAL.ajaxLoader);
	});*/
 
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
			// OPENINVITER EMAIL PROVIDER
			/*
			OPENINVITER.init();
			$('.event_invite_oi').live('click', function() {
				$('#update_event_form').html(EFGLOBAL.ajaxLoader);
				$.get(EFGLOBAL.baseUrl + '/event/edit/guest/inviter', {
					provider: this.href.split('#')[1]
				}, function(providerLoginPage) {
					$('#add_guest_right').html(providerLoginPage);
				});
			});
			
			$("a[rel]").overlay();
			$("img[rel]").overlay();
			*/
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
});
