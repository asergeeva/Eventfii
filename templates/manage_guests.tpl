{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">{if isset($message)}

		<header class="block">
			<p class="message">{$message}</p>
		</header>{else}

		<header class="block">
			<p class="message">Make changes to your event here.</p>
		</header>{/if}{if isset($error.add_guest)}

		<div class="block">
			<p class="message-error">The following guests were skipped because their e-mails are invalid: {$error.add_guest}</p>
		</div>{/if}

		{include file="manage_nav.tpl"}
		<div class="content">
			{include file="create_guest.tpl"}
			{include file="manage_invites.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}
</body>
</html>
