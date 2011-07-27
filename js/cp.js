 /*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var CP_EVENT = (function() {
	return {
		init: function() {
			$('#update_event_manage').live('click', this.manageEvent);
			$('#update_event_edit').live('click', this.editEvent);
			
			$('#update_event_before').live('click', this.beforeEvent);
			$('#update_event_on').live('click', this.onEvent);
			$('#update_event_after').live('click', this.afterEvent);
		
			$('#email_settings_top').live('click', this.emailSettings);
			$('#fb-logout').live('click', function() {
				FB.logout();
			});
		},
		
		openUpdateEvent: function(eid) {
			$('#manage_event_id').html(eid.split('-')[1]);
			this.manageEvent();
		},
		
		manageEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventPage) {
				$('#private').html(manageEventPage).ready(function() {
					$('#update_event_preview').attr('href', EFGLOBAL.baseUrl + '/event/' + $('#manage_event_id').html());
				});
			});
		},
		
		editEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/edit/save', {
				eventId: $('#manage_event_id').html()
			}, function(editEventPage) {
				$('#private').html(editEventPage).ready(function() {
					IMAGE_UPLOADER.init($('#manage_event_id').html(), 'update-file-uploader');
					$('#event_date_update').datepicker();
					$('#event_deadline_update').datepicker();
					$('#event_title_update').focus();
				});
			});
		},
		
		beforeEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/before', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventOnPage) {
				$('#private').html(manageEventOnPage);
			});
		},
		
		onEvent: function() {
			$('#email_settings_bottom').live('click', function() {
				$('#email_settings_top').trigger('click');
			});
			$.get(EFGLOBAL.baseUrl + '/event/manage/on', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventOnPage) {
				$('#private').html(manageEventOnPage);
			});
		},
		
		emailSettings: function() {
			$.get(EFGLOBAL.baseUrl + '/event/email', {
				eventId: $('#manage_event_id').html()
			}, function(emailSettingPage) {
				$('#private').html(emailSettingPage).ready(function() {
					$('#manage_event_email_tabs').tabs();
					$('#reminder_auto_send_date').datepicker();
					$('#followup_auto_send_date').datepicker();
					$('#attendance_auto_send_date').datepicker();
				});
			});
		},
		
		afterEvent: function() {
			$.get(EFGLOBAL.baseUrl + '/event/manage/after', {
				eventId: $('#manage_event_id').html()
			}, function(manageEventAfterPage) {
				$('#private').html(manageEventAfterPage);
			});
		},
		
		collectPaymentEvent: function(eid) {
			$.post(EFGLOBAL.baseUrl + '/payment/collect', {
				eventId: eid.split('-')[1],
				receiver_email: $('#paypal_email').val()
			}, this.collectPaymentEventCB);
		},
		
		collectPaymentEventCB: function(createdEventContainer) {
			$('#created_events').html(createdEventContainer);
		},
		
		updateProfile: function(uid) {
			$.post(EFGLOBAL.baseUrl + '/user/profile/update', {
				paypal_email: $('#paypal_email').val()
			}, this.updateProfileCB);
			$('#user_profile').html(EFGLOBAL.ajaxLoader);
		},
		
		updateProfileCB: function(profileContainer) {
			$('#user_profile').html(profileContainer);
		}
	}
	
	// INITIALIZATIONS
	// SAVE BUTTON
	$('#invite_guest_update').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/edit/guest/save', {
			eventId: $('#manage_event_id').html(),
			guest_email: $('#guest_email').val()
		});
		if ($('#update_guest_prevpage').html() == 'manage') {
			this.manageEvent();
		} else if ($('#update_guest_prevpage').html() == 'update') {
			this.editEvent();
		}
	});
	
	// OPENINVITER EMAIL PROVIDER
	OPENINVITER.init();
	$('.event_invite_oi').live('click', function() {
		$('#update_event_form').html(EFGLOBAL.ajaxLoader);
		$.get(EFGLOBAL.baseUrl + '/event/edit/guest/inviter', {
			provider: this.href.split('#')[1]
		}, function(providerLoginPage) {
			$('#add_guest_right').html(providerLoginPage);
		});
	});
	
	
	// ON EVENT
	$('.event_attendee_cb').live('click', function() {
		$.post(EFGLOBAL.baseUrl + '/event/checkin', {
			checkin: this.checked,
			guestId: $(this).val().split('_')[1],
			eventId: $(this).val().split('_')[2]
		});
	});
	$('#print_guest').live('click', function() {
		window.open(EFGLOBAL.baseUrl + '/event/print?eventId=' + $('#manage_event_id').html(), 'Print');
	});
})();

$(document).ready(function() {
	USER_IMAGE_UPLOADER.init();
	$("img[rel]").overlay();
	$("a[rel]").overlay();
	CP_EVENT.init();
	
	$('#editBtn').click(function(){
		$('.edit').click();
		$('#editBtn').hide();
	});
	
	$('.edit').click(function(){$('#editBtn').hide();});
	$('.edit').editable('user/status/update', {
			type:'textarea',
			indicator : 'Saving...',
			tooltip   : 'Click to edit...',
			style: 'border-style: inset; border-width: 2px',
			onblur: 'submit',
			callback : function(value, settings) {
				$('#editBtn').show();
				$('#div_2').html(value);
				$('#div_2').css('left','25px');
			}
	});
    
	$('#dtls_update').live('click', function() {
			$.post(EFGLOBAL.baseUrl + '/user/profile-dtls/update',{
			email: $('#email').val(),
			cell: $('#cell').val(),
			zip: $('#zip').val()
	}, function(returnCodes) {
			var errCodes = returnCodes.split(',');
			if(errCodes[0]=='1')
				$('#email_err').html('Please enter a valid email address');
			else
				$('#email_err').html('');
			if(errCodes[2]=='1')
				$('#cell_err').html('Please enter a valid cell phone number');
			else
				$('#cell_err').html('');
			if(errCodes[1]=='1')
				$('#zip_err').html('Please enter a valid zip code');
			else
				$('#zip_err').html('');
			if(errCodes[1]=='0' && errCodes[0]=='0' && errCodes[2]=='0')
				$('#update_success').html('Updated!');
		});
	});	
	
	$('#uploadPic').live('click',function() {
		$("input[name=file]").click();
	});
});
