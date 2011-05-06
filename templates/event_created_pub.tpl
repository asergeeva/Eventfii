<div id="current_events">
{foreach name=events item=event from=$createdEvents}
  <div class="event_created_item">
  	<a href="{$event['url']}" class="event_created_item_title"><h3>{$event['title']}</h3></a>
    <p>{$event['days_left']} days left</p>
  </div>
{/foreach}
</div>