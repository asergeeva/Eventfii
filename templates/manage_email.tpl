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
							<input type="checkbox" name="automatically" id="automatic_email_send_cb" {$eventReminder['isAuto']} /> Send automatically on 
              <input type="text" class="inputbox datebox" id="send-automatically" value="{$eventDate}" /> at 
              <select class="timebox" id="automatic_email_send_time">
              	<option {if $eventTime eq '01:00'}selected="selected"{/if}>01:00</option>
                <option {if $eventTime eq '02:00'}selected="selected"{/if}>02:00</option>
                <option {if $eventTime eq '03:00'}selected="selected"{/if}>03:00</option>
                <option {if $eventTime eq '04:00'}selected="selected"{/if}>04:00</option>
                <option {if $eventTime eq '05:00'}selected="selected"{/if}>05:00</option>
                <option {if $eventTime eq '06:00'}selected="selected"{/if}>06:00</option>
                <option {if $eventTime eq '07:00'}selected="selected"{/if}>07:00</option>
                <option {if $eventTime eq '08:00'}selected="selected"{/if}>08:00</option>
                <option {if $eventTime eq '09:00'}selected="selected"{/if}>09:00</option>
                <option {if $eventTime eq '10:00'}selected="selected"{/if}>10:00</option>
                <option {if $eventTime eq '11:00'}selected="selected"{/if}>11:00</option>
                <option {if $eventTime eq '12:00'}selected="selected"{/if}>12:00</option>
              </select>
              <select class="timebox" id="automatic_email_send_timeframe">
              	<option {if $eventTimeMid eq 'AM'}selected="selected"{/if}>AM</option>
                <option {if $eventTimeMid eq 'PM'}selected="selected"{/if}>PM</option>
              </select>
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
							<input type="text" class="inputbox autowidth" name="subject" value="{$eventReminder['subject']}" id="subject" />
						</div>
					</label>
					<label for="message">
						<strong>Message:</strong>
						<div>
							<textarea class="autowidth" name="message" id="message">{$eventReminder['message']}</textarea>
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
