{include file="index_header.tpl"}
<body>

<div id="fb-root"></div>
<header id="site-header">
	<h1 id="logo"><a href="{$CURHOST}">trueRSVP</a> <em>Beta</em></h1>
	<nav>
		<p><a href="{$CURHOST}/login">Log In</a> | <a href="{$CURHOST}/register">Sign Up</a> | <a href="{$CURHOST}/method">How Does It Work?</a></p>
	</nav>
	<section class="teaser">
		<header>
			<h1>Flake-proof your event.</h1>
		</header>
		<p>Create an event & find out how many people will <em>actually</em> show up.</p>
	</section>
	<section class="start">
		<form method="post" action="{$CURHOST}/event/create">
			<fieldset>
				<legend>Free to get started!</legend>
				<p>I'm planning <input type="text" class="inputbox" name="title" value="name of event" id="title" /> <em>and I want <input class="inputbox-small" type="text" value="max" name="goal" id="goal" /> people to come.</em></p>
				<footer class="buttons buttons-submit">
					<p><span class="btn btn-med"><input type="submit" name="submit" value="Go!" /></span></p>
				</footer>
			</fieldset>
		</form>
		<aside class="buttons">
			<p><a href="{$CURHOST}/register" class="btn btn-large"><span>Sign up here</span></a> <a href="{$CURHOST}/method" class="btn btn-large"><span>Find out more</span></a></p>
		</aside>
	</section>
	<section class="more">
		<aside>
			<p><a href="http://www.demo.com/alumni/demogods.html"><img src="{$CURHOST}/images/demogod.png" alt="Demogod" /></a></p>
		</aside>
		<aside class="more-testimonial">
			<blockquote>“Very valuable... this would have saved me $1000 in catering.”</blockuote>
			<p>- Garrett E, Palo Alto</p>
		</aside>
	</section>
</header>
<div id="main">
	<section class="block" id="featured">
		<header class="block-title">
			<h1>As featured in</h1>
		</header>
		<ul>
			<li><a href="http://venturebeat.com/2011/09/13/demo-pinevio-truersvp-consumer-technologies/"><span></span><img src="images/ft_venturebeat.gif" alt="VentureBeat" /></a></li>
			<li><a href="http://content.usatoday.com/communities/technologylive/post/2011/09/i-postmortem-a-demo-company-to-die-for/1"><span></span><img src="images/ft_usatoday.gif" alt="USA Today" /></a></li>
			<li><a href="http://www.gadgetizer.com/2011/09/13/truersvp-com-to-kick-evites-ass-at-long-last/"><span></span><img src="images/ft_gadgetizer.gif" alt="Gadgetizer" /></a></li>
			<li><a href="http://www.ubergizmo.com/2011/09/truersvp-event-planning-service"><span></span><img src="images/ft_ubergizmo.gif" alt="Ubergizmo" /></a></li>
			<li><a href="http://www.socaltech.com/truersvp_takes_on_online_event_planning/s-0038078.html"><span></span><img src="images/ft_socaltech.gif" alt="Socal Tech" /></a></li>
			<li><a href="http://www.marketwatch.com/story/truersvp-launches-new-online-event-planning-service-at-demo-fall-conference-2011-09-13"><span></span><img src="images/ft_marketwatch.gif" width="100px" alt="Market Watch" /></a></li>
		</ul>
	</section>
	<section class="block" id="reasons">
		<header class="block-title">
			<h1>Why use trueRSVP?</h1>
		</header>
		<section class="lose-old">
			<header>
				<h1>Lose the old system</h1>
			</header>
			<ul>
				<li>
					<h2>Unique RSVP options</h2>
					<p>Get an honest RSVP from your guests</p>
				</li>
				<li>
					<h2>Live event feed</h2>
					<p>All your pics and tweets in one place</p>
				</li>
			</ul>
		</section>
		<section class="lose-lines">
			<header>
				<h1>Lose the lines</h1>
			</header>
			<ul>
				<li>
					<h2>QR code check-ins</h2>
					<p>Easily scan in your guests using your phone</p>
				</li>
				<li>
					<h2>Geolocation check-ins</h2>
					<p>Have your guests check themselves in as soon as they arrive</p>
				</li>
			</ul>
		</section>
		<section class="lose-chain">
			<header>
				<h1>Lose the ball and chain</h1>
			</header>
			<ul>
				<li>
					<h2>Mobile application</h2>
					<p>Take your event management on-the-go</p>
				</li>
				<li>
					<h2>Group SMS</h2>
					<p>Contact all your guests in one text</p>
				</li>
			</ul>
		</section>
	</section>
	<p class="extra" id="notyet_container">
		<span>Not planning an event just yet?</span> 
		<span>
			<input type="text" name="e-mail" value="Leave us your e-mail and we'll remind you!" class="inputbox" id="email" /> 
			<input type="submit" name="submit" value="Send" class="submit" id="not_planning_yet" />
		</span>
	</p>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
<script src="{$JS_PATH}/home_event.js" type="text/javascript" charset="utf-8"></script>

</body>
</html>
