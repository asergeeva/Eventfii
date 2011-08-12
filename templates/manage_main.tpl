<header class="block">
			<p class="message"><em>{$smarty.session.manage_event->days_left}</em> days left until the event. Get excited!</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			<section class="block" id="cp-manage">
				<header class="rsvp-progress">
					<div class="meter" style="width: {$trsvpVal / $smarty.session.manage_event->goal * 100}%">
						<p class="trueRSVP"><em>{$trsvpVal}</em> <span>Your trueRSVP</span></p>
					</div>
					<p class="goal"><em>{$smarty.session.manage_event->goal}</em> <span>Your Goal</span></p>
				</header>
				<section class="block" id="cp-breakdown">
					<header class="block-collapsable-title">
						<h1>RSVP Breakdown</h1>
					</header>
					<dl class="table"> 
						<dt class="list-head">Response</dt> 
						<dd>#</dd> 
						<dt>I will absolutely be there</dt> 
						<dd>{$guestConf1}</dd> 
						<dt>I'm pretty sure i can make it</dt> 
						<dd>{$guestConf2}</dd> 
						<dt>I will make it if my schedule doesn't change</dt> 
						<dd>{$guestConf3}</dd> 
						<dt>I probably won't be able to make it</dt> 
						<dd>{$guestConf4}</dd> 
						<dt>Not attending, but show me as a supporter</dt> 
						<dd>{$guestConf5}</dd> 
						<dt>This is spam to me</dt> 
						<dd>{$guestConf6}</dd> 
						<dt>No Response</dt> 
						<dd>{$guestNoResp}</dd> 
					</dl>
					<div id="rsvp" style="display:none;">{{$guestConf1}+{$guestConf2}+{$guestConf3}}</div>
					<div id="goal" style="display:none;">{$smarty.session.manage_event->goal}</div>
				</section>
				<section class="block" id="cp-attendees">
					<header class="block-collapsable-title">
						<h1>Attendees</h1>
					</header>
					<ul class="list"> 
						<li class="list-head"><strong>Name</strong> <em>Certainty</em> <span>Showed Up?</span></li>{foreach $eventAttendees as $guest}
						<li><label for="attendee-{$guest->id}"><strong>{if isset($guest->fname) || isset($guest->lname)}{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if}{else}{$guest->email}{/if}</strong> <em>{$guest->confidence}%</em> <span><input type="checkbox" id="attendee-{$guest->id}" value="attendee_{$guest->id}_{$smarty.session.manage_event->eid}"{if isset($guest->checkedIn)} checked="checked"{/if} name="selecteditems" /></span></label></li>{/foreach}
					</ul>
				</section>
				<footer class="links-extra">
					<p><a href="{$CURHOST}/event/print?eventId={$smarty.session.manage_event->eid}" target="_blank">Print Attendance List</a</p> 
				</footer>
			</section>
		</div>
