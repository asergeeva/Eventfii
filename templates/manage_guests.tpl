{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">Make changes to your event here.</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			{include file="manage_invites.tpl"}
			{include file="create_guest.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}
{include file="js_cp.tpl"}
{include file="js_create.tpl"}

</body>
</html>
