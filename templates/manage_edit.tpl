{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<div class="form">
			<section class="block">{if isset($error)}

				<header class="block error">
					<p class="message">Please fix the errors below to update your event.</p>
				</header>{elseif isset($saved)}

				<header class="block notification">
					<p class="message">Event Updated.</p>
				</header>{/if}

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
{include file="js_create.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/edit.js"></script>
<script type="text/javascript" 

</body>
</html>

