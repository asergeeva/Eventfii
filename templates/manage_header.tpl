<!--div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
    {*include file="event_invite_guest_create.tpl"*}
</div-->

<div id="container">
	<header id="header">
		<h1>{$eventInfo['title']}</h1>
		<!--p class="event-date">{date("F j, Y, g:i A", strtotime($eventInfo['event_datetime']))}</p-->
		<span id="manage_event_id" style="display: none">{$eventInfo['id']}</span>
		<nav>
			<ul>
				<li{$page['manage']}><a href="manage?eventId={$eventInfo['id']}" id="update_event_manage"><span>Manage</span></a></li>
				<li{$page['edit']}><a href="edit?eventId={$eventInfo['id']}" id="update_event_edit"><span>Edit</span></a></li>
				<li><a href="{$eventInfo['id']}" id="update_event_preview" target="_blank"><span>Preview</span></a></li>
			</ul>
		</nav>
	</header>
