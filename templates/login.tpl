{include file="head.tpl" title="Jumpstart your social life"}
<body>

{include file="header.tpl"}
<div id="container">{if isset($smarty.get.redirect)}
	<header class="block error">
		<p class="message">You must be logged in to access this page.</p>		
	</header>{/if}{if isset($error.login)}

	<header class="block error">
		<p class="message">{$error.login}</p>
	</header>{/if}

	<section class="form-login block">
		{include file="login_form.tpl"}
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	FB.Event.subscribe('auth.login', FBCON.loginUser);
});
</script>
</body>
</html>
