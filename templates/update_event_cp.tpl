{include file="header.tpl"}
<body>

{include file="cp_header.tpl"}
{include file="manage_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">Make changes to your event here.</p>
		</header>
		{include file="update_event_form.tpl"}
	</section>
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/edit.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/manage.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/cp.js"></script>

</body>
</html>
