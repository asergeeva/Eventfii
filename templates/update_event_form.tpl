<div id="update_event_form">
<h3>Update Event</h3>
<div id="left_event_form">
	<div class="event_field">
    <h2 class="event_form_section_header">What are you planning</h2>
    <h3 class="event_form_section_subheader">Name of event</h3>
		<input type="text" name="updateEvent_title" id="updateEvent_title" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Brief description</h2>
    <h3 class="event_form_section_subheader">What is this event about?</h3>
		<input type="text" name="updateEvent_description" id="updateEvent_description" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Where</h2>
    <h3 class="event_form_section_subheader">Ex: 1234 Maple St, Los Angeles, CA 90007</h3>
		<input type="text" name="updateEvent_address" id="updateEvent_address" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">When</h2>
    <h3 class="event_form_section_subheader">Ex: 05/14/2011 (MM/DD/YYYY)</h3>
		<input type="text" name="updateEvent_date" id="updateEvent_date" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">What time</h2>
    <h3 class="event_form_section_subheader">Ex: 21:10:00 (HH:MM:SS)</h3>
		<input type="text" name="updateEvent_time" id="updateEvent_time" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Deadline to sign up</h2>
    <h3 class="event_form_section_subheader">Last day for anyone to reserve a spot</h3>
		<input type="text" name="updateEvent_deadline" id="updateEvent_deadline" />
  </div>
</div>
<div id="right_event_form">
	<div class="event_field">
  	<div class="event_field_left">
      <h2 class="event_form_section_header">Min # of spots</h2>
      <h3 class="event_form_section_subheader">Your "goal"</h3>
      <input type="text" name="updateEvent_min_spots" id="updateEvent_min_spots" />
    </div>
    <div class="event_field_right">
      <h2 class="event_form_section_header">Max # of spots</h2>
      <h3 class="event_form_section_subheader">Optional</h3>
      <input type="text" name="updateEvent_max_spots" id="updateEvent_max_spots" />
    </div>
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Cost per spot</h2>
    <h3 class="event_form_section_subheader">What each attendee pays</h3>
		<input type="text" name="updateEvent_cost" id="updateEvent_cost" />
  </div>
  <div class="event_field_ta">
    <h2 class="event_form_section_header">Each paid spot will get</h2>
    <h3 class="event_form_section_subheader">What each attendee will get once the event goes through</h3>
		<textarea name="updateEvent_gets" id="updateEvent_gets"></textarea>
  </div>
	<div class="event_field">
  	<div class="event_field_left">
      <input type="radio" name="updateEvent_ispublic" id="updateEvent_ispublic_yes" value="1" />
      <h2 class="event_form_section_header_radio">Public event</h2>
      <h3 class="event_form_section_subheader_radio">Anyone can sign up</h3>
    </div>
    <div class="event_field_right">
      <input type="radio" name="updateEvent_ispublic" id="updateEvent_ispublic_no" value="0" />
      <h2 class="event_form_section_header_radio">Private event</h2>
      <h3 class="event_form_section_subheader_radio">Attendees must be approved</h3>
    </div>
  </div>
  <div class="event_field_pic">
    <h2 class="event_form_section_header_radio">URL</h2>
		<input type="text" name="updateEvent_url" id="updateEvent_url" disabled="disabled" />
  </div>
  <div class="event_field_pic">
    <h2 class="event_form_section_header_radio">Upload picture</h2>
    <div id="update-file-uploader">       
        <noscript>          
            <p>Please enable JavaScript to use file uploader.</p>
            <!-- or put a simple form for upload here -->
        </noscript>         
    </div>
  </div>
  <a href="#" id="updateEvent_submit" class="updateButton">Update</a>
</div>
</div>