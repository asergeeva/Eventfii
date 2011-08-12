<header id="header">
		<h1>{$smarty.session.manage_event->title}</h1>
		<h2></h2>
		<span id="event-id" style="display: none">{$smarty.get.eventId}</span>
		<nav>
			<ul>
				<li{if isset($page.manage)} class="current"{/if} id="manage"><a href="{$CURHOST}/event/manage?eventId={$smarty.get.eventId}"><span>Manage</span></a></li>
				<li{if isset($page.edit)} class="current"{/if} id="edit"><a href="{$CURHOST}/event/manage/edit?eventId={$smarty.get.eventId}" id="update_event_edit"><span>Edit</span></a></li>
				<li><a href="{$CURHOST}/event/{$smarty.get.eventId}" id="update_event_preview" target="_blank"><span>Preview</span></a></li>
			</ul>
		</nav>
	</header>
