<header id="page-header">
	<h1 id="logo"><a href="{$CURHOST}">trueRSVP</a></h1>
	<aside>
		<ul>
			<li><a href="{$CURHOST}">Home</a> | </li>
			<li><a href="{$CURHOST}/share">Share</a> | </li>
			<li><a href="{$CURHOST}/method">Method</a> | </li>
			{if $smarty.session.user}
			<li><a href="{$CURHOST}/logout" onclick="FB.logout()">Log out</a></li>
			{else}
			<li><a href="{$CURHOST}/login">Sign Up/Log In</a></li>
			{/if}
			<li class="buttons"><a href="{$CURHOST}/event/create" class="btn"><span>Create New Event</span></a></li>
		</ul>
	</aside>
</header>
