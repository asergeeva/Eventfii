<div id="create_event_form">
  <h3>Create Event</h3>
  <div id="left_event_form">
		{include file="event_form_fields.tpl"}
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