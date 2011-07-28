<section class="block" id="events-attended">
				<header class="block-collapsable-title">
					<h1>I'm attending...</h1>
				</header>
				{assign var=attendingEvents_counts value=$attendingEvents|@count} 
				{if $attendingEvents_counts gt 0}
				<ul class="event-list">
				{foreach name=events item=event from=$attendingEvents}
					<li>
						<a href="{$CURHOST}/event/{$event['id']}">
							<h2>{$event['title']}</h2>
							<span>{$event['days_left']} days left</span>
						</a>
					</li>
				{/foreach}
				</ul>
				{else}
					<p class="message">{$userInfo['fname']} isn't attending any public events.</p>
				 {/if}
			</section>
