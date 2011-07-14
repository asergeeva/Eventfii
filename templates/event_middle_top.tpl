<header id="header">
		<h1 id="event-{$eventInfo['id']}">{$eventInfo['title']}</h1>
		<span id="event_id" style="display: none">{$eventInfo['id']}</span>
		<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($eventInfo['event_datetime']))}</time></p>
	</header>
	<section id="main">
		<header class="block">
			<p class="message"><em>{$eventInfo['time_left']}</em> days left until the event. Get excited!</p>
		</header>
		<!-- Not in design
			<div id="event_picture_container">
				<img src="{$IMG_UPLOAD}/{$eventInfo['id']}.jpg" id="event_picture" />
			</div>
			<div id="event_spots">
				{$curSignUp} people is attending<br />
				{$twitterHash}
			</div>
		-->
