<header class="block">
			<p class="message"><em>{$smarty.session.manage_event->days_left}</em> days left until the event. Get excited!</p>
		</header>
		{include file="manage_nav.tpl"}
		<div class="content">
			<section class="block" id="cp-manage">
				<p class="message">Want to increase your trueRSVP? Add more guests or remind your current guests to RSVP.</p>
				<header class="rsvp-progress">
					<div class="meter" style="width: {$trsvpVal / $smarty.session.manage_event->goal * 100}%">
						<p class="trueRSVP"><em>{$trsvpVal}</em> <span>Your trueRSVP</span></p>
					</div>
					<p class="goal"><em>{$smarty.session.manage_event->goal}</em> <span>Your Goal</span></p>
				</header>
				<p class="message">Click on the response type to see who has RSVP’d to your event.</p>
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
			</section>
		</div>
