{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1 id="event-{$eventInfo['id']}">{$eventInfo['title']}</h1>
		<span id="event_id" style="display: none">{$eventInfo['id']}</span>
		<p class="event-time"><time datetime="">{date("F j, Y, g:i A", strtotime($eventInfo['event_datetime']))}</time></p>
	</header>
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
