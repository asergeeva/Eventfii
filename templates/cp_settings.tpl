{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">
		<header class="block notification" {if !isset($responseMsg['user_success'])}style="display:none"{/if} id="notification-box">
			<p class="message">{$responseMsg['user_success']}</p>
		</header>

		{include file="cp_user.tpl"}
		<div class="content">
			<section class="block" id="settings">
				<form method="post" action="{$CURHOST}/settings" autocomplete="off">
				<fieldset>
					<legend>Account Info</legend>
					<dl>
						<dt>
							<label for="fname">First Name</label>
						</dt>
						<dd>
							<input type="text" name="fname" value="{$smarty.session.user->fname}" class="inputbox" id="fname" />{if isset($error.fname)}

							<em>{$error.fname}</em>{/if}
						</dd>
						<dt>
							<label for="lname">Last Name</label>
						</dt>
						<dd>
							<input type="text" name="lname" value="{$smarty.session.user->lname}" class="inputbox"  id="lname" />{if isset($error.lname)}

							<em>{$error.lname}</em>{/if}

						</dd>
						<dt>
							<label for="email">Email</label>
						</dt>
						<dd>
							<input type="text" name="email" value="{$smarty.session.user->email}" class="inputbox" id="email" disabled="disabled" />{if isset($error.email)}

							<em>{$error.email}</em>{/if}

					</dl>
					<label for="lname">
						<span>Last Name</span> 
					</label>

					</label>
					<label for="email">
						<span>Email</span> 
						
					</label>
					<label for="user-cell">
						<span>Cell #</span> 
						<input type="text" class="inputbox autowidth" name="phone" id="user-cell" value="{$smarty.session.user->phone}" />{if isset($error.phone)}
						<p class="message-error" id="titleErr">{$error.phone}</p>{/if}

					</label>
					<label for="user-zip">
						<span>Zip</span> 
						<input type="text" class="inputbox autowidth" name="zip" id="user-zip" value="{$smarty.session.user->zip}" maxlength="5" />{if isset($error.zip)}
						<p class="message-error" id="titleErr">{$error.zip}</p>{/if}

					</label>
				</fieldset>
				<fieldset>
					<legend>Connect with Social Networks</legend>
					<label for="twitter" class="autowidth">
						<a href="#"><span id="connect_twitter"></span></a>
					</label>
					<label for="fbconnect" class="autowidth">
						<div id="fb-root"></div>
						<p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="EF_SETTINGS.fbconnect()">Login with Facebook</fb:login-button></p>
						<span id="user_fbid">{$smarty.session.user->facebook}</span>
						<!-- Facebook Code -->
					</label>

				</fieldset>
				<fieldset>
					<legend>Notification Options</legend>
					<label for="features" class="fullwidth">
						<input type="checkbox" name="email-feature" value="1"  id="features" {if $smarty.session.user->notif_opt1 eq '1'}checked="checked"{/if} /> <em>Tell me about new features every month</em>
					</label>
					<label for="updates" class="fullwidth">
						<input type="checkbox" name="email-updates" value="1" id="updates" {if $smarty.session.user->notif_opt2 eq '1'}checked="checked"{/if} /> <em>Send me daily updates about my event when I’m the host</em>
					</label>
					<label for="attend" class="fullwidth">
						<input type="checkbox" name="email-friend" value="1"  id="attend" {if $smarty.session.user->notif_opt3 eq '1'}checked="checked"{/if} /> <em>Notify me when my friends are highly likely to attend the same event as I</em>
					</label>
				</fieldset>
				<fieldset>
					<legend>Password Change</legend>
					<label for="password-current" class="autowidth">
						<span>Enter current password</span> 
						<input type="password" class="inputbox autowidth" name="user-curpass" id="password-current" />
					</label>
					<label for="password-new" class="autowidth">
						<span>Enter new password</span> 
						<input type="password" class="inputbox autowidth" name="user-newpass" id="password-new" />
					</label>
					<label for="password-confirm" class="autowidth">
						<span>Confirm new password</span> 
						<input type="password" class="inputbox autowidth" name="user-confpass" id="password-confirm" />
					</label>{if isset($responseMsg['password'])}
						<p class="message-error" id="titleErr">{$responseMsg['password']}</p>{/if}

				</fieldset>
				<footer class="buttons buttons-submit">
					<p><span class="btn btn-small"><input type="submit" name="submit" value="Save All" /></span></p>
				</footer>
				</form>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/twitter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/settings.js"></script>
</body>
</html>