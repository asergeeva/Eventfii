		{include file="event_middle_bottom.tpl"}
			<section class="block" id="event-comments">
				<header class="block-title">
					<h1>Comments</h1>
				</header>
				<fb:comments href="{$CURHOST}/event/{$eventInfo['id']}" num_posts="2" width="496"></fb:comments>
			</section>
		</div>
		<aside class="extra">
			<section class="block" id="rsvp">
				<header class="block-title">
					<h1>Your RSVP</h1>
				</header>
				<p class="rsvp-message"><em>{$eventInfo['days_left']}</em> days left to RSVP</p>
				<fieldset>
					<ol class="rsvp-list" id="event_attending_response">
						<li><label for="event_attending_response_1"><input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT1}" {$conf90} id="event_attending_response_1" /> <em>Yes, I will absolutely be there</em></label></li>
						<li><label for="event_attending_response_2"><input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT2}" {$conf65} id="event_attending_response_2" /> <em>I'm pretty sure I will be there</em></label></li>
						<li><label for="event_attending_response_3"><input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT3}" {$conf35} id="event_attending_response_3" /> <em>I'll make it if my schedule doesn't change</em></label></li>
						<li><label for="event_attending_response_4"><input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT4}" {$conf15} id="event_attending_response_4" /> <em>I probably won't be able to make it</em></label></li>
						<li><label for="event_attending_response_5"><input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT5}" {$conf4} id="event_attending_response_5" /> <em>No, but show me as a supporter</em></label></li>
						<li><label for="event_attending_response_6"><input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT6}" {$conf1} id="event_attending_response_6" /> <em>No, I'm not interested</em></label></li>
					</ol>
				</fieldset>
				<div id="response_stat_msg"></div>
			</section>
			{include file="event_middle_creator.tpl"}			
			<section class="block" id="event-calendar">
				<header class="block-title">
					<h1>Add event to:</h1>
				</header>
				<figure>
					<a href="#">
						<img src="images/ical.jpg" alt="iCal" />
						<figcaption>iCal</figcaption>
					</a>
				</figure>
				<figure>
					<a href="#">
						<img src="images/gcal.jpg" alt="Google Calendar" />
						<figcaption>Google Calendar</figcaption>
					</a>
				</figure>
				<figure>
					<a href="#">
						<img src="images/outlook.jpg" alt="Microsoft Outlook" />
						<figcaption>Outlook</figcaption>
					</a>
				</figure>
			</section>
		</aside>
		<footer class="links-extra">
			<p><a href="#">Flag this event</a></p>
		</footer>
		<!--
		<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=121330687952296&amp;xfbml=1"></script><fb:like href="{$eventInfo['url']}" send="true" width="540" show_faces="true" font="" id="fb-like-button"></fb:like>
		-->		
