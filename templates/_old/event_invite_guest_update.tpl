<div id="event_invite_guest">
	<div id="add_guest_left">
    {include file="event_add_guest.tpl"}
    <div id="event_guest_csv">
      <h2 class="event_form_section_header">CSV:</h2>
      <div id="guest-invite-file-uploader-update">       
          <noscript>          
              <p>Please enable JavaScript to use file uploader.</p>
              <!-- or put a simple form for upload here -->
          </noscript>         
      </div>
    </div>
  </div>
  <div id="add_guest_right"></div>
  <div id="add_guest_save_container">
    <span id="update_guest_prevpage">{$prevPage}</span>
    <a href="#"><img src="{$IMG_PATH}/save.png" id="invite_guest_update" /></a>
  </div>
</div>