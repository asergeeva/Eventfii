<div class="form">
	<form method="post" action="{$CURHOST}/create_account{if isset($redirect)}{$redirect}{/if}">
	<fieldset id="new_user_login_form" class="one-col"> 
		<p class="message">Facebook login makes signing up 75% faster!</p>
		<div id="fb-root"></div>
        <p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button></p>  
        <div id="invalid_credentials"></div>
        <p class="message-small">or</p> 
		<p class="message-small">Create New Account</p> 
		<label for="ef_login_email_new">
			<strong>Email</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" name="email" id="ef_login_email_new" value="" />
			</div>{if isset($user_create_email)}
			<p class="message-error">{$user_create_email}</p>{/if}

		</label> 
		<label for="ef_login_pass_new">
			<strong>Password</strong> 
			<div>
				<input type="password" class="inputbox autowidth" name="pass" id="ef_login_pass_new" />
			</div>{if isset($user_create_pass)}
			<p class="message-error">{$user_create_pass}</p>{/if}

		</label>
		<label for="ef_fname_new">
			<strong>First Name</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{if isset($smarty.post.fname)}{$smarty.post.fname}{/if}" name="fname" id="ef_fname_new" />
			</div>{if isset($user_create_fname)}
			<p class="message-error">{$user_create_fname}</p>{/if}

		</label>
		<label for="ef_lname_new">
			<strong>Last Name</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{if isset($smarty.post.lname)}{$smarty.post.lname}{/if}" name="lname" id="ef_lname_new" />
			</div>{if isset($user_create_lname)}
			<p class="message-error">{$user_create_lname}</p>{/if}

		</label>
		<label for="ef_login_phone_new">
			<strong>Cell Phone #</strong> 
			<div>
				<input type="text" class="inputbox autowidth" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" name="phone" id="ef_login_phone_new" /> 
			</div>
			<p>So you can easily receive event updates through texts!</p>{if isset($user_create_phone)}
			<p class="message-error">{$user_create_phone}</p>{/if}

		</label>
		<label for="ef_zipcode_new">
			<strong>Zip Code</strong>
			<div>
				<input type="text" class="inputbox autowidth" value="{if isset($smarty.post.zipcode)}{$smarty.post.zipcode}{/if}" name="zipcode" id="ef_zipcode_new" /> 
			</div>
			<p>So we can tell you how close to your events you are.</p>{if isset($user_create_zipcode)}
			<p class="message-error">{$user_create_zipcode}</p>{/if}

		</label>
		<footer class="buttons-submit"> 
			<p><input type="submit" name="register" value="Done" /></p>
			<!--a href="#" onclick="LOGIN_FORM.newUserLogin()" class="btn-med" id="ef_create_user_btn"><span>Done</span></a-->
		</footer> 
	</fieldset>
	</form>
</div>
