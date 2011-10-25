<div class="navigation">
			<nav class="manage-nav">
				<ul class="buttons">
					<li><a href="{$CURHOST}/event/manage/attendees?eventId={$smarty.session.manage_event->eid}" class="{if isset($page.attendeelist)}current {/if}btn btn-nav nav-guestlist"><span><em>Guest List</em></span></a></li>
					<li><a href="{$CURHOST}/event/manage/guests?eventId={$smarty.session.manage_event->eid}" class="{if isset($page.addguests)}current {/if}btn btn-nav nav-addguests"><span><em>Add Guests</em></span></a></li>
					<li><a href="{$CURHOST}/event/manage/email?eventId={$smarty.session.manage_event->eid}" class="{if isset($page.email)}current {/if}btn btn-nav nav-email"><span><em>Email Guests</em></span></a></li>
					<li><a href="{$CURHOST}/event/manage/text?eventId={$smarty.session.manage_event->eid}" class="{if isset($page.text)}current {/if}btn btn-nav nav-text"><span><em>Text Guests</em></span></a></li>
				</ul>
			</nav>
			<footer class="links-extra">
				<p>{if ! isset($page.cp)}<a href="{$CURHOST}/event/manage?eventId={$smarty.session.manage_event->eid}">Back to manage page</a>{else}<a href="{$CURHOST}">Back to home</a>{/if}</p>
				<p><a href="#" id="cancel-event">Cancel this event</a></p>
			</footer>
		</div>
