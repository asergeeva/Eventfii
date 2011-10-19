{include file="head.tpl"}
<body>

{include file="admin/header.tpl"}
<div id="container">
	<section id="main">
		<div class="content" style="overflow-y:scroll;width:100%">
			<section class="block">
				<h1 style="font-size:24px;">Administration</h1>
				<table id="aggregation_stats">
					<tr><th># of events:</th><td>{$num_events}</td></tr>
					<tr><th># of users:</th><td>{$num_users}</td></tr>
					<tr><th># of invites:</th><td>{$num_invites}</td></tr>
				</table>
				<table id="events_stats_container" style="width:100%; text-align:center">
				<tr>
					<th>{counter start=0 skip=1}</th>
					<th>Event name</th>
					<th>Date of event</th>
					<th>Host name</th>
					<th># of invites</th>
					<th># of FB invites</th>
					<th># of checked off</th>
				</tr>
				{foreach $events as $event}
				<tr>
					<td>{counter}</td>
					<td>{$event['title']}</td>
					<td>{$event['event_datetime']}</td>
					<td>{$event['fname']} {$event['lname']}</td>
					<td>{$event['num_invites']}</td>
					<td>{$event['fb_invite']}</td>
					<td>{$event['num_checked']}</td>
				</tr>
				{/foreach}
				</table>
			</section>
		</div>
	</section>
	<footer class="buttons" style="text-align:center">
		<p>This page should only be seen by trueRSVP employees.</p>
	</footer>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>

</body>
</html>