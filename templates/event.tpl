{include file="head.tpl"}
<body>

{include file="header.tpl"}
<article id="container">{if isset($smarty.get.created) && $smarty.session.user->id == $event->organizer->id}

	<div class="block info-message">
		<h1>Woohoo! Here's your awesome event page!</h1>
		<h2>And your event link to share with everyone:</h2>
		<p class="event-link">{$CURHOST}/event/a/{$event->alias}</p>
		<footer class="buttons">
			<p><a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="btn btn-med"><span>Manage</span></a></p>
		</footer>
	</div>{/if}{if ! isset($smarty.session.user)}

	<div class="block info-message">
		<div class="turtle">
			<h1>Welcome to trueRSVP!</h1>{if $event->is_public}

			<h2>Share this event link with everyone you know:</h2>
			<p class="event-link">{if $event->alias == "1af"}{$CURHOST}/demo{else}{$CURHOST}/event/a/{$event->alias}{/if}</p>{/if}

		</div>
		<div class="buttons">
			<p><a href="{$CURHOST}/method" class="btn btn-med"><span>Take a Tour</span></a> <a href="{$CURHOST}/register" class="btn btn-med"><span>Sign Up</span></a></p>
		</div>
	</div>{/if}

	<header id="header">{if (isset($smarty.session.user) && $smarty.session.user->id == $event->organizer->id) && ( ! isset($smarty.get.public) || $smarty.get.public != true)}

		<nav>
			<ul>
				<li id="manage"><a href="{$CURHOST}/event/manage?eventId={$event->eid}"><span>Manage</span></a></li>
				<li id="edit"><a href="{$CURHOST}/event/manage/edit?eventId={$event->eid}" id="update_event_edit"><span>Edit</span></a></li>
				<li class="current"><a href="{$CURHOST}/event/a/{$event->alias}?preview=true" id="update_event_preview"><span>Preview</span></a></li>
			</ul>
		</nav>{else}{if $event->is_public}

		<div class="fb-like" data-href="{$CURHOST}/event/a/{$event->alias}" data-send="true" data-layout="button_count" data-show-faces="false"></div>{/if}{/if}

		<hgroup>
			<h1 id="event-{$event->eid}"><a href="{$CURHOST}/event/a/{$event->alias}">{$event->title}</a></h1>		
			<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($event->datetime))}</time>{if isset($event->end_date)} - {if $event->date == $event->end_date}<time datetime="">{date("g:i A", strtotime($event->end_datetime))}</time>{else}<time datetime="">{date("F j, Y, g:i A", strtotime($event->end_datetime))}</time>{/if}{/if}</p>
		</hgroup>
		<span id="event-id" style="display: none">{$event->eid}</span>
	</header>
	<div id="wrapper">
		<div class="block notification" {if !isset($attendNotification)}style="display:none"{/if} id="notification-container">
			<p class="message" id="notification-message">{if isset($attendNotification)}{$attendNotification}{/if}</p>
		</div>
		<div id="event-header" style="background-image: url({$CURHOST}/images/event_photo.jpg);">
			<div class="event-upload"><a href="#" class="btn btn-manage"><span>Upload Photo</span></a></div>
			<aside id="rsvp">
				<fieldset>
					<legend>RSVP Now</legend>{if $event->rsvp_days_left > 0}
	
					<p class="rsvp-message"><em id="rsvp_days_left"{if isset($loggedIn) && ($loggedIn)} class="loggedIn"{/if}>{$event->rsvp_days_left}</em> days left to RSVP</p>{else if $event->rsvp_days_left == 0}
	
					<p class="rsvp-message"><em id="rsvp_days_left" style="display:none"{if isset($loggedIn) && ($loggedIn)} class="loggedIn"{/if}>0</em>Today is the last day to RSVP for this event</p>{else}
	
					<p class="rsvp-message"><em id="rsvp_days_left" style="display:none"{if isset($loggedIn) && ($loggedIn)} class="loggedIn"{/if}></em>The deadline to RSVP for this event had passed</p>{/if}
				
					<ol class="rsvp-list" id="event_attending_response">
						<li>
							<label class="rsvp-1{if isset($select90)} selected{/if}" for="event_attending_response_1">
								<input type="radio" name="event_attending_response" value="{$CONFOPT1}"{if isset($conf90)}{$conf90}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_1" class="rsvp-opt" /> 
								<span>Absolutely</span>
								<em>I’ll definitely be there</em>
							</label>
						</li>
						<li>
							<label class="rsvp-2{if isset($select65)} selected{/if}" for="event_attending_response_2">
								<input type="radio" name="event_attending_response" value="{$CONFOPT2}"{if isset($conf65)}{$conf65}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_2" class="rsvp-opt" /> 
								<span>Pretty sure</span>
								<em>I’ll have to check my schedule</em>
							</label>
						</li>
						<li>
							<label class="rsvp-3{if isset($select35)} selected{/if}" for="event_attending_response_3">
								<input type="radio" name="event_attending_response" value="{$CONFOPT3}"{if isset($conf35)}{$conf35}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_3" class="rsvp-opt" /> 
								<span>50/50</span>
								<em>Interested, but not ready to commit</em>
							</label>
						</li>
						<li>
							<label class="rsvp-4{if isset($select15)} selected{/if}" for="event_attending_response_4">
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
			</aside>
			<div id="event">
				<header id="event-info">
					<hgroup>
						<h1><span>What:</span> {$event->title}</h1>
						<h2><span>When:</span> {date("F j, Y, g:i A", strtotime($event->datetime))}</h2>
						<h3><span>Where:</span> <address>{if isset($event->location) && trim($event->location) neq ""}{$event->location}, {/if}{$event->address}</address></h3>
					</hgroup>
				</header>
				<footer class="user-info">
					<h1>Hosted by:</h1>
					<p class="user-img"><a href="{$CURHOST}/user/a/{$event->organizer->alias}"><img src="{$event->organizer->pic}?type=square" width="26" height="26" alt="{$event->organizer->fname} {$event->organizer->lname}" /></a></p>
					<div>
						<p class="user-name"><a href="{$CURHOST}/user/a/{$event->organizer->alias}">{$event->organizer->fname} {$event->organizer->lname}</a></p>
						<p class="user-contact"><a href="mailto:{$event->organizer->email}">Send {$event->organizer->fname} an email</a></p>
					</div>
				</footer>
			</div>
		</div>
		<aside id="extra">
			<section class="block">
				<section class="event-more" id="event-cal">
					<header>
						<h1>Share this event:</h1>
					</header>
					<p><a href="#">http://truersvp.com/event/1ab</a></p>
					<header>
						<h1>Add event to:</h1>
					</header>
					<p class="icons"><a href="{$CURHOST}/calendar/ics?eventId={$event->eid}" class="icon-ical" target="_blank">iCal</a> <a href="http://www.google.com/calendar/event?action=TEMPLATE&amp;text={$event->title}&amp;dates={if isset($event->end_date) && isset($event->end_time)}{$event->getCalDate($event->date, $event->time)}/{$event->getCalDate($event->end_date, $event->end_time)}{else}{$event->getCalDate($event->date, $event->time)}/{$event->getCalDate($event->date, $event->time)}{/if}&amp;details={$event->description}&amp;location={$event->address}&amp;trp=false&amp;sprop={$EVENT_URL}/{$event->eid}&amp;sprop={$event->description}" class="icon-gcal" target="_blank">Google Calendar</a> <a href="{$CURHOST}/calendar/vcs?eventId={$event->eid}" class="icon-outlook" target="_blank">Outlook</a></p>
				</section>
				<section id="twitter">
					<header class="block-title">
						<h1>Live Feed</h1>
					</header>
					<p class="twitter-info">Use <span>#{if isset($event->twitter)}{$event->twitter}{else}trueRSVP{$event->alias}{/if}</span> to post your tweet & pics here!</p>
					<div class="twitStream {$NUM_TWEETS}" id="tweets" title="#{if isset($event->twitter)}{$event->twitter}{else}trueRSVP{$event->alias}{/if}"></div>
				</section>{*
				<section id="sponsors">
					<header class="block-title">
						<h1>Sponsors</h1>
					</header>
				</section>*}
			</section>
		</aside>
		<section id="content">
			<div class="block" id="event-more">
				{*if $event->days_left > 0}
				<header class="block">
					<p class="message"><em>{$event->days_left}</em> {if $event->days_left == 1}day{else}days{/if} left until the event.{if $event->days_left == 1} Get exited!{/if}<br /><br />Use <em>#{if isset($event->twitter)}{$event->twitter}{else}trueRSVP{$event->eid}{/if}</em> to post your tweet & pics here!</p>
				</header>{/if*}
				<section id="event-details">
					<header class="block-title">
						<h1>Details</h1>
					</header>
					<p class="event-desc">{$event->description}</p>
				</section>
				{if isset($attending)}<section id="event-attendants">
					<header class="block-title">
						<h1>Who's coming?</h1>
					</header>
					<ul class="thumbs">{foreach from=$attending key=index item=guest}{if $index lt {$MAX_DISPLAY_GUEST}}
	
						<li>
							<figure>
								<a href="{$CURHOST}/user/a/{$guest->alias}">
									<span></span><img src="{$guest->pic}?type=normal" alt="{$guest->fname} {$guest->lname}" />
									<figcaption>{$guest->fname} {$guest->lname}</figcaption>
								</a>
							</figure>
						</li>{/if}{/foreach}
	
					</ul>
					<footer class="links-extra">
						<p><a href="#" id="all-guests">See All ({sizeof($attending)})</a></p>
					</footer>
				</section>{/if}
	
				<section id="event-location">
					<header class="block-title">
						<h1>Location</h1>
					</header>
					<figure>
						<figcaption><address>{if isset($event->location) && trim($event->location) neq ""}{$event->location}, {/if}{$event->address}</address></figcaption>
						<iframe width="100%" height="203" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{$GOOGLE_MAP_URL}?q={$event->address|urlencode}&amp;hnear={$event->address|urlencode}&amp;hl=en&amp;sll={$event->location_lat},{$event->location_long}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed&amp;iwloc=near"></iframe>
					</figure>
				</section>
				<section id="event-comments">
					<header class="block-title">
						<h1>Comments</h1>
					</header>
					<div class="fb-comments" data-href="{$CURHOST}/event/{$event->eid}" data-num-posts="2" data-width="540px"></div>
				</section>
			</div>
		</section>
	</div>
</article>
{include file="footer.tpl"}
{include file="popup_login.tpl"}
{include file="popup_seeall.tpl"}
{include file="popup_rsvp_multiple.tpl"}

{include file="js_global.tpl"}
{include file="js_event.tpl"}
</body>
</html>