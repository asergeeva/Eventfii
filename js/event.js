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
		if(confidence >= 35)
		{
			$(this).children("label").addClass('selected');
			$(this).children("label").children('input').attr('checked', 'checked');
		}
	});
	/*$("#event_attending_response input:disabled").parent("label").click(function() {
		if (!$('#rsvp_days_left').hasClass('loggedIn')) {
			$.post(EFGLOBAL.baseUrl + '/event/attend/attempt', {
					eid: $('#event-id').text(),
					conf: $(this).children("input").val()
			});
			$("#log-in").fadeIn(500);		
		}
		return false;
	});*/
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
		if(confidence >= 35)
		{
			if (!$('#event_attending_response').hasClass('noMore')) {
				var id_s = '1';
				if(!$('#rsvp_days_left').hasClass('loggedIn'))
				{
					id_s = '1';	
				}
				saveRsvps(id_s);
			}
			else
			{
				$("#rsvp-multiple").fadeIn(500);
				$("#error").html('');
			}
		}
		return false;
	});
	$("#rsvp-multiple .popup-close a").click(function() {
		$("#rsvp-multiple").fadeOut(500);
		return false;
	});

	});
})(jQuery);

function showInviteFieldsEdit(total_fields)
{
	var total_fields_present = $("#total_guests").val();
	var html = '';
	if(total_fields > total_fields_present)
	{
		for(i=(parseInt(total_fields_present)+1);i<=total_fields;i++)
		{
			html += '<div class="clear5" id="clear_'+i+'"></div>';
			html += '<div class="c_lft" id="guest_id_'+i+'">Guest #'+i+':</div>';
			html += '<div class="c_rgt" id="guest_info_'+i+'"><input type="text" name="guest_name_'+i+'" value="Name" onfocus="$(this).val(\'\');" id="guest_name_'+i+'" /> <input type="text" name="guest_email_'+i+'" id="guest_email_'+i+'" value="Email" onfocus="$(this).val(\'\');" /></div>';
		}
		if($("#guest_info_"+total_fields_present).length == 0)
		{
			$("#showInviteList").html(html);
		}else
		{
			$("#guest_info_"+total_fields_present).after(html);
		}
		$("#total_guests").val(total_fields);
	}
	else if(total_fields < total_fields_present)
	{
		for(i=(parseInt(total_fields_present));i>=(parseInt(total_fields)+1);i--)
		{
			$("#clear_"+i).remove();
			$("#guest_id_"+i).remove();
			$("#guest_info_"+i).remove();
		}
		$("#total_guests").val(total_fields);
	}else
	{
		return false;	
	}
}

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
var t = "";
function saveRsvps(type, up_type)
{
	$("#error").html('');
	var emailRegex  = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	var error = true;
	var total_guests_invite = $("#total_rsvps :selected").val();
	for(i=1;i<=total_guests_invite;i++)
	{
		var name = $("#guest_name_"+i).val();
		var email = $("#guest_email_"+i).val();
		if(name != '' && name != 'Name')
		{
			if(email == '' || email == 'Email')
			{
				error = false;
			}else if(!email.match(emailRegex))
			{
				error = false;	
			}else
			{
				error = true;	
			}
		}
	}
	if(error == false)
	{
		$("#error").html('Please enter correct value for email.');
	}else
	{
		$("#error").html('');
		if(type == 1)
		{
			$.ajax({
				type: 'POST',
				url: EFGLOBAL.baseUrl +'/event/saversvp',
				data: $("#guests_form").serialize()+"&conf="+confidence,
				success: function(response)
				{
					if(typeof(up_type) != 'undefined')
					{
						$("#notification-box-update").show();
					}
					else
					{
						$("#notification-box").show();
					}
					$("#rsvp-multiple").fadeOut(500);
					t = setInterval("reloadPage()", 5000);	
				}	
			});
		}else
		{
			$.ajax({
				type: 'POST',
				url: EFGLOBAL.baseUrl +'/event/saversvplogout',
				data: $("#guests_form").serialize()+"&conf="+confidence,
				success: function(response)
				{
					$("#rsvp-multiple").fadeOut(500);
					$("#log-in").fadeIn(500);	
				}
			});	
		}
	}
}
function reloadPage()
{
	clearInterval(t);
	window.location.reload();
}