{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		{include file="manage_nav.tpl"}
		<div id="content">
			<header class="tabs">
				<ul>
					<li class="current"><a href="#"><span>Scheduled Emails (0)</span></a></li>
					<li><a href="#"><span>Sent Emails (7)</span></a></li>
				</ul>
				<p><a href="{$CURHOST}/event/manage/email?create=true&amp;eventId={$smarty.session.manage_event->eid}" class="btn btn-manage"><span>Create new e-mail</span></a></p>
			</header>
			<section class="block block-tabbed table">
				<header>
					<ul>
						<li>
							<dl>
								<dt>Subject</dt>
								<dd><strong>Recipients</strong></dd>
								<dd>Date</dd>
							</dl>
						</li>
					</ul>
				</header>
				<ul>
					<li>
						<p><a href="#">Edit</a> <a href="#">Delete</a></p>
						<dl>
							<dt><a href="#">Reminder - [Event name] is tomorrow!</a></dt>
							<dd><strong>All attendees</strong></dd>
							<dd><em>Oct 15, 2011 at 10:00AM</em></dd>
						</dl>
					</li>
				</ul>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>