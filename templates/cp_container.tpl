<section id="main">
		<header class="block">
			<p class="message">You can manage all of your upcoming events from this home page.</p>
		</header>
		<aside class="extra">
			<section class="block" id="user-pic">
				<p class="user-img">
        	<a href="#" class="info-pic"><img id="user_pic" src="{$CURHOST}/images/default_thumb.jpg" alt="{$userInfo['fname']} {$userInfo['lname']}" width="200px" height="150px" /></a>
        </p>
				<footer class="buttons-edit"><div id="user_image">    
				<noscript>          
				  <p>Please enable JavaScript to use file uploader.</p>
				  <!-- or put a simple form for upload here -->
				</noscript> 
				</div>
			  </footer>
				<footer class="buttons-edit"><a href="#" id="uploadPic"><span>Upload</span></a></footer>
			</section>
			<section class="block" id="user-desc">
				<div class="edit" id="div_2" style="position:relative; left:10px; font-size:13px;">{$userInfo['about']}</div>
				<footer class="buttons-edit"><p><a href="#" class="btn-small" id="editBtn"><span>Edit</span></a></p></footer>
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
				<div id="email_err"></div>
					<label for="user-email">
						<span>Email</span> 
						<input type="text" class="inputbox autowidth" name="email" id="email" value="{$userInfo['email']}" />
					</label>
					<div id="cell_err"></div>
					<label for="user-cell"><span>Cell #</span> <input type="text" class="inputbox autowidth" name="cell" id="cell" value="{$userInfo['phone']}" /></label>
					<div id="zip_err"></div>
					<label for="user-zip"><span>Zip</span> <input type="text" class="inputbox autowidth" name="zip" id="zip" value="{$userInfo['zip']}" maxlength="5" /></label>
					<div id="update_success"></div>
				</fieldset>
				<footer class="buttons-edit"><a href="#" id="dtls_update"><span>update</span></a></footer>
   </section>
			<!----- -->
		</aside>
		<div class"content">
			{include file="event_created.tpl"}
			{include file="event_attending.tpl"}
		</div>
