<section id="main">
		<header class="block">
			<p class="message">You can manage all of your upcoming events from this home page.</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
				<p class="user-img">
				  {if $userInfo['pic'] eq '' && $smarty.session.userProfilePic eq ''}
					<a href="#" class="info-pic"><img id="user_pic" src="{$CURHOST}/images/default_thumb.jpg" alt="{$userInfo['fname']} {$userInfo['lname']}" /></a>
				  {elseif $userInfo['pic'] ne ''}
					<a href="#" class="info-pic"><img id="user_pic" src="{$CURHOST}/upload/user/{$userInfo['pic']}" alt="{$userInfo['fname']} {$userInfo['lname']}" /></a>
				  {elseif $smarty.session.userProfilePic ne ''}
					<a href="#" class="info-pic"><img id="user_pic" src="{$smarty.session.userProfilePic}" alt="{$userInfo['fname']} {$userInfo['lname']}" /></a>
				  {/if}
				</p>
				<footer class="buttons buttons-extra">
					<a href="#"><span>Upload</span></a>
				</footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info">{$userInfo['about']}</p>
				<footer class="buttons buttons-extra">
					<p><a href="#"><span>Edit</span></a></p>
				</footer>
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
