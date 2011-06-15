<div id="update_event_form">
<h3>Update Event</h3>
<div id="left_event_form">
	{include file="event_form_fields.tpl"}
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
  <a href="#" id="updateEvent_submit" class="updateButton">Update</a>
</div>
</div>