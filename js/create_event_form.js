/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 TrueRSVP Inc. 
 * All rights reserved
 */
 
var CREATE_EVENT_FORM = (function() {
	return {
		init: function() {
			$('#end-date').click( function() {
				$(this).fadeOut(0);
				var date = $('#date').val()
				$('#end_date').val(date);
				var time = $('select[name="time"] option:selected').val();
				$('select[name="end_time"]').val(time).attr("selected", "selected");
				$('#add-end-time-title').fadeIn(500);
				$('#add-end-time').fadeIn(500);
				return false;
			});
			
			$('#date').blur( function() {
				if ( $('#deadline').val() == "" ) {
					var event_date = $('#date').val();
					$('#deadline').val(event_date);
				}
			});
			
			$('#title').focus();
			$('#date').datepicker();
			$('#end_date').datepicker();
			$('#deadline').datepicker();
			
			$( "#address" ).addresspicker();
			
			CSV_UPLOADER.init();
		}
	 }
})();

$(document).ready(function() {
	CREATE_EVENT_FORM.init();
	
	var title_init = "Name of Event";
	var description_init = "What should your guests know?";
	var location_init = "Ex: Jim's House";
	var address_init = "Ex: 1234 Maple St, Los Angeles, CA 90007";
	var goal_init = "In # of Attendees";
	
	if ( $("#location").val() == '' ) { 
		$("#location").val(location_init); 
		$("#location").addClass("default"); 
	}
	
	$("input[type=text]").focus(function() {
		if ( $(this).val() == title_init ) { 
			$(this).val(''); 
			$(this).removeClass("default");
			return; 
		}
		if ( $(this).val() == location_init ) { 
			$(this).val(''); 
			$(this).removeClass("default");
			return; 
		}	
		if ( $(this).val() == address_init ) { 
			$(this).val(''); 
			$(this).removeClass("default");
			return; 
		}		
		if ( $(this).val() == goal_init ) { 
			$(this).val(''); 
			$(this).removeClass("default");
			return; 
		}		
	});
	$("textarea").focus(function() {
		if ( $(this).val() == description_init ) { 
			$(this).val(''); 
			$(this).removeClass("default");
			return; 
		}
	});

	$("input[type=text]").focusout(function() {
		if ( $("#title").val() == '' ) { 
			$(this).val(title_init);
			$(this).addClass("default"); 
		}
		if ( $("#location").val() == '' ) { 
			$(this).val(location_init); 
			$(this).addClass("default"); 
		}
		if ( $("#address").val() == '' ) { 
			$(this).val(address_init); 
			$(this).addClass("default"); 
		}
		if ( $("#goal").val() == '' ) { 
			$(this).val(goal_init); 
			$(this).addClass("default"); 
		}
	});
	$("textarea").focusout(function() {
		if ( $("#description").val() == '' ) { 
			$(this).val(description_init); 
			$(this).addClass("default"); 			
		}
	});
});

// Title
var title = new LiveValidation('title', { validMessage: " ", wait: 1000 });
title.add(Validate.Length, { minimum: 5, tooShortMessage: "Title must be at least 5 characers."});
var empty_title = new LiveValidation( 'title', {validMessage: " ", onlyOnSubmit: true } );
empty_title.add( Validate.Presence );
empty_title.add( Validate.Exclusion, { within: ['Name of Event'], failureMessage: "Can't be empty!" } );

// Details
var details = new LiveValidation('description', { validMessage: " ", wait: 1000 } );
details.add(Validate.Length, { minimum: 5, tooShortMessage: "Title must be at least 5 characers."});
var empty_details = new LiveValidation( 'description', {validMessage: " ", onlyOnSubmit: true } );
empty_details.add( Validate.Presence );
empty_details.add( Validate.Exclusion, { within: ['What should your guests know?'], failureMessage: "Can't be empty!" } );

// Address
var empty_address = new LiveValidation('address', { validMessage: " ", onlyOnSubmit: true });
empty_address.add( Validate.Presence );
empty_address.add( Validate.Exclusion, { within: ['Ex: 1234 Maple St, Los Angeles, CA 90007'], failureMessage: "Can't be empty!" } );

// Date
var date = new LiveValidation('date', { validMessage: " ", onlyOnSubmit: true });
date.add( Validate.Presence );

// Goal
var goal = new LiveValidation('goal', { validMessage: " ", wait: 0 });
goal.add( Validate.Numericality, { onlyInteger: true } );
var empty_goal = new LiveValidation('goal', { validMessage: " ", onlyOnSubmit: true });
empty_goal.add( Validate.Presence );
empty_goal.add( Validate.Exclusion, { within: ['In # of Attendees'], failureMessage: "Can't be empty!" } );

// Deadline
var deadline = new LiveValidation('deadline', { validMessage: " ", onlyOnSubmit: true });
deadline.add( Validate.Presence );