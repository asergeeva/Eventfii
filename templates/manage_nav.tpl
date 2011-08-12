<div class="navigation">
			<nav class="block" id="cp-nav">
				<ul>
					<li{if isset($page["addguests"])} class="current"{/if}><a href="{$CURHOST}/event/manage/guests?eventId={$smarty.session.manage_event->eid}"><span>Add more guests</span></a></li>
					<li{if isset($page["email"])} class="current"{/if}><a href="{$CURHOST}/event/manage/email?eventId={$smarty.session.manage_event->eid}"><span>E-mail current guests</span></a></li>
					<li{if isset($page["text"])} class="current"{/if}><a href="{$CURHOST}/event/manage/text?eventId={$smarty.session.manage_event->eid}"><span>Sent text to guests</span></a></li>
				</ul>
			</nav>
			<footer class="links-extra">
				<p><a href="{$CURHOST}">Back to home</a></p>
			</footer>
		</div>