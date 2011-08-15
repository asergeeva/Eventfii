{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, {$smarty.session.user->fname}!</h1>
		<h2><a href="{$CURHOST}/user/{$smarty.session.user->id}" id="user-{$smarty.session.user->id}">View your public profile</a></h2>
		<span id="user-id" style="display:none;">{$smarty.session.user->id}</span>
		<nav>
			<ul>
				<li class="current"><a href="{$CURHOST}"><span>Home</span></a></li>
				<li><a href="#"><span>Calendar</span></a></li>
			</ul>
		</nav>
	</header>
	<section id="main">
		<header class="block">
			<p class="message">{if isset($responseMsg['user_success'])}{$responseMsg['user_success']}{else}Settings{/if}</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
					<p class="user-img">
						<a href="#" class="info-pic"><img id="user_pic" src="{$smarty.session.user->pic}" width="96px" height="96px" alt="{$smarty.session.user->fname} {$smarty.session.user->lname}" /></a>
					</p>
				<footer class="buttons buttons-extra">
					<p><a href="#" id="user_image"><span>Upload</span></a></p>
				</footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info edit">{if $smarty.session.user->about}{$smarty.session.user->about}{else}Click here to edit{/if}</p>
			</section>
			<footer class="link-home">
				<a href="{$CURHOST}">Back to Home</a>
			</footer>
		</aside>
		<div class="content">
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
					<h1>Account Info</h1>
				</header>
				<fieldset>
					<label for="twitter" class="autowidth">
						<span>Twitter Handle</span> 
						<input type="text" class="inputbox autowidth" name="twitter" id="twitter" value="{$smarty.session.user->twitter}" />{if isset($error.twitter)}
						<p class="message-error" id="titleErr">{$error.twitter}</p>{/if}

					</label>{if ! isset($smarty.session.user->facebook)}
					<label for="fbconnect" class="autowidth">
						<span>Facebook</span>
						<div id="fb-root"></div>
						<p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="EF_SETTINGS.fbconnect()">Login with Facebook</fb:login-button></p>
						<span id="user_fbid">{$smarty.session.user->facebook}</span>
						<!-- Facebook Code -->
					</label>{/if}

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
					<p><input type="submit" name="submit" value="Save All" /></p>
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
<script type="text/javascript" language="javascript" src="{$JS_PATH}/settings.js"></script>
</body>
</html>
