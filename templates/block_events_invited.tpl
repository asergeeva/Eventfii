<section class="block" id="events-attended">
				<header class="block-title">
					<h1>Events awaiting RSVP</h1>
				</header>{assign var=invitedEvents_counts value=$invitedEvents|@count}{if $invitedEvents_counts gt 0}

				<ul class="event-list">{foreach $invitedEvents as $event}

					<li>
						<a href="{$CURHOST}/event/a/{$event->alias}" class="event-manage">
							<span class="btn btn-manage"><em>update RSVP</em></span>
						</a>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2> 
						</a>
					</li>{/foreach}

				</ul>{else}

				<p class="message">You are not invited to any new events.</p>{/if}

			</section>
