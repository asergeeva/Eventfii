{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">{if isset($smarty.get.created) && $smarty.session.user->id == $event->organizer->id}

	<header class="block info-message">
		<h1>Woohoo! Here's your awesome event page!</h1>
		<h2>And your event link to share with everyone:</h2>
		<p class="event-link">{$CURHOST}/event/a/{$event->alias}</p>
		<footer class="buttons">
			<p><a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="btn btn-med"><span>Manage</span></a></p>
		</footer>
	</header>{/if}{if ! isset($smarty.session.user)}

	<header class="block info-message">
		<div class="turtle">
			<h1>Welcome to trueRSVP!</h1>{if $event->is_public}

			<h2>Share this event link with everyone you know:</h2>
			<p class="event-link">{if $event->alias == "1af"}{$CURHOST}/demo{else}{$CURHOST}/event/a/{$event->alias}{/if}</p>{/if}

		</div>
		<footer class="buttons">
			<p><a href="{$CURHOST}/method" class="btn btn-med"><span>Take a Tour</span></a> <a href="{$CURHOST}/register" class="btn btn-med"><span>Sign Up</span></a></p>
		</footer>
	</header>{/if}

	<header id="header">
		<h1 id="event-{$event->eid}">{$event->title}</h1>		
		<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($event->datetime))}</time>{if isset($event->end_date)} - {if $event->date == $event->end_date}<time datetime="">{date("g:i A", strtotime($event->end_datetime))}</time>{else}<time datetime="">{date("F j, Y, g:i A", strtotime($event->end_datetime))}</time>{/if}{/if}</p>
		<!--div class="fb-share"><fb:like href="{$EVENT_URL}/{$event->eid}" align="right" send="true" layout="button_count" width="25" style="float:right;" show_faces="false" action="like" font=""></fb:like></div-->
		<span id="event-id" style="display: none">{$event->eid}</span>{if (isset($smarty.session.user) && $smarty.session.user->id == $event->organizer->id ) && ( isset($smarty.get.preview) || isset($smarty.get.created))}{if $smarty.session.user->id == $event->organizer->id}

		<nav>
			<ul>
				<li id="manage"><a href="{$CURHOST}/event/manage?eventId={$event->eid}"><span>Manage</span></a></li>
				<li id="edit"><a href="{$CURHOST}/event/manage/edit?eventId={$event->eid}" id="update_event_edit"><span>Edit</span></a></li>
				<li class="current"><a href="{$CURHOST}/event/a/{$event->alias}?preview=true" id="update_event_preview"><span>Preview</span></a></li>
			</ul>
		</nav>{/if}{/if}

	</header>
	<section id="main">
		<header class="block notification" {if !isset($attendNotification)}style="display:none"{/if} id="notification-container">
			<p class="message" id="notification-message">{if isset($attendNotification)}{$attendNotification}{/if}</p>
		</header>
		{include file="event_side.tpl"}
		{include file="event_main.tpl"}
	</section>
</div>
{include file="footer.tpl"}
<div class="popup-container" id="log-in">
	<div class="popup block popup-small">
		<p class="message">Log in to RSVP/<a href="#">Don't have an account yet?</a></p>
		{include file="login_form.tpl"}
		<p class="popup-close"><a href="#">X</a></p>
	</div>
</div>
<div class="popup-container" id="see-all">
	<div class="popup block">
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
		<p class="popup-close"><a href="#">X</a></p>
	</div>
</div>

{include file="js_global.tpl"}
{include file="js_event.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/twitStream.js"></script>

</body>
</html>
