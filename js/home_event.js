/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
$(document).ready(function() {
	var title_init = $("#title").val();
	var goal_init = $("#goal").val();
	var email_init = $("#email").val();
	

	$("input[type=text]").focus(function() {
		if ( $(this).val() == title_init ) { $("#title").val(''); return; }
		if ( $(this).val() == goal_init ) { $("#goal").val(''); return; }
		if ( $(this).val() == email_init ) { $("#email").val(''); return; }		
	});

	$("input[type=text]").focusout(function() {
		if ( $("#title").val() == '' ) { $("#title").val(title_init); }
		if ( $("#goal").val() == '' ) { $("#goal").val(goal_init); }
		if ( $("#email").val() == '' ) { $("#email").val(email_init); }
	});
	
	$("#not_planning_yet").live('click', function() {
		$('#notyet_container').fadeOut('slow');
		$.post(EFGLOBAL.baseUrl + '/notyet', {
			email: $("#email").val()
		}, function(response) {
			$('#notyet_container').html(response).fadeIn('slow');
		});
	});
});
