<div id="created_events">
{foreach name=events item=event from=$createdEvents}
  <div class="event_created_item">
  	<a href="{$event['url']}" class="event_created_item_title"><h3>{$event['title']}</h3></a>
    <p>{$event['time_left']} time left</p>
    <a href="#" class="edit_event_created" id="event-{$event['id']}">
    	<img src="{$UP_IMG_PATH}/manage.png" rel="#update_event_form_overlay" onclick="CP_EVENT.openUpdateEvent('event-{$event['id']}')" />
    </a>
  </div>
{/foreach}
</div>