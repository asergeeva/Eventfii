<ul class="details">
{foreach name=events item=event from=$createdEvents}
  <li>
    <a href="{$CURHOST}/event/{$event['id']}" class="event-name">{$event['title']}</a>
  </li>
{/foreach}
</ul>