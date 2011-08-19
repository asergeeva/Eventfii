{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">Confirm who showed up to improve your trueRSVP accuracy for your next event!</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			<section class="block" id="cp-attendee-list">
				<section class="block" id="cp-attendees">
					<header class="block-collapsable-title">
						<h1>Attendees</h1>
					</header>
					<ul class="list"> 
						<li class="list-head"><strong>Name</strong> <em>Certainty</em> <span>Showed Up?</span></li>{foreach $eventAttendees as $guest}
						<li><label for="attendee-{$guest->id}"><strong>{if isset($guest->fname) || isset($guest->lname)}{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if}{else}{$guest->email}{/if}</strong> <em>{$guest->confidence}%</em> 
						<span><input type="checkbox" id="attendee-{$guest->id}" value="attendee_{$guest->id}_{$smarty.session.manage_event->eid}"{if isset($guest->checkedIn)} checked="checked"{/if} name="selecteditems" class="event_attendees" /></span></label></li>{/foreach}
					</ul>
					<footer class="buttons buttons-submit">
						<p><input type="submit" name="submit" value="Save" /></p> 
					</footer> 
				</section>
				<footer class="links-extra">
					<p><a href="{$CURHOST}/event/print?eventId={$smarty.session.manage_event->eid}" target="_blank">Print Attendance List</a></p> 
				</footer>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}
{include file="js_cp.tpl"}
{include file="js_create.tpl"}

</body>
</html>
