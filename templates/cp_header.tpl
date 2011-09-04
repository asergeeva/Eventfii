<header id="header">
		<h1>Welcome, {$smarty.session.user->fname}!</h1>
		<h2><a href="{$CURHOST}/user/a/{$smarty.session.user->alias}" id="user-{$smarty.session.user->id}">View your public profile</a></h2>
		<span id="user-id" style="display:none;">{$smarty.session.user->id}</span>
		<nav>
			<ul>
				<li{if isset($page.cp)} class="current"{/if} id="cp"><a href="{$CURHOST}"><span>Home</span></a></li>
				<li{if isset($page.contacts) || isset($page.addcontacts)} class="current"{/if} id="contacts"><a href="{$CURHOST}/contacts" id="update_event_edit"><span>My Contacts</span></a></li>
				<li{if isset($page.settings)} class="current"{/if} id="settings"><a href="{$CURHOST}/settings" id="update_event_edit"><span>Settings</span></a></li>
			</ul>
		</nav>
	</header>
