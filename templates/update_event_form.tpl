<div id="update_event_form">
<h3>Update Event</h3>
<div id="left_event_form">
  <div class="event_field">
    <h2 class="event_form_section_header">What are you planning?</h2>
    <h3 class="event_form_section_subheader">Name of event</h3>
    <input type="text" name="event_title_update" id="event_title_update" value="{$eventInfo['title']}" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Brief description</h2>
    <h3 class="event_form_section_subheader">What is this event about?</h3>
    <input type="text" name="event_description_update" id="event_description_update" value="{$eventInfo['description']}" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Where</h2>
    <h3 class="event_form_section_subheader">Ex: 1234 Maple St, Los Angeles, CA 90007</h3>
    <input type="text" name="event_address_update" id="event_address_update" value="{$eventInfo['location_address']}" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">When</h2>
    <h3 class="event_form_section_subheader">Ex: 05/14/2011 (MM/DD/YYYY)</h3>
    <input type="text" name="event_date_update" id="event_date_update" value="{$eventDate}" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">What time</h2>
    <h3 class="event_form_section_subheader">Ex: 21:10 (HH:MM)</h3>
    <input type="text" name="event_time_update" id="event_time_update" value="{$eventTime}" />
  </div>
  <div class="event_field">
    <h2 class="event_form_section_header">Deadline to sign up</h2>
    <h3 class="event_form_section_subheader">Last day for anyone to reserve a spot</h3>
    <input type="text" name="event_deadline_update" id="event_deadline_update" value="{$eventInfo['event_deadline']}" />
  </div>
  </div>
  <div id="right_event_form">
  <div class="event_field">
    <h2 class="event_form_section_header">Attendance goal</h2>
    <h3 class="event_form_section_subheader">In # of attendees</h3>
    <input type="text" name="event_goal_update" id="event_goal_update" value="{$eventInfo['goal']}" />
  </div>
  <div class="event_field_ta">
    <h2 class="event_form_section_header">Each paid spot will get</h2>
    <h3 class="event_form_section_subheader">What each attendee will get once the event goes through</h3>
    <textarea name="event_gets_update" id="event_gets_update">{$eventInfo['gets']}</textarea>
  </div>
  <div class="event_field">
    <input type="radio" name="event_ispublic_update" id="event_ispublic_yes_update" {$isEventPublic} value="1" />
    Anyone can sign up &amp; invite others
  </div>
  <div class="event_field">
    <input type="radio" name="event_ispublic_update" id="event_ispublic_no_update" {$isEventPrivate} value="0" />
    Only people you invite can attend
  </div>
  <div class="event_field_pic">
    <h2 class="event_form_section_header_radio">URL</h2>
    <input type="text" name="event_url" id="event_url_update" disabled="disabled" value="{$CURHOST}/event/{$eventInfo['id']}" />
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
  <div class="event_guest">
    <a href="#update"><img src="{$EC_IMG_PATH}/addguests.png" id="update_event_guest_invite" /></a>
  </div>
  <a href="#" id="event_update" class="updateButton">Update</a>
</div>
</div>