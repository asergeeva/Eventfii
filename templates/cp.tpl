{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">{if isset($isNewUser)}

	<header class="block info-message">
		<h1>Hi {$smarty.session.user->fname}, welcome to trueRSVP!</h1>
		<h2>Check out what you can do first:</h2>
		<footer class="buttons">
			<p><a href="{$CURHOST}/method" class="btn btn-med"><span>Take a Tour</span></a> <a href="{$CURHOST}/event/create" class="btn btn-med"><span>Create New Event</span></a> <a href="{$CURHOST}/settings" class="btn btn-med"><span>Update Profile</span></a></p>
		</footer>
	</header>{/if}

	{include file="cp_header.tpl"}
	<section id="main">{if isset($smarty.get.loggedIn) && ! isset($isNewUser)}

		<header class="block notification">{if $smarty.get.loggedIn == 'true'}

			<p class="message">You are logged in as {$smarty.session.user->fname} {$smarty.session.user->lname}</p>{else}
			
			<p class="message">You are already logged in as {$smarty.session.user->fname} {$smarty.session.user->lname}</p>{/if}

		</header>{/if}

		<aside class="extra">
			{include file="block_cp_user.tpl"}
			{include file="block_events_invited.tpl"}
		</aside>
		<div class="content">{if isset($smarty.get.view) && $smarty.get.view == "created"}

			{include file="block_events_created_past.tpl"}{elseif isset($smarty.get.view) && $smarty.get.view == "attended"}

			{include file="block_events_attended.tpl"}{else}

			<header class="block">
				<p class="message">You can manage all of your upcoming events from this home page.</p>
			</header>
			{include file="block_events_created.tpl"}
			{include file="block_events_attending.tpl"}{/if}

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