<div id="event_invite_guest">
	<div id="add_guest_left">
    {include file="event_add_guest.tpl"}
    <div id="event_guest_csv">
      <h2 class="event_form_section_header">CSV:</h2>
      <div id="guest-invite-file-uploader-create">       
          <noscript>          
              <p>Please enable JavaScript to use file uploader.</p>
              <!-- or put a simple form for upload here -->
          </noscript>         
      </div>
    </div>
  </div>
  <div id="add_guest_right"></div>
  <div id="add_guest_save_container">
  	<a href="#"><img src="{$EC_IMG_PATH}/save.png" id="invite_guest_submit" /></a>
  </div>
</div>