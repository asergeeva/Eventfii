<header id="section-header">
	<h1 id="event-{$eventInfo['id']}">{$eventInfo['title']}</h1>
	<p class="event-date">{date("F j, Y, g:i A", strtotime($eventInfo['event_datetime']))}</p>
	<!-- Not in design
		<h3 id="event_dayleft">{date("F j, Y, g:i A", strtotime($eventInfo['days_left']))} days left</h3>
		<div id="event_picture_container">
			<img src="{$IMG_UPLOAD}/{$eventInfo['id']}.jpg" id="event_picture" />
		</div>
		<div id="event_spots">
			{$curSignUp} people is attending<br />
			{$twitterHash}
		</div>
	-->
</header>
