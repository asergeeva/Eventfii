{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="fb-root"></div>
<div id="container">
	<div class="section section-login" id="login_container">
		<section class="block">
			<h1 class="block-title">Forgot Password</h1>
			<p class="message">Reset password link has been sent to your email</p>
			<form method="post" action="{$CURHOST}/login/forgot/submit"  id="login_forgot_form">
				<fieldset>
					<label for="login_fogot_email"><span>Email</span> <input type="text" class="inputbox" name="login_forgot_email" id="login_forgot_email" /></label>
					<div class="submit-buttons">
						<p class="btn-med"><input type="submit" name="login_forgot_reset" id="login_forgot_reset" value="Reset" /></p>
					</div>
				</fieldset>
			</form>
		</section>
	</div>
</div>
{include file="footer.tpl"}
