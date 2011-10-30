<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I'm attending...</h1>
				</header>{assign var=attendingEvents_counts value=$attendingEvents|@count}{if $attendingEvents_counts gt 0}

				<ul class="event-list">{foreach $attendingEvents as $event}

					<li>
						<a href="{$CURHOST}/event/a/{$event->alias}" class="event-manage">
							<span class="btn btn-manage"><em>update RSVP</em></span>
						</a>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2> 
						</a>
					</li>{/foreach}

				</ul>{else}

				<p class="message-extra">You haven't RSVP'd to any events yet.</p>{/if}

				<p class="message-extra"><a href="{$CURHOST}/home?view=attended">View past events you've attended</a></p>
			</section>
