<section class="block" id="user-pic">
				<p class="user-img">
				{if $userInfo['pic'] eq ''}
					<img class="info-pic" src="{$CURHOST}/images/default_thumb.jpg" alt="{$userInfo['fname']} {$userInfo['lname']}" />
				{else}
					<img class="info-pic" src="{$CURHOST}/upload/user/{$userInfo['pic']}" alt="{$userInfo['fname']} {$userInfo['lname']}" />
				{/if}
				</p>
				<p class="user-info">Very witty personal statement...</p>
			</section>
