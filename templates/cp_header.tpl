<header id="header">
	<h1 id="logo"><a href="{$CURHOST}">trueRSVP</a></h1>
	<aside>
		<ul>
    	<li><a href="{$CURHOST}/logout" onclick="FB.logout()">Sign off</a></li>
			<li><a href="#">My Events</a></li>
			<li><a href="#" class="btn" id="create_new_event" rel="#create_event_form_overlay"><span>Create New Event</span></a></li>
		</ul>
	</aside>
</header>
<div class="create_event_form_overlay" id="create_event_form_overlay">
	{include file="create_event_form.tpl"}
</div>