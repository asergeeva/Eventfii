/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

var EVENT = (function() {
	$('.rsvp-opt').live('click', function() {
		$('.rsvp-label').removeClass('selected');
		$(this).parent('label').addClass('selected');
		$.post(EFGLOBAL.baseUrl + '/event/attend', {
				eid: $('#event-id').text(),
				conf: $(this).val()
		}, function(resultPage) {
			$('#notification-message').html(resultPage);
			$('#notification-container').fadeIn('slow');
		});
		$('#response_stat_msg').html(EFGLOBAL.ajaxLoader);
	});
			
	return {

	}
})();

$(document).ready(function() {
	$("#showLoginPopup").click(function(){
		$("#log-in").fadeIn(500);
	});
	$("#event_attending_response input:disabled").parent("label").click(function() {
		if (!$('#rsvp_days_left').hasClass('loggedIn')) {
			$.post(EFGLOBAL.baseUrl + '/event/attend/attempt', {
					eid: $('#event-id').text(),
					conf: $(this).children("input").val()
			});
			$("#log-in").fadeIn(500);		
		}
		return false;
	});
	$("#log-in .popup-close a").click(function() {
		$("#log-in").fadeOut(500);
		return false;
	});
	
	$("#all-guests").click(function() {
		$("#see-all").fadeIn(500);
		var position = $("#event-attendants").position();
		var top = position.top - 150;
		$("#see-all").css("top", top + "px");
		return false;
	});
	$("#see-all .popup-close a").click(function() {
		$("#see-all").fadeOut(500);
		return false;
	});
	
	$("#event_attending_response").click(function() {
		$("#rsvp-multiple").fadeIn(500);
		return false;
	});
	$("#rsvp-multiple .popup-close a").click(function() {
		$("#rsvp-multiple").fadeOut(500);
		return false;
	});

	/* $("#create-acc .popup-close a").click( function() {
		$("#create-acc").fadeOut(500);
		return false;
	});
	$("#log-in .message a").click( function() {
		$("#log-in").fadeOut(500);
		$("#create-acc").fadeIn(500);
		return false;
	});*/
});