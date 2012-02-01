{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	<div class="page-error">
		<h1>You can't add guests to an event that has already happened!</h1>
		<p><a href="{$CURHOST}/event/manage?eventId={$smarty.session.manage_event->eid}">Back to manage page</a></p>
		<p>Think you're seeing this message in error? <a href="{$CURHOST}/contact">Let us know!</a></p>
	</div>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_event.tpl"}

</body>
</html>
