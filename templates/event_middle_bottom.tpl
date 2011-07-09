<div class="section section-event">
		<section class="block" id="event_metadata">
			<h1 class="block-title">Find out more</h1>
			<div class="column">
				<p id="event_description">{$eventInfo['description']}</p>
				<!-- Not in design
				<div id="event_cost">
				  <span id="event_gets_price">You will get</span>
					<div id="event_gets">
					{$eventInfo['gets']}
					</div>
				  </div>
				</div>
				-->
			</div>
			<div class="column">
				<p id="event_location">{$eventInfo['location_address']}</p>
				
				
			</div>
			<div style="position:relative;left:50px;">
			<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$eventInfo['location_address']|urlencode}&amp;hnear={$eventInfo['location_address']|urlencode}&amp;hl=en&amp;sll={$eventInfo['location_lat']},{$eventInfo['location_long']}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed"></iframe>
			</div>
		</section>
		<section class="block">
			<h1 class="block-title">Who's coming</h1>
			<ul class="thumbs">
				<li><a href="#"><img src="../images/thumb_1.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_2.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_3.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_4.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_1.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_2.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_3.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_4.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_1.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_2.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_3.jpg" alt="User" /></a></li>
				<li><a href="#"><img src="../images/thumb_4.jpg" alt="User" /></a></li>
			</ul>
		</section>
	</div>
