/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
$(document).ready(function() {
	var TXT_INIT = $('input#create_event_textarea').val();
	$('input#create_event_textarea').focus(function() {
		if ($('input#create_event_textarea').val() === TXT_INIT) {
  			$('input#create_event_textarea').val('');
		}
	});
	
	$('input#create_event_textarea').focusout(function() {
		if ($('input#create_event_textarea').val() === '') {
  			$('input#create_event_textarea').val(TXT_INIT);
		}
	});
});