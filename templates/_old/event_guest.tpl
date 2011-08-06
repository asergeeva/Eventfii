{include file="header.tpl"}
{include file="cp_header.tpl"}
<div id="container">
  <header id="header">
    <h1 id="event-{$eventInfo['id']}">{$eventInfo['title']}</h1>
    <span id="event_id" style="display: none">{$eventInfo['id']}</span>
    <p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($eventInfo['event_datetime']))}</time></p>
  </header>
  <section id="main">
    <header class="block">
      <p class="message"><em>{$eventInfo['days_left']}</em> days left until the event. Get excited!</p>
    </header>
	<div class="content">
			<section class="block" id="event-info">
				<header class="block-title">
					<h1>Find out more</h1>
				</header>
				<div class="event-desc">
					<figure>
						<figcaption><address>{$eventInfo['location_address']}</address></figcaption>
						<iframe width="167" height="137" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$eventInfo['location_address']|urlencode}&amp;hnear={$eventInfo['location_address']|urlencode}&amp;hl=en&amp;sll={$eventInfo['location_lat']},{$eventInfo['location_long']}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed"></iframe>
					</figure>
					<p>{$eventInfo['description']}</p>
					<!--p>{$twitterHash}</p-->
				</div>
				<!-- Not in design
				<div id="event_cost">
					<span id="event_gets_price">You will get</span>
					<div id="event_gets">
						{*$eventInfo['gets']*}
					</div>
				</div>
				-->
			</section>
			<section class="block" id="event-attendants">
				<header class="block-title">
					<h1>Who's coming?</h1>
				</header>
				<ul class="thumbs">
					{foreach from=$attending key=k item=v}
						<li>
							<figure>
								<a href="{$CURHOST}/user/{$v['user_id']}">
								{if $v['pic']==''}
									<img src="{$CURHOST}/images/default_thumb.jpg" />
								{else}
									<img src="{$CURHOST}/upload/user/{$v['pic']}" />
								{/if}
									<figcaption>{$v['fname']} {$v['lname']}</figcaption>
								</a>
							</figure>
						</li>
					{/foreach}
				</ul>
				<footer class="link-extra">
					<p><a href="#">See All (?)</a></p>
				</footer>
			</section>
			<section class="block" id="event-comments">
				<header class="block-title">
					<h1>Comments</h1>
				</header>
				<p><a href="{$CURHOST}/login">Log in to comments</a></p>
			</section>
		</div>
		<aside class="extra">
			<section class="block" id="rsvp">
				<header class="block-title">
					<h1>Your RSVP</h1>
				</header>
				<p class="rsvp-message"><em>{$eventInfo['days_left']}</em> days left to RSVP</p>
				<p><a href="{$CURHOST}/login">Log in to reserve a spot</a></p>
				<div id="response_stat_msg"></div>
			</section>
			<section class="block" id="event-hosted">
				<header class="block-title">
					<h1>Hosted by</h1>
				</header>
				<p class="user-name"><a href="{$CURHOST}/user/{$organizer['id']}">{$organizer['fname']} {$organizer['lname']}</a></p>
				<p class="user-img">
					<a href="{$CURHOST}/user/{$organizer['id']}">
					{if $organizer['pic'] eq ''}
						<img src="{$CURHOST}/images/default_thumb.jpg" alt="{$organizer['fname']} {$organizer['lname']}" />
					{else}
						<img src="{$CURHOST}/upload/user/{$organizer['pic']}" alt="{$organizer['fname']} {$organizer['lname']}" />
					{/if}
					</a>
				</p>
				<ul class="user-name-extra">
					<li>USC Student</li>
					<li>President of USG</li>
				</ul>
				<footer class="link-extra">
					<p><a href="#">Send Anna a message</a></p>
				</footer>
			</section>		
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
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/event.js"></script>
{include file="footer.tpl"}
