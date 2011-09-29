<aside class="extra">

			<section class="block" id="rsvp">
				<header class="block-title">
					<h1>Your RSVP</h1>
				</header>
				
				{if $event->rsvp_days_left > 0}
				<p class="rsvp-message"><em id="rsvp_days_left" {if isset($loggedIn) && ($loggedIn)}class="loggedIn"{/if}>{$event->rsvp_days_left}</em> days left to RSVP</p>
				{else if $event->rsvp_days_left == 0}
				<p class="rsvp-message"><em id="rsvp_days_left" style="display:none" {if isset($loggedIn) && ($loggedIn)}class="loggedIn"{/if}>0</em>Today is the last day to RSVP for this event</p>
				{else}
				<p class="rsvp-message"><em id="rsvp_days_left" style="display:none" {if isset($loggedIn) && ($loggedIn)}class="loggedIn"{/if}></em>The deadline to RSVP for this event had passed</p>
				{/if}
				
				<fieldset>				
					<ol class="rsvp-list" id="event_attending_response">
						<li>
							<label class="rsvp-label rsvp-1{if isset($select90)} selected{/if}" for="event_attending_response_1">
								<input type="radio" name="event_attending_response" value="{$CONFOPT1}"{if isset($conf90)}{$conf90}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_1" class="rsvp-opt" /> 
								<span>Absolutely</span>
								<em>I’ll definitely be there</em>
							</label>
						</li>
						<li>
							<label class="rsvp-label rsvp-2{if isset($select65)} selected{/if}" for="event_attending_response_2">
								<input type="radio" name="event_attending_response" value="{$CONFOPT2}"{if isset($conf65)}{$conf65}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_2" class="rsvp-opt" /> 
								<span>Pretty sure</span>
								<em>I’ll have to check my schedule</em>
							</label>
						</li>
						<li>
							<label class="rsvp-label rsvp-3{if isset($select35)} selected{/if}" for="event_attending_response_3">
								<input type="radio" name="event_attending_response" value="{$CONFOPT3}"{if isset($conf35)}{$conf35}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_3" class="rsvp-opt" /> 
								<span>50/50</span>
								<em>Interested, but not ready to commit</em>
							</label>
						</li>
						<li>
							<label class="rsvp-label rsvp-4{if isset($select15)} selected{/if}" for="event_attending_response_4">
								<input type="radio" name="event_attending_response" value="{$CONFOPT4}"{if isset($conf15)}{$conf15}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_4" class="rsvp-opt" /> 
								<span>Not likely</span>
								<em>I probably won’t go</em>
							</label>
						</li>
						<li>
							<label class="rsvp-label rsvp-5{if isset($select4)} selected{/if}" for="event_attending_response_5">
								<input type="radio" name="event_attending_response" value="{$CONFOPT5}"{if isset($conf4)}{$conf4}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_5" class="rsvp-opt" /> 
								<span>Raincheck</span>
								<em>Can’t make it this time</em>
							</label>
						</li>{*
						<li>
							<label class="rsvp-6{if isset($select1)} selected{/if}" for="event_attending_response_6">
								<input type="radio" name="event_attending_response" value="{$CONFOPT6}"{if isset($conf1)}{$conf1}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_6" /> 
								<span>Spam. Take me off this host’s list.</span>
							</label>
						</li>*}
					</ol>
				</fieldset>
			</section>

			<section class="block" id="twitter">
				<header class="block-title">
					<h1>Live Feed</h1>
				</header>
				<p class="twitter-info">Use <span>#{if isset($event->twitter)}{$event->twitter}{else}trueRSVP{$event->eid}{/if}</span> to post your tweet & pics here!</p>
				<div class="twitStream {$NUM_TWEETS}" id="tweets" title="#{if isset($event->twitter)}{$event->twitter}{else}trueRSVP{$event->eid}{/if}"></div>
			</section>
		</aside>
		<!--footer class="links-extra">
			<p><a href="#">Flag this event</a></p>
		</footer-->