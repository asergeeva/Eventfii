	{include file="event_middle_bottom.tpl"}
	<div class="section">
		<section class="block" id="event_attending">
			<h1 class="block-title">Your RSVP</h1>
			<ul class="rsvp" id="event_attending_response">
				<li><label for="event_attending_response_1"><input type="button" class="event_attending_response" name="event_attending_response" value="90" {$conf90} id="event_attending_response_1" />Yes, I will absolutely be there</label></li>
				<li><label for="event_attending_response_2"><input type="button" class="event_attending_response" name="event_attending_response" value="70" {$conf70} id="event_attending_response_2" /> I'm pretty sure I will be there</label></li>
				<li><label for="event_attending_response_3"><input type="button" class="event_attending_response" name="event_attending_response" value="50" {$conf50} id="event_attending_response_3" /> If I remember</label></li>
				<li><label for="event_attending_response_4"><input type="button" class="event_attending_response" name="event_attending_response" value="30" {$conf30} id="event_attending_response_4" /> If I have nothing better to do</label></li>
				<li><label for="event_attending_response_5"><input type="button" class="event_attending_response" name="event_attending_response" value="10" {$conf10} id="event_attending_response_5" /> No, but show me as a supporter</label></li>
			</ul>
			<div id="response_stat_msg"></div>
		</section>
		{include file="event_middle_creator.tpl"}
	</div>
	<footer class="event-extra">
		<p><a href="#">Flag this event</a></p>
		<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=121330687952296&amp;xfbml=1"></script><fb:like href="{$eventInfo['url']}" send="true" width="540" show_faces="true" font="" id="fb-like-button"></fb:like>
	</footer>
