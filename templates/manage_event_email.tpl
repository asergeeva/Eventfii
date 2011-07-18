{include file="header.tpl"}
<body id="cp_body">

{include file="cp_header.tpl"}
{include file="manage_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">Send Email reminders and followups here</p>
		</header>
		<div class="navigation">
			<nav class="block" id="cp-nav">
				<ul>
					<li{$page["addguests"]}><a href="addguests.html"><span>Add more guests</span></a></li>
					<li{$page["email"]}><a href="email?eventId={$eventInfo['id']}"><span>E-mail current guests</span></a></li>
					<li{$page["text"]}><a href="text.html"><span>Sent text to guests</span></a></li>
				</ul>
			</nav>
			<footer class="links-extra">
				<p><a href="{$CURHOST}">Back to home</a></p>
			</footer>
		</div>
		<div class="content">
			<section class="block" id="cp-text">
				<nav class="horizontal-nav">
					<ul>
						<li><a href="#">Reminder</a></li>
						<li><a href="#">Follow-Up</a></li>
						<li><a href="#">Custom</a></li>
					</ul>
				</nav>
        <div id="email_form_container">
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
        
        I would like to remind you that [Event name] that's happening on [Time]. Please go to trueRSVP at the link below to update your RSVP!
        
        - [Host Name]</textarea>
            </div>
          </label>
        </fieldset>
        </div>
				<footer class="buttons-submit">
					<a href="#"><span>Update</span></a> <a href="#"><span>Send Now</span></a>
				</footer>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}

</body>
</html>
