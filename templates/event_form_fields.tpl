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
  <h3 class="event_form_section_subheader">Ex: 21:10 (HH:MM)</h3>
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
  <h2 class="event_form_section_header">Attendance goal</h2>
  <h3 class="event_form_section_subheader">In # of attendees</h3>
  <input type="text" name="createEvent_goal" id="createEvent_goal" />
</div>
<div class="event_field_ta">
  <h2 class="event_form_section_header">Each paid spot will get</h2>
  <h3 class="event_form_section_subheader">What each attendee will get once the event goes through</h3>
  <textarea name="createEvent_gets" id="createEvent_gets"></textarea>
</div>
<div class="event_field">
  <input type="radio" name="createEvent_ispublic" id="createEvent_ispublic_yes" checked="checked" value="1" />
  Anyone can sign up &amp; invite others
</div>
<div class="event_field">
  <input type="radio" name="createEvent_ispublic" id="createEvent_ispublic_no" value="0" />
  Only people you invite can attend
</div>
<div class="event_field_pic">
  <h2 class="event_form_section_header_radio">URL</h2>
  <input type="text" name="createEvent_url" id="createEvent_url" disabled="disabled" value="{$CURHOST}/event/{$maxEventId}" />
</div>