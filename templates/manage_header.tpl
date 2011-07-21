<header id="header">
		<h1>{$eventInfo['title']}</h1>
		<span id="event-id" style="display: none">{$eventInfo['id']}</span>
		<nav>
			<ul>
				<li{$page['manage']} id="manage"><a href="{$CURHOST}/event/manage?eventId={$eventInfo['id']}"><span>Manage</span></a></li>
				<li{$page['edit']} id="edit"><a href="{$CURHOST}/event/edit?eventId={$eventInfo['id']}" id="update_event_edit"><span>Edit</span></a></li>
				<li><a href="{$eventInfo['id']}" id="update_event_preview" target="_blank"><span>Preview</span></a></li>
			</ul>
		</nav>
	</header>
