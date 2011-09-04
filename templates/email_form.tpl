<section class="block" id="manage-email">
<fieldset>
	<label for="mail_to">
		<strong>To:</strong>
		<div>
			<select name="attendees" class="autowidth" id="email-to">
				<option value="1" selected="selected">All Attendees</option>
				<option value="2" selected="selected">Absolutely Attending</option>
				<option value="3" selected="selected">Pretty sure, 50/50, Not likely</option>
				<option value="4" selected="selected">Not Attending</option>
				<option value="5" selected="selected">No Response Yet</option>
			</select>
		</div>
	</label>
	<label>
		<strong>Subject:</strong>
		<div id="subject">Update about {$smarty.session.manage_event->title}!</div>
		<span id="event_id" style="display:none">{$smarty.session.manage_event->eid}</span>
	</label>
	<label for="message">
		<strong>Message:</strong>
		<div>
			<p>Hi {literal}{Guest Name}{/literal},</p>
			<textarea class="autowidth" name="message" id="message"></textarea>
			<input type="hidden" id="is_followup" value="{if isset($is_followup)}1{else}0{/if}" />
			<p>Thanks!<br />{$smarty.session.user->fname}</p>
		</div>
	</label>
	<footer class="buttons buttons-send">
		<!--label for="automatic_email_send_cb"><input type="checkbox" name="automatically"{if isset($eventReminder['isAuto'])} checked="checked"{/if} id="automatic_email_send_cb" /> Send automatically on</label> <input type="text" name="date" class="inputbox datebox" id="automatic_email_event_date" value="{if !isset($eventDate)}{$smarty.session.manage_event->date}{else}{$eventDate}{/if}" /> at <select name="time" id="automatic_email_send_time">{include file="timeselect.tpl" time="{$eventTime}"}</select-->
		<p><span class="btn btn-small"><input type="button" name="send" value="Send" id="send_email_reminder" /></span></p>
		</select>
		<span id="reminder_status"></span>
	</footer>
</fieldset>
</section> 
