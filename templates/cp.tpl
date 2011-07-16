{include file="header.tpl"}
<body id="cp_body">

{include file="cp_header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, {$currentUser['fname']}!</h1>
	</header>
	<!--h2><a href="{$CURHOST}/user/{$currentUser['id']}" id="user-{$userInfo['id']}">View your public profile</a></h2>
	<span id="usersid" style="display:none;">{$currentUser['id']}</span-->
	<span id="usersid" style="display:none;">{$currentUser['id']}</span>
	{include file="cp_container.tpl"}
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/manage.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/cp.js"></script>

</body>
</html>
