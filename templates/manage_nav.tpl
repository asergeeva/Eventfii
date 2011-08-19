<div class="navigation">
			<nav class="block" id="manage-before">
				<header class="block-title">
					<h1>Before Event</h1>
				</header>
				<ul>
					<li{if isset($page["addguests"])} class="current"{/if}><a href="{$CURHOST}/event/manage/guests?eventId={$smarty.session.manage_event->eid}"><span>Add more guests</span></a></li>
					<li{if isset($page["email"])} class="current"{/if}><a href="{$CURHOST}/event/manage/email?eventId={$smarty.session.manage_event->eid}"><span>E-mail current guests</span></a></li>
					<li{if isset($page["text"])} class="current"{/if}><a href="{$CURHOST}/event/manage/text?eventId={$smarty.session.manage_event->eid}"><span>Sent text to guests</span></a></li>
				</ul>
			</nav>
			<nav class="block" id="manage-during">
				<header class="block-title">
					<h1>During Event</h1>
				</header>
				<ul>
					<li{if isset($page["attendeelist"])} class="current"{/if}><a href="{$CURHOST}/event/manage/attendees?eventId={$smarty.session.manage_event->eid}"><span>Attendee List</span></a></li>
					<li{if isset($page["checkin"])} class="current"{/if} class="broken"><a href="{$CURHOST}/event/manage/checkin?eventId={$smarty.session.manage_event->eid}"><span>Check-in options</span></a></li>
					<li{if isset($page["feed"])} class="current"{/if} class="broken"><a href="{$CURHOST}/event/manage/feed?eventId={$smarty.session.manage_event->eid}"><span>Live feed</span></a></li>
				</ul>
			</nav>
			<nav class="block" id="manage-during">
				<header class="block-title">
					<h1>After event</h1>
				</header>
				<ul>
					<li{if isset($page["confirm"])} class="current"{/if} class="broken"><a href="{$CURHOST}/event/manage/confirm?eventId={$smarty.session.manage_event->eid}"><span>Confirm attendee email</span></a></li>
					<li{if isset($page["followup"])} class="current"{/if} class="broken"><a href="{$CURHOST}/event/manage/followup?eventId={$smarty.session.manage_event->eid}"><span>Send follow-up email</span></a></li>
					<li{if isset($page["next"])} class="current"{/if} class="broken"><a href="{$CURHOST}/event/manage/next?eventId={$smarty.session.manage_event->eid}"><span>Plan your next event</span></a></li>
				</ul>
			</nav>
			<footer class="links-extra">
				<p><a href="{$CURHOST}">Back to home</a></p>
				<p><a href="{$CURHOST}/event/manage/cancel?eventId={$smarty.session.manage_event->eid}">Cancel this event</a></p>
			</footer>
		</div>