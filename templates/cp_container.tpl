<section id="main">
		<header class="block">
			<p class="message">You can manage all of your upcoming events from this home page.</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
				<p class="user-img">
					<a href="{$CURHOST}/user/{$smarty.session.user->id}" class="info-pic"><img id="user_pic" src="{$smarty.session.user->pic}" width="96px" height="96px" alt="{$smarty.session.user->fname} {$smarty.session.user->lname}" /></a>
				</p>
				<footer class="buttons buttons-extra">
					<p><a href="#" id="user_image"><span>Upload</span></a></p>
				</footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info edit">{if $smarty.session.user->about}{$smarty.session.user->about}{else}Click here to edit{/if}</p>
			</section>
			<footer class="links-extra">
				<p><a href="{$CURHOST}/settings">Settings</a></p>
			</footer>
		</aside>
		<div class="content">
			{include file="event_created.tpl"}
			{include file="event_attending.tpl"}
		</div>
	</section>
