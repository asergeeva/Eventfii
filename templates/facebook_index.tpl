<h1>trueRSVP on Facebook</h1>

{$fbUser['first_name']}, you are invited to:
<h2>{$event->title}</h2>
<h3>{$event->friendly_date} {$event->friendly_time}</h3>
<ul>
	<li><a href="{$EVENT_URL}/a/{$event->alias}?fba=1&fbu={$fbUser['id']}&gref={$event->global_ref}" target="_blank">Absolutely</a></li>
	<li><a href="{$EVENT_URL}/a/{$event->alias}?fba=2&fbu={$fbUser['id']}&gref={$event->global_ref}" target="_blank">Pretty sure</a></li>
	<li><a href="{$EVENT_URL}/a/{$event->alias}?fba=3&fbu={$fbUser['id']}&gref={$event->global_ref}" target="_blank">50/50</a></li>
	<li><a href="{$EVENT_URL}/a/{$event->alias}?fba=4&fbu={$fbUser['id']}&gref={$event->global_ref}" target="_blank">Not likely</a></li>
	<li><a href="{$EVENT_URL}/a/{$event->alias}?fba=5&fbu={$fbUser['id']}&gref={$event->global_ref}" target="_blank">Raincheck</a></li>
</ul>