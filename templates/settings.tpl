{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Welcome, Anna!</h1>
		<nav>
			<ul>
				<li class="current"><a href="{$CURHOST}"><span>Home</span></a></li>
				<li><a href="#"><span>Calendar</span></a></li>
			</ul>
		</nav>
	</header>
	<section id="main">
		<header class="block">
			<p class="message">Settings => still working</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
				<p class="user-img"><img src="images/user.jpg" alt="User" /></p>
				<footer class="buttons-extra"><a href="#"><span>Upload</span></a></footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info">Say something witty about yourself here!</p>
				<footer class="buttons-extra"><a href="#"><span>Edit</span></a></footer>
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
				<fieldset>
					<label for="fname">
						<span>First Name</span> 
						<input type="text" class="inputbox autowidth" name="user-fname" id="fname" value="Anna" />
					</label>
					<label for="lname">
						<span>Last Name</span> 
						<input type="text" class="inputbox autowidth" name="user-lname" id="lname" value="Anna" />
					</label>
					<label for="email">
						<span>Email</span> 
						<input type="text" class="inputbox autowidth" name="user-email" id="email" value="sergeeva@usc.edu" />
					</label>
					<label for="user-cell"><span>Cell #</span> <input type="text" class="inputbox autowidth" name="user-cell" id="user-cell" value="303-886-1808" /></label>
					<label for="user-zip"><span>Zip</span> <input type="text" class="inputbox autowidth" name="user-zip" id="user-zip" value="90007" maxlength="5" /></label>
				</fieldset>
				<header class="block-title">
					<h1>Account Info</h1>
				</header>
				<fieldset>
					<label for="twitter" class="autowidth">
						<span>Twitter Handle</span> 
						<input type="text" class="inputbox autowidth" name="user-email" id="email" value="sergeeva@usc.edu" />
					</label>
					<label for="fbconnect" class="autowidth">
						<span>Connect your facebook</span> 
						<!-- Facebook Code -->
					</label>
				</fieldset>
				<header class="block-title">
					<h1>Notification Options</h1>
				</header>
				<fieldset>
					<label for="features" class="fullwidth">
						<input type="checkbox" name="email-feature" id="features" /> <em>Tell me about new features every month</em>
					</label>
					<label for="updates" class="fullwidth">
						<input type="checkbox" name="email-updates" id="updates" /> <em>Send me daily updates about my event when I’m the host</em>
					</label>
					<label for="attend" class="fullwidth">
						<input type="checkbox" name="email-updates" id="attend" /> <em>Notify me when my friends are highly likely to attend the same event as I</em>
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
				</fieldset>
				<footer class="buttons-extra">
					<a href="cp.html"><span>Save All</span></a>
				</footer>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}

</body>
</html>
