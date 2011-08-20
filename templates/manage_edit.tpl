{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">{if isset($saved)}Event Saved.{else}Make changes to your event here.{/if}</p>
		</header>
		<div class="form">{if isset($error)}

			<header class="block">
				<p class="message">Please fix the errors below before continuing.</p>
			</header>{/if}

			<section class="block">
				<form method="post" action="{$CURHOST}/event/manage/edit?eventId={$smarty.session.manage_event->eid}">
					{include file="create_form.tpl"}
				</form>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/edit.js"></script>

</body>
</html>

