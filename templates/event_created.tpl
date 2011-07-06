<ul class="details">
{foreach name=events item=event from=$createdEvents}
  <li><a class="btn-small" href="{$CURHOST}/event/manage?eventId={$event['id']}"><span>Manage</span></a> <a href="{$CURHOST}/event/{$event['id']}" class="event-name"><span>{$event['title']}</span></a></li>
{/foreach}
</ul>