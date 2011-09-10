<div class="form block">
		<form method="post" action="{$CURHOST}/login{if isset($redirect)}{$redirect}{/if}" autocomplete="off">
			<fieldset>
				<legend>Sign In</legend>
				<fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button>
				<div id="invalid_credentials"></div>
				<p class="message-small">or</p>
				<dl>
					<dt>
						<label for="email">Email</label>
					</dt>
					<dd{if isset($error.email)} class="error"{/if}>
						<input type="text" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" class="inputbox" id="email" />{if isset($error.email)}

						<em>{$error.email}</em>{/if}

					</dd>
					<dt>
						<label for="password">Password</label>
					</dt>
					<dd{if isset($error.password)} class="error"{/if}>
						<input type="password" name="password" class="inputbox" id="password" />{if isset($error.password)}

						<em>{$error.password}</em>{/if}

					</dd>
				</dl>
				<footer class="links-extra">
					<p><a href="{$CURHOST}/register">Create New Account</a> | <a href="{$CURHOST}/login/forgot">Forgot Password</a></p>
					<!--label for="remember"><input type="checkbox" name="remember" id="remember" /> Sign in automatically next time?</label-->
				</footer>
				<footer class="buttons buttons-submit"> 
					<p><span class="btn btn-med"><input type="submit" name="login" value="Log In" /></span></p>
				</footer>
			</fieldset>
		</form>
	</div>
