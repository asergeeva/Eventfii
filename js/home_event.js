/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
$(document).ready(function() {
	var title_init = $("#title").val();
	var goal_init = $("#goal").val();

	$("input[type=text]").focus(function() {
		if ( $(this).val() == title_init ) {
			$("#title").val('');
			return;
		}
		if ( $(this).val() == goal_init ) {
			$("#goal").val('');
			return;
		}
	});

	$("input[type=text]").focusout(function() {
		if ( $("#title").val() == '' ) {
			$("#title").val(title_init);
		}
		if ( $("#goal").val() == '' ) {
			$("#goal").val(goal_init);
		}
	});
});
