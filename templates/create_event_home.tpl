{include file="header.tpl"}
<body>

{include file="home_header.tpl"}
<div id="container">
	<header id="header">
		<h1>Create a New Event</h1>
		<span id="create_event_eventid" style="display: none">{$maxEventId}</span>
	</header>
  {include file="create_event_form.tpl"}
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>

</body>
<html>
