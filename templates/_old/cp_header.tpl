<header id="page-header">
	<h1 id="logo"><a href="{$CURHOST}">trueRSVP</a></h1>
	<aside>
		<ul>
			<li><a href="{$CURHOST}">Home</a></li>
			<li><a href="share.html">Share</a></li>
			<li><a href="method.html">Method</a></li>
			<li><a href="{$CURHOST}/logout" onclick="FB.logout()">Log out</a></li>
			<li><a href="{$CURHOST}/cp/event/create" class="btn"><span>Create New Event</span></a></li>
		</ul>
	</aside>
</header>
<div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
  	{include file="event_invite_guest_create.tpl"}
</div>