<div id="manage_event_on">
  <div class="event_score">
    <h3 class="event_score_title">True RSVP</h3>
    <h4 class="event_score_val">{$trsvpVal}</h4>
  </div>
  <a href="#"><img src="{$IMG_PATH}/print_03.png" id="print_guest" /></a>
  <div id="check_off_attendance" class="scroll">
  <table>
  	<tr>
    	<th>Name</th>
      <th>Email</th>
      <th>% Reliability</th>
    </tr>
  	{foreach name=attendees item=eventAttendee from=$eventAttendees}
  	<tr>
    	<td><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} /> {$eventAttendee['fname']} {$eventAttendee['lname']}</td>
      <td>{$eventAttendee['email']}</td>
      <td>0</td>
    </tr>
  	{/foreach}
  </table>
  </div>
</div>