{include file="home_header.tpl"}
<body>
<div id="container">
	<header>
		<p><a href="login">Log In/Create New Account</a></p>
	</header>
	<h1><a href="#">trueRSVP</a></h1>
	<h2>a new RSVP system based on reputation</h2>
	<fieldset>
	  <form id="create_event_home" name="create_event_home" method="post" action="home">
		<p>I'm planning <input type="text" class="inputbox" name="title" /> and I want <input class="inputbox-small" type="text" name="goal" /> people to come! <input class="submit" type="submit" /></p>
	  </form>
	</fieldset>
	
	<p>We factor in your attendees' reliability to give you an <span>accurate prediction</span> of how many people are actually going to show up to your event.</p>
 
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
{include file="home-footer.tpl"}

{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/home_event.js"></script>

</body>
</html>