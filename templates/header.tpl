<header id="page-header">
	<h1 id="logo"><a href="{$CURHOST}">trueRSVP</a> <em>Beta</em></h1>
	<aside>
		<nav>
			<p>{if ! isset($smarty.session.user)}<a href="{$CURHOST}/login">Log In</a> | <a href="{$CURHOST}/register">Sign Up</a> | {else}<a href="{$CURHOST}">Home</a> | {/if}<a href="{$CURHOST}/method">How Does It Work?</a>{if isset($smarty.session.user)} | <a href="{$CURHOST}/logout" onclick="FB.logout()">Log Out</a>{/if}</p>
		</nav>
		<p class="buttons buttons-create"><a href="{$CURHOST}/event/create" class="btn btn-small"><span>Create New Event</span></a></p>
	</aside>
</header>
<div id="fb-root"></div>
