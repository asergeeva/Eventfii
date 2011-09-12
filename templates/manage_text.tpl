{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block notification" style="display:none" id="notification-box">
			<p class="message" id="notification-message">Text sent successfully.</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="manage">
			<header class="block">
				<p class="message">Text your guests here.</p>
				<span id="event_id" style="display:none">{$smarty.session.manage_event->eid}</span>
			</header>
			<section class="block" id="manage-text">
				<fieldset>
					<dl>
						<dt class="inline"><label for="text-to">To:</label></dt>
						<dd>
							<select name="reminderRecipient" id="text-to">
								<option value="1" selected="selected">All Attendees</option>
								<option value="2" selected="selected">Absolutely Attending</option>
								<option value="3" selected="selected">Pretty sure, 50/50, Not likely</option>
								<option value="4" selected="selected">Not Attending</option>
								<option value="5" selected="selected">No Response Yet</option>
							</select>

						</dd>
						<dt class="inline">
							<label for="text-message">SMS Message: (140 characters or less)</label>
						</dt>
						<dd>
							<div>
								<textarea class="inputbox" id="text-message"></textarea>
								<p class="counter">Character Count: <em id="character-count">135</em></p>
							</div>
						</dd>
					</dl>
					<footer class="buttons buttons-send">{*
						<label for="automatic_text_send_cb"><input type="checkbox" name="automatically" id="automatic_email_send_cb" /> Send automatically on</label> <input type="text" name="date" class="inputbox datebox" id="automatic_text_event_date" value="{if !isset($eventDate)}{$smarty.session.manage_event->date}{else}{$eventDate}{/if}" /> at <select name="time" id="automatic_text_send_time">{include file="timeselect.tpl" time="{$eventTime}"}</select>*}

						<p><span class="btn btn-small"><input type="submit" name="send" value="Send" id="send_text_reminder" /></span></p>
					</footer>
					<span id="reminder_status"></span>
				</fieldset>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>
