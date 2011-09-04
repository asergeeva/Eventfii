{include file="head.tpl" title="Jumpstart your social life"}
<body>

{include file="header.tpl"}
<div id="container">
	<div class="form">
		<form method="post" action="{$CURHOST}/register{if isset($redirect)}{$redirect}{/if}" autocomplete="off">
		<fieldset id="new_user_login_form" class="one-col">{if isset($smarty.session.fb)}
			
			<p class="fb-connected"><span>Your trueRSVP account has been created.</span></p>
			<p class="message-small">Enter New Account Details</p>{else}

			<p class="message">Facebook login makes signing up 75% faster!</p>
			<div id="fb-root"></div>
			<p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Connect with Facebook</fb:login-button></p>  
			<div id="invalid_credentials"></div>
			<p class="message-small">or</p> 
			<p class="message-small">Create New Account</p>{/if}

			<label for="ef_login_email_new">
				<strong>Email</strong> 
				<div>{if isset($smarty.session.user)}

					<p>{$smarty.session.user->email}</p>{else}

					<input type="text" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{elseif isset($smarty.session.fb->email)}{$smarty.session.fb->email}{/if}"{if isset($smarty.post.email) || isset($smarty.session.fb->email)} readonly="readonly"{/if} class="inputbox autowidth{if isset($smarty.session.fb->email)} input-facebook{/if}" id="ef_login_email_new" />{/if}

				</div>{if isset($user_create_email)}

				<p class="message-error">{$user_create_email}</p>{/if}

			</label>{if ! isset($smarty.session.user)}

			<label for="ef_login_pass_new">
				<strong>Password</strong> 
				<div>
					<input type="password" class="inputbox autowidth" name="pass" id="ef_login_pass_new" />
				</div>{if isset($user_create_pass)}

				<p class="message-error">{$user_create_pass}</p>{/if}

			</label>{/if}

			<label for="ef_fname_new">
				<strong>First Name</strong> 
				<div>{if isset($smarty.session.user)}

					<p>{$smarty.session.user->fname}</p>{/if}

					<input type="text" name="fname" value="{if isset($smarty.post.fname)}{$smarty.post.fname}{elseif isset($smarty.session.fb->fname)}{$smarty.session.fb->fname}{/if}"{if isset($smarty.session.fb->fname)} readonly="readonly"{/if} class="inputbox autowidth{if isset($smarty.session.fb->fname)} input-facebook{/if}" id="ef_fname_new" /></p>
				</div>{if isset($user_create_fname)}
				<p class="message-error">{$user_create_fname}</p>{/if}

			</label>
			<label for="ef_lname_new">
				<strong>Last Name</strong> 
				<div>{if isset($smarty.session.user)}

					<p>{$smarty.session.user->lname}</p>{else}

					<input type="text" name="lname" value="{if isset($smarty.post.lname)}{$smarty.post.lname}{elseif isset($smarty.session.fb->lname)}{$smarty.session.fb->lname}{/if}"{if isset($smarty.session.fb->lname)} readonly="readonly"{/if} class="inputbox autowidth{if isset($smarty.session.fb->fname)} input-facebook{/if}" id="ef_lname_new" />{/if}

				</div>{if isset($user_create_lname)}
				<p class="message-error">{$user_create_lname}</p>{/if}

			</label>
			<label for="ef_login_phone_new">
				<strong>Cell Phone #</strong> 
				<div>
					<input type="text" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" class="inputbox autowidth" id="ef_login_phone_new" /> 
				</div>
				<p>So you can easily receive event updates through texts!</p>{if isset($user_create_phone)}
				<p class="message-error">{$user_create_phone}</p>{/if}

			</label>
			<label for="ef_zipcode_new">
				<strong>Zip Code</strong>
				<div>
					<input type="text" name="zip" value="{if isset($smarty.post.zip)}{$smarty.post.zip}{/if}" class="inputbox autowidth" id="ef_zipcode_new" /> 
				</div>
				<p>So we can tell you how close to your events you are.</p>{if isset($user_create_zipcode)}
				<p class="message-error">{$user_create_zipcode}</p>{/if}

			</label>
			<footer class="buttons-submit"> 
				<p><span class="btn btn-med"><input type="submit" name="register" value="Done" /></span></p>
				<!--a href="#" onclick="LOGIN_FORM.newUserLogin()" class="btn-med" id="ef_create_user_btn"><span>Done</span></a-->
			</footer> 
		</fieldset>
		</form>
	</div>
</div>

{include file="js_global.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>

</body>
</html>
