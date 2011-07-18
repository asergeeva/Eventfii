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
						<li><a id="email_reminder" href="#">Reminder</a></li>
						<li><a id="email_followup" href="#">Follow-Up</a></li>
						<li><a id="email_custom" href="#">Custom</a></li>
					</ul>
				</nav>
        <div id="email_form_container">
        {include file="email_form_container.tpl"}
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
