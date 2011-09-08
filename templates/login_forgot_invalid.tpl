{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="fb-root"></div>
<div id="container">
	<div class="section section-login" id="login_container">
		<section class="block">
			<h1 class="block-title">Forgot Password</h1>
			<p class="message error">Invalid email address</p>
			<p class="message">Enter the e-mail you registered with and we'll send you your password</p>
			<form method="post" action="{$CURHOST}/login/forgot/submit"  id="login_forgot_form">
				<fieldset>
					<label for="login_forgot_email"><span>Email</span> <input type="text" class="inputbox" name="login_forgot_email" id="login_forgot_email" /></label>
					<div class="buttons buttons-submit">
						<p><span class="btn btn-small"><input type="submit" name="login_forgot_reset" value="Reset" id="login_forgot_reset" /></span></p>
					</div>
				</fieldset>
			</form>
		</section>
	</div>
</div>
{include file="footer.tpl"}
