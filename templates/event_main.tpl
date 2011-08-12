	<div class="twitStream {$NUM_TWEETS}" id="tweets" title="#truersvp{$event->eid}"></div>
  <header class="block">
			<p class="message"><em>{$event->days_let]}</em> days left until the event. Get excited!</p>
			<p class="message">{$QR}</p>
		</header>
		<!-- Not in design
		<div id="event_picture_container">
			<img src="{$IMG_UPLOAD}/{$event->id}.jpg" id="event_picture" />
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
					<p>{$event->description}</p>
					<!--p>{$twitterHash}</p-->
          <!-- Facebook share -->
    <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=230238300346205&amp;xfbml=1"></script><fb:like href="{$EVENT_URL}/{$event->id}" send="true" layout="button_count" width="450" show_faces="false" action="like" font=""></fb:like>
          <!-- End Facebook -->
				</div>
			</section>
			<section class="block" id="event-attendants">
				<header class="block-title">
					<h1>Who's coming?</h1>
				</header>
				<ul class="thumbs">{foreach $attending as $guest}
					<li>
						<figure>
							<a href="{$CURHOST}/user/{$guest->id}">
								<img src="{$guest->pic}" width="64px" height="64px" alt="{$v['fname']} {$guest->lname}" />
								<figcaption>{$guest->fname} {$guest->lname}</figcaption>
							</a>
						</figure>
					</li>{/foreach}
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
