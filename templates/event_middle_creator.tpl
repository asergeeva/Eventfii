<section class="block" id="event-hosted">
				<header class="block-title">
					<h1>Hosted by</h1>
				</header>
				<p class="user-name"><a href="{$CURHOST}/user/{$organizer['id']}">{$organizer['fname']} {$organizer['lname']}</a></p>
				<p class="user-img">
					<a href="{$CURHOST}/user/{$organizer['id']}">
					{if $organizer['pic'] eq ''}
						<img src="{$CURHOST}/images/default_thumb.jpg" alt="{$organizer['fname']} {$organizer['lname']}" />
					{else}
						<img src="{$CURHOST}/upload/user/{$organizer['pic']}" alt="{$organizer['fname']} {$organizer['lname']}" />
					{/if}
					</a>
				</p>
				<ul class="user-name-extra">
					<li>USC Student</li>
					<li>President of USG</li>
				</ul>
				<footer class="link-extra">
					<p><a href="#">Send Anna a message</a></p>
				</footer>
			</section>
