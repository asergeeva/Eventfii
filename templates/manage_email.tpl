{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block notification" style="display:none" id="notification-box">
			<p class="message">Email sent successfully</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			<header class="block">
				<p class="message">Send reminders and followup emails here</p>
			</header>
			{include file="email_form.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>
