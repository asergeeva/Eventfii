<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I'm attending...</h1>
				</header>
				<ul class="event-list">
				{foreach name=events item=event from=$attendingEvents}
					<li><a href="{$CURHOST}/event/{$event['id']}"><h2>{$event['title']}</h2> <span>update RSVP</span></a></li>
				{/foreach}
				</ul>
			</section>
