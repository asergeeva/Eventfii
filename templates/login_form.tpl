<div class="form">
	<form method="post" action="{$CURHOST}/login{$redirect}">
	<fieldset id="existing_user_login_form" class="two-col">
		<p class="message">Facebook login makes signing up 75% faster!</p> 
		<div id="fb-root"></div>
		<p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button></p>  
		<div id="invalid_credentials"></div>
		<p class="message-small">or</p> 
		<label for="ef_login_email_exist">
			<strong>Email</strong> 
			<div>
				<input type="text" class="inputbox autowidth" name="email" value="{$smarty.post.email}" id="ef_login_email_exist" />
			</div>
			<p class="message-error">{$user_login_email}</p>
		</label> 
		<label for="ef_login_pass_exist">
			<strong>Password</strong> 
			<div>
				<input type="password" class="inputbox autowidth" name="pass" id="ef_login_pass_exist" />
			</div>
			<p class="message-error">{$user_login_password}</p>
		</label> 
		<footer class="links-extra">
			<p><a href="{$CURHOST}/login/forgot">Forgot Password</a></p>
			<label for="remember"><input type="checkbox" name="remember" id="remember" /> Sign in automatically next time?</label>
		</footer>
		<footer class="buttons buttons-submit"> 
			<p><input type="submit" name="login" value="Log In" /></p>
			<!--a href="#" onclick="LOGIN_FORM.existingUserLogin()" class="btn-med" id="ef_login_btn"><span>Log In</span></a--> 
		</footer>
	</fieldset>
	</form>
	<form method="post" action="{$CURHOST}/login{$redirect}">
	<fieldset id="new_user_login_form" class="two-col"> 
		<p class="message-small">Create New Account</p> 
		<label for="ef_login_email_new">
			<strong>Email</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.email}" name="email" id="ef_login_email_new" value="" />
			</div>
			<p class="message-error">{$user_create_email}</p>
		</label> 
		<label for="ef_login_pass_new">
			<strong>Password</strong> 
			<div>
				<input type="password" class="inputbox autowidth" name="pass" id="ef_login_pass_new" />
			</div>
			<p class="message-error">{$user_create_pass}</p>
		</label>
		<label for="ef_fname_new">
			<strong>First Name</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.fname}" name="fname" id="ef_fname_new" />
			</div>
			<p class="message-error">{$user_create_fname}</p>
		</label>
		<label for="ef_lname_new">
			<strong>Last Name</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.lname}" name="lname" id="ef_lname_new" />
			</div>
			<p class="message-error">{$user_create_lname}</p>
		</label>
		<label for="ef_login_phone_new">
			<strong>Cell Phone #</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.phone}" name="phone" id="ef_login_phone_new" /> 
			</div>
			<p>So you can easily receive event updates through texts!</p>
			<p class="message-error">{$user_create_phone}</p>
		</label>
		<label for="ef_zipcode_new">
			<strong>Zip Code</strong>
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.zipcode}" name="zipcode" id="ef_zipcode_new" /> 
			</div>
			<p>So we can tell you how close to your events you are.</p>
			<p class="message-error">{$user_create_zipcode}</p>
		</label>
		<footer class="buttons-submit"> 
			<p><input type="submit" name="register" value="Done" /></p>
			<!--a href="#" onclick="LOGIN_FORM.newUserLogin()" class="btn-med" id="ef_create_user_btn"><span>Done</span></a-->
		</footer> 
	</fieldset>
	</form>
</div>
