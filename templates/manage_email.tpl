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
							<input type="checkbox" name="automatically" /> Send automatically on <input type="text" class="inputbox datebox" id="send-automatically" /> at <select class="timebox"></select>
						</div>
					</label>
					<label for="mail_to">
						<strong>To:</strong>
						<div>
							<input type="text" class="inputbox autowidth" name="mail_to" value="All Attendees" id="mail_to" />
						</div>
					</label>
					<label for="subject">
						<strong>Subject:</strong>
						<div>
							<input type="text" class="inputbox autowidth" name="subject" value="Event Name --- Time --- trueRSVP" id="subject" />
						</div>
					</label>
					<label for="message">
						<strong>Message:</strong>
						<div>
							<textarea class="autowidth" name="message" id="message">Hi [First Name]!

I would like to remind you that [Event name] that’s happening on [Time]. Please go to trueRSVP at the link below to update your RSVP!

- [Host Name]</textarea>
						</div>
					</label>
				</fieldset>
				<footer class="buttons-submit">
					<a href="#"><span>Update</span></a> <a href="#"><span>Send Now</span></a>
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
