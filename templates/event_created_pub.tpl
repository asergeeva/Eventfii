<ul class="details">
{foreach name=events item=event from=$createdEvents}
  <li>
    <a href="event/{$event['id']}" class="event-name">{$event['title']}</a>
  </li>
{/foreach}
</ul>