<div id="current_events">
{foreach name=events item=event from=$attendingEvents}
  <div class="event_created_item">
  	<a href="{$event['url']}" class="event_created_item_title"><h3>{$event['title']}</h3></a>
    {$event['days_left']} days left
  </div>
{/foreach}
</div>