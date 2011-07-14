<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>
				<ul class="event-list">
				{foreach name=events item=event from=$createdEvents}
					<li><a href="{$CURHOST}/event/manage?eventId={$event['id']}"><h2>{$event['title']}</h2> <span>Manage</span></a></li>
				{/foreach}
				</ul>
			</section>
