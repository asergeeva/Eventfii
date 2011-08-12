<section class="block" id="events-created">
				<header class="block-collapsable-title">
					<h1>I'm hosting...</h1>
				</header>
				<ul class="event-list">
				{assign var=createdEvents_counts value=$createdEvents|@count} 
				{if $createdEvents_counts gt 0}
				<ul class="event-list">
					{foreach $createdEvents as $event}
					<li>
						<a href="{$CURHOST}/event/{$event->eid}">
							<h2>{$event->title}</h2>
						</a>
					</li>
					{/foreach}
				</ul>
				{else}
				<p class="message">{$profile->fname} hasn't created any public events yet.</p>
				{/if}
			</section>
