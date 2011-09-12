		{include file="manage_nav.tpl"}
		<div class="content">{if $smarty.session.manage_event->days_left > 0}

			<header class="block">{if $smarty.session.manage_event->days_left == 1}

				<p class="message"><em>1</em> day left until the event. Get excited!</p>{else}

				<p class="message"><em>{$smarty.session.manage_event->days_left}</em> days left until the event.</p>{/if}

			</header>{/if}

			<section class="block" id="cp-manage">
				<header class="turtle">
					<p><strong>Your trueRSVP #</strong> = how many people will actually show up!</p>
					<p>We’ve calculated that your <strong>trueRSVP</strong> number is <em>{$trsvpVal}</em></p>
				</header>
				<div class="rsvp-progress">
					<div class="meter" style="width: {$trsvpVal / $smarty.session.manage_event->goal * 100}%">
						<p class="trueRSVP"><em>{$trsvpVal}</em> <span>Your trueRSVP</span></p>
					</div>
					<p class="goal"><em>{$smarty.session.manage_event->goal}</em> <span>Your Goal</span></p>
				</div>
				<p class="message">Want to increase your trueRSVP? <a href="{$CURHOST}/event/manage/guests?eventId={$smarty.session.manage_event->eid}">Add more guests</a> or <a href="{$CURHOST}/event/manage/email?eventId={$smarty.session.manage_event->eid}">remind</a> your  guests to RSVP.</p>{*
				<p class="message">Click on the response type to see who has RSVP’d to your event.</p>*}
				<section class="block" id="cp-breakdown">
					<header class="block-collapsable-title">
						<h1>RSVP Breakdown</h1>
					</header>
					<dl class="table"> 
						<dt class="list-head">Response</dt> 
						<dd>#</dd> 
						<dt>Absolutely - I'll definitely be there!</dt> 
						<dd>{$guestConf1}</dd> 
						<dt>Pretty sure - I'll have to check my schedule</dt> 
						<dd>{$guestConf2}</dd> 
						<dt>50/50 - Interested, but not ready to commit</dt> 
						<dd>{$guestConf3}</dd> 
						<dt>Most likely not - I probably won't go</dt> 
						<dd>{$guestConf4}</dd> 
						<dt>Raincheck - Can't make it this time</dt> 
						<dd>{$guestConf5}</dd>
						<dt>No Response</dt> 
						<dd>{$guestNoResp}</dd> 
					</dl>
					<div id="rsvp" style="display:none;">{{$guestConf1}+{$guestConf2}+{$guestConf3}}</div>
					<div id="goal" style="display:none;">{$smarty.session.manage_event->goal}</div>
				</section>
			</section>
		</div>
