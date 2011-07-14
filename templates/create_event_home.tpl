{include file="header.tpl"}
<body>

{include file="home_header.tpl"}
<!--div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
  	{*include file="event_invite_guest_create.tpl"*}
</div-->
<!-- BEGIN Add Guest Section
<div class="event_guest">
	<a href="#"><img src="{$EC_IMG_PATH}/addguests.png" id="event_guest_invite" rel="#event_guest_invite_overlay" /></a>
</div>
END Add Guest Section-->
	
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
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>

</body>
<html>
