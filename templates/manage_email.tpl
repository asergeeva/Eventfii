{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">Send Email reminders and followups here</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			<section class="block" id="manage-email">
				<fieldset>
					<label for="send-automatically">
						<strong></strong>
						<div>
							<input type="checkbox" name="automatically" id="automatic_email_send_cb"{if isset($eventReminder['isAuto'])} checked="checked"{/if} /> Send automatically on 
              <input type="text" class="inputbox datebox" id="automatic_email_event_date" value="{if isset($eventDate)}{$eventDate}{/if}" /> at 
              <input type="text" class="timebox" id="automatic_email_send_time" value="{if isset($eventTime)}{$eventTime}{/if}" />
						</div>
					</label>
					<label for="mail_to">
						<strong>To:</strong>
						<div>
							<select class="autowidth" id="email-to">
								<option>All Attendees</option>
							</select>
						</div>
					</label>
					<label for="subject">
						<strong>Subject:</strong>
						<div>
							<input type="text" class="inputbox autowidth" name="subject" value="{if isset($eventReminder['subject'])}{$eventReminder['subject']}{/if}" id="subject" />
						</div>
					</label>
					<label for="message">
						<strong>Message:</strong>
						<div>
							<textarea class="autowidth" name="message" id="message">{if isset($eventReminder['message'])}{$eventReminder['message']}{/if}</textarea>
     {literal}
     {Guest name}
		 {Host name}
		 {Event name}
		 {Event time}
     {/literal}
						</div>
					</label>
				</fieldset>
				<footer class="buttons buttons-submit">
					<a href="#" id="update_email_reminder"><span>Update</span></a> 
					<a href="#" id="send_email_reminder"><span>Send Now</span></a>
					<span id="reminder_status"></span>
				</footer>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>
