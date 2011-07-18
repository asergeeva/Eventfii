{include file="header.tpl"}
{include file="cp_header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, {$currentUser['fname']}!</h1>
	</header>
	<span id="usersid" style="display:none;">{$currentUser['id']}</span>
	{include file="cp_container.tpl"}
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/jquery.jeditable.mini.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/manage.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/cp.js"></script>
{include file="footer.tpl"}