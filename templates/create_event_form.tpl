<div id="create_event_form">
	<span id="create_event_eventid">{$maxEventId}</span>
  <h3>Create Event</h3>
  <div id="left_event_form">
    <div class="event_field">
      <h2 class="event_form_section_header">What are you planning?</h2>
      <h3 class="event_form_section_subheader">Name of event</h3>
      <input type="text" name="event_title_create" id="event_title_create" value="{$eventTitle}" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Brief description</h2>
      <h3 class="event_form_section_subheader">What is this event about?</h3>
      <input type="text" name="event_description_create" id="event_description_create" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Where</h2>
      <h3 class="event_form_section_subheader">Ex: 1234 Maple St, Los Angeles, CA 90007</h3>
      <input type="text" name="event_address_create" id="event_address_create" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">When</h2>
      <h3 class="event_form_section_subheader">Ex: 05/14/2011 (MM/DD/YYYY)</h3>
      <input type="text" name="event_date_create" id="event_date_create" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">What time</h2>
      <h3 class="event_form_section_subheader">Ex: 21:10 (HH:MM)</h3>
      <input type="text" name="event_time_create" id="event_time_create" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Deadline to sign up</h2>
      <h3 class="event_form_section_subheader">Last day for anyone to reserve a spot</h3>
      <input type="text" name="event_deadline_create" id="event_deadline_create" />
    </div>
    </div>
    <div id="right_event_form">
    <div class="event_field">
      <h2 class="event_form_section_header">Attendance goal</h2>
      <h3 class="event_form_section_subheader">In # of attendees</h3>
      <input type="text" name="event_goal_create" id="event_goal_create" />
    </div>
    <div class="event_field_ta">
      <h2 class="event_form_section_header">Each paid spot will get</h2>
      <h3 class="event_form_section_subheader">What each attendee will get once the event goes through</h3>
      <textarea name="event_gets_create" id="event_gets_create"></textarea>
    </div>
    <div class="event_field">
      <input type="radio" name="event_ispublic_create" id="event_ispublic_yes_create" checked="checked" value="1" />
      Anyone can sign up &amp; invite others
    </div>
    <div class="event_field">
      <input type="radio" name="event_ispublic_create" id="event_ispublic_no_create" value="0" />
      Only people you invite can attend
    </div>
    <div class="event_field_pic">
      <h2 class="event_form_section_header_radio">URL</h2>
      <input type="text" name="event_url_create" id="event_url_create" disabled="disabled" value="{$CURHOST}/event/{$maxEventId}" />
    </div>
    <div class="event_field_pic">
      <h2 class="event_form_section_header_radio">Upload picture</h2>
      <div id="create-file-uploader">       
          <noscript>          
              <p>Please enable JavaScript to use file uploader.</p>
              <!-- or put a simple form for upload here -->
          </noscript>         
      </div>
    </div>
    <div class="event_guest">
      <a href="#"><img src="{$EC_IMG_PATH}/addguests.png" id="event_guest_invite" rel="#event_guest_invite_overlay" /></a>
    </div>
    <a href="#" id="event_create" class="goButton">Go</a>
  </div>
</div>