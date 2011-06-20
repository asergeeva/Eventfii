<div id="manage_event_form">
	<h2 id="event_title"><span id="event-{$eventInfo['id']}">{$eventInfo['title']}</span></h2>
  <div id="event_datetime">
    <strong>When:</strong> {$eventInfo['event_datetime']}
  </div>
  <div id="event_score_container">
    <div class="event_score">
      <h3 class="event_score_title">True RSVP</h3>
      <h4 class="event_score_val">{$trsvpVal}</h4>
    </div>
    <div class="event_score">
      <h3 class="event_score_title">Guestimate</h3>
      <h4 class="event_score_val">{$guestimate}</h4>
    </div>
    <div class="event_score">
      <h3 class="event_score_title">Your Goal</h3>
      <h4 class="event_score_val">{$eventInfo['goal']}</h4>
    </div>
  </div>
  <a href="#manage"><img src="{$EC_IMG_PATH}/addguests.png" id="update_event_guest_invite" /></a>
  <table id="total_rsvp">
  	<tr>
    	<th>Yes, I will absolutely be there</th>
      <td>{$guestConf1}</td>
    </tr>
    <tr>
    	<th>I'm pretty sure I'll be there</th>
      <td>{$guestConf2}</td>
    </tr>
    <tr>
    	<th>I will make it if plans don't change</th>
      <td>{$guestConf3}</td>
    </tr>
    <tr>
    	<th>Probably won't make it</th>
      <td>{$guestConf4}</td>
    </tr>
    <tr>
    	<th>Not attending, but supporter</th>
      <td>{$guestConf5}</td>
    </tr>
    <tr>
    	<th>Not attending, not supporter</th>
      <td>{$guestConf6}</td>
    </tr>
    <tr>
    	<th>Not responded yet</th>
      <td>{$guestNoResp}</td>
    </tr>
  </table>
</div>