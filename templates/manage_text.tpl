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
			<section class="block" id="manage-text">
				<fieldset>
					<label for="send-automatically">
						<strong></strong>
						<div>
							<input type="checkbox" name="automatically" /> Send automatically on <input type="text" class="inputbox datebox" id="send-automatically" /> at <select class="timebox"></select>
						</div>
					</label>
					<label for="text-to">
						<strong>To:</strong>
						<div>
							<select class="autowidth" id="email-to">
								<option>All Attendees</option>
							</select>
						</div>
					</label>
					<label for="text-message">
						<strong>SMS Message: (140 characters or less)</strong>
						<div>
							<textarea class="autowidth" id="text-message">Thanks for coming to [eventname]! It was a blast. Go to http://truersvp.com/event2 to see all the cool pics & tweets! -[hostname]</textarea>
						</div>
						<p class="counter">Character Count: <em>135</em></p>
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
