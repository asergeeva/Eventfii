{include file="head.tpl" title="Jumpstart your social life"}
<body>

{include file="new_header.tpl"}
<div id="container">
	<section class="form-register">
		<div class="turtle">
			<h2>Why sign up for trueRSVP?</h2>
			<p>For hosts, finally get an accurate attendance count.</p>
			<p>For guests, access immediate updates from events you care about!</p>
			<p><a href="{$CURHOST}/method">Take a tour</a> to find out more!</p>
		</div>
		<div class="block">
			<form method="post" action="{$CURHOST}/register{if isset($redirect)}{$redirect}{/if}" autocomplete="off">
			<fieldset>{if isset($smarty.session.fb)}
				
				<p class="fb-connected"><span>Your trueRSVP account has been created.</span></p>
				<p class="message-small">Enter remaining account details</p>{else}

				<legend>Create a new account</legend>
                {if isset($user_create_email)}
                	<div style="background: none repeat scroll 0 0 #FAE5E3;padding-bottom: 10px;padding-top: 10px;text-align: center;">{$user_create_email}</div>
                {/if}
                {if ! isset($smarty.get.step) || ($smarty.get.step != "create" && $smarty.get.step != "moreinfo")}

				<div id="fb-root"></div>
				<p class="message-small"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Connect with Facebook</fb:login-button></p>
				<div id="invalid_credentials"></div>
				<p class="message-small">or</p> 
				<p class="message-small"><a href="{$CURHOST}/register?step=create">Create an account without using Facebook</a></p>{/if}{/if}{if isset($smarty.get.step) && ($smarty.get.step == "create" || $smarty.get.step == "moreinfo")}
				
				<dl>
					<dt class="inline">
						<label for="email">Email<span>*</span></label>
					</dt>
					<dd{if isset($error.email)} class="error"{/if}>
						<input type="text" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{elseif isset($smarty.session.fb->email)}{$smarty.session.fb->email}{/if}"{if isset($smarty.session.fb->email)} readonly="readonly"{/if} class="inputbox{if isset($smarty.session.fb->email)} input-facebook{/if}" />{if isset($error.email)}

						<em>{$error.email}</em>{/if}

					</dd>
					<dt class="inline">
						<label for="email">Re-type Email<span>*</span></label>
					</dt>
					<dd{if isset($error.email)} class="error"{/if}>
						<input type="text" name="check_email" value="{if isset($smarty.post.check_email)}{$smarty.post.check_email}{elseif isset($smarty.session.fb->email)}{$smarty.session.fb->email}{/if}"{if isset($smarty.session.fb->email)} readonly="readonly"{/if} class="inputbox{if isset($smarty.session.fb->email)} input-facebook{/if}" />{if isset($error.email)}

						<em>{$error.email}</em>{/if}

					</dd>
					<dt class="inline">
						<label for="password">Password<span>*</span></label>
					</dt>
					<dd{if isset($error.password)} class="error"{/if}>
						<input type="password" name="password" class="inputbox" />{if isset($error.password)}

						<em>{$error.password}</em>{/if}

					</dd>
					<dt class="inline">
						<label for="fname">First Name<span>*</span></label>
					</dt>
					<dd{if isset($error.fname)} class="error"{/if}>
                    	<!--readonly="readonly"-->
						<input type="text" name="fname" value="{if isset($smarty.post.fname)}{$smarty.post.fname}{elseif isset($smarty.session.fb->fname)}{$smarty.session.fb->fname}{/if}"{if isset($smarty.session.fb->fname)} {/if} class="inputbox{if isset($smarty.session.fb->fname)} input-facebook{/if}" />{if isset($error.fname)}

						<em>{$error.fname}</em>{/if}

					</dd>
					<dt class="inline">
						<label for="lname">Last Name<span>*</span></label>
					</dt>
					<dd{if isset($error.lname)} class="error"{/if}>
                    <!-- readonly="readonly"-->
						<input type="text" name="lname" value="{if isset($smarty.post.lname)}{$smarty.post.lname}{elseif isset($smarty.session.fb->lname)}{$smarty.session.fb->lname}{/if}"{if isset($smarty.session.fb->lname)}{/if} class="inputbox{if isset($smarty.session.fb->fname)} input-facebook{/if}" />{if isset($error.lname)}

						<em>{$error.lname}</em>{/if}

					</dd>
					<dt class="inline">
						<label for="phone">Cell Phone #</label>
					</dt>
					<dd{if isset($error.phone)} class="error"{/if}>
						<input type="text" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" class="inputbox" />{if isset($error.phone)}

						<em>{$error.phone}</em>{/if}

						<p class="information">So you can easily receive event updates through texts!</p>
					</dd>
					<dt class="inline">
						<label>Zip Code<span>*</span></label>
					</dt>
					<dd{if isset($error.zip)} class="error"{/if}>
						<input type="text" name="zip" value="{if isset($smarty.post.zip)}{$smarty.post.zip}{/if}" class="inputbox" />{if isset($error.zip)}

						<em>{$error.zip}</em>{/if}

						<p class="information">So we can tell you how close to your events you are.</p>
					</dd>
				</dl>
				<footer class="buttons-submit"> 
					<p><span class="btn btn-med"><input type="submit" name="register" value="Go" /></span></p>{*
					<a href="#" onClick="LOGIN_FORM.newUserLogin()" class="btn-med" id="ef_create_user_btn"><span>Done</span></a>*}{/if}

				</footer> 
			</fieldset>
			</form>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
<script src="{$JS_PATH}/md5-min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/fb.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/login.js" type="text/javascript" charset="utf-8"></script>

</body>
</html>
