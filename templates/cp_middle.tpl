<div class="create_event_form_overlay" id="create_event_form_overlay">
	{include file="create_event_form.tpl"}
</div>
<div class="update_event_form_overlay" id="update_event_form_overlay">
	<span id="update_event_overlay_eventid"></span>
	<ul id="update_event_top_nav">
  	<li><a href="#" id="update_event_before">Before Event</a></li>
    <li><a href="#" id="update_event_on">Day of Event</a></li>
		<li><a href="#" id="update_event_after">After Event</a></li>
  </ul>
	<ul id="update_event_left_nav">
  	<li><a href="#" id="update_event_manage">Manage</a></li>
    <li><a href="#" id="update_event_edit">Edit</a></li>
  </ul>
  <div id="manage_event_container">
		{include file="manage_event_form.tpl"}
  </div>
</div>
<div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
	{include file="event_invite_guest_create.tpl"}
</div>
<div id="event_created_container">
	<h2>Events created</h2>
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