<div class="content">
			<header class="block">
				<p class="message"><em>{$event->days_left}</em> days left until the event. Get excited!</p>
			</header>
			<section class="block" id="event-info">
				<header class="block-title">
					<h1>Find out more</h1>
					<h2><a href="#">Flag this event</a></h2>
				</header>
				<div class="event-info">
					<p class="event-desc">{$event->description}</p>
					<section class="event-more" id="event-hosted">
						<header>
							<h1>Hosted by:</h1>
						</header>
						<p class="user-img">
							<a href="{$CURHOST}/user/{$event->organizer->id}"><img src="{$event->organizer->pic}" width="36px" height="36px" alt="{$event->organizer->fname} {$event->organizer->lname}" /></a>
						</p>
						<footer class="user-info">
							<p class="user-name"><a href="{$CURHOST}/user/{$event->organizer->id}">{$event->organizer->fname} {$event->organizer->lname}</a></p>
							<p class="user-contact"><a href="mailto:{$event->organizer->email}">Send {$event->organizer->fname} an email</a></p>
						</footer>
					</section>
					<section class="event-more" id="event-cal">
						<header>
							<h1>Add event to:</h1>
						</header>
						<p class="icons"><a href="{$CURHOST}/calendar/ics?eventId={$event->eid}" class="icon-ical" target="_blank">iCal</a> <a href="http://www.google.com/calendar/event?action=TEMPLATE&amp;text={$event->title}&amp;dates={$event->getCalDate()}/{$event->getCalDate()}&amp;details={$event->description}&amp;location={$event->address}&amp;trp=false&amp;sprop={$EVENT_URL}/{$event->eid}&amp;sprop={$event->description}" class="icon-gcal" target="_blank">gcal</a> <a href="{$CURHOST}/calendar/vcs?eventId={$event->eid}" class="icon-outlook" target="_blank">Outlook</a></p>
					</section>
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
					<iframe width="558" height="203" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$event->address|urlencode}&amp;hnear={$event->address|urlencode}&amp;hl=en&amp;sll={$event->location_lat},{$event->location_long}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed&amp;iwloc=near"></iframe>
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
