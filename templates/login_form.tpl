<div id="login_container">
	<div class="section section-login">
		<section class="block" id="login_container">
			<h1 class="block-title">Login</h1>
			<p class="facebook-text"><a href="#">Facebook login makes signing up 75% faster!</a></p>
			<fieldset id="existing_user_login_form">
				<p class="message">Log in</p>
				<label for="ef_login_email_exist"><span>Email</span> <input type="text" class="inputbox" name="ef_login_email_exist" id="ef_login_email_exist" /></label>
				<label for="ef_login_pass_exist"><span>Password</span> <input type="password" class="inputbox" name="ef_login_pass_exist" id="ef_login_pass_exist" /></label>
				<p class="forgot-password"><a href="{$CURHOST}/login/forgot">Forgot Password</a></p>
				<div class="submit-buttons">
					<a href="#" onclick="LOGIN_FORM.existingUserLogin()" class="btn-med" id="ef_login_btn"><span>Sign In</span></a>
					<!--
					<button name="ef_login_btn" id="ef_login_btn" value="login" onclick="LOGIN_FORM.existingUserLogin()">Login</button> 
              	<fb:login-button perms="email,publish_stream,create_event" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button>
					-->
				</div>
			</fieldset>
			<fieldset id="new_user_login_form">
				<p class="message">Create New Account</p>
				<label for="ef_login_email_new"><span>Email</span> <input type="text" class="inputbox" name="ef_login_email" id="ef_login_email_new" /></label>
				<label for="ef_login_pass_new"><span>Password</span> <input type="password" class="inputbox" name="ef_login_pass_new" id="ef_login_pass_new" /></label>
				<label for="ef_fname_new"><span>First Name</span> <input type="text" class="inputbox" name="ef_fname" id="ef_fname_new" /></label>
				<label for="ef_lname_new"><span>Last Name</span> <input type="text" class="inputbox" name="ef_lname" id="ef_lname_new" /></label>
				<label for="ef_login_phone_new"><span>Cell Phone #</span> <input type="text" class="inputbox" name="ef_login_phone" id="ef_login_phone_new" /> <p>So you can easily receive event updates through texts!</p></label>
				<!-- Not included in old template: Zip code -->
				<label for="zipcode"><span>Zip Code</span> <input type="text" class="inputbox" name="zipcode" id="zipcode" /> <p>So we can tell you how close to your events you are.</p></label>
				<div class="submit-buttons">
					<a href="#" onclick="LOGIN_FORM.newUserLogin()" class="btn-med" id="ef_create_user_btn"><span>Done</span></a>
					<!--
					<button name="ef_create_user_btn" id="ef_create_user_btn" value="submit" onclick="LOGIN_FORM.newUserLogin()">Create</button>
					-->
				</div>
			</fieldset>
		</section>
	</div>
</div>