{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1 id="event-{$event->eid}">{$event->title}</h1>
		<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($event->event_datetime))}</time></p>
		<span id="event-id" style="display: none">{$event->eid}</span>
	</header>
	<section id="main">
		{include file="event_side.tpl"}
		{include file="event_main.tpl"}
	</section>
</div>
{include file="footer.tpl"}
<div class="popup-container" id="log-in">
	<div class="popup block">
		<p class="message">Log in or Sign up for trueRSVP to RSVP to <a href="{$CURHOST}/event/{$event->eid}">{$event->title}</a>. <strong>Click <a href="{$CURHOST}/method">here</a> to find out why trueRSVP can help make your next event a success.</strong></p>
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
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/twitStream.js"></script>

</body>
</html>