{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">{if isset($smarty.get.loggedIn)}

		<header class="block notification">{if $smarty.get.loggedIn == 'true'}

			<p class="message">You are now logged in as {$smarty.session.user->fname} {$smarty.session.user->lname}</p>{else}
			
			<p class="message">You are already logged in as {$smarty.session.user->fname} {$smarty.session.user->lname}</p>{/if}

		</header>{/if}

		{include file="cp_user.tpl"}
		<div class="content">
			<header class="block">
				<p class="message">You can manage all of your upcoming events from this home page.</p>
			</header>
			{include file="event_created.tpl"}
			{include file="event_attending.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>

</body>
</html>