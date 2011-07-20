{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, {$userInfo['fname']}!</h1>
		<h2><a href="{$CURHOST}/user/{$userInfo['id']}" id="user-{$userInfo['id']}">View your public profile</a></h2>
	<span id="user-id" style="display:none;">{$userInfo['id']}</span>
	</header>
	{include file="cp_container.tpl"}
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>

</body>
</html>