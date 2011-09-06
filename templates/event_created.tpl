<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>{assign var=createdEvents_counts value=$createdEvents|@count}{if $createdEvents_counts gt 0}

				<ul class="event-list">{foreach $createdEvents as $event}

					<li>
						<a href="{$CURHOST}/event/a/{$event->alias}">
							<h2><strong>{$event->title}</strong> {$event->getHumanReadableEventTime()}</h2>
						</a> 
						<a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="event-manage">
							<span class="btn btn-manage"><em>Manage</em></span>
						</a>
					</li>{/foreach}

				</ul>{else}
				
				<div style="margin-left: auto; margin-right: auto; text-align: center;">
					<p class="message">Create your first event!</p>
					<p class="buttons buttons-create"><a href="{$CURHOST}/event/create" class="btn btn-small"><span>Create New Event</span></a></p>
				</div>
				{/if}

			</section>
