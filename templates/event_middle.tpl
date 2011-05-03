<div id="event_info">
  <h2>{$eventInfo['title']}</h2>
  <h3>Days left: {$eventInfo['days_left']}</h3>
  
  <div id="event_details">
  <h3>Description</h3>
  {$eventInfo['description']}
  </div>
  
  <div id="event_location">
  <h3>Location</h3>
  {$eventInfo['location_address']}
  </div>
  
  <div id="event_datetime">
  <h3>Date &amp; Time</h3>
  {$eventInfo['event_datetime']}
  </div>
  
  <div id="event_attendance">
  <h3>Spots</h3>
  {$curSignUp} / {$eventInfo['min_spot']}
  </div>
  
  <div id="event_organizer">
  <h3>Event Organizer</h3>
  {$organizer['fname']} {$organizer['lname']}
  </div>
  
  <div id="event_cost">
  <h3>Cost</h3>
  ${$eventInfo['cost']}
  </div>
  
  <div id="event_attending">
  <h2>Attending?</h2>
  <a href="#" id="event-{$eventId}"><img src="{$EP_IMG_PATH}/yes.png" class="ep_yes" id="attend_event_confirm" /></a>
  </div>
</div>