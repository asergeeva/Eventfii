{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, {$smarty.session.user->fname}!</h1>
		<h2><a href="{$CURHOST}/user/{$smarty.session.user->id}" id="user-{$smarty.session.user->id}">View your public profile</a></h2>
		<span id="user-id" style="display:none;">{$smarty.session.user->id}</span>
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