<ul class="details">
{foreach name=events item=event from=$createdEvents}
  <li>
    <a href="{$CURHOST}/event/{$event['id']}" class="event-name">{$event['title']}</a>
    <a href="{$CURHOST}/event/manage?eventId={$event['id']}" class="edit_event_created"><img src="{$IMG_PATH}/manage.png" /></a>
  </li>
{/foreach}
</ul>