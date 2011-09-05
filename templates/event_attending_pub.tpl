<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I'm attending...</h1>
				</header>{assign var=attendingEvents_counts value=$attendingEvents|@count}{if $attendingEvents_counts gt 0}

				<ul class="event-list">{foreach $attendingEvents as $event}

					<li>
						<a href="{$CURHOST}/event/{$event->eid}">
							<h2><strong>{$event->title}</strong></h2>
							<span class="event-daysleft">{$event->days_left} days left</span>
						</a>
					</li>{/foreach}

				</ul>{else}

				<p class="message">{$profile->fname} isn't attending any public events.</p>{/if}

			</section>
