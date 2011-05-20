<div id="created_events">
{foreach name=events item=event from=$createdEvents}
  <div class="event_created_item">
  	<a href="{$event['url']}" class="event_created_item_title"><h3>{$event['title']}</h3></a>
    <p>{$event['days_left']} days left</p>
    <a href="#" class="edit_event_created" id="event-{$event['id']}">
    	<img src="{$UP_IMG_PATH}/edit.png" rel="#update_event_form_overlay" onclick="CP_EVENT.openUpdateEvent('event-{$event['id']}')" />
    </a>
    <a href="#" class="collect_payment_event" id="collect-{$event['id']}">
    {$event['collect_button']}
    </a>
  </div>
{/foreach}
</div>