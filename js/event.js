/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */

var EVENT = (function() {
	return {
		init: function() {
			$('#attend_event_confirm').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/attend', {
						uid: $('#current_user').find('a').attr('id').split('-')[1],
						eid: $('#attend_event_confirm').parent().attr('id').split('-')[1]
					}, function(resultPage) {
					$('#attend_event_form').submit();
				});
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