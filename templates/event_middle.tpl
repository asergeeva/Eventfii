<div id="event_info">
  <div id="event_attending">
  	<ul id="event_attending_response">
  		<li><input type="radio" class="event_attending_response" name="event_attending_response" value="90" {$conf90} /> Yes, I will absolutely be there</li>
    	<li><input type="radio" class="event_attending_response" name="event_attending_response" value="70" {$conf70} /> I'm pretty sure I will be there</li>
    	<li><input type="radio" class="event_attending_response" name="event_attending_response" value="50" {$conf50} /> I will make it if I remember</li>
    	<li><input type="radio" class="event_attending_response" name="event_attending_response" value="30" {$conf30} /> I will make it if I have nothing better to do</li>
    	<li><input type="radio" class="event_attending_response" name="event_attending_response" value="10" {$conf10} /> Not attending, but show me as a supporter</li>
  	</ul>
  </div>
  <div id="response_stat_msg"></div>
	<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=121330687952296&amp;xfbml=1"></script><fb:like href="{$eventInfo['url']}" send="true" width="450" show_faces="true" font="" id="fb-like-button"></fb:like>
  {include file="event_middle_bottom.tpl"}
</div>
{include file="event_middle_creator.tpl"}
