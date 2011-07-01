<h2 id="event_title">{$eventInfo['title']}</h2>

<div id="time_info">
  <h3 id="event_dayleft">{$eventInfo['days_left']} days left</h3>
  <div id="event_datetime">
    <strong>When:</strong> {$eventInfo['event_datetime']}
  </div>
</div>

<div id="event_picture_container">
  <img src="{$IMG_UPLOAD}/{$eventInfo['id']}.jpg" id="event_picture" />
</div>

<div id="event_spots">
  {$curSignUp} people is attending<br />
  {$twitterHash}
</div>