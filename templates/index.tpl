{include file="index_header.tpl"}
<body>

<header id="site-header">
	<h1><a href="{$CURHOST}">trueRSVP</a></h1>
	<nav>
		<p><a href="{$CURHOST}/login">Log In</a> | <a href="{$CURHOST}/register">Sign Up</a> | <a href="{$CURHOST}/method">How Does It Work?</a></p>
	</nav>
	<section class="about">
		<header>
			<h1>A new RSVP system based on <em>reputation</em></h1>
			<p>Don’t let last minute flakes affect the success of your event. trueRSVP tells you how many people will <span>actually</span> show up!</p>
		</header>
		<form method="post" action="{$CURHOST}/event/create" class="block">
			<fieldset>
				<legend>Get Started!</legend>
				<p>I'm planning <input type="text" class="inputbox" name="title" value="name of event" id="title" /> and I want <input class="inputbox-small" type="text" value="goal" name="goal" id="goal" /> people to come.</p>
				<p class="submit"><input type="submit" value="Go" class="go" /></p>
			</fieldset>
		</form>
	</section>
</header>
<div id="main">
	<section class="block">
		<header class="block-title">
			<h1>What’s our top secret reputation formula?</h1>
		</header>
		<div class="formula">
			<figure>
				<img src="{$CURHOST}/images/formula_zodiac.gif" alt="Zodiac Sign" />
				<figcaption>Zodiac Sign</figcaption>
			</figure>
			<span>+</span>
			<figure>
				<img src="{$CURHOST}/images/formula_height.gif" alt="Height" />
				<figcaption>Height</figcaption>
			</figure>
			<span>+</span>
			<figure>
				<img src="{$CURHOST}/images/formula_sat.gif" alt="SAT Score" />
				<figcaption>SAT Score</figcaption>
			</figure>
		</div>
		<p class="message">(Just kidding! You have to plan an event to see how it really works.) <a href="{$CURHOST}/event/create" class="btn-small">Create an event</a></p>
	</section>
	<section class="block">
		<header class="block-title">
			<h1>Why use trueRSVP?</h1>
			<a href="{$CURHOST}/method" class="btn-large">More awesome features</a>
		</header>
		<p class="message">Let’s face it, event planning sucks. We’re here to make it suck a lot less.</p>
		<section class="column">
			<header>
				<h1>Lose the old system</h1>
			</header>
			<ul>
				<li class="icon icon-rsvp">
					<h2>Unique RSVP options</h2>
					<p>Get an honest RSVP from your guests</p>
				</li>
				<li class="icon icon-feed">
					<h2>Live event feed</h2>
					<p>All your pics and tweets in one place</p>
				</li>
			</ul>
		</section>
		<section class="column">
			<header>
				<h1>Lose the lines</h1>
			</header>
			<ul>
				<li class="icon icon-qr">
					<h2>QR code check-ins</h2>
					<p>Easily scan in your guests using your phone</p>
				</li>
				<li class="icon icon-geolocation">
					<h2>Geolocation check-ins</h2>
					<p>Have your guests check themselves in as soon as they arrive</p>
				</li>
			</ul>
		</section>
		<section class="column">
			<header>
				<h1>Lose the ball and chain</h1>
			</header>
			<ul>
				<li class="icon icon-mobile">
					<h2>Mobile application</h2>
					<p>Take your event management on-the-go</p>
				</li>
				<li class="icon icon-sms">
					<h2>Group SMS</h2>
					<p>Contact all your guests in one text</p>
				</li>
			</ul>
		</section>
	</section>
	<p class="extra"><span>Not planning an event just yet?</span> <input type="text" name="e-mail" value="Leave us your e-mail and we'll remind you!" class="inputbox" id="email" /> <input type="submit" value="Send" class="submit" /></p>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/home_event.js"></script>

</body>
</html>
