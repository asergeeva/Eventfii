<fieldset id="existing_user_login_form" class="two-col">
		<p class="message">Facebook login makes signing up 75% faster!</p> 
		<div id="fb-root"></div>
		<p><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button></p>  
		<p class="message-small">or</p> 
		<label for="ef_login_email_exist">
		<div id="invalid_credentials"></div>
			<strong>Email</strong> 
			<div>
				<input type="text" class="inputbox autowidth" name="ef_login_email_exist" id="ef_login_email_exist" />
			</div>
		</label> 
		<label for="ef_login_pass_exist">
			<strong>Password</strong> 
			<div>
				<input type="password" class="inputbox autowidth" name="ef_login_pass_exist" id="ef_login_pass_exist" />
			</div>
		</label> 
		<footer class="links-extra">
			<p><a href="{$CURHOST}/login/forgot">Forgot Password</a></p>
		</footer>
		<footer class="buttons-submit"> 
			<a href="#" onclick="LOGIN_FORM.existingUserLogin()" class="btn-med" id="ef_login_btn"><span>Log In</span></a> 
			<!--
			<button name="ef_login_btn" id="ef_login_btn" value="login" onclick="LOGIN_FORM.existingUserLogin()">Login</button> 
      	<fb:login-button perms="email,publish_stream,create_event" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button>
			--> 
		</footer> 
	</fieldset> 
	<fieldset id="new_user_login_form" class="two-col"> 
		<p class="message-small">Create New Account</p> 
		<label for="ef_login_email_new">
			<strong>Email</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.email}" name="ef_login_email" id="ef_login_email_new" value="" />
			</div>
			<p><span style="color:red; top:0px; font-weight:bold;">{$user_create_email}</span></p>
		</label> 
		<label for="ef_login_pass_new">
			<strong>Password</strong> 
			<div>
				<input type="password" class="inputbox autowidth" name="ef_login_pass_new" id="ef_login_pass_new" value="" />
			</div>
			<p><span style="color:red; top:0px; font-weight:bold;">{$user_create_pass}</span></p>
		</label>
		<label for="ef_fname_new">
			<strong>First Name</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.fname}" name="ef_fname" id="ef_fname_new" />
			</div>
			<p><span style="color:red; top:0px; font-weight:bold;">{$user_create_fname}</span></p>
		</label>
		<label for="ef_lname_new">
			<strong>Last Name</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.lname}" name="ef_lname" id="ef_lname_new" />
			</div>
			<p><span style="color:red; top:0px; font-weight:bold;">{$user_create_lname}</span></p>
		</label>
		<label for="ef_login_phone_new">
			<strong>Cell Phone #</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.phone}" name="ef_login_phone" id="ef_login_phone_new" /> 
			</div>
			<p>So you can easily receive event updates through texts!</p>
			<p><span style="color:red; top:0px; font-weight:bold;">{$user_create_phone}</span></p>
		</label>
		<label for="ef_zipcode_new">
			<strong>Zip Code</strong>
			<div>
				<input type="text" class="inputbox autowidth" value="{$smarty.post.zipcode}" name="ef_zipcode" id="ef_zipcode_new" /> 
			</div>
			<p>So we can tell you how close to your events you are.</p>
			<p><span style="color:red; top:0px; font-weight:bold;">{$user_create_zipcode}</span></p>
		</label>
		<footer class="buttons-submit"> 
			<a href="#" onclick="LOGIN_FORM.newUserLogin()" class="btn-med" id="ef_create_user_btn"><span>Done</span></a> 
			<!--
			<button name="ef_create_user_btn" id="ef_create_user_btn" value="submit" onclick="LOGIN_FORM.newUserLogin()">Create</button>
			--> 
		</footer> 
	</fieldset>
