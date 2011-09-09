{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1 id="event-{$event->eid}">{$event->title}</h1>		
		<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($event->datetime))}</time>{if isset($event->end_date)} - {if $event->date == $event->end_date}<time datetime="">{date("g:i A", strtotime($event->end_datetime))}</time>{else}<time datetime="">{date("F j, Y, g:i A", strtotime($event->end_datetime))}</time>{/if}{/if}</p>
		<!--div class="fb-share"><fb:like href="{$EVENT_URL}/{$event->eid}" align="right" send="true" layout="button_count" width="25" style="float:right;" show_faces="false" action="like" font=""></fb:like></div-->
		<span id="event-id" style="display: none">{$event->eid}</span>{if isset($smarty.session.user) && isset($preview)}{if $smarty.session.user->id == $event->organizer->id}

		<nav>
			<ul>
				<li id="manage"><a href="{$CURHOST}/event/manage?eventId={$event->eid}"><span>Manage</span></a></li>
				<li id="edit"><a href="{$CURHOST}/event/manage/edit?eventId={$event->eid}" id="update_event_edit"><span>Edit</span></a></li>
				<li class="current"><a href="{$CURHOST}/event/a/{$event->alias}?preview=true" id="update_event_preview"><span>Preview</span></a></li>
			</ul>
		</nav>{/if}{/if}

	</header>
	<section id="main">
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
