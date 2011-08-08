	<div class="twitStream {$NUM_TWEETS}" id="tweets" title="#truersvp{$eventInfo['id']}"></div>
  <header class="block">
			<p class="message"><em>{$eventInfo['days_left']}</em> days left until the event. Get excited!</p>
		</header>
		<!-- Not in design
		<div id="event_picture_container">
			<img src="{$IMG_UPLOAD}/{$eventInfo['id']}.jpg" id="event_picture" />
		</div>
		<div id="event_spots">
			{$curSignUp} people is attending<br />
			{$twitterHash}
		</div>
		-->
		<div class="content">
			<section class="block" id="event-info">
				<header class="block-title">
					<h1>Find out more</h1>
				</header>
				<div class="event-desc">
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
									<img src="{$v['pic']}" width="64px" height="64px" alt="{$v['fname']} {$v['lname']}" />
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
			<section class="block" id="event-location">
				<header class="block-title">
					<h1>Location</h1>
				</header>
				<figure>
					<figcaption><address>{$eventInfo['location_address']}</address></figcaption>
					<iframe width="558" height="203" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$eventInfo['location_address']|urlencode}&amp;hnear={$eventInfo['location_address']|urlencode}&amp;hl=en&amp;sll={$eventInfo['location_lat']},{$eventInfo['location_long']}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed"></iframe>
				</figure>
			</section>
			<section class="block" id="event-comments">
				<header class="block-title">
					<h1>Comments</h1>
				</header>
				<div id="fb-root"></div>
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
				<fb:comments href="{$CURHOST}/event/{$eventInfo['id']}"></fb:comments>
				<!-- Consider adding to header:
					 Ability for users to receive notifications when their event gets comments

				<meta property="fb:admins" content="{*YOUR_USER_ID*}">
				-->
			</section>
		</div>
