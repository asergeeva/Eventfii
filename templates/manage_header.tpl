<span id="manage_event_id">{$eventInfo['id']}</span>
<div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
    {include file="event_invite_guest_create.tpl"}
</div>
<div id="section-header">
	<h1 class="event-title">{$eventInfo['title']}</h1>
	<p class="event-date">{$eventInfo['event_datetime']}</p>
	<nav class="section-nav">
		<ul>
			<li class="section-current"><a href="#" id="update_event_before">Before Event</a></li>
			<li><a href="#" id="update_event_on">Day of Event</a></li>
			<li><a href="#" id="update_event_after">After Event</a></li>
			<li><a href="#" id="email_settings">E-Mail Options</a></li>
		</ul>
	</nav>
</div>