<div class="form">
	<form method="post" action="{$SCURHOST}/login{if isset($redirect)}{$redirect}{/if}">
	<fieldset id="existing_user_login_form" class="one-col">
		<p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button></p>  
		<div id="invalid_credentials"></div>
		<p class="message-small">or</p> 
		<label for="ef_login_email_exist">
			<strong>Email</strong> 
			<div>
				<input type="text" class="inputbox autowidth" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" id="ef_login_email_exist" />
			</div>{if isset($user_login_email)}
			<p class="message-error">{$user_login_email}</p>{/if}

		</label> 
		<label for="ef_login_pass_exist">
			<strong>Password</strong> 
			<div>
				<input type="password" class="inputbox autowidth" name="pass" id="ef_login_pass_exist" />
			</div>{if isset($user_login_password)}
			<p class="message-error">{$user_login_password}</p>{/if}

		</label> 
		<footer class="links-extra">
			<p><a href="{$CURHOST}/register">Create New Account</a> | <a href="{$CURHOST}/login/forgot">Forgot Password</a></p>
			<label for="remember"><input type="checkbox" name="remember" id="remember" /> Sign in automatically next time?</label>
		</footer>
		<footer class="buttons buttons-submit"> 
			<p><input type="submit" name="login" value="Log In" /></p>
		</footer>
	</fieldset>
	</form>
</div>
