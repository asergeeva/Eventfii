<ul class="details">
{foreach name=events item=event from=$attendingEvents}
  <li>
  	<a href="{$event['url']}" class="event_created_item_title"><span>{$event['title']}</span></a>
    {$event['days_left']} days left
  </li>
{/foreach}
</ul>