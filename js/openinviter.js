/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var OPENINVITER = (function() {
	return {
		init: function() {
			$('#oi_import').live('click', function() {
				$.post(EFGLOBAL.baseUrl + '/event/edit/guest/inviter', {
					oi_provider: $('#oi_provider').val(),
					oi_email: $('#oi_email').val(),
					oi_pass: $('#oi_pass').val()
				}, function(contactListPage) {
					$('#add_guest_right').html(contactListPage);
				});
			});
			$('#add_import_contact_list').live('click', function() {
				var selected_contacts = $('input:checkbox.selected_contact:checked'),
						guest_email = '',
						i = 0;
				for (i = 0; i < selected_contacts.length; ++i) {
					guest_email += selected_contacts[i].value;
					if (i < selected_contacts.length - 1) {
						guest_email += ',';
					}
				}
				$('#guest_email').val(guest_email);
			});
		}
	}
}());