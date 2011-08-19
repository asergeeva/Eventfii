/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
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
					$('#response_stat_msg').html(EFGLOBAL.attendSucceed);
				});
				$('#response_stat_msg').html(EFGLOBAL.ajaxLoader);
			});
		}
	}
})();

$(document).ready(function() {
	EVENT.init();
	$("#event_attending_response input:disabled").parent("label").click( function() {
		$("#log-in").fadeIn(500);
		return false;
	});
	$("#log-in .popup-close a").click( function() {
		$("#log-in").fadeOut(500);
		return false;
	});
	
	$("#all-guests").click( function() {
		$("#see-all").fadeIn(500);
		return false;
	});
	$("#see-all .popup-close a").click( function() {
		$("#see-all").fadeOut(500);
		return false;
	});
});