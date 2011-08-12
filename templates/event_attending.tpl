<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I'm attending...</h1>
				</header>
				{assign var=attendingEvents_counts value=$attendingEvents|@count} 
				{if $attendingEvents_counts gt 0}
				<ul class="event-list">{foreach $attendingEvents as $event}
					<li>
						<a href="{$CURHOST}/event/{$event->eid}">
							<h2>{$event->title}</h2> 
							<span class="button"><em>update RSVP</em></span>
						</a>
					</li>
				{/foreach}
				{else}
					<p class="message">You haven't RSVP'd to any events yet.</p>
				 {/if}
				</ul>
			</section>
