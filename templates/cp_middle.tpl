<div class="create_event_form_overlay" id="create_event_form_overlay">
	{include file="create_event_form.tpl"}
</div>
<div class="update_event_form_overlay" id="update_event_form_overlay">
	{include file="update_event_form.tpl"}
</div>
<div id="event_created_container">
	<h2>Event created</h2>
	{include file="event_created.tpl"}
</div>
<div id="event_attended_container">
	<h2>Events attending</h2>
	{include file="event_attending.tpl"}
</div>
<div id="user_profile_container">
	<h2>Profile</h2>
  {include file="user_profile.tpl"}
  <a href="#"><img src="{$UP_IMG_PATH}/startnew.png" id="create_new_event" rel="#create_event_form_overlay" /></a>
</div>