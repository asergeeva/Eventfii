<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I attended...</h1>
				</header>{assign var=attendedEvents_counts value=$attendedEvents|@count}{if $attendedEvents_counts gt 0}

				<ul class="event-list">{foreach $attendedEvents as $event}

					<li>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2> 
						</a>
						<a href="{$CURHOST}/event/a/{$event->alias}" class="event-manage">
							<span class="btn btn-manage"><em>update RSVP</em></span>
						</a>
					</li>{/foreach}

				</ul>{else}

				<p class="message">You have not attended to any events</p>{/if}

			</section>
