<aside class="extra">
			<section class="block" id="rsvp">
				<header class="block-title">
					<h1>Your RSVP</h1>
				</header>
				<p class="rsvp-message"><em>{$eventInfo['days_left']}</em> days left to RSVP</p>
				<fieldset>				
					<ol class="rsvp-list" id="event_attending_response">
						<li><label for="event_attending_response_1"><input type="radio" name="event_attending_response" value="{$CONFOPT1}" {$conf90} id="event_attending_response_1" {$disabled}/> <em>Yes, I'll absolutely be there</em></label></li>
						<li><label for="event_attending_response_2"><input type="radio" name="event_attending_response" value="{$CONFOPT2}" {$conf65} id="event_attending_response_2" {$disabled}/> <em>I'm pretty sure I'll be there</em></label></li>
						<li><label for="event_attending_response_3"><input type="radio" name="event_attending_response" value="{$CONFOPT3}" {$conf35} id="event_attending_response_3" {$disabled}/> <em>I'll go unless my plans change</em></label></li>
						<li><label for="event_attending_response_4"><input type="radio" name="event_attending_response" value="{$CONFOPT4}" {$conf15} id="event_attending_response_4" {$disabled}/> <em>I probably can't go</em></label></li>
						<li><label for="event_attending_response_5"><input type="radio" name="event_attending_response" value="{$CONFOPT5}" {$conf4} id="event_attending_response_5" {$disabled}/> <em>No, but I'm a supporter</em></label></li>
						<li><label for="event_attending_response_6"><input type="radio" name="event_attending_response" value="{$CONFOPT6}" {$conf1} id="event_attending_response_6" {$disabled}/> <em>No, I'm not interested</em></label></li>
					</ol>
				</fieldset>
				<div id="response_stat_msg"></div>
			</section>
			{include file="event_creator.tpl"}			
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
