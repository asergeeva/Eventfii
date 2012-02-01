{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block notification" style="display:none" id="notification-box">
			<p class="message">Email sent successfully</p>
		</header>
		{include file="manage_nav.tpl"}
		<div id="content">
			<section class="block" id="manage-email">
				<form method="post" action="">
					<fieldset>
						<dl>
							<dt class="inline">
								<label for="mail_to">To:</label>
							</dt>
							<dd>
								<select name="attendees" id="email-to">
									<option value="1" selected="selected">All Attendees</option>
									<option value="2" selected="selected">Absolutely Attending</option>
									<option value="3" selected="selected">Pretty sure, 50/50, Not likely</option>
									<option value="4" selected="selected">Not Attending</option>
									<option value="5" selected="selected">No Response Yet</option>
								</select>
							</dd>
							<dt class="inline">
								<label for="subject">Subject:</label>
							</dt>
							<dd>
								<input type="text" name="subject" id="subject" class="inputbox" value="Update about {$smarty.session.manage_event->title}!" />
							</dd>
							<dt class="inline">
								<label for="message">Message:</label>
							</dt>
							<dd>
								<div>
									<p>Hi {literal}{Guest Name}{/literal},</p>
									<textarea name="message" class="inputbox" id="message"></textarea>
									<input type="hidden" id="is_followup" value="{if isset($is_followup)}1{else}0{/if}" />
									<p>Thanks!<br />{$smarty.session.user->fname}</p>
								</div>
							</dd>
							<dt class="inline">
								<label for="send">Send:</label>
							</dt>
							<dd>
								<div>
									<p><label for="now"><input type="radio" name="send" id="now" /> Now</label></p>
									<p><label for="date"><input type="radio" name="send" id="date" /> on <input type="text" class="inputbox datebox" /> at <select name="time">{include file="timeselect.tpl" time=null}</select></label></p>
									<p><label for="before"><input type="radio" name="send" id="before" /> <input type="text" class="inputbox inputbox-small" /> Days <input type="text" class="inputbox inputbox-small" /> Hours <input type="text" class="inputbox inputbox-small" /> Minutes before the event starts</label></p>
								</div>
							</dd>
						</dl>
						<footer class="buttons buttons-send">{*
							<label for="automatic_email_send_cb"><input type="checkbox" name="automatically"{if isset($eventReminder['isAuto'])} checked="checked"{/if} id="automatic_email_send_cb" /> Send automatically on</label> <input type="text" name="date" class="inputbox datebox" id="automatic_email_event_date" value="{if !isset($eventDate)}{$smarty.session.manage_event->date}{else}{$eventDate}{/if}" /> at <select name="time" id="automatic_email_send_time">{include file="timeselect.tpl" time="{$eventTime}"}</select>*}
							<p><span class="btn btn-small"><input type="button" name="send" value="Save" id="send_email_reminder" /></span></p>
							</select>
							<span id="reminder_status"></span>
						</footer>
					</fieldset>
				</form>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>