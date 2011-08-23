/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var OPENINVITER = (function() {
	// OPENINVITER EMAIL PROVIDER
	$('.event_invite_oi').live('click', function() {
		$('#oi_container').html(EFGLOBAL.ajaxLoader);
		
		$.get(EFGLOBAL.baseUrl + '/guest/inviter', {
			provider: this.href.split('#')[1]
		}, function(providerLoginPage) {
			$('#oi_container').html(providerLoginPage);
		});
	});
	
	$('#oi_import').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/guest/inviter', {
			oi_provider: $('#oi_provider').val(),
			oi_email: $('#oi_email').val(),
			oi_pass: $('#oi_pass').val()
		}, function(contactListPage) {
			$('#oi_container').html(contactListPage);
		});
	});
	
	$('#add_import_contact_list').live('click', function() {
		var selected_contacts = $('input:checkbox.selected_contact:checked'),
				guest_email = [],
				entered_email,
				merged_email = '',
				i;
		for (i = 0; i < selected_contacts.length; ++i) {
			if (typeof selected_contacts[i].value != 'undefined') {
				guest_email[selected_contacts[i].value] = 1;
			}
		}
		
		entered_email = $('#emails').val().split(',');
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
	
		$('#emails').val(merged_email.substring(0, merged_email.length - 1));
	});
	
	return {

	}
}());