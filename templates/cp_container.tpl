<section id="main">
		<header class="block">
			<p class="message">You can manage all of your upcoming events from this home page.</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
				<p class="user-img">
					{if $userInfo['pic'] eq '' && $smarty.session.userProfilePic eq ''}
						<img id="user_pic" src="{$CURHOST}/images/default_thumb.jpg" alt="{$userInfo['fname']} {$userInfo['lname']}" />
					{elseif $userInfo['pic'] ne ''}
						<img id="user_pic" src="{$CURHOST}/upload/user/{$userInfo['pic']}" alt="{$userInfo['fname']} {$userInfo['lname']}" />
					{elseif $smarty.session.userProfilePic ne ''}
						<img id="user_pic" src="{$smarty.session.userProfilePic}" alt="{$userInfo['fname']} {$userInfo['lname']}" />
					{/if}
				</p>
				<footer class="buttons-edit"><a href="#"><span>Upload</span></a></footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info">Say something witty about yourself here!</p>
				<footer class="buttons-edit"><a href="#"><span>Edit</span></a></footer>
			</section>
			<!--
			<div id="user_image" style="position:relative; left:20px;"></p>       
			<noscript>          
			<p>Please enable JavaScript to use file uploader.</p>
			-- or put a simple form for upload here --
			</noscript>         
			</div>
			-->
			<section class="block" id="user-pic">
				<fieldset>
					<label for="user-email">
						<span>Email</span> 
						<input type="text" class="inputbox autowidth" name="user-email" id="user-email" value="sergeeva@usc.edu" />
					</label>
					<label for="user-cell"><span>Cell #</span> <input type="text" class="inputbox autowidth" name="user-cell" id="user-cell" value="303-886-1808" /></label>
					<label for="user-zip"><span>Zip</span> <input type="text" class="inputbox autowidth" name="user-zip" id="user-zip" value="90007" maxlength="5" /></label>
				</fieldset>
				<footer class="buttons-edit"><a href="#"><span>update</span></a></footer>
			</section>
		</aside>
		<div class"content">
			{include file="event_created.tpl"}
			{include file="event_attending.tpl"}
		</div>
