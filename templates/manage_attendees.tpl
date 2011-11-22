{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<div class="navigation">
			<nav class="attendee-nav">
				
			</nav>
		</div>
		<div class="manage">
			<header class="block">
				<p class="message">Confirm who showed up to improve your trueRSVP accuracy for your next event!</p>
			</header>
			<section class="block" id="cp-attendee-list">{if ! isset($eventAttendees)}

				<header class="block">
					<p class="message">No attendees</p>
				</header>{else}

				<section class="block" id="cp-attendees">

					<header class="block-collapsable-title">
						<h1>Guest List</h1>
						<span id="attendee-header"></span>
					</header>
					<ul class="list">
						<li class="list-head">
							<div>
								<em id="head-show">Showed Up?</em><span id="head-name"><a href="#">Name</a></span><strong id="head-rsvp"><a href="#">RSVP</a></strong> 
							</div>
						</li>
					</ul>
					<ul class="list" id="attendee-list">{foreach $eventAttendees as $guest}

						<li>
							<label for="attendee-{$guest->id}">
								<em><input type="checkbox" id="attendee-{$guest->id}" value="attendee_{$guest->id}_{$smarty.session.manage_event->eid}"{if $guest->is_attending eq 1} checked="checked"{/if} name="selecteditems" class="event_attendees" /></em><span title="{if isset($guest->fname) || isset($guest->lname)}{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if}{else}{$guest->email}{/if}">{if isset($guest->fname) || isset($guest->lname)}{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if}{else}{$guest->email}{/if}</span><strong title="{$guest->confidence}">{$guest->friendly_confidence}</strong>								
							</label>
						</li>{/foreach}

					</ul>
					<!--footer class="buttons buttons-submit">
						<p><input type="submit" name="submit" value="Save" /></p> 
					</footer--> 
				</section>
				<!--section class="block" id="nr-cp-attendees">
					<header class="block-collapsable-title">
						<h1>No Response</h1>
						<span id="nr-attendee-header"></span>
					</header>
					<ul class="list">
						<li class="list-head">
							<span id="nr-head-show">Showed Up?</span>
							<strong id="nr-head-name"><a href="#">Email</a></strong> 
							<em id="nr-head-rsvp"><a href="#">RSVP</a></em> 
						</li>
					</ul>
					<ul class="list" id="nr-attendee-list">{foreach $noResponseAttendees as $guest}

						<li>
							<label for="attendee-{$guest->id}">
								<strong title="{if isset($guest->fname) || isset($guest->lname)}{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if}{else}{$guest->email}{/if}">{if isset($guest->fname) || isset($guest->lname)}{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if}{else}{$guest->email}{/if}</strong> <em title="{$guest->confidence}">{$guest->friendly_confidence}</em> 
								<span><input type="checkbox" id="attendee-{$guest->id}" value="attendee_{$guest->id}_{$smarty.session.manage_event->eid}"{if $guest->is_attending eq 1} checked="checked"{/if} name="selecteditems" class="event_attendees" /></span>
							</label>
						</li>{/foreach}

					</ul>
					<footer class="buttons buttons-submit">
						<p><input type="submit" name="submit" value="Save" /></p> 
					</footer> 
				</section>
				<footer class="links-extra">
					<p><a href="{$CURHOST}/event/print?eventId={$smarty.session.manage_event->eid}" target="_blank">Print Attendance List</a></p> 
				</footer>
			</section-->{/if}

		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>
