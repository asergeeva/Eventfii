{include file="header.tpl" title="Jumpstart your social life"}

<body id="cp_body">
{include file="cp_header.tpl"}
<div id="section-header">
	<h1>Welcome, {$currentUser['fname']}!</h1>
	<h2><a href="{$CURHOST}/user/{$currentUser['id']}" id="user-{$userInfo['id']}">View your public profile</a></h2>
</div>
<div id="private">
	<div id="main">
		<div id="container">
		{include file="cp_container.tpl"}
		</div>
	</div>
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/manage.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/cp.js"></script>
{include file="footer.tpl"}