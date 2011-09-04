{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">{if isset($responseMsg['user_success'])}

		<header class="block notification">
			<p class="message">{$responseMsg['user_success']}</p>
		</header>{/if}

		{include file="cp_user.tpl"}
		<div class="content">
			<header class="block">
				<p class="message">Settings</p>
			</header>
			<section class="block" id="settings">
				<header class="block-title">
					<h1>Account Info</h1>
				</header>
				<form method="post" action="{$CURHOST}/settings" autocomplete="off">
				<fieldset>
					<label for="fname">
						<span>First Name</span> 
						<input type="text" class="inputbox autowidth" name="fname" id="fname" value="{$smarty.session.user->fname}" />{if isset($error.fname)}
						<p class="message-error" id="titleErr">{$error.fname}</p>{/if}

					</label>
					<label for="lname">
						<span>Last Name</span> 
						<input type="text" class="inputbox autowidth" name="lname" id="lname" value="{$smarty.session.user->lname}" />{if isset($error.lname)}
						<p class="message-error" id="titleErr">{$error.lname}</p>{/if}

					</label>
					<label for="email">
						<span>Email</span> 
						<input type="text" class="inputbox autowidth" name="email" id="email" disabled="disabled" value="{$smarty.session.user->email}" />{if isset($error.email)}
						<p class="message-error" id="titleErr">{$error.email}</p>{/if}

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
				<header class="block-title">
					<h1>Social Network</h1>
				</header>
				<fieldset>
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
				<header class="block-title">
					<h1>Notification Options</h1>
				</header>
				<fieldset>
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
				<header class="block-title">
					<h1>Password Change</h1>
				</header>
				<fieldset>
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
