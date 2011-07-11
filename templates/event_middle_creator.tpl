<section class="block" id="created_by">
			<h1 class="block-title">Hosted By</h1>
			<p class="info-name"><a href="{$CURHOST}/user/{$organizer['id']}">{$organizer['fname']} {$organizer['lname']}</a></p>
			{if $organizer['pic'] eq ''}
				<a class="info-pic" href="{$CURHOST}/user/{$organizer['id']}"><img src="{$CURHOST}/images/default_thumb.jpg" width=200px height=150px /></a>
			{else}	
				<a class="info-pic" href="{$CURHOST}/user/{$organizer['id']}"><img src="{$CURHOST}/upload/user/{$organizer['pic']}" width=200px height=150px /></a>
			{/if}
			<p class="info-about"><span>USC Student</span> <span>President of USG</span></p> 
			<p class="message"><a href="#">Send Anna a Message</a></p> 
		</section>
