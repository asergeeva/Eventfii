	<div class="twitStream {$NUM_TWEETS}" id="tweets" title="#truersvp{$event->eid}"></div>
  <header class="block">
			<p class="message"><em>{$event->days_left}</em> days left until the event. Get excited!</p>
		</header>
		<div class="content">
			<section class="block" id="event-info">
				<header class="block-title">
					<h1>Find out more</h1>
				</header>
				<div class="event-desc">
					<p>{$event->description}</p>
					<!--p>{$twitterHash}</p-->
				</div>
			</section>
			{if isset($attending)}<section class="block" id="event-attendants">
				<header class="block-title">
					<h1>Who's coming?</h1>
				</header>
				<ul class="thumbs">{foreach $attending as $guest}

					<li>
						<figure>
							<a href="{$CURHOST}/user/{$guest->id}">
								<img src="{$guest->pic}" width="64px" height="64px" alt="{$guest->fname} {$guest->lname}" />
								<figcaption>{$guest->fname} {$guest->lname}</figcaption>
							</a>
						</figure>
					</li>{/foreach}

				</ul>
				<footer class="link-extra">
					<p><a href="#" id="all-guests">See All ({$curSignUp})</a></p>
				</footer>
			</section>{/if}

			<section class="block" id="event-location">
				<header class="block-title">
					<h1>Location</h1>
				</header>
				<figure>
					<figcaption><address>{$event->address}</address></figcaption>
					<iframe width="558" height="203" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$event->location_address|urlencode}&amp;hnear={$event->location_address|urlencode}&amp;hl=en&amp;sll={$event->location_lat},{$event->location_long}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed"></iframe>
				</figure>
			</section>
			<section class="block" id="event-comments">
				<header class="block-title">
					<h1>Comments</h1>
				</header>
				<div id="fb-root"></div>
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
				<fb:comments href="{$CURHOST}/event/{$event->eid}"></fb:comments>
				<!-- Consider adding to header:
					 Ability for users to receive notifications when their event gets comments

				<meta property="fb:admins" content="{*YOUR_USER_ID*}">
				-->
			</section>
		</div>
