/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var EVENT = (function() {
	return {
		init: function() {
			$('.event_attending_response').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/attend', {
						eid: $('#event_id').html(),
						conf: $('input:radio[name=event_attending_response]:checked').val()
					}, function(resultPage) {
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
});