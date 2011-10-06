	<section class="block" id="events-created">
				<header class="block-title">
					<h1>Past hosted events</h1>
				</header>{assign var=pastCreatedEvents_counts value=$pastCreatedEvents|@count}{if $pastCreatedEvents_counts gt 0}

				<ul class="event-list">{foreach $pastCreatedEvents as $event}

					<li>
						<a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="event-manage">
							<span class="btn btn-manage"><em>Manage</em></span>
						</a>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2>
						</a> 
					</li>{/foreach}

				</ul>{else}
				
				<p class="message">There are no past hosted events.</p>
				{/if}

			</section>
