/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
 
var CREATE_EVENT_FORM = (function() {
	var title_init = "Name of Event",
		description_init = "What should your guests know?",
		location_init = "Ex: Jim's House",
		address_init = "Ex: 1234 Maple St, Los Angeles, CA 90007",
		goal_init = "In # of Attendees",
		twitter_init = "Ex: #TurtlesRock",
		
		_title = $('#title'),
		_description = $('#description'),
		_location = $('#location'),
		_address = $('#address'),
		_goal = $('#goal'),
		_twitter = $('#twitter');
		
	// Non-required fields init
	if (_twitter.val().trim() == '') {
		_twitter.val(twitter_init);
	}
	if (_location.val().trim() == '') {
		_location.val(location_init);
	}

	// When the cursor is focus
	_title.focus(function() {
		if ( _title.val() == title_init ) { 
			_title.val(''); 
			_title.removeClass("default");
		}
	});
	_location.focus(function() {
		if ( _location.val() == location_init ) { 
			_location.val(''); 
			_location.removeClass("default");
		}	
	});
	_address.focus(function() {
		if ( _address.val() == address_init ) { 
			_address.val(''); 
			_address.removeClass("default");
		}	
	});
	_goal.focus(function() {
		if ( _goal.val() == goal_init ) { 
			_goal.val(''); 
			_goal.removeClass("default"); 
		}
	});
	_twitter.focus(function() {
		if ( _twitter.val() == twitter_init ) { 
			_twitter.val(''); 
			_twitter.removeClass("default");
		}	
	});
	_description.focus(function() {
		if ( _description.val() == description_init ) { 
			_description.val(''); 
			_description.removeClass("default");
		}
	});


	// When the cursor is out of focus
	_description.focusout(function() {
		if ( _description.val() == '' ) { 
			_description.val(description_init); 
			_description.addClass("default"); 			
		}	
	});
	_title.focusout(function() {
		if ( _title.val() == '' ) {
			_title.val(title_init);
			_title.addClass("default"); 
		}
	});
	_location.focusout(function() {
		if ( _location.val() == '' ) { 
			_location.val(location_init); 
			_location.addClass("default"); 
		}
	});
	_address.focusout(function() {
		if ( _address.val() == '' ) { 
			_address.val(address_init); 
			_address.addClass("default"); 
		}
	});
	_goal.focusout(function() {
		if ( _goal.val() == '' ) { 
			_goal.val(goal_init); 
			_goal.addClass("default"); 
		}
	});
	_twitter.focusout(function() {
		if ( _twitter.val() == '' ) { 
			_twitter.val(twitter_init); 
			_twitter.addClass("default"); 
		}
	});
	
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
			
			// broken with datepicker
			// $('#date').focusout( function() {
			$('#time').click( function() {
				var event_date;
				if ( $('#deadline').val() == "" ) {
					event_date = $('#date').val();
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
});