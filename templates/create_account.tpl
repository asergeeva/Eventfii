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
			<fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Connect with Facebook</fb:login-button> 
			<div id="invalid_credentials"></div>
			<p class="message-small">or</p> 
			<p class="message-small">Create New Account</p>{/if}
			
			<dl>
				<dt>
					<label for="email">Email</label>
				</dt>
				<dd{if isset($error.email)} class="error"{/if}>
					<input type="text" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{elseif isset($smarty.session.fb->email)}{$smarty.session.fb->email}{/if}"{if isset($smarty.session.fb->email)} readonly="readonly"{/if} class="inputbox{if isset($smarty.session.fb->email)} input-facebook{/if}" />{if isset($error.email)}

					<em>{$error.email}</em>{/if}

				</dd>
				<dt>
					<label for="password">Password</label>
				</dt>
				<dd{if isset($error.password)} class="error"{/if}>
					<input type="password" name="password" class="inputbox" />{if isset($error.password)}

					<em>{$error.password}</em>{/if}

				</dd>
				<dt>
					<label for="fname">First Name</label>
				</dt>
				<dd{if isset($error.fname)} class="error"{/if}>
					<input type="text" name="fname" value="{if isset($smarty.post.fname)}{$smarty.post.fname}{elseif isset($smarty.session.fb->fname)}{$smarty.session.fb->fname}{/if}"{if isset($smarty.session.fb->fname)} readonly="readonly"{/if} class="inputbox{if isset($smarty.session.fb->fname)} input-facebook{/if}" />{if isset($error.fname)}

					<em>{$error.fname}</em>{/if}

				</dd>
				<dt>
					<label for="lname">Last Name</label>
				</dt>
				<dd{if isset($error.lname)} class="error"{/if}>
					<input type="text" name="lname" value="{if isset($smarty.post.lname)}{$smarty.post.lname}{elseif isset($smarty.session.fb->lname)}{$smarty.session.fb->lname}{/if}"{if isset($smarty.session.fb->lname)} readonly="readonly"{/if} class="inputbox{if isset($smarty.session.fb->fname)} input-facebook{/if}" />{if isset($error.lname)}

					<em>{$error.lname}</em>{/if}

				</dd>
				<dt>
					<label for="phone">Cell Phone #</label>
				</dt>
				<dd{if isset($error.phone)} class="error"{/if}>
					<input type="text" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" class="inputbox" />{if isset($error.phone)}

					<em>{$error.phone}</em>{/if}

					<p>So you can easily receive event updates through texts!</p>
				</dd>
				<dt>
					<label>Zip Code</label>
				</dt>
				<dd{if isset($error.zip)} class="error"{/if}>
					<input type="text" name="zip" value="{if isset($smarty.post.zip)}{$smarty.post.zip}{/if}" class="inputbox" />{if isset($error.zip)}

					<em>{$error.zip}</em>{/if}

					<p>So we can tell you how close to your events you are.</p>
				</dd>
			</dl>
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
