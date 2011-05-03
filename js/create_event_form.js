/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
var CREATE_EVENT_FORM = (function() {
 $('#createEvent_date').datepicker();
 $('#createEvent_deadline').datepicker();
 $('#createEvent_description').focus();
	 
 $('#createEvent_submit').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/submit', {
			title: 				$('#createEvent_title').val(),
			description:	$('#createEvent_description').val(),
			address: 			$('#createEvent_address').val(),
			date: 				$('#createEvent_date').val(),
			time:					$('#createEvent_time').val(),
			deadline: 		$('#createEvent_deadline').val(),
			min_spot: 		$('#createEvent_min_spots').val(),
			max_spot: 		$('#createEvent_max_spots').val(),
			cost: 				$('#createEvent_cost').val(),
			gets: 				$('#createEvent_gets').val(),
			eo_bio: 			$('#createEvent_bio').val(),
			is_public: 		$('input:radio[name=createEvent_ispublic]:checked').val(),
			url:					$('#createEvent_url').val()
		}, CREATE_EVENT_FORM.createEventSubmit);
		$('#middle').html(EFGLOBAL.ajaxLoader);
 });
 
 return {
	 init: function() {
			$('#createEvent_date').datepicker();
			$('#createEvent_deadline').datepicker();
			$('#createEvent_title').focus();
	 },
	 
	 createEventSubmit: function(loginForm) {
			$('#container').html(loginForm).ready(function() {
				$("img[rel]").overlay();
			});
	 }
 }
})();