			<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I'm attending...</h1>
				</header>
				{assign var=attendingEvents_counts value=$attendingEvents|@count} 
				{if $attendingEvents_counts gt 0}
				<ul class="event-list">
				{foreach name=events item=event from=$attendingEvents}
					<li><a href="{$CURHOST}/event/{$event['id']}"><h2>{$event['title']}</h2> <span>update RSVP</span></a></li>
				{/foreach}
				{else}
					<p class="message">You haven't RSVP'd to any events yet.</p>
				 {/if}
				</ul>
			</section>
