/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var EVENT = (function() {
	return {
		init: function() {
			$('#event_attending_response input:enabled').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/attend', {
						eid: $('#event-id').html(),
						conf: $('input:radio[name=event_attending_response]:checked').val()
				}, function(resultPage) {
					$('#event_attending_response li').removeClass();
					$('#event_attending_response input:checked').parent('li').addClass('selected');
					$('#response_stat_msg').html(EFGLOBAL.attendSucceed);
				});
				$('#response_stat_msg').html(EFGLOBAL.ajaxLoader);
			});
			
			FB.Event.subscribe('message.send', function(response) {
				console.log(response);
			});
		}
	}
})();

$(document).ready(function() {
	EVENT.init();
	$("#event_attending_response input:disabled").parent("label").click( function() {
		$("#log-in").fadeIn(500);
	});
	$("#log-in .popup-close a").click( function() {
		$("#log-in").fadeOut(500);
	});
});