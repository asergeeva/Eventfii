<aside class="extra">
			<section class="block" id="rsvp">
				<header class="block-title">
					<h1>Your RSVP</h1>
				</header>
				<p class="rsvp-message"><em>{$eventInfo['rsvp_days_left']}</em> days left to RSVP</p>
				<fieldset>				
					<ol class="rsvp-list" id="event_attending_response">
						<li{$select90}>
							<label for="event_attending_response_1">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT1}"{$conf90}{$disabled} id="event_attending_response_1" /> 
								<em>Yes, I'll absolutely be there</em>
							</label>
						</li>
						<li{$select65}>
							<label for="event_attending_response_2">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT2}"{$conf65}{$disabled} id="event_attending_response_2" /> 
								<em>I'm pretty sure I'll be there</em>
							</label>
						</li>
						<li{$select35}>
							<label for="event_attending_response_3">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT3}"{$conf35}{$disabled} id="event_attending_response_3" /> 
								<em>I'll go unless my plans change</em>
							</label>
						</li>
						<li{$select15}>
							<label for="event_attending_response_4">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT4}"{$conf15}{$disabled} id="event_attending_response_4" /> 
								<em>I probably can't go</em>
							</label>
						</li>
						<li{$select4}>
							<label for="event_attending_response_5">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT5}"{$conf4}{$disabled} id="event_attending_response_5" /> 
								<em>No, but I'm a supporter</em>
							</label>
						</li>
						<li{$select1}>
							<label for="event_attending_response_6">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT6}"{$conf1}{$disabled} id="event_attending_response_6" /> 
								<em>No, I'm not interested</em>
							</label>
						</li>
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
						<img src="{$CURHOST}/images/ical.jpg" alt="iCal" />
						<figcaption>iCal</figcaption>
					</a>
				</figure>
				<figure>
					<a href="#">
						<img src="{$CURHOST}/images/gcal.jpg" alt="Google Calendar" />
						<figcaption>Google Calendar</figcaption>
					</a>
				</figure>
				<figure>
					<a href="#">
						<img src="{$CURHOST}/images/outlook.jpg" alt="Microsoft Outlook" />
						<figcaption>Outlook</figcaption>
					</a>
				</figure>
			</section>
		</aside>
		<!--footer class="links-extra">
			<p><a href="#">Flag this event</a></p>
		</footer-->