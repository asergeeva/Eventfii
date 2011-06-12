<div id="create_event_form">
  <div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
    {include file="event_invite_guest.tpl"}
  </div>
  <h3>Create Event</h3>
  <div id="left_event_form">
    <div class="event_field">
      <h2 class="event_form_section_header">What are you planning?</h2>
      <h3 class="event_form_section_subheader">Name of event</h3>
      <input type="text" name="createEvent_title" id="createEvent_title" value="{$eventTitle}" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Brief description</h2>
      <h3 class="event_form_section_subheader">What is this event about?</h3>
      <input type="text" name="createEvent_description" id="createEvent_description" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Where</h2>
      <h3 class="event_form_section_subheader">Ex: 1234 Maple St, Los Angeles, CA 90007</h3>
      <input type="text" name="createEvent_address" id="createEvent_address" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">When</h2>
      <h3 class="event_form_section_subheader">Ex: 05/14/2011 (MM/DD/YYYY)</h3>
      <input type="text" name="createEvent_date" id="createEvent_date" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">What time</h2>
      <h3 class="event_form_section_subheader">Ex: 21:10:00 (HH:MM:SS)</h3>
      <input type="text" name="createEvent_time" id="createEvent_time" />
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Deadline to sign up</h2>
      <h3 class="event_form_section_subheader">Last day for anyone to reserve a spot</h3>
      <input type="text" name="createEvent_deadline" id="createEvent_deadline" />
    </div>
  </div>
  <div id="right_event_form">
    <div class="event_field">
      <div class="event_field_left">
        <h2 class="event_form_section_header">Min # of spots</h2>
        <h3 class="event_form_section_subheader">Your "goal"</h3>
        <input type="text" name="createEvent_min_spots" id="createEvent_min_spots" />
      </div>
      <div class="event_field_right">
        <h2 class="event_form_section_header">Max # of spots</h2>
        <h3 class="event_form_section_subheader">Optional</h3>
        <input type="text" name="createEvent_max_spots" id="createEvent_max_spots" />
      </div>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Cost per spot</h2>
      <h3 class="event_form_section_subheader">What each attendee pays</h3>
      <input type="text" name="createEvent_cost" id="createEvent_cost" />
    </div>
    <div class="event_field_ta">
      <h2 class="event_form_section_header">Each paid spot will get</h2>
      <h3 class="event_form_section_subheader">What each attendee will get once the event goes through</h3>
      <textarea name="createEvent_gets" id="createEvent_gets"></textarea>
    </div>
    <div class="event_field">
      <div class="event_field_left">
        <input type="radio" name="createEvent_ispublic" id="createEvent_ispublic_yes" checked="checked" value="1" />
        <h2 class="event_form_section_header_radio">Public event</h2>
        <h3 class="event_form_section_subheader_radio">Anyone can sign up</h3>
      </div>
      <div class="event_field_right">
        <input type="radio" name="createEvent_ispublic" id="createEvent_ispublic_no" value="0" />
        <h2 class="event_form_section_header_radio">Private event</h2>
        <h3 class="event_form_section_subheader_radio">Attendees must be approved</h3>
      </div>
    </div>
    <div class="event_field_pic">
      <h2 class="event_form_section_header_radio">URL</h2>
      <input type="text" name="createEvent_url" id="createEvent_url" disabled="disabled" value="{$CURHOST}/event/{$maxEventId}" />
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
    <a href="#" id="createEvent_submit" class="goButton">Go</a>
  </div>
</div>