<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>{assign var=createdEvents_counts value=$createdEvents|@count}{if $createdEvents_counts gt 0}

				<ul class="event-list">{foreach $createdEvents as $event}

					<li>
						<a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="event-manage">
							<span class="btn btn-manage"><em>Manage</em></span>
						</a>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2>
						</a> 
					</li>{/foreach}

				</ul>{else}

				<p class="message-extra">You are not hosting any events.</p>{/if}
				
				<p class="message-extra"><a href="{$CURHOST}/home?view=created">View past events you've hosted</a></p>
			</section>
