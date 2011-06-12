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
 		<form id="attend_event_form" name="attend_event_form" action="{$CURHOST}/event/payment/submit">
    	<input type="hidden" name="maxTotalAmountOfAllPayments" value="{$eventInfo['cost']}" />
    	<a href="#" id="event-{$eventId}">
      	<img src="{$EP_IMG_PATH}/yes.png" class="ep_yes" id="attend_event_confirm" />
      </a>
    </form>
    <div id="event_cost_attend">${$eventInfo['cost']}/spot</div>
  </div>
  <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=121330687952296&amp;xfbml=1"></script><fb:like href="{$eventInfo['url']}" send="true" width="450" show_faces="true" font="" id="fb-like-button"></fb:like>
  
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