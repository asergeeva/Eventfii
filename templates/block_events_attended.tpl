<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>Past attended events</h1>
				</header>{assign var=attendedEvents_counts value=$attendedEvents|@count}{if $attendedEvents_counts gt 0}

				<ul class="event-list">{foreach $attendedEvents as $event}

					<li>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2> 
						</a>
					</li>{/foreach}

				</ul>{else}

				<p class="message">There are no past events.</p>{/if}

				<p class="message-extra"><a href="{$CURHOST}">View events you're scheduled to attend</a></p>
			</section>
