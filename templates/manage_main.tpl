<header class="block">
			<p class="message"><em>{$eventInfo['days_left']}</em> days left until the event. Get excited!</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			<section class="block" id="cp-manage">
				<header>
					<!-- Progress code goes here 
					trueRSVP: {$trsvpVal}
					Guestimate: {$guestimate}
					Goal: {$eventInfo['goal']}

					<div class="demo">
						<div id="progress_bar" style="position:relative; bottom:5px; width:95%; left:10px;"></div>
						<span style="position:relative; left:30%; bottom:5px;">Your trueRSVP:<b>{{$guestConf1}+{$guestConf2}+{$guestConf3}}</b> / Your Goal:<b>{$eventInfo['goal']}</b></span>
					</div>
					
					-->
				</header>
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
				<section class="block" id="cp-attendees">
					<header class="block-collapsable-title">
						<h1>Attendees</h1>
					</header>
					<ul class="list"> 
						<li class="list-head"><strong>Name</strong> <em>Certainty</em> <span>Showed Up?</span></li> 
						{foreach name=attendees item=eventAttendee from=$eventAttendees}
						<li><label for="attendee-{$eventAttendee['id']}"><strong>{$eventAttendee['fname']} {$eventAttendee['lname']}</strong> <em>90%</em> <span><input type="checkbox" id="attendee-{$eventAttendee['id']}" value="attendee_{$eventAttendee['id']}_{$eventInfo['id']}" {$eventAttendee['checkedIn']} name="selecteditems" /></span></label></li> 
						{/foreach}
					</ul>
				</section>
				<footer class="links-extra">
					<p><a href="{$CURHOST}/event/print?eventId={$eventInfo['id']}" target="_blank">Print Attendance List</a</p> 
				</footer>
			</section>
		</div>
