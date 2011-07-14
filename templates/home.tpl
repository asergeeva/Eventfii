{include file="index_header.tpl"}
<body>

<div id="container">
	<header>
		<p><a href="{$CURHOST}/login">Log In/Create New Account</a></p>
	</header>
	<h1><a href="{$CURHOST}">trueRSVP</a></h1>
	<h2>a new RSVP system based on reputation</h2>
	<form id="create_event_home" name="create_event_home" method="post" action="home">
		<fieldset>
			<p>I'm planning <input type="text" class="inputbox" value="name of event" name="event_title_create" id="event_title_create" /> and I want <input type="text" class="inputbox-small" value="goal" name="event_goal_create" id="event_goal_create" /> people to come! <input class="submit" type="submit" /></p>
		</fieldset>
	</form>
	
	<p>We factor in your attendees’ reliability to give you an <span>accurate prediction</span> of how many people are actually going to show up to your event.</p>
 
	<footer id="footer">
		<h3>So, how does it work?</h2>
		<ul class="info-pics">
			<li>Easily send invites to all your friends and contacts</li>
			<li>Guests RSVP using options based on their actual behavior</li>
			<li>We tell you your trueRSVP, aka how many guests you should expect</li>
			<li>Check off attendance to improve 
	your trueRSVP for your next event</li>
		</ul>
	</footer>
</div>
<footer id="site-map">
	<ul> 
		<li> 
			<span>Company</span> 
			<ul> 
				<li><a href="#">Privacy Policy</a></li> 
				<li><a href="#">Terms of Service</a></li> 
			</ul> 
		</li> 
		<li> 
			<span>Contact Us</span> 
			<ul> 
				<li><a href="#">E-mail</a></li> 
			</ul> 
		</li> 
		<li> 
			<span>Learn</span> 
			<ul> 
				<li><a href="#">FAQ</a></li> 
				<li><a href="#">Team</a></li> 
				<li><a href="#">About Us</a></li> 
				<li><a href="#">Blog</a></li> 
			</ul> 
		</li> 
	</ul>
</footer>

{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/home_event.js"></script>

</body>
</html>
