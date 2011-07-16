<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>
				<ul class="event-list">
				{assign var=createdEvents_counts value=$createdEvents|@count} 
				{if $createdEvents_counts gt 0}
				  {foreach name=events item=event from=$createdEvents}
					<li><a href="{$CURHOST}/event/manage?eventId={$event['id']}"><h2>{$event['title']}</h2> <span>Manage</span></a></li>
				  {/foreach}
				 {else}
				   <p class="message">Create your first event!</p>
					  <footer class="buttons-create">
					  <a href="home"><span>New Event</span></a>
				 {/if}
				</ul>
			</section>
