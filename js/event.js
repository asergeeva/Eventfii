/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

var EVENT = (function() {
	return {
		init: function() {
			$('#event_attending_response label').live('click', function() {
				$('#event_attending_response label').removeClass('selected');
				$(this).addClass('selected');
				$.post(EFGLOBAL.baseUrl + '/event/attend', {
						eid: $('#event-id').text(),
						conf: $(this).children("input").val()
				}, function(resultPage) {
					$('#notification-message').html(resultPage);
					$('#notification-container').fadeIn('slow');
				});
				$('#response_stat_msg').html(EFGLOBAL.ajaxLoader);
			});
		}
	}
})();

$(document).ready(function() {
	EVENT.init();
	$("#event_attending_response input:disabled").parent("label").click( function() {
		$.post(EFGLOBAL.baseUrl + '/event/attend/attempt', {
				eid: $('#event-id').text(),
				conf: $(this).children("input").val()
		});
		$("#log-in").fadeIn(500);
		return false;
	});
	$("#log-in .popup-close a").click( function() {
		$("#log-in").fadeOut(500);
		return false;
	});
	
	$("#all-guests").click( function() {
		$("#see-all").fadeIn(500);
		var position = $("#event-attendants").position();
		var top = position.top - 150;
		$("#see-all").css("top", top + "px");
		return false;
	});
	$("#see-all .popup-close a").click( function() {
		$("#see-all").fadeOut(500);
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
