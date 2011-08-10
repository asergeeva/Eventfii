{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, {$userInfo['fname']}!</h1>
		<nav>
			<ul>
				<li class="current"><a href="{$CURHOST}"><span>Home</span></a></li>
				<li><a href="#"><span>Calendar</span></a></li>
			</ul>
		</nav>
	</header>
	<section id="main">
		<header class="block">
			<p class="message">Settings</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
					<p class="user-img">
						<a href="#" class="info-pic"><img id="user_pic" src="{$userInfo['pic']}" width="96px" height="96px" alt="{$userInfo['fname']} {$userInfo['lname']}" /></a>
					</p>
				<footer class="buttons buttons-extra">
					<p><a href="#" id="user_image"><span>Upload</span></a></p>
				</footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info">{$userInfo['about']}</p>
				<footer class="buttons buttons-extra">
					<p><a href="#"><span>Edit</span></a></p>
				</footer>
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
				<form method="post" action="{$CURHOST}/settings">
				<fieldset>
					<label for="fname">
						<span>First Name</span> 
						<input type="text" class="inputbox autowidth" name="fname" id="fname" value="{$userInfo['fname']}" />
						<p class="message-error" id="titleErr">{$error.fname}</p>
					</label>
					<label for="lname">
						<span>Last Name</span> 
						<input type="text" class="inputbox autowidth" name="lname" id="lname" value="{$userInfo['lname']}" />
						<p class="message-error" id="titleErr">{$error.lname}</p>
					</label>
					<label for="email">
						<span>Email</span> 
						<input type="text" class="inputbox autowidth" name="email" id="email" value="{$userInfo['email']}" />
						<p class="message-error" id="titleErr">{$error.email}</p>
					</label>
					<label for="user-cell">
						<span>Cell #</span> 
						<input type="text" class="inputbox autowidth" name="phone" id="user-cell" value="{$userInfo['phone']}" />
						<p class="message-error" id="titleErr">{$error.phone}</p>
					</label>
					<label for="user-zip">
						<span>Zip</span> 
						<input type="text" class="inputbox autowidth" name="zip" id="user-zip" value="{$userInfo['zip']}" maxlength="5" />
						<p class="message-error" id="titleErr">{$error.zip}</p>
					</label>å
				</fieldset>
				<header class="block-title">
					<h1>Account Info</h1>
				</header>
				<fieldset>
					<label for="twitter" class="autowidth">
						<span>Twitter Handle</span> 
						<input type="text" class="inputbox autowidth" name="twitter" id="twitter" value="{$userInfo['twitter']}" />
            <p class="message-error" id="titleErr">{$error.twitter}</p>
					</label>
					<label for="fbconnect" class="autowidth">
							<div id="fb-root"></div>
		<p class="fb-login"><fb:login-button perms="email,publish_stream" id="fb-login-button" onlogin="EF_SETTINGS.fbconnect()">Login with Facebook</fb:login-button></p><span id="user_fbid">{$userInfo['facebook']}</span>
						<!-- Facebook Code -->
					</label>
				</fieldset>
				<header class="block-title">
					<h1>Notification Options</h1>
				</header>
				<fieldset>
					<label for="features" class="fullwidth">
						<input type="checkbox" name="email-feature" id="features" {if $userInfo['notif_opt1'] eq '1'}checked="checked"{/if} /> <em>Tell me about new features every month</em>
					</label>
					<label for="updates" class="fullwidth">
						<input type="checkbox" name="email-updates" id="updates" {if $userInfo['notif_opt2'] eq '1'}checked="checked"{/if} /> <em>Send me daily updates about my event when I’m the host</em>
					</label>
					<label for="attend" class="fullwidth">
						<input type="checkbox" name="email-friend" id="attend" {if $userInfo['notif_opt3'] eq '1'}checked="checked"{/if} /> <em>Notify me when my friends are highly likely to attend the same event as I</em>
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
					</label>
          <p class="message-error" id="titleErr">{$responseMsg['password']}</p>
				</fieldset>
				<footer class="buttons buttons-submit">
					<p><input type="submit" name="submit" value="Save All" /></p>
          	{$responseMsg['user_success']}
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
