<div id="main">
  <div id="container">
    <aside class="section-extra">
      <section class="block">
        <h1 class="block-title">Tips</h1>
        <ol class="tips">
          <li>Invite enough guests so that "Our Guestimate" matches "Your Goal".</li>
          <li>As your guests begin to RSVP, "Your trueRSVP" number will increase towards "Your Goal"!</li>
        </ol>
      </section>
    </aside>
    <div class="section section-primary">
      <section class="block">
        <h1 class="block-title">Attendees</h1>
        <ul class="list">
          <li class="list-head"><span>Name</span> <em>Certainty</em></li>
          {foreach name=attendees item=eventAttendee from=$eventAttendees}
          <li><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} /><span>{$eventAttendee['fname']} {$eventAttendee['lname']}</span>	<em>90%</em></li>
          {/foreach}
        </ul>
      </section>
    </div>
    <aside class="section-extra">
      <ul>
        <li><a href="#" class="btn"><span>Send Reminder Email</span></a></li>
        <li><a href="#" class="btn"><span>Print Attendance List</span></a></li>
      </ul>
    </aside>
  </div>
</div>