<aside class="extra">
			<section class="block" id="rsvp">
				<header class="block-title">
					<h1>Your RSVP</h1>
				</header>
				<p class="rsvp-message"><em>{$event->rsvp_days_left}</em> days left to RSVP</p>
				<fieldset>				
					<ol class="rsvp-list" id="event_attending_response">
						<li{if isset($select90)} class="selected"{/if}>
							<label for="event_attending_response_1">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT1}"{if isset($conf90)}{$conf90}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_1" /> 
								<em>Yes, I'll absolutely be there</em>
							</label>
						</li>
						<li{if isset($select65)} class="selected"{/if}>
							<label for="event_attending_response_2">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT2}"{if isset($conf65)}{$conf65}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_2" /> 
								<em>I'm pretty sure I'll be there</em>
							</label>
						</li>
						<li{if isset($select35)} class="selected"{/if}>
							<label for="event_attending_response_3">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT3}"{if isset($conf35)}{$conf35}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_3" /> 
								<em>I'll go unless my plans change</em>
							</label>
						</li>
						<li{if isset($select15)} class="selected"{/if}>
							<label for="event_attending_response_4">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT4}"{if isset($conf15)}{$conf15}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_4" /> 
								<em>I probably can't go</em>
							</label>
						</li>
						<li{if isset($select4)} class="selected"{/if}>
							<label for="event_attending_response_5">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT5}"{if isset($conf4)}{$conf4}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_5" /> 
								<em>No, but I'm a supporter</em>
							</label>
						</li>
						<li{if isset($select1)} class="selected"{/if}>
							<label for="event_attending_response_6">
								<input type="radio" class="event_attending_response" name="event_attending_response" value="{$CONFOPT6}"{if isset($conf1)}{$conf1}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_6" /> 
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