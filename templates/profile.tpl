{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Meet {$profile->fname} {$profile->lname}!</h1>
	</header>
	<section id="main">
		<aside class="extra">
			{include file="profile_user.tpl"}{*{if $profile->facebook || $profile->twitter}
			<section class="block" id="user-social">
				<ul class="network">
					{if $profile->facebook}<li><a href="http://facebook.com/profile.php?id={$profile->facebook}" class="icon-facebook">{$profile->fname} {$profile->lname}</a></li>{/if}
					{if $profile->twitter}<li><a href="http://twitter.com/{$profile->twitter}" class="icon-twitter">@{$profile->twitter}</a></li>{/if}
				</ul>
			</section>{/if}<footer class="follow">
				<p><a href="#{$profile->id}" id="follow_host"><span id="follow_button">{if $is_following eq 1}Unfollow{else}Follow{/if}</span></a></p>
			</footer>*}
		</aside>
		<div class="content">
			{include file="block_events_created_pub.tpl"}
			{include file="block_events_attending_pub.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_profile.tpl"}

</body>
</html>
