/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var confidence = 0;
/*jQuery.noConflict();
(function($) { 
  $(function() {
var EVENT = (function() {
	$('#event_attending_response ol li').live('click', function() {
		$('.rsvp-label').removeClass('selected');
		$(this).parent('label').addClass('selected');
		$.post(EFGLOBAL.baseUrl + '/event/attend', {
				eid: $('#event-id').text(),
				conf: $(this).val()
		}, function(resultPage) {
			$('#notification-message').html(resultPage);
			$('#notification-container').fadeIn('slow');
		});
		$('#response_stat_msg').html(EFGLOBAL.ajaxLoader);
	});
			
	return {

	}
})();
});
})(jQuery);*/

jQuery.noConflict();
(function($) { 
  $(function() {
	$("#showLoginPopup").click(function(){
		$("#log-in").fadeIn(500);
	});
	$('#event_attending_response li').click(function(){
		$('#event_attending_response li').each(function(){
			$(this).children("label").removeClass('selected');
			$(this).children("label").children('input').removeAttr('checked');
		});
	});
	$('#event_attending_response li').click(function(){
		confidence = $(this).children("label").children('input').val();
		$(this).children("label").addClass('selected');
		$(this).children("label").children('input').attr('checked', 'checked');
	});
	$("#event_attending_response input:disabled").parent("label").click(function() {
		if (!$('#rsvp_days_left').hasClass('loggedIn')) {
			$.post(EFGLOBAL.baseUrl + '/event/attend/attempt', {
					eid: $('#event-id').text(),
					conf: $(this).children("input").val()
			});
			$("#log-in").fadeIn(500);		
		}
		return false;
	});
	$("#log-in .popup-close a").click(function() {
		$("#log-in").fadeOut(500);
		return false;
	});
	
	$("#all-guests").click(function() {
		$("#see-all").fadeIn(500);
		var position = $("#event-attendants").position();
		var top = position.top - 150;
		$("#see-all").css("top", top + "px");
		return false;
	});
	$("#see-all .popup-close a").click(function() {
		$("#see-all").fadeOut(500);
		return false;
	});
	
	$("#event_attending_response").click(function() {
		$("#rsvp-multiple").fadeIn(500);
		return false;
	});
	$("#rsvp-multiple .popup-close a").click(function() {
		$("#rsvp-multiple").fadeOut(500);
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
})(jQuery);

function showInviteFields(total_fields)
{
	var html = '';
	html += '<div class="clear5"></div>';
	html += '<div>We will e-mail your guest a reminder.</div>';
	for(i=1;i<(parseInt(total_fields)+1);i++)
	{
		html += '<div class="clear5"></div>';
		html += '<div class="c_lft">Guest #'+i+':</div>';
		html += '<div class="c_rgt"><input type="text" name="guest_name_'+i+'" value="Name" id="guest_name_'+i+'" onfocus="$(this).val(\'\');" /> <input type="text" name="guest_email_'+i+'" id="guest_email_'+i+'" value="Email" onfocus="$(this).val(\'\');" /></div>';
	}
	html += '<div class="clear5"></div>';
	$("#showInviteList").html(html);
}
function saveRsvps()
{
	var error = true;
	var total_guests_invite = $("#total_rsvps :selected").val();
	for(i=1;i<=total_guests_invite;i++)
	{
		if(($("#guest_name_"+i).val() != '' && $("#guest_name_"+i).val() != 'Name'))
		{
			if($("#guest_email_"+i).val() == '' || $("#guest_email_"+i).val() == 'Email')
			{
				error = false;			
			}
			break;
		}
	}
	if(error == false)
	{
		$("#error").html('Please enter correct value.');
	}else
	{
		$("#error").html('');
		$.ajax({
			type: 'POST',
			url: EFGLOBAL.baseUrl +'/event/saversvp',
			data: $("#guests_form").serialize()+"&conf="+confidence,
			success: function(response)
			{
				window.location.reload();
			}	
		});
	}
}