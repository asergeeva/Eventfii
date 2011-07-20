<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>
				{assign var=createdEvents_counts value=$createdEvents|@count} 
				{if $createdEvents_counts gt 0}
				<ul class="event-list">
					{foreach name=events item=event from=$createdEvents}
					<li><a href="{$CURHOST}/event/{$event['id']}"><h2>{$event['title']}</h2></a> <a href="{$CURHOST}/event/manage?eventId={$event['id']}" class="event-manage"><span>Manage</span></a></li>
					{/foreach}
				</ul>
				{else}
				<p class="message">Create your first event!</p>
				{/if}
				<footer class="buttons-create">
					<a href="create"><span>New Event</span></a>
				</footer> 
			</section>
