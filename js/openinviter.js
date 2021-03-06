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
		listFilter: function (form, list) {
		    // create and add the filter form to the header
		    var input = form.find("input"),
		    	initVal = input.val();
		    
		    var previous;
		    
		    input.focus(function() {
		    	previous = $(this).val();
		    	$(this).val('');
		    });
		    
		    input.focusout(function() {
		    	if ( $(this).val() == '' )
		    		input.val(previous);
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
				FB.api('', {"ids": requestIds.to.join(',') }, function(requestInfo) {
					for (requestId in requestInfo) {
						$.post(EFGLOBAL.baseUrl + '/fb/invite', {
							request_id: requestIds.request,
							to_fbid: requestInfo[requestId].id
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
	OPENINVITER.listFilter($("#search-guestlist"), $("#attendee-list"));
	OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
	
	// Gmail & Yahoo Importer
	$('#oi_import').live('click', function() {
		var emailProvider = $('#oi_email').val().split("@");
		
		if (typeof(emailProvider[1]) != 'undefined') {
			emailProvider = emailProvider[1].split(".");
			
			$.post(EFGLOBAL.baseUrl + '/guest/inviter', {
				oi_provider: emailProvider[0],
				oi_email: $('#oi_email').val(),
				oi_pass: $('#oi_pass').val()
			}, function(contactListPage) {
					if(contactListPage=="Invalid username/password" || contactListPage == 'Invalid service provider')
					{
						var errorMsg= "<span style='color:#c00; font-size:16px;margin-left:160px;'>"+contactListPage+"</span>" 
						$('#oi_container').html(errorMsg);
					}else
					{
						$('#oi_container').html(contactListPage).ready(function() {
							if (contactListPage != 'Invalid username/password' &&
								contactListPage != 'Invalid service provider') {
								OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
								$('#import_form_container').css('display', 'block');
								$('#oi_logo').html('<img src="' + EFGLOBAL.baseUrl + '/images/' + emailProvider[0] + '_logo.png" style="float:left" />');
								filterRecord();
								//$('#contacts-list').find('input').attr('checked', 'checked');
							} else {
								// Do notification
							}
						});
				 }
			});
		} else {
			alert('Invalid email address');
		}
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
				message: $('#fb-message').html(),
				to: fbids
			}, OPENINVITER.fbRequestCallback);
		} else {
			$('#submit_create_guests').trigger('click');
		}
	});
	
});

// used for filter record.
function filterRecord(){
		var  emailProvider = $('#oi_provider_filter').val();
		$("#filterSearch").keyup(function(e){
			if(e.keyCode == '13'){
			 $.post(EFGLOBAL.baseUrl + '/guest/inviterFilter', {
					oi_provider:$('#oi_provider_filter').val(),
					oi_email: $('#oi_email_filter').val(),
					oi_pass: $('#oi_pass_filter').val(),oi_filter: $("#filterSearch").val()
				}, function(contactListPage) {
						if(contactListPage=="No record match")
						{
							var errorMsg= "<span style='color:#c00; font-size:16px; display:block; margin:20px 0 10px; text-align:center;'>"+contactListPage+"</span>" 
							$('#contacts-list-filter').html(errorMsg);
							$('#import_form_container').css('display', 'none');
						}else
						{
							$('#contacts-list-filter').html(contactListPage).ready(function() {
								if (contactListPage != 'Invalid username/password' &&
										contactListPage != 'Invalid service provider') {
										OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
									//$('#import_form_container').css('display', 'block');
									$('#oi_logo').html('<img src="' + EFGLOBAL.baseUrl + '/images/' + emailProvider + '_logo.png" style="float:left" />');
									//$('#contacts-list').find('input').attr('checked', 'checked');
								} else {
									// Do notification
								}
							});
					 }
				});
			
			}
		});
	}
	
	function disableInviteButtton(){
		$('#import_form_container').css('display', 'none');
	}
	
	function enableInviteButtton(){
		$('#import_form_container').css('display', 'block');
	}