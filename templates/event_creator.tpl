<section class="block" id="event-hosted">
				<header class="block-title">
					<h1>Hosted by</h1>
				</header>
				<p class="user-name"><a href="{$CURHOST}/user/{$event->organizer->id}">{$event->organizer->fname} {$event->organizer->lname}</a></p>
				<p class="user-img">
					<a href="{$CURHOST}/user/{$event->organizer->id}"><img src="{$event->organizer->pic}" width="96px" height="96px" alt="{$event->organizer->fname} {$event->organizer->lname}" /></a>
				</p>
				<ul class="user-name-extra">
					<li>{$event->organizer->about}</li>
				</ul>
				<footer class="link-extra">
					<p><a href="mailto:{$event->organizer->email}">Send {$event->organizer->fname} a message</a></p>
				</footer>
			</section>
