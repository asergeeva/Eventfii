<div class="content">
			<section class="block" id="event-info">
				<header class="block-title">
					<h1>Find out more</h1>
				</header>
				<div class="event-desc">
					<figure>
						<figcaption><address>{$eventInfo['location_address']}</address></figcaption>
						<iframe width="167" height="137" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$eventInfo['location_address']|urlencode}&amp;hnear={$eventInfo['location_address']|urlencode}&amp;hl=en&amp;sll={$eventInfo['location_lat']},{$eventInfo['location_long']}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed"></iframe>
					</figure>
					<p>{$eventInfo['description']}</p>
					<!--p>{$twitterHash}</p-->
				</div>
				<!-- Not in design
				<div id="event_cost">
					<span id="event_gets_price">You will get</span>
					<div id="event_gets">
						{*$eventInfo['gets']*}
					</div>
				</div>
				-->
			</section>
			<section class="block" id="event-attendants">
				<header class="block-title">
					<h1>Who's coming?</h1>
				</header>
				<ul class="thumbs">
					{foreach from=$attending key=k item=v}
						<li>
							<figure>
								<a href="{$CURHOST}/user/{$v['user_id']}">
								{if $v['pic']==''}
									<img src="{$CURHOST}/images/default_thumb.jpg" />
								{else}
									<img src="{$CURHOST}/upload/user/{$v['pic']}" />
								{/if}
									<figcaption>{$v['fname']} {$v['lname']}</figcaption>
								</a>
							</figure>
						</li>
					{/foreach}
				</ul>
				<footer class="link-extra">
					<p><a href="#">See All (?)</a></p>
				</footer>
			</section>