/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 TrueRSVP Inc. 
 * All rights reserved
 */
 
var CREATE_EVENT_FORM = (function() {
	return {
		init: function() {
			$('#end-date').click( function() {
				$(this).fadeOut(0);
				var date = $('#date').val()
				$('#end_date').val(date);
				var time = $('select[name="time"] option:selected').val();
				$('select[name="end_time"]').val(time).attr("selected", "selected");
				$('#add-end-time').fadeIn(500);
			});
			
			$('#title').focus();
			$('#date').datepicker();
			$('#end_date').datepicker();
			$('#deadline').datepicker();
		
			$( "#address" ).addresspicker();
	 }
)();

$(document).ready( function() {
	CREATE_EVENT_FORM.init();
});