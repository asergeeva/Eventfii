<div id="event_info">
  <h2 id="event_title">{$eventInfo['title']}</h2>
  
  <div id="time_info">
    <h3 id="event_dayleft">{$eventInfo['days_left']} days left</h3>
    <div id="event_datetime">
      <strong>When:</strong> {$eventInfo['event_datetime']}
    </div>
  </div>
  
  <div id="event_picture_container">
  	<img src="{$CURHOST}/upload/event/{$eventInfo['id']}.jpg" id="event_picture" />
  </div>
  
  <div id="event_spots">
  	{$curSignUp} / {$eventInfo['min_spot']} spots left
  </div>
  
  <div id="event_attending">
    Login to reserve a spot
    <div id="event_cost_attend">${$eventInfo['cost']}/spot</div>
  </div>
  
  <div id="event_metadata">
    <div id="event_description">
    {$eventInfo['description']}
    </div>
    
    <div id="event_location">
    Where: {$eventInfo['location_address']}
    </div>
        
    <div id="event_cost">
    ${$eventInfo['cost']} <span id="event_gets_price">gets you</span>
    	<div id="event_gets">
      {$eventInfo['gets']}
      </div>
    </div>
  </div>
</div>
<div id="created_by">
  <h3>Created by</h3>
  <a href="{$CURHOST}/user/{$organizer['id']}"><h4>{$organizer['fname']} {$organizer['lname']}</h4></a>
  <a href="{$CURHOST}/user/{$organizer['id']}"><img src="{$CURHOST}/images/default_thumb.jpg" /></a>
</div>