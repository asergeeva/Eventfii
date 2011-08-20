<header id="page-header">
	<h1 id="logo"><a href="{$CURHOST}">trueRSVP</a></h1>
	<aside>
		<p>{if ! isset($smarty.session.user)}<a href="{$CURHOST}/login">Log In</a> | <a href="{$CURHOST}/signup">Sign Up</a> | {else}<a href="{$CURHOST}">Home</a> | {/if}<a href="{$CURHOST}/method">How Does It Work?</a>{if isset($smarty.session.user)} | <a href="{$CURHOST}/logout" onclick"FB.logout()">Log Out</a>{/if}</p>
		<p class="buttons buttons-create"><a href="{$CURHOST}/event/create" class="btn"><span>Create New Event</span></a></p>
		</ul>
	</aside>
</header>
