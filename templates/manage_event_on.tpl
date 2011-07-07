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
        <!--ul class="list">
          <li class="list-head"><span>Name</span> <em>Certainty</em></li>
          {foreach name=attendees item=eventAttendee from=$eventAttendees}
          <li><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} /><span>{$eventAttendee['fname']} {$eventAttendee['lname']}</span>	<em>90%</em></li>
          {/foreach}
        </ul-->
		<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable">
		  <thead>
			<tr>
				<th>Name</th>
				<th>Certainty</th>
			</tr>
		  </thead>
		  <tbody>
		  {foreach name=attendees item=eventAttendee from=$eventAttendees}
			<tr>
				<td>{$eventAttendee['lname']}</td>
				<td>90%</td>
			</tr>
			{/foreach}
			<tr>
				<td>abc</td>
				<td>80%</td>
			</tr>
			<tr>
				<td>def</td>
				<td>70%</td>
			</tr>
		  </tbody>
		</table> 
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
<script>
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = false;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("table",1);
</script>