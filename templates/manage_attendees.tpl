{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<aside class="checkin">
			<div class="turtle">
				<p>Check-in to improve trueRSVP’s accuracy!</p>
			</div>
			<p class="checkin-options">Three ways to check in your guests</p>
			<ul>
				<li class="checkin-qr">Scan QR tickets with our trueRSVP mobile app.</li>
				<li class="checkin-manual">Manually check off guests who showed up on this page.</li>
				<li class="checkin-app">Let your guest check themselves in by downloading our trueRSVP app.</li>
			</ul>
			<footer class="links-extra">
				<p><a href="{$CURHOST}/event/manage?eventId={$smarty.session.manage_event->eid}" class="btn btn-manage"><span><span class="btn-back">Back to manage page</span></span></a></p>
			</footer>
		</aside>
		<div class="manage">
			<header class="block">
				<p class="message">Confirm who showed up to improve your trueRSVP accuracy for your next event!</p>
			</header>{if ! isset($eventAttendees)}

			<section class="block" id="cp-attendee-list">
				<header class="block">
					<p class="message">No attendees</p>
				</header>
			</section>{else}
			
			<form method="post" action="#" class="quicksearch" id="search-guestlist">
				<fieldset>
					<input type="text" value="Search by name" />
				</fieldset>
			</form>
			<section class="block" id="cp-attendees">

				<header class="block-collapsable-title">
					<h1>Guest List</h1>
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

				</ul>{*
				<footer class="buttons buttons-submit">
					<p><input type="submit" name="submit" value="Save" /></p> 
				</footer>*}
			</section>{*
			<section class="block" id="nr-cp-attendees">
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
		</section>*}{/if}

		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>
