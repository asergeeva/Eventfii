<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>{assign var=createdEvents_counts value=$createdEvents|@count}{if $createdEvents_counts gt 0}

				<ul class="event-list">{foreach $createdEvents as $event}

					<li>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2>{$event->title}<br><small>{$event->getHumanReadableEventTime()}</small></h2>
						</a> 
						<a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="event-manage">
							<span class="button"><em>Manage</em></span>
						</a>
					</li>{/foreach}

				</ul>{else}

				<p class="message">Create your first event!</p>{/if}

			</section>
