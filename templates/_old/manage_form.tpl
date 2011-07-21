<header class="block">
  <p class="message"><em>{$eventInfo['days_left']}</em> days left until the event. Get excited!</p>
</header>
<div class="navigation">
  <nav class="block" id="cp-nav">
    <ul>
      <li{$page["addguests"]}><a href="#manage" id="update_event_guest_invite" rel="#event_guest_invite_overlay"><span>Add More Guests</span></a></li>
      <li{$page["email"]}><a href="email/reminder?eventId={$eventInfo['id']}" id="email_settings_top"><span>E-mail current guests</span></a></li>
      <li{$page["text"]}><a href="text.html"><span>Sent text to guests</span></a></li>
    </ul>
  </nav>
  <footer class="links-extra">
    <p><a href="{$CURHOST}">Back to home</a></p>
  </footer>
</div>
<div class="content">
  <section class="block" id="cp-manage">
    <header>
      <!-- Progress code goes here 
      trueRSVP: {$trsvpVal}
      Guestimate: {$guestimate}
      Goal: {$eventInfo['goal']}
      -->
    </header>
    <div class="demo">
    <div id="progress_bar" style="position:relative; bottom:5px; width:95%; left:10px;"></div>
    <span style="position:relative; left:30%; bottom:5px;">Your trueRSVP:<b>{{$guestConf1}+{$guestConf2}+{$guestConf3}}</b> / Your Goal:<b>{$eventInfo['goal']}</b></span>
    </div>
    <section class="block" id="cp-breakdown">
      <header class="block-collapsable-title">
      <h1>RSVP Breakdown</h1>
      </header>
      <dl class="table"> 
        <dt class="list-head">Response</dt> 
        <dd>#</dd> 
        <dt>I will absolutely be there</dt> 
        <dd>{$guestConf1}</dd> 
        <dt>I'm pretty sure i can make it</dt> 
        <dd>{$guestConf2}</dd> 
        <dt>I will make it if my schedule doesn't change</dt> 
        <dd>{$guestConf3}</dd> 
        <dt>I probably won't be able to make it</dt> 
        <dd>{$guestConf4}</dd> 
        <dt>Not attending, but show me as a supporter</dt> 
        <dd>{$guestConf5}</dd> 
        <dt>This is spam to me</dt> 
        <dd>{$guestConf6}</dd> 
        <dt>No Response</dt> 
        <dd>{$guestNoResp}</dd> 
      </dl>
      <div id="rsvp" style="display:none;">{{$guestConf1}+{$guestConf2}+{$guestConf3}}</div>
      <div id="goal" style="display:none;">{$eventInfo['goal']}</div>
    </section>
    <section class="block">
      <header class="block-collapsable-title">
        <h1>Attendees</h1>
      </header>
      <table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable">
          <thead>
          <tr>
            <th>First name</th>
            <th>Last name</th>
            <th class="nosort">Showed Up?</th>
          </tr>
          </thead>
          <tbody>
          {foreach name=attendees item=eventAttendee from=$eventAttendees}
          <tr>
            <td>{$eventAttendee['fname']}</td>
            <td>{$eventAttendee['lname']}</td>
            <td><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} name="selecteditems" /></td>
          </tr>	
          {/foreach}
          </tbody>
        </table> 
		</section>
    <footer class="links-extra">
      <p><a href="{$CURHOST}/event/print?eventId={$eventInfo['id']}" target="_blank">Print Attendance List</a></p> 
    </footer>
  </section>
</div>