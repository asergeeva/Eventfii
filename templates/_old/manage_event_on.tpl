<section class="block" id="cp-attendees">
					<header class="block-collapsable-title">
						<h1>Attendee List</h1>
					</header>
					<ul class="list"> 					
						<li class="list-head"><strong>Name</strong> <em>Certainty</em> <span>Showed Up?</span></li>
						{foreach name=attendees item=eventAttendee from=$eventAttendees}
						<li><label for="attendee_{$eventAttendee['id']}_{$eventInfo['id']}"><strong>{$eventAttendee['fname']} {$eventAttendee['lname']}</strong> <em>90%</em> <span><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} name="selecteditems" /></span></label></li>
						{/foreach}
					</ul>
				</section>

<!--div id="main">
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
        --ul class="list">
          <li class="list-head"><span>Name</span> <em>Certainty</em></li>
          {foreach name=attendees item=eventAttendee from=$eventAttendees}
          <li><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} /><span>{$eventAttendee['fname']} {$eventAttendee['lname']}</span>	<em>90%</em></li>
          {/foreach}
        </ul--
		<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable">
		  <thead>
			<tr>
				<td class="nosort"><input type="checkbox" class="checkAll" value="checkall" /></td>
				<th>First name</th>
        <th>Last name</th>
			</tr>
		  </thead>
		  <tbody>
		  {foreach name=attendees item=eventAttendee from=$eventAttendees}
			<tr>
				<td><input type="checkbox" class="event_attendee_cb" id="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} name="selecteditems" /></td>
				<td>{$eventAttendee['fname']}</td>
				<td>{$eventAttendee['lname']}</td>
			</tr>
			{/foreach}
		  </tbody>
		</table> 
      </section>
    </div>
    <aside class="section-extra">
      <ul>
        <li><a href="#" id="email_settings_bottom" class="btn"><span>Send Reminder Email</span></a></li>
        <li><a href="{$CURHOST}/event/print?eventId={$eventInfo['id']}" target="_blank" class="btn"><span>Print Attendance List</span></a></li>
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
<script>
$(".checkAll").click(function() {
	if("checkall" === $(this).val() && $(this).attr('checked',true))
	{   
      $(".event_attendee_cb").attr('checked', true);  
       $(this).val("uncheckall"); //change button text  
	}    
	else if("uncheckall" === $(this).val() && $(this).attr('checked',false))
 	{         
	$(".event_attendee_cb").attr('checked', false);   
      	$(this).val("checkall"); //change button text    
	}
}
);
</script-->