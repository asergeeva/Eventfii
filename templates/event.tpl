{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1 id="event-{$eventInfo['id']}">{$eventInfo['title']}</h1>
		<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($eventInfo['event_datetime']))}</time></p>
		<span id="event-id" style="display: none">{$eventInfo['id']}</span>
	</header>
	<!--
	<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=121330687952296&amp;xfbml=1"></script><fb:like href="{$eventInfo['url']}" send="true" width="540" show_faces="true" font="" id="fb-like-button"></fb:like>
	-->
	<section id="main">
		{include file="event_main.tpl"}
		{include file="event_side.tpl"}
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_event.tpl"}

</body>
</html>