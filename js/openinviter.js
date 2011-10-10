/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var OPENINVITER = (function() {
	// SEARCH CONTACT
	// custom css expression for a case-insensitive contains()
	jQuery.expr[':'].Contains = function(a,i,m){
	  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	};
	
	// OPENINVITER EMAIL PROVIDER
	$('.event_invite_oi').live('click', function() {
		$('#oi_container').html(EFGLOBAL.ajaxLoader);
		var emailProvider = this.href.split('#')[1];
		
		$.get(EFGLOBAL.baseUrl + '/guest/inviter', {
			provider: emailProvider
		}, function(providerLoginPage) {
			$('#oi_container').html(providerLoginPage).ready(function() {
				OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
			});
		});
	});
	
	$('#guest_facebook_add').live('click', function() {
		FB.ui({method: 'apprequests',
			title: "Invitation to " + $('#manage-title').html(),
			message: "Hello, I'm inviting you to the event that I created, " + $('#manage-title').html() + ", at trueRSVP!",
			data: 'event-' + $('#event-id').html()
		}, OPENINVITER.fbRequestCallback);
	});
	
	return {
		listFilter: function (header, list) {
		    // create and add the filter form to the header
		    var initVal = "Search by name",
		    	form = $("<form>").attr({"class":"filterform","action":"#"}),
		        input = $("<input>").attr({"class":"filterinput","type":"text","value":initVal});
		    $(form).append(input).appendTo(header);
		    
		    input.focus(function() {
		    	$(this).val('');
		    });
		    
		    input.focusout(function() {
		    	input.val(initVal);
		    });
		 
		    $(input)
		      .change( function () {
		        var filter = $(this).val();
		        if(filter && filter !== initVal) {
		          // this finds all links in a list that contain the input,
		          // and hide the ones not containing the input while showing the ones that do
		          $(list).find("label:not(:Contains(" + filter + "))").parent().hide();
		          $(list).find("label:Contains(" + filter + ")").parent().show();
		        } else {
		          $(list).find("li").show();
		        }
		        return false;
		      })
		    .keyup( function () {
		        // fire the above change event after every letter
		        $(this).change();
		    });
		},
		
		fbRequestCallback: function(requestIds) {
			if (requestIds != null) {
				var i,
					requestInfo;
				FB.api('', {"ids": requestIds.request_ids.join(',') }, function(requestInfo) {
					for (requestId in requestInfo) {
						$.post(EFGLOBAL.baseUrl + '/fb/invite', {
							request_id: requestId,
							from_fbid: requestInfo[requestId].from.id,
							to_fbid: requestInfo[requestId].to.id,
							data: requestInfo[requestId].data
						}, function() {
							$('#submit_create_guests').trigger('click');
						});
					}
					$('#fb-notification-box').fadeIn();
				});
			}
		}
	}
}());

$(document).ready(function() {
	// Search for add contacts
	OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
	OPENINVITER.listFilter($("#attendee-header"), $("#attendee-list"));
	
	// Gmail & Yahoo Importer
	$('#oi_import').live('click', function() {
		var emailProvider = $('#oi_email').val().split("@");
		emailProvider = emailProvider[1].split(".");
		
		$.post(EFGLOBAL.baseUrl + '/guest/inviter', {
			oi_provider: emailProvider[0],
			oi_email: $('#oi_email').val(),
			oi_pass: $('#oi_pass').val()
		}, function(contactListPage) {
			$('#oi_container').html(contactListPage).ready(function() {
				OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
				$('#import_form_container').css('display', 'block');
				$('#oi_logo').html('<img src="' + EFGLOBAL.baseUrl + '/images/' + emailProvider[0] + '_logo.png" style="float:left" />');
			});
		});
	});
	
	// Before submitting, add it to the text area first
	$('#add_import_contact_list').live('click', function() {
		var selected_contacts = $('input:checkbox.selected_contact:checked'),
				guest_email = [],
				fb_ids = [],
				entered_email,
				merged_email = '',
				i;
		for (i = 0; i < selected_contacts.length; ++i) {
			if (typeof selected_contacts[i].value != 'undefined') {
				if ($(selected_contacts[i]).hasClass('contact-email')) {
					guest_email[selected_contacts[i].value] = 1;
				} else {
					fb_ids.push(selected_contacts[i].value);
				}
			}
		}
		
		entered_email = $('#emails-hidden').val().split(',');
		for (i = 0; i < entered_email.length; ++i) {
			if (entered_email[i] && guest_email[entered_email[i]] != 1) {
				guest_email[entered_email[i]] = 1;
			}
		}
		
		for (i in guest_email) {
			if (i && typeof i != 'undefined') {
				merged_email += (i + ',');
			}
		}
			
		$('#emails-hidden').val(merged_email.substring(0, merged_email.length - 1));
		
		if (fb_ids.length > 0) {
			var fbids = fb_ids.join(',');
			FB.ui({method: 'apprequests',
				message: 'My Great Request',
				to: fbids
			}, OPENINVITER.fbRequestCallback);
		} else {
			$('#submit_create_guests').trigger('click');
		}
	});
});