{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<section id="main">
		<section class="block"><header class="centered-title"><h1>Contact Us</h1></section>
		<p class="message-small">Have any questions or concerns?<br>We'd love to hear from you!</p>
		<div class="form">
			<form method="post" action="{$CURHOST}/contact">
				<fieldset id="contact_form" class="one-col">
					<label for="name_input">
						<strong>Your name</strong>
						<div>
							<input type="text" class="inputbox autowidth" name="name" id="name_input"
									value="{if isset($name_value)}{$name_value}{/if}">
						</div>
					</label>
					<label for="email_input">
						<strong>Your e-mail</strong>
						<div>
							<input type="text" class="inputbox autowidth" name="email" id="email_input"
									value="{if isset($email_value)}{$email_value}{/if}">
						</div>{if isset($invalid_email_message)}
						<p class="message-error">{$invalid_email_message}</p>{/if}
					</label>
					<label for="subject_input">
						<strong>Subject</strong>
						<div>
							<input type="text" class="inputbox autowidth" name="subject" id="subject_input"
									value="{if isset($subject_value)}{$subject_value}{/if}">
						</div>
					</label>
					<label for="message_input">
						<strong>Message</strong>
						<div>
							<textarea type="text" class="inputbox autowidth" name="message" id="message_input">{if isset($message_value)}{$message_value}{/if}</textarea>
						</div>{if isset($invalid_message_content_message)}
						<p class="message-error">{$invalid_message_content_message}</p>{/if}
					</label>
					<footer class="buttons buttons-submit">
						<p>
							<input type="submit" name="submit" value="Submit">
						</p>
					</footer>
				</fieldset>
			</form>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_event.tpl"}

</body>
</html>
